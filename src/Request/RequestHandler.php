<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Request;

use Doctrine\Common\Inflector\Inflector;
use KejawenLab\Semart\Collection\Collection;
use KejawenLab\Semart\Skeleton\Application;
use ReflectionProperty;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class RequestHandler
{
    public const REQUEST_TOKEN_NAME = 'APP_CSRF_TOKEN';

    private $application;

    private $propertyAccessor;

    private $validator;

    private $eventDispatcher;

    private $translator;

    private $errors;

    public function __construct(Application $application, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher, TranslatorInterface $translator)
    {
        $this->propertyAccessor = new PropertyAccessor();
        $this->application = $application;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->errors = [];
    }

    public function handle(Request $request, object $object)
    {
        $event = new RequestEvent($request, $object);
        $this->eventDispatcher->dispatch($event);

        $reflection = new \ReflectionObject($object);
        if ($parent = $reflection->getParentClass()) {
            $reflection = $parent;
        }

        Collection::collect($reflection->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED))
            ->each(function ($value) use ($request, $object) {
                /** @var ReflectionProperty $value */
                $field = $value->getName();
                $value = $request->request->get($field);
                if ('id' !== strtolower($field) && null !== $value && '' !== $value) {
                    $this->bindValue($object, $field, $value);
                }
            })
        ;

        $this->validate($object, $reflection);
    }

    public function isValid()
    {
        return empty($this->errors) ? true : false;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validate(object $object, \ReflectionClass $reflection): void
    {
        $this->errors = Collection::collect($this->validator->validate($object))
            ->flatten()
            ->map(function ($value) use ($reflection) {
                /* @var ConstraintViolationInterface $value */
                return sprintf('<b><i>%s</i></b>: %s', $this->translator->trans(sprintf('label.%s.%s', Inflector::tableize($reflection->getShortName()), Inflector::tableize($value->getPropertyPath()))), $this->translator->trans($value->getMessage()));
            })
            ->toArray()
        ;
    }

    private function bindValue(object $object, string $field, $value): void
    {
        try {
            $this->propertyAccessor->setValue($object, $field, $value);
        } catch (\Exception $e) {
            $service = $this->application->getService(new \ReflectionObject($object), $field);
            if ($service) {
                $this->propertyAccessor->setValue($object, $field, $service->get($value));
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace KejawenLab\Application\SemartHris\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use KejawenLab\Application\SemartHris\Component\Encryptor\Encryptor;
use KejawenLab\Application\SemartHris\Configuration\Encrypt;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class EncryptorSubscriber implements EventSubscriber
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var Encrypt
     */
    private $encrypt;

    /**
     * @param AnnotationReader          $annotationReader
     * @param PropertyAccessorInterface $propertyAccess
     * @param Encryptor                 $encryptor
     */
    public function __construct(AnnotationReader $annotationReader, PropertyAccessorInterface $propertyAccess, Encryptor $encryptor)
    {
        $this->annotationReader = $annotationReader;
        $this->propertyAccessor = $propertyAccess;
        $this->encryptor = $encryptor;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        $this->readAnnotation(new \ReflectionObject($entity));
        $this->process($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        $this->readAnnotation(new \ReflectionObject($entity));
        $this->process($entity);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        $this->readAnnotation(new \ReflectionObject($entity));
        $this->process($entity, 'decrypt');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate, Events::postLoad];
    }

    /**
     * @param object $entity
     * @param string $operation
     */
    private function process($entity, string $operation = 'encrypt'): void
    {
        if (!$this->encrypt) {
            return;
        }

        $properties = $this->encrypt->getProperties();
        foreach ($properties as $property) {
            $plain = $this->propertyAccessor->getValue($entity, $property);
            if ($plain) {
                $encrypted = $this->encryptor->$operation($plain, $this->propertyAccessor->getValue($entity, $this->encrypt->getKeyStore()));
                $this->propertyAccessor->setValue($entity, $property, $encrypted);
            }
        }

        $this->propertyAccessor->setValue($entity, $this->encrypt->getKeyStore(), $this->encryptor->getKey());
    }

    /**
     * @param \ReflectionObject $reflection
     */
    private function readAnnotation(\ReflectionObject $reflection): void
    {
        $this->encrypt = $this->annotationReader->getClassAnnotation($reflection, Encrypt::class);
    }
}

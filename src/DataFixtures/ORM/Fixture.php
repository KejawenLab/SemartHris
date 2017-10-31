<?php

namespace KejawenLab\Application\SemartHris\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture as Base;
use Doctrine\Common\Persistence\ObjectManager;
use KejawenLab\Application\SemartHris\Util\StringUtil;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@kejawenlab.id>
 */
abstract class Fixture extends Base
{
    const REF_KEY = 'ref';

    /**
     * @return string
     */
    abstract protected function getFixtureFilePath(): string;

    /**
     * @return mixed
     */
    abstract protected function createNew();

    /**
     * @return string
     */
    abstract protected function getReferenceKey(): string;

    public function load(ObjectManager $manager)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($this->getData() as $data) {
            $entity = $this->createNew();
            foreach ($data as $key => $value) {
                if (self::REF_KEY === $key) {
                    $this->setReference(StringUtil::uppercase(sprintf('%s#%s', $this->getReferenceKey(), $value)), $entity);
                } else {
                    if (false !== strpos($value, self::REF_KEY)) {
                        $value = $this->getReference(StringUtil::uppercase(str_replace('ref:', '', $value)));
                    }

                    $accessor->setValue($entity, $key, $value);
                }
            }

            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        $path = sprintf('%s/data/%s', $this->container->getParameter('kernel.project_dir'), $this->getFixtureFilePath());

        return Yaml::parse(file_get_contents($path));
    }
}

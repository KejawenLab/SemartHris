<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
trait PrimaryableTrait
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @Groups({"read"})
     */
    protected $id;

    public function getId(): string
    {
        return (string) $this->id;
    }
}

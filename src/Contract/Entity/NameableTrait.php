<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Contract\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHLAK\Twine\Str;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
trait NameableTrait
{
    /**
     * @ORM\Column(name="nama", type="string", length=77)
     *
     * @Assert\Length(max=77)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    protected $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = Str::make($name)->uppercase()->__toString();
    }
}

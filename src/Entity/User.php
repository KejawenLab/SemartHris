<?php

declare(strict_types=1);

namespace KejawenLab\Semart\Skeleton\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use KejawenLab\Semart\Skeleton\Contract\Entity\PrimaryableTrait;
use KejawenLab\Semart\Skeleton\Query\Searchable;
use KejawenLab\Semart\Skeleton\Query\Sortable;
use KejawenLab\Semart\Skeleton\Validator\UniqueEntity;
use PHLAK\Twine\Str;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="semart_pengguna", indexes={@ORM\Index(name="semart_pengguna_search_idx", columns={"nama_pengguna"})})
 * @ORM\Entity()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Searchable({"group.code", "group.name", "fullName", "username"})
 * @Sortable({"group.name", "fullName", "username"})
 *
 * @UniqueEntity(fields={"username"}, repositoryClass="KejawenLab\Semart\Skeleton\Repository\UserRepository")
 *
 * @author Muhamad Surya Iksanudin <surya.iksanudin@gmail.com>
 */
class User implements UserInterface, \Serializable
{
    use BlameableEntity;
    use PrimaryableTrait;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="KejawenLab\Semart\Skeleton\Entity\Group")
     * @ORM\JoinColumn(name="grup_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     **/
    private $group;

    /**
     * @ORM\Column(name="nama_lengkap", type="string", length=77, nullable=true)
     *
     * @Assert\Length(max=77)
     *
     * @Groups({"read"})
     */
    private $fullName;

    /**
     * @ORM\Column(name="nama_pengguna", type="string", length=12, unique=true)
     *
     * @Assert\Length(max=17)
     * @Assert\NotBlank()
     *
     * @Groups({"read"})
     */
    private $username;

    /**
     * @ORM\Column(name="foto_profil", type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Groups({"read"})
     */
    private $profileImage;

    /**
     * @ORM\Column(name="kata_sandi", type="string")
     */
    private $password;

    private $plainPassword;

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): void
    {
        $this->group = $group;
    }

    public function getFullName(): string
    {
        return $this->fullName ?: $this->username;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = Str::make($fullName)->uppercase();
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = Str::make($username)->lowercase();
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(string $profileImage): void
    {
        $this->profileImage = $profileImage;
    }

    public function getRoles(): array
    {
        return ['ROLE_SEMART'];
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->fullName,
            $this->username,
            $this->password,
            $this->profileImage,
            $this->group,
        ]);
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->fullName, $this->username, $this->password, $this->profileImage, $this->group) = unserialize($serialized);
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}

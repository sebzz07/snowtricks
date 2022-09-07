<?php

namespace App\Entity;

use App\Repository\PicturesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;



#[ORM\Entity(repositoryClass: PicturesRepository::class)]
class Pictures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $pictureLink;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pictureName;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private $trick;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictureLink(): ?string
    {
        return $this->pictureLink;
    }

    public function setPictureLink(string $pictureLink): self
    {
        $this->pictureLink = $pictureLink;

        return $this;
    }

    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile(File $file): void
    {
        $this->file = $file;
    }
}

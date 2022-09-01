<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{

    public const PUBLICATION_STATUS_WAITING = 'Waiting_validation';
    public const PUBLICATION_STATUS_PUBLISHED = 'Published';
    public const PUBLICATION_STATUS_UNPUBLISHED = 'Unpublished';
    public const PUBLICATION_STATUSES = [
        self::PUBLICATION_STATUS_PUBLISHED,
        self::PUBLICATION_STATUS_WAITING,
        self::PUBLICATION_STATUS_UNPUBLISHED
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 9999)]
    private string $description;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Pictures::class, cascade: ['persist'])]
    #[Assert\Valid]
    private Collection $pictures;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class, cascade: ['persist'])]
    #[Assert\Valid]
    private Collection $video;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Choice(choices: self::PUBLICATION_STATUSES)]
    private string $publicationStatusTrick;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'tricks')]
    private Collection $categories;


    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $modified_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->modified_at = new \DateTimeImmutable();
        $this->pictures = new ArrayCollection();
        $this->video = new ArrayCollection();
        $this->publicationStatusTrick = self::PUBLICATION_STATUS_WAITING;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Pictures>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPictures(Pictures $pictures): self
    {
        if (!$this->pictures->contains($pictures)) {
            $this->pictures->add($pictures);
            $pictures->setTrick($this);
        }

        return $this;
    }

    public function removePictures(Pictures $pictures): self
    {
        if ($this->pictures->removeElement($pictures)) {
            // set the owning side to null (unless already changed)
            if ($pictures->getTrick() === $this) {
                $pictures->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideo(): Collection
    {
        return $this->video;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->video->contains($video)) {
            $this->video[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->video->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

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

    public function getPublicationStatusTrick(): ?string
    {
        return $this->publicationStatusTrick;
    }

    public function setPublicationStatusTrick(string $publicationStatusTrick): self
    {
        $this->publicationStatusTrick = $publicationStatusTrick;

        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setTrick($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getTrick() === $this) {
                $post->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeImmutable $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }
}

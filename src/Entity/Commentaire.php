<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 * @ApiResource()
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"commentaire:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"commentaire:read"})
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=FilDeDiscution::class, inversedBy="commentaires", cascade={"persist", "remove"})
     */
    private $filDeDiscution;

    public function __construct()
    {
        $this->createAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getFilDeDiscution(): ?FilDeDiscution
    {
        return $this->filDeDiscution;
    }

    public function setFilDeDiscution(?FilDeDiscution $filDeDiscution): self
    {
        $this->filDeDiscution = $filDeDiscution;

        return $this;
    }
}

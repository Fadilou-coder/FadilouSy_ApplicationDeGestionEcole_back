<?php

namespace App\Entity;

use App\Repository\FilDeDiscutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FilDeDiscutionRepository::class)
 * @ApiResource()
 */
class FilDeDiscution
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="filDeDiscution", cascade={"persist", "remove"})
     * @Groups({"commentaire:read"})
     */
    private $commentaires;

    /**
     * @ORM\OneToOne(targetEntity=ApprenantLivrablePartiel::class, inversedBy="filDeDiscution", cascade={"persist", "remove"})
     */
    private $apprenantLivrablePartiel;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFilDeDiscution($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFilDeDiscution() === $this) {
                $commentaire->setFilDeDiscution(null);
            }
        }

        return $this;
    }

    public function getApprenantLivrablePartiel(): ?ApprenantLivrablePartiel
    {
        return $this->apprenantLivrablePartiel;
    }

    public function setApprenantLivrablePartiel(?ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        $this->apprenantLivrablePartiel = $apprenantLivrablePartiel;

        return $this;
    }
}

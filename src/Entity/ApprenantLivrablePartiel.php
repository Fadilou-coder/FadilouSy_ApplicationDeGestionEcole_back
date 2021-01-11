<?php

namespace App\Entity;

use App\Repository\ApprenantLivrablePartielRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantLivrablePartielRepository::class)
 * @ApiResource(
 * )
 */
class ApprenantLivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"liv:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"status:whrite"})
     */
    private $etat;

    /**
     * @ORM\Column(type="date")
     */
    private $delai;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="apprenantLivrablePartiels")
     * @Groups({"collectApp:read"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=LivrablePartiel::class, inversedBy="apprenantLivrablePartiels")
     */
    private $livrablePartiel;

    /**
     * @ORM\OneToOne(targetEntity=FilDeDiscution::class, mappedBy="apprenantLivrablePartiel", cascade={"persist", "remove"})
     * @Groups({"commentaire:read", "liv:whrite"})
     */
    private $filDeDiscution;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDelai(): ?\DateTimeInterface
    {
        return $this->delai;
    }

    public function setDelai(\DateTimeInterface $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getLivrablePartiel(): ?LivrablePartiel
    {
        return $this->livrablePartiel;
    }

    public function setLivrablePartiel(?LivrablePartiel $livrablePartiel): self
    {
        $this->livrablePartiel = $livrablePartiel;

        return $this;
    }

    public function getFilDeDiscution(): ?FilDeDiscution
    {
        return $this->filDeDiscution;
    }

    public function setFilDeDiscution(?FilDeDiscution $filDeDiscution): self
    {
        $this->filDeDiscution = $filDeDiscution;

        // set (or unset) the owning side of the relation if necessary
        $newApprenantLivrablePartiel = null === $filDeDiscution ? null : $this;
        if ($filDeDiscution->getApprenantLivrablePartiel() !== $newApprenantLivrablePartiel) {
            $filDeDiscution->setApprenantLivrablePartiel($newApprenantLivrablePartiel);
        }

        return $this;
    }
}

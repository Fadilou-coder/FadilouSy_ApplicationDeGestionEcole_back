<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"groupe:read"}},
 *      attributes={
 *          "security"="is_granted('ROLE_Administrateur')",
 *          "security_message"="Vous n'avez pas acces à ce ressource",
 *      },
 *      routePrefix="/admin",
 *      collectionOperations={
 *          "get",
 *          "post",
 *          "get_app"={
 *              "method"="get",
 *              "path"="/groupes/apprenants",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          }
 *          
 *      },
 *      itemOperations={
 *          "get",
 *          "put"={
 *              "method"="put",
 *              "route_name"="add_apprenant"
 *          },
 *          "supp_app,"={
 *              "method"="delete",
 *              "path"="/groupes/{id}/apprenants/{Id}",
 *              
 *          }
 *      }
 * )
 * 
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreateAt;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes")
     * @Groups({"groupe:read", "apprenant:read"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups({"groupe:read"})
     */
    private $formateur;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupe")
     * @Groups({"groupe:read"})
     */
    private $promo;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->CreateAt;
    }

    public function setCreateAt(\DateTimeInterface $CreateAt): self
    {
        $this->CreateAt = $CreateAt;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenant->removeElement($apprenant);

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateur->removeElement($formateur);

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }
}

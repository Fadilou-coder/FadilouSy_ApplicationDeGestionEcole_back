<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"groupe:read"}},
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas acces à ce ressource",
 *      },
 *      routePrefix="/admin",
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "path"="/groupes",
 *              "route_name"="add_groupe"
 *          },
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
 *              "route_name"="del_apprenant"
 *          }
 *      },
 *     subresourceOperations={
 *          "api_promos_groupes_apprenants_get_subresource"={
 *              "method"="GET",
 *              "path"="/admnin/promos/{id}/groupes/{iD}/apprenants",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          }
 *     },
 * )
 * @UniqueEntity(
 *      "nom",
 *      message="Cet groupe existe deja"
 * )
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"principale:read", "promo:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo:whrite"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:read", "promo:whrite"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite", "promo:whrite"})
     */
    private $status= "En cours";

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreateAt;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes", cascade="persist")
     * @Groups({"groupe:read", "apprenant:read", "principale:read", "promo:whrite"})
     * @ApiSubresource
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes", cascade="persist")
     * @Groups({"groupe:read", "promo:whrite"})
     */
    private $formateur;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupe", cascade="persist")
     * @Groups({"groupe:read"})
     * @Assert\NotBlank(message="Le promo est obligatoire")
     */
    private $promo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="groupe")
     * @ApiSubresource
     */
    private $etatbriefgroupe;


    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->formateur = new ArrayCollection();
        $this->CreateAt = new \DateTime('now');
        $this->etatBriefGroupes = new ArrayCollection();
        $this->etatbriefgroupe = new ArrayCollection();
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

    public function getArchiver(): ?bool
    {
        return $this->archiver;
    }

    public function setArchiver(bool $archiver): self
    {
        $this->archiver = $archiver;

        return $this;
    }

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatbriefgroupe(): Collection
    {
        return $this->etatbriefgroupe;
    }

    public function addEtatbriefgroupe(EtatBriefGroupe $etatbriefgroupe): self
    {
        if (!$this->etatbriefgroupe->contains($etatbriefgroupe)) {
            $this->etatbriefgroupe[] = $etatbriefgroupe;
            $etatbriefgroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeEtatbriefgroupe(EtatBriefGroupe $etatbriefgroupe): self
    {
        if ($this->etatbriefgroupe->removeElement($etatbriefgroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatbriefgroupe->getGroupe() === $this) {
                $etatbriefgroupe->setGroupe(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"={"collectApp:read"}}},
 *          "getCollection_apprenant"={
 *              "method"="get",
 *              "path"="/formateurs/promo/{id}/referentiel/{iD}/competences",
 *              "normalization_context"={"groups"={"collectApp:read"}}
 *          },
 *          "getOneApprenantAvecCpmt"={
 *              "method"="get",
 *              "path"="/apprenant/{id}/promo/{iD}/referentiel/{Id}/competences",
 *              "normalization_context"={"groups"={"collectApp:read"}}
 *          },
 *          "getOneAppAvecBrief"={
 *              "method"="get",
 *              "path"="apprenants/{idap}/promo/{idp}/referentiel/{idr}/statistiques/briefs",
 *              "route_name"="brief_stat"
 *
 *          },
 *          "getStatistique"={
 *              "method"="get",
 *              "path"="/formateurs/promo/{idp}/referentiel/{idr}/statistiques/competences",
 *              "route_name"="form_stat"
 *          },
 *          "post"={
 *              "path"="/formateurs/livrablepartiels/{id}/commentaires",
 *              "denormalization_context"={"groups"={"liv:whrite"}},
 *              "route_name"="for_add_supp_liv"
 *
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/formateurs/livrablepartiels/{id}/commentaires",
 *              "normalization_context"={"groups"={"commentaire:read"}},
 *          },
 *          "add_supp_liv"={
 *              "method"="put",
 *              "path"="/formateurs/promo/{id}/brief/{iD}/livrablepartiels",
 *              "route_name"="add_supp_liv"
 *          },
 *     }
 * )
 */
class LivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="date")
     */
    private $delai;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreRendu;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreCorriger;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="livrablePartiel", cascade="persist")
     * @Groups({"collectApp:read", "liv:whrite", "status:whrite", "commentaire:read"})
     * @ApiSubresource
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromo::class, inversedBy="livrablePartiel")
     */
    private $briefMaPromo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver;

    public function __construct()
    {
        $this->apprenantLivrablePartiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getNbreRendu(): ?int
    {
        return $this->nbreRendu;
    }

    public function setNbreRendu(int $nbreRendu): self
    {
        $this->nbreRendu = $nbreRendu;

        return $this;
    }

    public function getNbreCorriger(): ?int
    {
        return $this->nbreCorriger;
    }

    public function setNbreCorriger(int $nbreCorriger): self
    {
        $this->nbreCorriger = $nbreCorriger;

        return $this;
    }

    /**
     * @return Collection|ApprenantLivrablePartiel[]
     */
    public function getApprenantLivrablePartiels(): Collection
    {
        return $this->apprenantLivrablePartiels;
    }

    public function addApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if (!$this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels[] = $apprenantLivrablePartiel;
            $apprenantLivrablePartiel->setLivrablePartiel($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getLivrablePartiel() === $this) {
                $apprenantLivrablePartiel->setLivrablePartiel(null);
            }
        }

        return $this;
    }

    public function getBriefMaPromo(): ?BriefMaPromo
    {
        return $this->briefMaPromo;
    }

    public function setBriefMaPromo(?BriefMaPromo $briefMaPromo): self
    {
        $this->briefMaPromo = $briefMaPromo;

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
}

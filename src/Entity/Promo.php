<?php

namespace App\Entity;

use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @Apiresource(
 *     normalizationContext={"groups"={"promo:read"}},
 *     denormalizationContext={"groups"={"promo:whrite"}},
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas acces à ce ressource",
 *      },
 *      routePrefix="/admin",
 *      collectionOperations={
 *          "get",
 *          "post" = {
 *              "method"="post",
 *              "path"="/promos",
 *              "route_name"="add_promo"
 *          },
 *          "get_grpPrincipale"={
 *              "method"="get",
 *              "path"="/promos/principal",
 *              "normalization_context"={"groups"={"principale:read"}}
 *          },
 *          "get_appAttente"={
 *              "method"="get",
 *              "path"="/promos/apprenants/attente",
 *          },
 *      },
 *      itemOperations={
 *          "get",
 *          "getOne_grpPrincipale"={
 *              "method"="get",
 *              "path"="/promos/{id}/principal",
 *              "normalization_context"={"groups"={"principale:read"}}
 *          },
 *          "getOne_ref"={
 *              "method"="get",
 *              "path"="/promos/{id}/referentiel",
 *              "normalization_context"={"groups"={"refs:read"}}
 *          },
 *          "getOne_appAttente"={
 *              "method"="get",
 *              "path"="/promos/{id}/apprenants/attente",
 *          },
 *          "get_formateur"={
 *              "method"="get",
 *              "path"="/promos/{id}/formateurs"
 *          },
 *          "put_promo"={
 *              "method"="put",
 *              "path"="/promos/{id}/referentiels",
 *          },
 *          "put_apprenant"={
 *              "method"="put",
 *              "path"="/promos/{id}/apprenants",
 *              "route_name"="add_supp_app"
 *          },
 *          "put_formateur"={
 *              "method"="put",
 *              "path"="/promos/{id}/formateurs",
 *              "route_name"="add_supp_for"
 *          },
 *          "put_statusGroupe"={
 *              "method"="put",
 *              "path"="/promos/{id}/groupes/{Id}"
 *          },
 *
 *      },
 *     subresourceOperations={
 *          "api_promos_groupes_apprenants_get_subresource"={
 *              "method"="GET",
 *              "path"="/admnin/promos/{id}/groupes/{iD}/apprenants",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          },
 *          "api_promos_brief_ma_promos_get_subresource"={
 *              "method"="GET",
 *              "path"="/formateurs/promos/{id}/briefs",
 *              "normalization_context"={"groups"={"brief:read"}}
 *          }
 *     },
 * )
 * @UniqueEntity(
 *      "titre",
 *      message="Cette Promo existe deja"
 * )
 *
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:whrite"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"promo:whrite"})
     */
    private $dateFinProvisoire;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"promo:whrite"})
     */
    private $dataFinReelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $etat = "creer";

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"promo:whrite"})
     */
    private $fabrique;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo", cascade="persist")
     * @Groups({"promo:read", "groupe:read", "promo:whrite"})
     * @ApiSubresource
     */
    private $groupe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="promo")
     * @Groups({"promo:whrite"})
     * @ApiSubresource
     */
    private $briefMaPromos;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     * @Groups({"promo:whrite"})
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="promo")
     * @Groups({"promo:whrite"})
     * @ApiSubresource
     */
    private $competencesValides;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo", cascade="persist")
     * @Groups ({"principale:read"})
     */
    private $apprenants;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="promo")
     */
    private $chats;

    /**
     * @ORM\ManyToMany(targetEntity=ProfilsDeSortie::class, mappedBy="promo")
     * @ApiSubresource
     */
    private $profilsDeSorties;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promos")
     */
    private $referentiels;

    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->profilsDeSorties = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFinProvisoire(): ?\DateTimeInterface
    {
        return $this->dateFinProvisoire;
    }

    public function setDateFinProvisoire(\DateTimeInterface $dateFinProvisoire): self
    {
        $this->dateFinProvisoire = $dateFinProvisoire;

        return $this;
    }

    public function getDataFinReelle(): ?\DateTimeInterface
    {
        return $this->dataFinReelle;
    }

    public function setDataFinReelle(\DateTimeInterface $dataFinReelle): self
    {
        $this->dataFinReelle = $dataFinReelle;

        return $this;
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

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

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
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setPromo($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getPromo() === $this) {
                $briefMaPromo->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }

    /**
     * @return Collection|CompetencesValides[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValides $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setPromo($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->removeElement($competencesValide)) {
            // set the owning side to null (unless already changed)
            if ($competencesValide->getPromo() === $this) {
                $competencesValide->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromo() === $this) {
                $apprenant->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setPromo($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getPromo() === $this) {
                $chat->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProfilsDeSortie[]
     */
    public function getProfilsDeSorties(): Collection
    {
        return $this->profilsDeSorties;
    }

    public function addProfilsDeSorty(ProfilsDeSortie $profilsDeSorty): self
    {
        if (!$this->profilsDeSorties->contains($profilsDeSorty)) {
            $this->profilsDeSorties[] = $profilsDeSorty;
            $profilsDeSorty->addPromo($this);
        }

        return $this;
    }

    public function removeProfilsDeSorty(ProfilsDeSortie $profilsDeSorty): self
    {
        if ($this->profilsDeSorties->removeElement($profilsDeSorty)) {
            $profilsDeSorty->removePromo($this);
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        $this->referentiels->removeElement($referentiel);

        return $this;
    }


}

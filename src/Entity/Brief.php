<?php

namespace App\Entity;

use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"brief:read"}},
 *      collectionOperations={
 *          "get"={
 *              "path"="formateur/briefs",
 *          },
 *          "get_brief_app"={
 *              "method"="GET",
 *              "path"="apprenants/promos/{id}/briefs",
 *          },
 *          "get_briefBrouillos_formateur"={
 *              "method"="GET",
 *              "path"="formateurs/{id}/briefs/broullons",
 *          },
 *          "get_briefvalide_formateur"={
 *              "method"="GET",
 *              "path"="formateurs/{id}/briefs/valide",
 *          },
 *          "get_Onebrief_promo"={
 *              "method"="GET",
 *              "path"="formateurs/promo/{id}/briefs/{idbr}",
 *          },
 *          "add_brief"={
 *              "path"="/formateurs/brief",
 *              "method"="POST",
 *              "access_control"="(is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_brief",
 *          },
 *          "dupliquer_brief"={
 *              "path"="/formateurs/brief/{id}",
 *              "method"="POST",
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="dupliquer_brief",
 *          },
 *          "add_url"={
 *              "path"="/apprenants/{idAp}/groupe/{idGr}/livrables",
 *              "method"="POST",
 *              "access_control"="(is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_url",
 *          },
 *     },
 *     itemOperations={
 *          "get",
 *          "put_brief"={
 *              "path"="/formateurs/promo/{idPr}/brief/{idBr}",
 *              "method"="PUT",
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="put_brief",
 *          },
 *          "affecter_brief"={
 *              "path"="/formateurs/promo/{idPr}/brief/{idBr}/assignation",
 *              "method"="PUT",
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="affecter_brief",
 *          },
 *     }
 * )
 */
class Brief
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"brief:read", "brApp:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"appbr:read", "brief:read", "brApp:read"})
     */
    private $NomBrief;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="blob")
     */
    private $imagePromo;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"brief:whrite"})
     */
    private $archiver;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"brief:whrite"})
     */
    private $etatBrouillonsAssigneValide;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="brief")
     */
    private $briefMaPromos;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @Groups ({"brief:read", "brief:whrite"})
     */
    private $tag;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, mappedBy="brief")
     * @Groups ({"brief:read", "brief:whrite"})
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     * @Groups ({"brief:read", "brief:whrite"})
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="Brief")
     */
    private $etatBriefGroupes;

    /**
     * @ORM\ManyToMany(targetEntity=Niveaux::class, inversedBy="briefs")
     * @Groups ({"brief:read", "brief:whrite"})
     */
    private $niveaux;


    public function __construct()
    {
        $this->briefMaPromos = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->livrableAttendus = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->etatBriefGroupes = new ArrayCollection();
        $this->niveauxes = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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

    public function getNomBrief(): ?string
    {
        return $this->NomBrief;
    }

    public function setNomBrief(string $NomBrief): self
    {
        $this->NomBrief = $NomBrief;

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

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getModaliteEvaluation(): ?string
    {
        return $this->modaliteEvaluation;
    }

    public function setModaliteEvaluation(string $modaliteEvaluation): self
    {
        $this->modaliteEvaluation = $modaliteEvaluation;

        return $this;
    }

    public function getImagePromo()
    {
        return $this->imagePromo;
    }

    public function setImagePromo($imagePromo): self
    {
        $this->imagePromo = $imagePromo;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getEtatBrouillonsAssigneValide(): ?string
    {
        return $this->etatBrouillonsAssigneValide;
    }

    public function setEtatBrouillonsAssigneValide(string $etatBrouillonsAssigneValide): self
    {
        $this->etatBrouillonsAssigneValide = $etatBrouillonsAssigneValide;

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
            $briefMaPromo->setBrief($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getBrief() === $this) {
                $briefMaPromo->setBrief(null);
            }
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->livrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus[] = $livrableAttendu;
            $livrableAttendu->addBrief($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->removeElement($livrableAttendu)) {
            $livrableAttendu->removeBrief($this);
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefGroupes(): Collection
    {
        return $this->etatBriefGroupes;
    }

    public function addEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if (!$this->etatBriefGroupes->contains($etatBriefGroupe)) {
            $this->etatBriefGroupes[] = $etatBriefGroupe;
            $etatBriefGroupe->setBrief($this);
        }

        return $this;
    }

    public function removeEtatBriefGroupe(EtatBriefGroupe $etatBriefGroupe): self
    {
        if ($this->etatBriefGroupes->removeElement($etatBriefGroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatBriefGroupe->getBrief() === $this) {
                $etatBriefGroupe->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Niveaux[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveaux $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveaux $niveau): self
    {
        $this->niveaux->removeElement($niveau);

        return $this;
    }
}

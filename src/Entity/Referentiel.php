<?php

namespace App\Entity;

use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @Apiresource(
 *      routePrefix="/admin",
 *      collectionOperations={
 *          "get"={
 *              "path"="/referentiels",
 *              "normalization_context"={"groups"={"ref:read"}},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get_grpecomp"={
 *              "method"="GET",
 *              "path"="/referentiels/grpecompetences",
 *              "normalization_context"={"groups"={"grpecompt:read"}},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "post"={
 *              "method"="POST",
 *              "path"="/referentiels",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name" = "addRef",
 *          },
 *          
 *      },
 *      itemOperations={
 *          "get"={
 *              "method"="get",
 *              "path"="/referentiels/{id}",
 *              "normalization_context"={"groups"={"ref:read"}},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "put"={
 *              "method"="put",
 *              "path"="/referentiels/{id}",
 *              "access_control"="(is_granted('ROLE_Administrateur'), or is_granted('ROLE_Formateur') or is_granted('ROLE_CM') or is_granted('ROLE_Apprenant'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="put_ref"
 *          },
 *          "delete"={
 *              "method"="delete",
 *              "path"="/referentiels/{id}",
 *              "route_name"="delRef",
 *          }
 *     }
 * )
 * @UniqueEntity(
 *      fields={"libelle"},
 *      message="Le libelle doit être unique"
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref:read", "refs:whrite", "promo:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     * @Groups({"ref:read", "refs:whrite", "promo:whrite"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=510)
     * @Assert\NotBlank(message="La presentation est obligatoire")
     * @Groups({"ref:read", "refs:whrite", "promo:whrite"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="blob")
     * @Assert\NotBlank(message="Le programme est obligatoire")
     * @Groups({"ref:read", "refs:whrite", "promo:whrite"})
     */
    private $programme;

    /**
     * @ORM\ManyToMany(targetEntity=GrpeCompetences::class, inversedBy="referentiels")
     * @Groups({"ref:read", "grpecompt:read", "grpecomptences:read", "comptences:read", "refs:read", "refs:whrite"})
     * @ApiSubresource
     */
    private $grpeCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="referentiel")
     * @ApiSubresource
     */
    private $competencesValides;

    /**
     * @ORM\Column(type="string", length=510)
     * @Groups({"ref:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=510)
     * @Groups({"ref:read"})
     */
    private $critereAdmission;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="referentiels")
     */
    private $promos;

    public function __construct()
    {
        $this->grpeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getProgramme()
    {
        if ($this->programme) {
            return base64_encode(stream_get_contents($this->programme));
        }
       
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    

    /**
     * @return Collection|GrpeCompetences[]
     */
    public function getGrpeCompetences(): Collection
    {
        return $this->grpeCompetences;
    }

    public function addGrpeCompetence(GrpeCompetences $grpeCompetence): self
    {
        if (!$this->grpeCompetences->contains($grpeCompetence)) {
            $this->grpeCompetences[] = $grpeCompetence;
        }

        return $this;
    }

    public function removeGrpeCompetence(GrpeCompetences $grpeCompetence): self
    {
        $this->grpeCompetences->removeElement($grpeCompetence);

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
            $competencesValide->setReferentiel($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->removeElement($competencesValide)) {
            // set the owning side to null (unless already changed)
            if ($competencesValide->getReferentiel() === $this) {
                $competencesValide->setReferentiel(null);
            }
        }

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

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->addReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeReferentiel($this);
        }

        return $this;
    }
}

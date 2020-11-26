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
 *              "normalization_context"={"groups"={"ref:read"}},
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "get_grpecomp"={
 *              "method"="GET",
 *              "path"="/referentiels/grpecompetences",
 *              "normalization_context"={"groups"={"grpecompt:read"}},
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "post"={
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"grpecomptences:read"}},
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM') or is_granted('ROLE_Apprenant'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "put"={
 *              "method"="put",
 *              "path"="api/admin/referencetiels/{id}",
 *              "access_control"="(is_granted('ROLE_Administrateur'), or is_granted('ROLE_Formateur') or is_granted('ROLE_CM') or is_granted('ROLE_Apprenant'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="put_grpecompt",
 *          },
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
     * @Groups({"ref:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     * @Groups({"ref:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La presentation est obligatoire")
     */
    private $presentation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le programme est obligatoire")
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Critere d'admission est obligatoire")
     */
    private $critereAdmission;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $critereEvaluation;

    /**
     * @ORM\ManyToMany(targetEntity=GrpeCompetences::class, inversedBy="referentiels")
     * @Groups({"ref:read", "grpecompt:read", "grpecomptences:read"})
     * @ApiSubresource
     */
    private $grpeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    public function __construct()
    {
        $this->grpeCompetences = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

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

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

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
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }
}

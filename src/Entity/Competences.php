<?php

namespace App\Entity;

use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *
 *          "get_niveaux"={
 *              "method"="GET",
 *              "path"="/admin/competences",
 *              "normalization_context"={"groups"={"niveaux:read"}},
     *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "post"={
 *              "path"="/admin/competences",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "denormalization_context"={"groups"={"cmpt:whrite"}}
 *          },
 *      },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/competences/{id}",
 *              "normalization_context"={"groups"={"niveaux:read"}},
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 * 
 *          "put"={
 *              "method"="put",
 *              "path"="/admin/competences/{id}",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "denormalization_context"={"groups"={"cmpt:whrite"}},
 *              "normalization_context"={"groups"={"niveaux:read"}},
 *          }
 *     },
 * )
 * @UniqueEntity(
 *      "libelle",
 *      message="Cet competence existe deja"
 * )
 * @ApiFilter(SearchFilter::class, properties={"archiver": "partial"})
 */
class Competences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"refs:whrite", "cmpt:read", "grpe:read", "compt:read", "grpecompt:read", "comptences:read", "refs:read", "grpcmpt:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"refs:whrite", "cmpt:read", "grpe:read", "compt:read", "grpecompt:read", "comptences:read", "refs:read", "cmpt:whrite", "grpcmpt:whrite", "collectApp:read"})
     * @Assert\NotBlank(message="Le libelle du competence est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GrpeCompetences::class, mappedBy="competences")
     */
    private $grpeCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\OneToMany(targetEntity=Niveaux::class, mappedBy="competences", cascade="persist")
     * @Groups({"niveaux:read", "cmpt:whrite"})
     * @Assert\NotBlank(message="Les niveaux sont obligatoire")
     * @Assert\Count(
     *      min = 3,
     *      max = 3,
     *      minMessage = "Une competence doit avoir 3 niveaux",
     *      maxMessage = "Une competence doit avoir 3 niveaux"
     * )
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="competences")
     */
    private $competencesValides;

    public function __construct()
    {
        $this->grpeCompetences = new ArrayCollection();
        $this->niveau = new ArrayCollection();
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
            $grpeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGrpeCompetence(GrpeCompetences $grpeCompetence): self
    {
        if ($this->grpeCompetences->removeElement($grpeCompetence)) {
            $grpeCompetence->removeCompetence($this);
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
     * @return Collection|Niveaux[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveaux $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
            $niveau->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveau(Niveaux $niveau): self
    {
        if ($this->niveau->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetences() === $this) {
                $niveau->setCompetences(null);
            }
        }

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
            $competencesValide->setCompetences($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->removeElement($competencesValide)) {
            // set the owning side to null (unless already changed)
            if ($competencesValide->getCompetences() === $this) {
                $competencesValide->setCompetences(null);
            }
        }

        return $this;
    }

    
}

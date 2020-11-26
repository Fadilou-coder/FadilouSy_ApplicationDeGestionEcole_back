<?php

namespace App\Entity;

use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "path"="/admin/gprecompetences",
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "get_niveaux"={
 *              "method"="GET",
 *              "path"="/admin/competences",
 *              "normalization_context"={"groups"={"niveaux:read"}},
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "post"={
 *              "path"="/admin/competences",
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_niveau"
 *          },
 *      },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/competences/{id}",
 *              "normalization_context"={"groups"={"niveaux:read"}},
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 * 
 *          "put"={
 *              "method"="put",
 *              "path"="/admin/competences/{id}",
 *              "access_control"="(is_granted('ROLE_Administrateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="put_niveau",
 *          }
 *     },
 * )
 * @ApiFilter(SearchFilter::class, properties={"archiver": "partial"})
 */
class Competences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cmpt:read", "grpe:read", "compt:read", "grpecompt:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cmpt:read", "grpe:read", "compt:read", "grpecompt:read"})
     * @Assert\NotBlank(message="Le libelle est obligatoire")
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
     * @ORM\OneToMany(targetEntity=Niveaux::class, mappedBy="competences",cascade={"persist"})
     * @Groups({"niveaux:read"})
     * @Assert\NotBlank(message="Les niveaux sont obligatoire")
     */
    private $niveaux;

    public function __construct()
    {
        $this->grpeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveaux(Niveaux $niveaux): self
    {
        if (!$this->niveaux->contains($niveaux)) {
            $this->niveaux[] = $niveaux;
            $niveaux->setCompetences($this);
        }

        return $this;
    }

    public function removeNiveaux(Niveaux $niveaux): self
    {
        if ($this->niveaux->removeElement($niveaux)) {
            // set the owning side to null (unless already changed)
            if ($niveaux->getCompetences() === $this) {
                $niveaux->setCompetences(null);
            }
        }

        return $this;
    }
}

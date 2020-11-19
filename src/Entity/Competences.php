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
 *      normalizationContext={"groups"={"cmpt:read"}},
 *     attributes={
 *          "security"="is_granted('ROLE_Administrateur', 'ROLE_FORMATEUR', 'ROLE_CM')",
 *          "security_message"="Vous n'avez pas acces à ce ressource"
 *      },
 *      collectionOperations={
 *          "get"={"path"="/admin/competences"},
 *          "post"={"path"="/admin/competences"},
 *      },
 *     itemOperations={
 *          "get"={"path"="/admin/competences/{id}"},
 *          "put"={"path"="/admin/competences/{id}"}
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
     * @Groups({"cmpt:read", "grpe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cmpt:read", "grpe:read"})
     *@Assert\NotBlank(message="Le libelle est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cmpt:read", "grpe:read"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=GrpeCompetences::class, mappedBy="competences")
     */
    private $grpeCompetences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    public function __construct()
    {
        $this->grpeCompetences = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

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
}

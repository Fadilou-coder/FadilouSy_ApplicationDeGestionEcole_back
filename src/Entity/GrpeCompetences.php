<?php

namespace App\Entity;

use App\Repository\GrpeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=GrpeCompetencesRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"grpe:read"}},
 *     collectionOperations={
 *     "get_competences"={
 *          "method"="GET",
 *          "path"="/admin/grpecompetences",
 *          "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *     },
 *     "post_groupe_competences"={
 *          "method"="POST",
 *          "path"="/admin/grpecompetences",
 *          "access_control"="(is_granted('ROLE_Administrateur'))",
 *          "access_control_message"="Vous n'avez pas access à cette Ressource",
 *      },
 * },
 *      itemOperations={
 *          "get"={"path"="/admin/grpecompetences/{id}","security"="is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM')"},
 *          "put"={"path"="/admin/grpecompetences/{id}","security"="is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM')"},
 *
 *     },
 *
 * )
 * @ApiFilter(SearchFilter::class, properties={"archiver": "partial"})
 */
class GrpeCompetences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grpe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     * @Groups({"grpe:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="grpeCompetences")
     * @Assert\NotBlank (message="un grpe de compétences a au moins une sous compétence")
     * @Groups({"grpe:read"})
     * @ApiSubresource
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archiver=false; 

    public function __construct()
    {
        $this->competences = new ArrayCollection();
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
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    public function getArchiver(): ?bool
    {
        return $this->archiver;
    }

    public function setArchiver(?bool $archiver): self
    {
        $this->archiver = $archiver;

        return $this;
    }
}

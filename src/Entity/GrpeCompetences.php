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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=GrpeCompetencesRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"compt:read"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/gprecompetences",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "get_competences"={
 *              "method"="GET",
 *              "path"="/admin/grpecompetences/competences",
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *          },
 *          "post"={
 *              "path"="/admin/grpecompetences",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "denormalization_context"={"groups"={"grpcmpt:whrite"}},
 *              "route_name"="addGrpCompt"
 *          },
 * },
 *      itemOperations={
 *          "get"={
 *                  "path"="/admin/gprecompetences/{id}",
 *                  "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')"
 *          },
 *          
 *          "put"={
 *              "method"="put",
 *              "path"="/admin/grpecompetences/{id}",
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "denormalization_context"={"groups"={"grpcmpt:whrite"}},
 *              "route_name"="editGrpCompt",
 *          },
 *      
 *          "delete"={
 *              "method"="delete",
 *              "path"="/admin/grpecompetences/{id}",
 *              "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *              "route_name" = "delGrpCompt",
 *          },
 *     },
 *
 * )
 * @UniqueEntity(
 *      "libelle",
 *      message="Cet groupe de competences existe deja"
 * )
 * @ApiFilter(SearchFilter::class, properties={"archiver": "partial"})
 */
class GrpeCompetences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cmpt:read", "grpe:read", "ref:read", "grpecompt:read", "grpecomptences:read", "refs:whrite", "compt:read", "cmpt:whrite", "niveaux:read"})
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     * @Groups({"refs:whrite", "cmpt:read", "grpe:read", "ref:read", "grpecompt:read", "grpecomptences:read", "grpcmpt:whrite", "compt:read", "niveaux:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=510)
     * @Groups({"refs:whrite", "cmpt:read", "grpe:read", "grpcmpt:whrite", "compt:read", "niveaux:read"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="grpeCompetences", cascade="persist")
     * @Assert\NotBlank (message="un grpe de compétences a au moins une sous compétence")
     * @Groups({"refs:whrite", "compt:read", "grpecompt:read", "comptences:read", "refs:read", "grpcmpt:whrite"})
     * @ApiSubresource
     */
    
    private $competences;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archiver=false;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="grpeCompetences")
     */
    private $referentiels; 

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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
            $referentiel->addGrpeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGrpeCompetence($this);
        }

        return $this;
    }
}
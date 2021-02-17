<?php

namespace App\Entity;

use App\Repository\NiveauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=NiveauxRepository::class)
 * @Apiresource()
 */
class Niveaux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"niveaux:read", "cmpt:whrite", "brief:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux:read", "cmpt:whrite"})
     * @Assert\NotBlank(message="Le libelle Niveaux est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=510)
     * @Groups({"niveaux:read", "cmpt:whrite"})
     * @Assert\NotBlank(message="critere d'evaluation Niveaux est obligatoire")
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=510)
     * @Groups({"niveaux:read", "cmpt:whrite"})
     * @Assert\NotBlank(message="groupe d'action Niveaux est obligatoire")
     */
    private $groupeAction;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\ManyToOne(targetEntity=Competences::class, inversedBy="niveau")
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="niveaux")
     */
    private $briefs;

    public function __construct()
    {
        $this->brief = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getGroupeAction(): ?string
    {
        return $this->groupeAction;
    }

    public function setGroupeAction(string $groupeAction): self
    {
        $this->groupeAction = $groupeAction;

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

    public function getCompetences(): ?Competences
    {
        return $this->competences;
    }

    public function setCompetences(?Competences $competences): self
    {
        $this->competences = $competences;

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addNiveau($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeNiveau($this);
        }

        return $this;
    }

}

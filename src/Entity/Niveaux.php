<?php

namespace App\Entity;

use App\Repository\NiveauxRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NiveauxRepository::class)
 */
class Niveaux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"niveaux:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"niveaux:read"})
     */
    private $groupeAction;

    /**
     * @ORM\ManyToOne(targetEntity=Competences::class, inversedBy="niveaux")
     */
    private $competences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver;

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

    public function getCompetences(): ?Competences
    {
        return $this->competences;
    }

    public function setCompetences(?Competences $competences): self
    {
        $this->competences = $competences;

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

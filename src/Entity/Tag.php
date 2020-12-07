<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *      routePrefix="/admin",
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas acces à ce ressource"
 *      },
 *      collectionOperations={
 *          "get"={"normalization_context"={"groups"={"tag:read"}}},
 *          "post"={"normalization_context"={"groups"={"tag:whrite"}}},
 *      },
 *      itemOperations={
 *          "get"={"normalization_context"={"groups"={"tag:read"}}},
 *          "put"={"normalization_context"={"groups"={"tag:whrite"}}},
 *      },
 * )
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"grptag:read", "grptg:whrite", "tag:whrite", "brief:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le libelle est obligatoire")
     * @Groups({"grptag:read", "grptg:whrite", "tag:whrite"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tag")
     * @Groups({"tag:read", "tag:whrite"})
     */
    private $groupeTags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="tag")
     */
    private $briefs;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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

    /**
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
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
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeTag($this);
        }

        return $this;
    }
}

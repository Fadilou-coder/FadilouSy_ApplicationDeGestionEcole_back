<?php

namespace App\Entity;

use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource(
 *      routePrefix="/admin",
 *      normalizationContext={"groups"={"grptag:read"}},
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas acces à ce ressource"
 *      },
 *      collectionOperations={
 *          "get"={"path"="/grptags"},
 *          "post"={
 *              "path"="/grptags",
 *              "denormalization_context"={"groups"={"grptg:whrite"}}
 *          }
 *      },
 *      itemOperations={
 *          "get"={"path"="/grptags"},
 *          "put"={
 *              "path"="/grptags/{id}",
 *              "denormalization_context"={"groups"={"grptg:whrite"}}
 *          }
 *      },
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"tag:read", "tag:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read", "grptg:whrite"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tag:read", "grptg:whrite"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags", cascade="persist")
     * @Groups({"grptag:read", "grptg:whrite"})
     * @ApiSubresource()
     */
    private $tag;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

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

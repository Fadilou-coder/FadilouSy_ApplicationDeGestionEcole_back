<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR') )",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          }     
 *      },
 *     itemOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "put"={
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     }
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateur")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="formateur")
     * @ApiSubresource
     */
    private $briefs;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="formateurs")
     */
    private $promos;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->promos = new ArrayCollection();
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

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
            $brief->setFormateur($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getFormateur() === $this) {
                $brief->setFormateur(null);
            }
        }

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
            $promo->addFormateur($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeFormateur($this);
        }

        return $this;
    }
}

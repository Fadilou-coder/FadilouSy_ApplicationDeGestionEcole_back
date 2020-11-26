<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "access_control"="(is_granted(is_granted('ROLE_Administrateur') or 'ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }     
 *      },
 *     itemOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "put"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 * )
 */
class Formateur extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateur")
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
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
}

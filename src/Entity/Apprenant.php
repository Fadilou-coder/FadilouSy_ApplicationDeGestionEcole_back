<?php

namespace App\Entity;

use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }, 
 *          "post_apprenant"={
 *              "method"="post",
 *              "path"="/apprenant",
 *              "access_control"="(is_granted(is_granted('ROLE_Administrateur') or 'ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_app",
 *          }     
 *      },
 *     itemOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "put"={
 *              "access_control"="(is_granted('ROLE_Administrateur') or is_granted('ROLE_Formateur'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          }
 *     },
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant")
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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }
}

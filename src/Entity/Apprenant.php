<?php

namespace App\Entity;

use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *     subresourceOperations={
 *          "api_promos_groupes_apprenants_get_subresource"={
 *              "method"="GET",
 *              "path"="/admnin/promos/{id}/groupes/{iD}/apprenants",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          }
 *     },
 *      collectionOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          }, 
 *          "post_apprenant"={
 *              "method"="post",
 *              "path"="/apprenant",
 *              "access_control"="(is_granted(is_granted('ROLE_ADMIN') or 'ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_app",
 *          }     
 *      },
 *     itemOperations={
 *          "get"={
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "normalization_context"={"groups"={"apprenant:read"}}
 *          },
 *          "put"={
 *              "access_control"="(is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource"
 *          },
 *          "put_liv"={
 *              "method"="PUT",
 *              "path"="/apprenants/{id}/livrablepartiels/{iD}",
 *              "route_name"="put_status"
 *          }
 *
 *     }
 *
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenant")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity=ApprenantLivrablePartiel::class, mappedBy="apprenant")
     * @Groups({"status:whrite"})
     */
    private $apprenantLivrablePartiels;

    /**
     * @ORM\OneToMany(targetEntity=BriefApprenant::class, mappedBy="apprenant")
     * @Groups({"appbr:read"})
     */
    private $briefApprenant;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="apprenant")
     * @Groups({"collectApp:read"})
     */
    private $competencesValides;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants")
     */
    private $promo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $attente=true;

    /**
     * @ORM\OneToMany(targetEntity=LivrableAttenduApprenant::class, mappedBy="apprenant")
     */
    private $livrableAttendu;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilsDeSortie::class, inversedBy="apprenant", cascade="persist")
     */
    private $profilsDeSortie;


    public function __construct($email)
    {
        $this->groupes = new ArrayCollection();
        $this->setNom("")
            ->setPrenom("")
            ->setImage("")
            ->setPassword("bienvenue")
            ->setEmail($email);
        $this->apprenantLivrablePartiels = new ArrayCollection();
        $this->briefApprenant = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
        $this->livrableAttendu = new ArrayCollection();
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

    /**
     * @return Collection|ApprenantLivrablePartiel[]
     */
    public function getApprenantLivrablePartiels(): Collection
    {
        return $this->apprenantLivrablePartiels;
    }

    public function addApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if (!$this->apprenantLivrablePartiels->contains($apprenantLivrablePartiel)) {
            $this->apprenantLivrablePartiels[] = $apprenantLivrablePartiel;
            $apprenantLivrablePartiel->setApprenant($this);
        }

        return $this;
    }

    public function removeApprenantLivrablePartiel(ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        if ($this->apprenantLivrablePartiels->removeElement($apprenantLivrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($apprenantLivrablePartiel->getApprenant() === $this) {
                $apprenantLivrablePartiel->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefApprenant[]
     */
    public function getBriefApprenant(): Collection
    {
        return $this->briefApprenant;
    }

    public function addBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if (!$this->briefApprenant->contains($briefApprenant)) {
            $this->briefApprenant[] = $briefApprenant;
            $briefApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeBriefApprenant(BriefApprenant $briefApprenant): self
    {
        if ($this->briefApprenant->removeElement($briefApprenant)) {
            // set the owning side to null (unless already changed)
            if ($briefApprenant->getApprenant() === $this) {
                $briefApprenant->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompetencesValides[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValides $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->removeElement($competencesValide)) {
            // set the owning side to null (unless already changed)
            if ($competencesValide->getApprenant() === $this) {
                $competencesValide->setApprenant(null);
            }
        }

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getAttente(): ?bool
    {
        return $this->attente;
    }

    public function setAttente(bool $attente): self
    {
        $this->attente = $attente;

        return $this;
    }

    /**
     * @return Collection|LivrableAttenduApprenant[]
     */
    public function getLivrableAttendu(): Collection
    {
        return $this->livrableAttendu;
    }

    public function addLivrableAttendu(LivrableAttenduApprenant $livrableAttendu): self
    {
        if (!$this->livrableAttendu->contains($livrableAttendu)) {
            $this->livrableAttendu[] = $livrableAttendu;
            $livrableAttendu->setApprenant($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttenduApprenant $livrableAttendu): self
    {
        if ($this->livrableAttendu->removeElement($livrableAttendu)) {
            // set the owning side to null (unless already changed)
            if ($livrableAttendu->getApprenant() === $this) {
                $livrableAttendu->setApprenant(null);
            }
        }

        return $this;
    }

    public function getProfilsDeSortie(): ?ProfilsDeSortie
    {
        return $this->profilsDeSortie;
    }

    public function setProfilsDeSortie(?ProfilsDeSortie $profilsDeSortie): self
    {
        $this->profilsDeSortie = $profilsDeSortie;

        return $this;
    }

}

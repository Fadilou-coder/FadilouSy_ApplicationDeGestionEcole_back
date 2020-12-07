<?php

namespace App\Entity;

use App\Repository\ProfilsDeSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilsDeSortieRepository::class)
 * @Apiresource (
 *     normalizationContext={"groups"={"pr:read"}},
 *     denormalizationContext={"groups"={"pr:whrite"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/admin/profilsorties",
 *          },
 *          "get_app_by_promo"={
 *              "method"="GET",
 *              "path"="/admin/promo/{id}/profilsorties",
 *          },
 *          "post"={
 *              "path"="/admin/profilsorties",
 *          },
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/admin/profilsorties/{id}",
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/admin/profilsortie/{id}",
 *          }
 *     }
 *  )
 */
class ProfilsDeSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"pr:read", "pr:whrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"pr:read", "pr:whrite"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilsDeSortie", cascade="persist")
     * @Groups ({"pr:read", "pr:whrite"})
     * @ApiSubresource
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, inversedBy="profilsDeSorties")
     */
    private $promo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->promo = new ArrayCollection();
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
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
            $apprenant->setProfilsDeSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilsDeSortie() === $this) {
                $apprenant->setProfilsDeSortie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromo(): Collection
    {
        return $this->promo;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promo->contains($promo)) {
            $this->promo[] = $promo;
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        $this->promo->removeElement($promo);

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

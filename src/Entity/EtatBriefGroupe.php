<?php

namespace App\Entity;

use App\Repository\EtatBriefGroupeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EtatBriefGroupeRepository::class)*
 * @Apiresource (
 *     subresourceOperations={
 *          "api_groupes_etatbriefgroupes_get_subresource"={
 *              "method"="GET",
 *              "path"="api/admin/groupes/{idg}/brief",
 *              "normalization_context"={"groups"={"brief:read"}}
 *          }
 *
 *     },
 * )
 */
class EtatBriefGroupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status="assigner";

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="etatBriefGroupes")
     * @Groups ({"brief:read"})
     */
    private $Brief;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="etatbriefgroupe")
     *
     */
    private $groupe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBrief(): ?Brief
    {
        return $this->Brief;
    }

    public function setBrief(?Brief $Brief): self
    {
        $this->Brief = $Brief;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}

<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"formateur"="Formateur", "apprenant"="Apprenant", "admin"="Admin", "cm"="Cm", "user"="User"})
 * @ApiResource(
 *      normalizationContext={"groups"={"user:read"}},
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas acces à ce ressource",
 *      },
 *      routePrefix="/admin",
 *      collectionOperations={
 *          "get",
 *          "add_user"={
 *              "method"="POST",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="add_user",
 *          },
 *          
 *      },
 *      itemOperations={
 *          "get",
 *          "put_user"={
 *              "method"="PUT",
 *              "access_control"="(is_granted('ROLE_ADMIN'))",
 *              "access_control_message"="Vous n'avez pas access à cette Ressource",
 *              "route_name"="put_user",
 *              
 *          },
 *      }
 * 
 * )
 *  @UniqueEntity(
 *      "email",
 *      message="L'email doit être unique"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"pr:read", "pr:whrite", "profil:read", "user:read", "promo:whrite", "collectApp:read", "status:read", "grp", "pr:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @Assert\Email(message="email invalid")
     * @Groups({"pr:read", "collectApp:read", "profil:read", "user:read", "groupe:read", "apprenant:read", "promo:read", "principale:read", "promo:whrite", "status:read", "grp"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le mot de passe est obligatoire")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pr:read", "profil:read", "user:read", "groupe:read", "apprenant:read", "promo:read", "principale:read"})
     * @Assert\NotBlank(message="Le prenom est obligatoire")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"pr:read", "profil:read", "user:read", "groupe:read", "apprenant:read", "promo:read", "principale:read"})
     * @Assert\NotBlank(message="Le nom est obligatoire")
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="user")
     * @Groups({"promo:whrite"})
     * @Assert\NotBlank(message="Le profil est obligatoire")
     */
    private $profil;


    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"profil:read", "user:read", "groupe:read", "apprenant:read"})
     * @Assert\NotBlank(message="L'image est obligatoire")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archiver=false;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="user")
     */
    private $chats;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getImage( )
    {
        if($this->image){
            return base64_encode(stream_get_contents($this->image));
        }
    }

    public function setImage($image): self
    {
        $this->image = $image;

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
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getUser() === $this) {
                $chat->setUser(null);
            }
        }

        return $this;
    }
}

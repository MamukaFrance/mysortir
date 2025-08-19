<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[orm\Entity(repositoryClass: ParticipantRepository::class)]
#[orm\UniqueConstraint(name: 'uniq_identifier_email', fields: ['email'])]
#[uniqueentity(fields: ['email'], message: 'there is already an account with this email')]
class Participant implements userinterface, passwordauthenticateduserinterface
{
    #[orm\Id]
    #[orm\GeneratedValue]
    #[orm\Column]
    private ?int $id = null;

    #[orm\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> the user roles
     */
    #[orm\Column]
    private array $roles = [];

    /**
     * @var string the hashed password
     */
    #[orm\Column]
    private ?string $password = null;

    #[orm\Column(length: 50)]
    private ?string $nom = null;

    #[orm\Column(length: 50)]
    private ?string $prenom = null;

    #[orm\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[orm\Column]
    private ?bool $administrateur = null;

    #[orm\Column]
    private ?bool $actif = null;

    #[orm\ManyToOne(inversedBy: 'participants')]
    private ?site $site = null;

    /**
     * @var collection<int, sortie>
     */
    #[orm\ManyToMany(targetEntity: sortie::class, mappedBy: 'participants')]
    private collection $sorties;

    /**
     * @var collection<int, sortie>
     */
    #[orm\OneToMany(targetEntity: sortie::class, mappedBy: 'organisateur')]
    private collection $organisateurorties;

    #[orm\Column]
    private bool $isVerified = false;

    /**
     * @var collection<int, groupeprive>
     */
    #[orm\OneToMany(targetEntity: groupeprive::class, mappedBy: 'createur')]
    private collection $groupeprives;

    /**
     * @var collection<int, groupeprive>
     */
    #[orm\ManyToMany(targetEntity: groupeprive::class, mappedBy: 'participants')]
    private collection $participantgroupeprives;

    public function __construct()
    {
        $this->administrateur = false;
        $this->actif = true;
        $this->sorties = new arraycollection();
        $this->organisateursorties = new arraycollection();
        $this->groupeprives = new arraycollection();
        $this->participantgroupeprives = new arraycollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * a visual identifier that represents this user.
     *
     * @see userinterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see userinterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has role_user
        $roles[] = 'role_user';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setroles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see passwordauthenticateduserinterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\deprecated]
    public function erasecredentials(): void
    {
        // @deprecated, to be removed when upgrading to symfony 8
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getSite(): ?site
    {
        return $this->site;
    }

    public function setSite(?site $site): static
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return collection<int, sortie>
     */
    public function getSorties(): collection
    {
        return $this->sorties;
    }

    public function addSorty(sortie $sorty): static
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(sortie $sorty): static
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return collection<int, sortie>
     */
    public function getOrganisateurSorties(): collection
    {
        return $this->organisateursorties;
    }

    public function addOrganisateurSorty(sortie $organisateursorty): static
    {
        if (!$this->organisateursorties->contains($organisateursorty)) {
            $this->organisateursorties->add($organisateursorty);
            $organisateursorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeOrganisateursorty(sortie $organisateursorty): static
    {
        if ($this->organisateursorties->removeelement($organisateursorty)) {
            // set the owning side to null (unless already changed)
            if ($organisateursorty->getOrganisateur() === $this) {
                $organisateursorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified();
    }

    public function setIsVerified(bool $isverified): static
    {
        $this->isverified = $isverified;

        return $this;
    }

    /**
     * @return collection<int, groupeprive>
     */
    public function getgroupeprives(): collection
    {
        return $this->groupeprives;
    }

    public function addgroupeprive(groupeprive $groupeprive): static
    {
        if (!$this->groupeprives->contains($groupeprive)) {
            $this->groupeprives->add($groupeprive);
            $groupeprive->setcreateur($this);
        }

        return $this;
    }

    public function removegroupeprive(groupeprive $groupeprive): static
    {
        if ($this->groupeprives->removeelement($groupeprive)) {
            // set the owning side to null (unless already changed)
            if ($groupeprive->getcreateur() === $this) {
                $groupeprive->setcreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return collection<int, groupeprive>
     */
    public function getparticipantgroupeprives(): collection
    {
        return $this->participantgroupeprives;
    }

    public function addparticipantgroupeprive(groupeprive $participantgroupeprive): static
    {
        if (!$this->participantgroupeprives->contains($participantgroupeprive)) {
            $this->participantgroupeprives->add($participantgroupeprive);
            $participantgroupeprive->addparticipant($this);
        }

        return $this;
    }

    public function removeparticipantgroupeprive(groupeprive $participantgroupeprive): static
    {
        $this->participantgroupeprives->removeelement($participantgroupeprive);
        $participantgroupeprive->removeparticipant($this);

        return $this;
    }
}

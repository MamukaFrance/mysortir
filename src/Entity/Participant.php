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
    private collection $organisateursorties;

    #[orm\Column]
    private bool $isverified = false;

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

    public function getid(): ?int
    {
        return $this->id;
    }

    public function getemail(): ?string
    {
        return $this->email;
    }

    public function setemail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * a visual identifier that represents this user.
     *
     * @see userinterface
     */
    public function getuseridentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see userinterface
     */
    public function getroles(): array
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
    public function getpassword(): ?string
    {
        return $this->password;
    }

    public function setpassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\deprecated]
    public function erasecredentials(): void
    {
        // @deprecated, to be removed when upgrading to symfony 8
    }

    public function getnom(): ?string
    {
        return $this->nom;
    }

    public function setnom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getprenom(): ?string
    {
        return $this->prenom;
    }

    public function setprenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function gettelephone(): ?string
    {
        return $this->telephone;
    }

    public function settelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isadministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setadministrateur(bool $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isactif(): ?bool
    {
        return $this->actif;
    }

    public function setactif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getsite(): ?site
    {
        return $this->site;
    }

    public function setsite(?site $site): static
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return collection<int, sortie>
     */
    public function getsorties(): collection
    {
        return $this->sorties;
    }

    public function addsorty(sortie $sorty): static
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->addparticipant($this);
        }

        return $this;
    }

    public function removesorty(sortie $sorty): static
    {
        if ($this->sorties->removeelement($sorty)) {
            $sorty->removeparticipant($this);
        }

        return $this;
    }

    /**
     * @return collection<int, sortie>
     */
    public function getorganisateursorties(): collection
    {
        return $this->organisateursorties;
    }

    public function addorganisateursorty(sortie $organisateursorty): static
    {
        if (!$this->organisateursorties->contains($organisateursorty)) {
            $this->organisateursorties->add($organisateursorty);
            $organisateursorty->setorganisateur($this);
        }

        return $this;
    }

    public function removeorganisateursorty(sortie $organisateursorty): static
    {
        if ($this->organisateursorties->removeelement($organisateursorty)) {
            // set the owning side to null (unless already changed)
            if ($organisateursorty->getorganisateur() === $this) {
                $organisateursorty->setorganisateur(null);
            }
        }

        return $this;
    }

    public function isverified(): bool
    {
        return $this->isverified;
    }

    public function setisverified(bool $isverified): static
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

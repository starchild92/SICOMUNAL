<?php

namespace SICBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity()
 */
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="username", type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="username_canonical", type="string", length=180, unique=true)
     */
    private $usernameCanonical;

    /**
     * @ORM\Column(name="email", type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="email_canonical", type="string", length=180, unique=true)
     */
    private $emailCanonical;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = false;

    /**
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(name="confirmation_token", type="string", length=180, unique=true, nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @ORM\Column(name="roles", type="json")
     */
    private $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(name="primerNombre", type="string", length=255)
     */
    private $primerNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoNombre", type="string", length=255, nullable=true)
     */
    private $segundoNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="primerApellido", type="string", length=255)
     */
    private $primerApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="segundoApellido", type="string", length=255, nullable=true)
     */
    private $segundoApellido;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=255)
     */
    private $cedula;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     */
    private $fechaNacimiento;

    /**
     * @ORM\ManyToMany(targetEntity="Telefono", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="usuario_telefonos",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="cascade")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="telefono_id", referencedColumnName="id", onDelete="cascade", unique=true)}
     *      )
     */
    private $telefono;

    /**
     * @ORM\OneToMany(targetEntity="Planillas", mappedBy="empadronador")
     */
    private $planillas;

    /**
     * @ORM\OneToMany(targetEntity="Noticia", mappedBy="usuario")
     */
    private $noticias;

    /**
    * @ORM\OneToMany(targetEntity="Bitacora", mappedBy="usuario")
    **/
    private $bitacora;

    /**
     * @ORM\OneToMany(targetEntity="Comunicado", mappedBy="emisor")
     */
    private $comunicados;

    // --- UserInterface ---

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (is_string($roles)) {
            // Compatibilidad con el formato serializado de PHP antiguo
            $roles = unserialize($roles) ?: [];
        }
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void {}

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    // --- Getters / Setters ---

    public function getId(): ?int { return $this->id; }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        $this->usernameCanonical = strtolower($username);
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $this->emailCanonical = strtolower($email);
        return $this;
    }

    public function getEmail(): ?string { return $this->email; }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function nombreyapellido(): string
    {
        return $this->primerNombre . ' ' . $this->primerApellido;
    }

    public function cedula(): string
    {
        return number_format($this->cedula, 0, '', '.');
    }

    public function getPrimerNombre(): ?string { return $this->primerNombre; }
    public function setPrimerNombre(string $primerNombre): self { $this->primerNombre = $primerNombre; return $this; }

    public function getSegundoNombre(): ?string { return $this->segundoNombre; }
    public function setSegundoNombre(?string $segundoNombre): self { $this->segundoNombre = $segundoNombre; return $this; }

    public function getPrimerApellido(): ?string { return $this->primerApellido; }
    public function setPrimerApellido(string $primerApellido): self { $this->primerApellido = $primerApellido; return $this; }

    public function getSegundoApellido(): ?string { return $this->segundoApellido; }
    public function setSegundoApellido(?string $segundoApellido): self { $this->segundoApellido = $segundoApellido; return $this; }

    public function getCedula(): ?string { return $this->cedula; }
    public function setCedula(string $cedula): self { $this->cedula = $cedula; return $this; }

    public function getFechaNacimiento(): ?\DateTime { return $this->fechaNacimiento; }
    public function setFechaNacimiento(\DateTime $fechaNacimiento): self { $this->fechaNacimiento = $fechaNacimiento; return $this; }

    public function fechaNacimiento(): string
    {
        $fecha = $this->getFechaNacimiento();
        $dias = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
        $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
        return $dias[$fecha->format('w')] . ", " . $fecha->format('d') . " de " . $meses[$fecha->format('n')-1] . " " . $fecha->format('Y');
    }

    public function addTelefono(\SICBundle\Entity\Telefono $telefono): self { $this->telefono[] = $telefono; return $this; }
    public function removeTelefono(\SICBundle\Entity\Telefono $telefono): void { $this->telefono->removeElement($telefono); }
    public function getTelefono() { return $this->telefono; }

    public function addPlanilla(\SICBundle\Entity\Planillas $planillas): self { $this->planillas[] = $planillas; return $this; }
    public function removePlanilla(\SICBundle\Entity\Planillas $planillas): void { $this->planillas->removeElement($planillas); }
    public function getPlanillas() { return $this->planillas; }

    public function addNoticia(\SICBundle\Entity\Noticia $noticias): self { $this->noticias[] = $noticias; return $this; }
    public function removeNoticia(\SICBundle\Entity\Noticia $noticias): void { $this->noticias->removeElement($noticias); }
    public function getNoticias() { return $this->noticias; }

    public function addBitacora(\SICBundle\Entity\Bitacora $bitacora): self { $this->bitacora[] = $bitacora; return $this; }
    public function removeBitacora(\SICBundle\Entity\Bitacora $bitacora): void { $this->bitacora->removeElement($bitacora); }
    public function getBitacora() { return $this->bitacora; }

    public function addComunicado(\SICBundle\Entity\Comunicado $comunicados): self { $this->comunicados[] = $comunicados; return $this; }
    public function removeComunicado(\SICBundle\Entity\Comunicado $comunicados): void { $this->comunicados->removeElement($comunicados); }
    public function getComunicados() { return $this->comunicados; }
}

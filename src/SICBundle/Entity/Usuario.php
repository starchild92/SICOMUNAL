<?php

namespace SICBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity()
 */
class Usuario extends BaseUser
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

    public function cedula(){ return number_format($this->cedula, 0, '', '.'); }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function nombreyapellido()
    {
        return $this->primerNombre.' '.$this->primerApellido;
    }

    /**
     * Set primerNombre
     *
     * @param string $primerNombre
     * @return Usuario
     */
    public function setPrimerNombre($primerNombre)
    {
        $this->primerNombre = $primerNombre;

        return $this;
    }

    /**
     * Get primerNombre
     *
     * @return string 
     */
    public function getPrimerNombre()
    {
        return $this->primerNombre;
    }

    /**
     * Set segundoNombre
     *
     * @param string $segundoNombre
     * @return Usuario
     */
    public function setSegundoNombre($segundoNombre)
    {
        $this->segundoNombre = $segundoNombre;

        return $this;
    }

    /**
     * Get segundoNombre
     *
     * @return string 
     */
    public function getSegundoNombre()
    {
        return $this->segundoNombre;
    }

    /**
     * Set primerApellido
     *
     * @param string $primerApellido
     * @return Usuario
     */
    public function setPrimerApellido($primerApellido)
    {
        $this->primerApellido = $primerApellido;

        return $this;
    }

    /**
     * Get primerApellido
     *
     * @return string 
     */
    public function getPrimerApellido()
    {
        return $this->primerApellido;
    }

    /**
     * Set segundoApellido
     *
     * @param string $segundoApellido
     * @return Usuario
     */
    public function setSegundoApellido($segundoApellido)
    {
        $this->segundoApellido = $segundoApellido;

        return $this;
    }

    /**
     * Get segundoApellido
     *
     * @return string 
     */
    public function getSegundoApellido()
    {
        return $this->segundoApellido;
    }

    /**
     * Add telefono
     *
     * @param \SICBundle\Entity\Telefono $telefono
     * @return Usuario
     */
    public function addTelefono(\SICBundle\Entity\Telefono $telefono)
    {
        $this->telefono[] = $telefono;

        return $this;
    }

    /**
     * Remove telefono
     *
     * @param \SICBundle\Entity\Telefono $telefono
     */
    public function removeTelefono(\SICBundle\Entity\Telefono $telefono)
    {
        $this->telefono->removeElement($telefono);
    }

    /**
     * Get telefono
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add planillas
     *
     * @param \SICBundle\Entity\Planillas $planillas
     * @return Usuario
     */
    public function addPlanilla(\SICBundle\Entity\Planillas $planillas)
    {
        $this->planillas[] = $planillas;

        return $this;
    }

    /**
     * Remove planillas
     *
     * @param \SICBundle\Entity\Planillas $planillas
     */
    public function removePlanilla(\SICBundle\Entity\Planillas $planillas)
    {
        $this->planillas->removeElement($planillas);
    }

    /**
     * Get planillas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlanillas()
    {
        return $this->planillas;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     * @return Usuario
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * Get cedula
     *
     * @return string 
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Usuario
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }
    
    public function fechaNacimiento()
    {
        $fecha = $this->getFechaNacimiento();
        $dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        return $dias[$fecha->format('w')].", ".$fecha->format('d')." de ".$meses[$fecha->format('n')-1]. " ".$fecha->format('Y');
    }

    /**
     * Add noticias
     *
     * @param \SICBundle\Entity\Noticia $noticias
     * @return Usuario
     */
    public function addNoticia(\SICBundle\Entity\Noticia $noticias)
    {
        $this->noticias[] = $noticias;

        return $this;
    }

    /**
     * Remove noticias
     *
     * @param \SICBundle\Entity\Noticia $noticias
     */
    public function removeNoticia(\SICBundle\Entity\Noticia $noticias)
    {
        $this->noticias->removeElement($noticias);
    }

    /**
     * Get noticias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNoticias()
    {
        return $this->noticias;
    }

    /**
     * Add bitacora
     *
     * @param \SICBundle\Entity\Bitacora $bitacora
     * @return Usuario
     */
    public function addBitacora(\SICBundle\Entity\Bitacora $bitacora)
    {
        $this->bitacora[] = $bitacora;

        return $this;
    }

    /**
     * Remove bitacora
     *
     * @param \SICBundle\Entity\Bitacora $bitacora
     */
    public function removeBitacora(\SICBundle\Entity\Bitacora $bitacora)
    {
        $this->bitacora->removeElement($bitacora);
    }

    /**
     * Get bitacora
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBitacora()
    {
        return $this->bitacora;
    }

    /**
     * Add comunicados
     *
     * @param \SICBundle\Entity\Comunicado $comunicados
     * @return Usuario
     */
    public function addComunicado(\SICBundle\Entity\Comunicado $comunicados)
    {
        $this->comunicados[] = $comunicados;

        return $this;
    }

    /**
     * Remove comunicados
     *
     * @param \SICBundle\Entity\Comunicado $comunicados
     */
    public function removeComunicado(\SICBundle\Entity\Comunicado $comunicados)
    {
        $this->comunicados->removeElement($comunicados);
    }

    /**
     * Get comunicados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComunicados()
    {
        return $this->comunicados;
    }
}

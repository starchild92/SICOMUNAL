<?php

namespace SICBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JefeGrupoFamiliar
 *
 * @ORM\Table(name="jefe_grupo_familiar")
 * @ORM\Entity(repositoryClass="SICBundle\Repository\JefeGrupoFamiliarRepository")
 */
class JefeGrupoFamiliar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=255)
     */
    private $nombres;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="cedula", type="string", length=255)
     */
    private $cedula;

    /**
     * @var string
     *
     * @ORM\Column(name="nacionalidad", type="string", length=255)
     */
    private $nacionalidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     */
    private $fechaNacimiento;

    /**
     * @var int
     *
     * @ORM\Column(name="edad", type="integer")
     */
    private $edad;

    /**
     * @var string
     *
     * @ORM\Column(name="cne", type="string", length=255)
     */
    private $cne;

    /**
     * @var string
     *
     * @ORM\Column(name="tiempoEnComunidad", type="string", length=255)
     */
    private $tiempoEnComunidad;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=20)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="incapacitado", type="string", length=255)
     */
    private $incapacitado;

    /**
     * @var string
     *
     * @ORM\Column(name="incapacitadoTipo", type="string", length=255)
     */
    private $incapacitadoTipo;

    /**
     * @var string
     *
     * @ORM\Column(name="pensionado", type="string", length=255)
     */
    private $pensionado;

    /**
     * @var string
     *
     * @ORM\Column(name="pensionadoInstitucion", type="string", length=255)
     */
    private $pensionadoInstitucion;

    /**
     * @var string
     *
     * @ORM\Column(name="estadoCivil", type="string", length=255)
     */
    private $estadoCivil;

    /**
     * @var string
     *
     * @ORM\Column(name="nivelInstruccion", type="string", length=255)
     */
    private $nivelInstruccion;

    /**
     * @var string
     *
     * @ORM\Column(name="profesion", type="string", length=255)
     */
    private $profesion;

    /**
     * @var string
     *
     * @ORM\Column(name="trabajaActualmente", type="string", length=255)
     */
    private $trabajaActualmente;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="telefono", type="object", nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="ingresoFamiliar", type="string", length=255)
     */
    private $ingresoFamiliar;

    /**
     * @var float
     *
     * @ORM\Column(name="ingresoMensual", type="float")
     */
    private $ingresoMensual;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return JefeGrupoFamiliar
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return JefeGrupoFamiliar
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set cedula
     *
     * @param string $cedula
     * @return JefeGrupoFamiliar
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
     * Set nacionalidad
     *
     * @param string $nacionalidad
     * @return JefeGrupoFamiliar
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return string 
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return JefeGrupoFamiliar
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

    /**
     * Set edad
     *
     * @param integer $edad
     * @return JefeGrupoFamiliar
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;

        return $this;
    }

    /**
     * Get edad
     *
     * @return integer 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set cne
     *
     * @param string $cne
     * @return JefeGrupoFamiliar
     */
    public function setCne($cne)
    {
        $this->cne = $cne;

        return $this;
    }

    /**
     * Get cne
     *
     * @return string 
     */
    public function getCne()
    {
        return $this->cne;
    }

    /**
     * Set tiempoEnComunidad
     *
     * @param string $tiempoEnComunidad
     * @return JefeGrupoFamiliar
     */
    public function setTiempoEnComunidad($tiempoEnComunidad)
    {
        $this->tiempoEnComunidad = $tiempoEnComunidad;

        return $this;
    }

    /**
     * Get tiempoEnComunidad
     *
     * @return string 
     */
    public function getTiempoEnComunidad()
    {
        return $this->tiempoEnComunidad;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     * @return JefeGrupoFamiliar
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set incapacitado
     *
     * @param string $incapacitado
     * @return JefeGrupoFamiliar
     */
    public function setIncapacitado($incapacitado)
    {
        $this->incapacitado = $incapacitado;

        return $this;
    }

    /**
     * Get incapacitado
     *
     * @return string 
     */
    public function getIncapacitado()
    {
        return $this->incapacitado;
    }

    /**
     * Set incapacitadoTipo
     *
     * @param string $incapacitadoTipo
     * @return JefeGrupoFamiliar
     */
    public function setIncapacitadoTipo($incapacitadoTipo)
    {
        $this->incapacitadoTipo = $incapacitadoTipo;

        return $this;
    }

    /**
     * Get incapacitadoTipo
     *
     * @return string 
     */
    public function getIncapacitadoTipo()
    {
        return $this->incapacitadoTipo;
    }

    /**
     * Set pensionado
     *
     * @param string $pensionado
     * @return JefeGrupoFamiliar
     */
    public function setPensionado($pensionado)
    {
        $this->pensionado = $pensionado;

        return $this;
    }

    /**
     * Get pensionado
     *
     * @return string 
     */
    public function getPensionado()
    {
        return $this->pensionado;
    }

    /**
     * Set pensionadoInstitucion
     *
     * @param string $pensionadoInstitucion
     * @return JefeGrupoFamiliar
     */
    public function setPensionadoInstitucion($pensionadoInstitucion)
    {
        $this->pensionadoInstitucion = $pensionadoInstitucion;

        return $this;
    }

    /**
     * Get pensionadoInstitucion
     *
     * @return string 
     */
    public function getPensionadoInstitucion()
    {
        return $this->pensionadoInstitucion;
    }

    /**
     * Set estadoCivil
     *
     * @param string $estadoCivil
     * @return JefeGrupoFamiliar
     */
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * Get estadoCivil
     *
     * @return string 
     */
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Set nivelInstruccion
     *
     * @param string $nivelInstruccion
     * @return JefeGrupoFamiliar
     */
    public function setNivelInstruccion($nivelInstruccion)
    {
        $this->nivelInstruccion = $nivelInstruccion;

        return $this;
    }

    /**
     * Get nivelInstruccion
     *
     * @return string 
     */
    public function getNivelInstruccion()
    {
        return $this->nivelInstruccion;
    }

    /**
     * Set profesion
     *
     * @param string $profesion
     * @return JefeGrupoFamiliar
     */
    public function setProfesion($profesion)
    {
        $this->profesion = $profesion;

        return $this;
    }

    /**
     * Get profesion
     *
     * @return string 
     */
    public function getProfesion()
    {
        return $this->profesion;
    }

    /**
     * Set trabajaActualmente
     *
     * @param string $trabajaActualmente
     * @return JefeGrupoFamiliar
     */
    public function setTrabajaActualmente($trabajaActualmente)
    {
        $this->trabajaActualmente = $trabajaActualmente;

        return $this;
    }

    /**
     * Get trabajaActualmente
     *
     * @return string 
     */
    public function getTrabajaActualmente()
    {
        return $this->trabajaActualmente;
    }

    /**
     * Set telefono
     *
     * @param \stdClass $telefono
     * @return JefeGrupoFamiliar
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return \stdClass 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return JefeGrupoFamiliar
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set ingresoFamiliar
     *
     * @param string $ingresoFamiliar
     * @return JefeGrupoFamiliar
     */
    public function setIngresoFamiliar($ingresoFamiliar)
    {
        $this->ingresoFamiliar = $ingresoFamiliar;

        return $this;
    }

    /**
     * Get ingresoFamiliar
     *
     * @return string 
     */
    public function getIngresoFamiliar()
    {
        return $this->ingresoFamiliar;
    }

    /**
     * Set ingresoMensual
     *
     * @param float $ingresoMensual
     * @return JefeGrupoFamiliar
     */
    public function setIngresoMensual($ingresoMensual)
    {
        $this->ingresoMensual = $ingresoMensual;

        return $this;
    }

    /**
     * Get ingresoMensual
     *
     * @return float 
     */
    public function getIngresoMensual()
    {
        return $this->ingresoMensual;
    }
}
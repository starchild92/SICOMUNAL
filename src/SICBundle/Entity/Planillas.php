<?php

namespace SICBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planillas
 *
 * @ORM\Table(name="planillas")
 * @ORM\Entity(repositoryClass="SICBundle\Repository\PlanillasRepository")
 */
class Planillas
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
     * @ORM\Column(name="empadronador", type="string", length=255)
     */
    private $empadronador;

    /**
     * @var string
     *
     * @ORM\Column(name="jefeGrupoFamiliar", type="string", length=255)
     */
    private $jefeGrupoFamiliar;

    /**
     * @var string
     *
     * @ORM\Column(name="miembrosGrupoFamiliar", type="string", length=255)
     */
    private $miembrosGrupoFamiliar;

    /**
     * @var string
     *
     * @ORM\Column(name="situacionEconomica", type="string", length=255)
     */
    private $situacionEconomica;

    /**
     * @var string
     *
     * @ORM\Column(name="situacionVivienda", type="string", length=255)
     */
    private $situacionVivienda;

    /**
     * @var string
     *
     * @ORM\Column(name="situacionSalud", type="string", length=255)
     */
    private $situacionSalud;

    /**
     * @var string
     *
     * @ORM\Column(name="situacionServicios", type="string", length=255)
     */
    private $situacionServicios;

    /**
     * @var string
     *
     * @ORM\Column(name="participacionComunitaria", type="string", length=255)
     */
    private $participacionComunitaria;

    /**
     * @var string
     *
     * @ORM\Column(name="situacionComunidad", type="string", length=255)
     */
    private $situacionComunidad;


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
     * Set empadronador
     *
     * @param string $empadronador
     * @return Planillas
     */
    public function setEmpadronador($empadronador)
    {
        $this->empadronador = $empadronador;

        return $this;
    }

    /**
     * Get empadronador
     *
     * @return string 
     */
    public function getEmpadronador()
    {
        return $this->empadronador;
    }

    /**
     * Set jefeGrupoFamiliar
     *
     * @param string $jefeGrupoFamiliar
     * @return Planillas
     */
    public function setJefeGrupoFamiliar($jefeGrupoFamiliar)
    {
        $this->jefeGrupoFamiliar = $jefeGrupoFamiliar;

        return $this;
    }

    /**
     * Get jefeGrupoFamiliar
     *
     * @return string 
     */
    public function getJefeGrupoFamiliar()
    {
        return $this->jefeGrupoFamiliar;
    }

    /**
     * Set miembrosGrupoFamiliar
     *
     * @param string $miembrosGrupoFamiliar
     * @return Planillas
     */
    public function setMiembrosGrupoFamiliar($miembrosGrupoFamiliar)
    {
        $this->miembrosGrupoFamiliar = $miembrosGrupoFamiliar;

        return $this;
    }

    /**
     * Get miembrosGrupoFamiliar
     *
     * @return string 
     */
    public function getMiembrosGrupoFamiliar()
    {
        return $this->miembrosGrupoFamiliar;
    }

    /**
     * Set situacionEconomica
     *
     * @param string $situacionEconomica
     * @return Planillas
     */
    public function setSituacionEconomica($situacionEconomica)
    {
        $this->situacionEconomica = $situacionEconomica;

        return $this;
    }

    /**
     * Get situacionEconomica
     *
     * @return string 
     */
    public function getSituacionEconomica()
    {
        return $this->situacionEconomica;
    }

    /**
     * Set situacionVivienda
     *
     * @param string $situacionVivienda
     * @return Planillas
     */
    public function setSituacionVivienda($situacionVivienda)
    {
        $this->situacionVivienda = $situacionVivienda;

        return $this;
    }

    /**
     * Get situacionVivienda
     *
     * @return string 
     */
    public function getSituacionVivienda()
    {
        return $this->situacionVivienda;
    }

    /**
     * Set situacionSalud
     *
     * @param string $situacionSalud
     * @return Planillas
     */
    public function setSituacionSalud($situacionSalud)
    {
        $this->situacionSalud = $situacionSalud;

        return $this;
    }

    /**
     * Get situacionSalud
     *
     * @return string 
     */
    public function getSituacionSalud()
    {
        return $this->situacionSalud;
    }

    /**
     * Set situacionServicios
     *
     * @param string $situacionServicios
     * @return Planillas
     */
    public function setSituacionServicios($situacionServicios)
    {
        $this->situacionServicios = $situacionServicios;

        return $this;
    }

    /**
     * Get situacionServicios
     *
     * @return string 
     */
    public function getSituacionServicios()
    {
        return $this->situacionServicios;
    }

    /**
     * Set participacionComunitaria
     *
     * @param string $participacionComunitaria
     * @return Planillas
     */
    public function setParticipacionComunitaria($participacionComunitaria)
    {
        $this->participacionComunitaria = $participacionComunitaria;

        return $this;
    }

    /**
     * Get participacionComunitaria
     *
     * @return string 
     */
    public function getParticipacionComunitaria()
    {
        return $this->participacionComunitaria;
    }

    /**
     * Set situacionComunidad
     *
     * @param string $situacionComunidad
     * @return Planillas
     */
    public function setSituacionComunidad($situacionComunidad)
    {
        $this->situacionComunidad = $situacionComunidad;

        return $this;
    }

    /**
     * Get situacionComunidad
     *
     * @return string 
     */
    public function getSituacionComunidad()
    {
        return $this->situacionComunidad;
    }
}
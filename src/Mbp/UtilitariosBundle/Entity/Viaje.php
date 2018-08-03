<?php

namespace Mbp\UtilitariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Viaje
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\UtilitariosBundle\Entity\ViajeRepository")
 */
class Viaje
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idViaje", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idViaje;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="date")
     */
    private $fechaEmision;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaDesde", type="date")
     */
    private $fechaDesde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaHasta", type="date")
     */
    private $fechaHasta;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=255)
     */
    private $domicilio;

    /**
     * @var string
     *
     * @ORM\Column(name="horarios", type="string", length=255)
     */
    private $horarios;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=255)
     */
    private $estado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="autorizado", type="boolean")
     */
    private $autorizado=false;

    /**
     * @var \Mbp\SecurityBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users")
     * @ORM\JoinColumn(name="emisor", referencedColumnName="id", unique=false, nullable=false)	 
     */
    private $emisor;


    /**
     * Get idViaje
     *
     * @return integer
     */
    public function getIdViaje()
    {
        return $this->idViaje;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return Viaje
     */
    public function setFechaEmision($fechaEmision)
    {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return Viaje
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return Viaje
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Viaje
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set domicilio
     *
     * @param string $domicilio
     *
     * @return Viaje
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio
     *
     * @return string
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set horarios
     *
     * @param string $horarios
     *
     * @return Viaje
     */
    public function setHorarios($horarios)
    {
        $this->horarios = $horarios;

        return $this;
    }

    /**
     * Get horarios
     *
     * @return string
     */
    public function getHorarios()
    {
        return $this->horarios;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Viaje
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Viaje
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set autorizado
     *
     * @param boolean $autorizado
     *
     * @return Viaje
     */
    public function setAutorizado($autorizado)
    {
        $this->autorizado = $autorizado;

        return $this;
    }

    /**
     * Get autorizado
     *
     * @return boolean
     */
    public function getAutorizado()
    {
        return $this->autorizado;
    }

    /**
     * Set emisor
     *
     * @param \Mbp\SecurityBundle\Entity\Users $emisor
     *
     * @return Viaje
     */
    public function setEmisor(\Mbp\SecurityBundle\Entity\Users $emisor)
    {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * Get emisor
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getEmisor()
    {
        return $this->emisor;
    }
}

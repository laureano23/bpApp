<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProduccionSoldado
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\ProduccionSoldadoRepository")
 */
class ProduccionSoldado
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCarga", type="date", nullable=false)
	 * @Assert\DateTime()
	 * @Assert\NotNull()
     */
    private $fechaCarga;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
	 * @Assert\NotNull()
     */
    private $fecha;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=false)
	 * @Assert\NotNull()
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Ot")
     * @ORM\JoinColumn(name="ot", referencedColumnName="ot", nullable=false)
	 * @Assert\NotNull()
     */
    private $ot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hsInicio", type="time", nullable=false)
	 * @Assert\Time()
	 * @Assert\NotNull()
     */
    private $hsInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hsFin", type="time", nullable=false)
	 * @Assert\Time()
	 * @Assert\NotNull()
     */
    private $hsFin;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text")
     */
    private $observaciones;
	
	/**
	 * @var \Mbp\PersonalBundle\Entity\Personal
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Personal")
	 * @ORM\JoinColumn(name="personalId", referencedColumnName="idP", nullable=false)
	 * @Assert\NotNull()
	 */
	 private $personalId;
	 
	 /**
	 * @var \Mbp\ProduccionBundle\Entity\Operaciones
	 * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Operaciones")
	 * @ORM\JoinColumn(name="operacionId", referencedColumnName="id", nullable=false)
	 * @Assert\NotNull()
	 */
	 private $operacionId;
	 
	 /**
	 * @var \Mbp\SecurityBundle\Entity\Users
	 * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users")
	 * @ORM\JoinColumn(name="usuario", referencedColumnName="id", nullable=false)
	 * @Assert\NotNull()
	 */
	 private $usuarioId;


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
     * Set fechaCarga
     *
     * @param \DateTime $fechaCarga
     *
     * @return ProduccionSoldado
     */
    public function setFechaCarga($fechaCarga)
    {
        $this->fechaCarga = $fechaCarga;

        return $this;
    }

    /**
     * Get fechaCarga
     *
     * @return \DateTime
     */
    public function getFechaCarga()
    {
        return $this->fechaCarga;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ProduccionSoldado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set ot
     *
     * @param string $ot
     *
     * @return ProduccionSoldado
     */
    public function setOt($ot)
    {
        $this->ot = $ot;

        return $this;
    }

    /**
     * Get ot
     *
     * @return string
     */
    public function getOt()
    {
        return $this->ot;
    }

    /**
     * Set hsInicio
     *
     * @param \DateTime $hsInicio
     *
     * @return ProduccionSoldado
     */
    public function setHsInicio($hsInicio)
    {
        $this->hsInicio = $hsInicio;

        return $this;
    }

    /**
     * Get hsInicio
     *
     * @return \DateTime
     */
    public function getHsInicio()
    {
        return $this->hsInicio;
    }

    /**
     * Set hsFin
     *
     * @param \DateTime $hsFin
     *
     * @return ProduccionSoldado
     */
    public function setHsFin($hsFin)
    {
        $this->hsFin = $hsFin;

        return $this;
    }

    /**
     * Get hsFin
     *
     * @return \DateTime
     */
    public function getHsFin()
    {
        return $this->hsFin;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return ProduccionSoldado
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set personalId
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $personalId
     *
     * @return ProduccionSoldado
     */
    public function setPersonalId(\Mbp\PersonalBundle\Entity\Personal $personalId = null)
    {
        $this->personalId = $personalId;

        return $this;
    }

    /**
     * Get personalId
     *
     * @return \Mbp\PersonalBundle\Entity\Personal
     */
    public function getPersonalId()
    {
        return $this->personalId;
    }

    /**
     * Set operacionId
     *
     * @param \Mbp\ProduccionBundle\Entity\Operaciones $operacionId
     *
     * @return ProduccionSoldado
     */
    public function setOperacionId(\Mbp\ProduccionBundle\Entity\Operaciones $operacionId = null)
    {
        $this->operacionId = $operacionId;

        return $this;
    }

    /**
     * Get operacionId
     *
     * @return \Mbp\ProduccionBundle\Entity\Operaciones
     */
    public function getOperacionId()
    {
        return $this->operacionId;
    }
	
	public function validarHoras()
	{
		if($this->hsFin == "" || $this->hsInicio == "") return;
		
		if($this->hsFin > $this->hsInicio){
			return "La hora de fin debe ser mayor a la de ininio";
		}
	}

    /**
     * Set usuarioId
     *
     * @param \Mbp\SecurityBundle\Entity\Users $usuarioId
     *
     * @return ProduccionSoldado
     */
    public function setUsuarioId(\Mbp\SecurityBundle\Entity\Users $usuarioId)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return ProduccionSoldado
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
}

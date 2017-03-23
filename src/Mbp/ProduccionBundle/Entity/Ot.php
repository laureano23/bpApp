<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ot
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\OtRepository")
 */
class Ot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ot", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $ot;

    
     /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="idCodigo", referencedColumnName="idArticulos", nullable=false)	 
     */
    private $idCodigo;
	
	/**
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="idCliente", referencedColumnName="idCliente", nullable=false)	 
     */
    private $idCliente;

    /**
     * @var integer
     * @Assert\Range(
     *      min = 0.001,
     *      max = 999999,
     *      minMessage = "La cantidad mínima es {{ limit }}",
     *      maxMessage = "La cantidad máxima es {{ limit }}"
     * )
     * @ORM\Column(name="cantidad", type="decimal", precision=10, scale=3)
     */
    private $cantidad;
	
	/**
     * @var datetime
     * @Assert\DateTime()
     * @ORM\Column(name="fechaEmision", type="datetime")
     */
    private $fechaEmision;
	
	/**
     * @var date
     * @Assert\GreaterThanOrEqual("today")
     * @ORM\Column(name="fechaProg", type="date")
     */
    private $fechaProg;
	
	/**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=250)
     */
    private $observaciones;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="anulada", type="boolean")
     */
    private $anulada=0;
	
	/**
     * @var \Mbp\SecurityBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users")
     * @ORM\JoinColumn(name="idUsuario", referencedColumnName="id", nullable=false)	 
     */
    private $idUsuario;

	 /**
     * @var smallint
     *
     * @ORM\Column(name="tipo", type="smallint")
	 * @Assert\Range(
     *      min = 1,
     *      max = 4
     * )
     */
    private $tipo;

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
     * Set ot
     *
     * @param integer $ot
     * @return Ot
     */
    public function setOt($ot)
    {
        $this->ot = $ot;

        return $this;
    }

    /**
     * Get ot
     *
     * @return integer 
     */
    public function getOt()
    {
        return $this->ot;
    }

    /**
     * Set idCodigo
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idCodigo
     * @return Ot
     */
    public function setIdCodigo($idCodigo)
    {
        $this->idCodigo = $idCodigo;

        return $this;
    }

    /**
     * Get idCodigo
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos
     */
    public function getIdCodigo()
    {
        return $this->idCodigo;
    }
    
	/**
     * Set idCliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $idCliente
     * @return Ot
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;

        return $this;
    }

    /**
     * Get idCliente
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Ot
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

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return Ot
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Ot
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
     * Set anulada
     *
     * @param boolean $anulada
     *
     * @return Ot
     */
    public function setAnulada($anulada)
    {
        $this->anulada = $anulada;

        return $this;
    }

    /**
     * Get anulada
     *
     * @return boolean
     */
    public function getAnulada()
    {
        return $this->anulada;
    }

    /**
     * Set idUsuario
     *
     * @param \Mbp\SecurityBundle\Entity\Users $idUsuario
     *
     * @return Ot
     */
    public function setIdUsuario(\Mbp\SecurityBundle\Entity\Users $idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set fechaProg
     *
     * @param \DateTime $fechaProg
     *
     * @return Ot
     */
    public function setFechaProg($fechaProg)
    {
        $this->fechaProg = $fechaProg;

        return $this;
    }

    /**
     * Get fechaProg
     *
     * @return \DateTime
     */
    public function getFechaProg()
    {
        return $this->fechaProg;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return Ot
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}

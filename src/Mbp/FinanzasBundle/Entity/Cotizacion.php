<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cotizacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\CotizacionRepository")
 */
class Cotizacion
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
     * @ORM\Column(name="emision", type="date")
     */
    private $emision;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="string", length=255)
     */
    private $cliente;

    /**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=20)
     */
    private $cuit;

    /**
     * @var string
     *
     * @ORM\Column(name="condVenta", type="string", length=255)
     */
    private $condVenta;

    /**
     * @var boolean
     *
     * @ORM\Column(name="moneda", type="boolean")
     */
    private $moneda;

    /**
     * @var decimal
     *
     * @ORM\Column(name="tc", type="decimal", precision= 8, scale=2)
     */
    private $tc;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="total", type="decimal", precision= 11, scale=2)
     */
    private $total;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="descuento", type="decimal", precision= 11, scale=2)
     */
    private $descuento;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255)
     */
    private $observaciones;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	 * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=true)
	 */
	private $clienteId;
	
	/**
	 * @ORM\OneToMany(targetEntity="Mbp\FinanzasBundle\Entity\DetalleCotizacion", mappedBy="cotizacion", cascade={"persist", "remove"})
	 */
	private $detalle;
	
	/**
     * @var \Mbp\SecurityBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users")
     * @ORM\JoinColumn(name="idUsuario", referencedColumnName="id", nullable=false)	 
     */
    private $idUsuario;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactiva", type="boolean")
     */
    private $inactiva=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esExportacion", type="boolean", nullable=false)
     */
    private $esExportacion=0;
	
	public function __construct() {
        $this->detalle = new ArrayCollection();
    }

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
     * Set emision
     *
     * @param \DateTime $emision
     *
     * @return Cotizacion
     */
    public function setEmision($emision)
    {
        $this->emision = $emision;

        return $this;
    }

    /**
     * Get emision
     *
     * @return \DateTime
     */
    public function getEmision()
    {
        return $this->emision;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Cotizacion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     *
     * @return Cotizacion
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set condVenta
     *
     * @param string $condVenta
     *
     * @return Cotizacion
     */
    public function setCondVenta($condVenta)
    {
        $this->condVenta = $condVenta;

        return $this;
    }

    /**
     * Get condVenta
     *
     * @return string
     */
    public function getCondVenta()
    {
        return $this->condVenta;
    }

    /**
     * Set moneda
     *
     * @param boolean $moneda
     *
     * @return Cotizacion
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda
     *
     * @return boolean
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set tc
     *
     * @param string $tc
     *
     * @return Cotizacion
     */
    public function setTc($tc)
    {
        $this->tc = $tc;

        return $this;
    }

    /**
     * Get tc
     *
     * @return string
     */
    public function getTc()
    {
        return $this->tc;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Cotizacion
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
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return Cotizacion
     */
    public function setClienteId(\Mbp\ClientesBundle\Entity\Cliente $clienteId = null)
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * Add detalle
     *
     * @param \Mbp\FinanzasBundle\Entity\DetalleCotizacion $detalle
     *
     * @return Cotizacion
     */
    public function addDetalle(\Mbp\FinanzasBundle\Entity\DetalleCotizacion $detalle)
    {
        $this->detalle[] = $detalle;

        return $this;
    }

    /**
     * Remove detalle
     *
     * @param \Mbp\FinanzasBundle\Entity\DetalleCotizacion $detalle
     */
    public function removeDetalle(\Mbp\FinanzasBundle\Entity\DetalleCotizacion $detalle)
    {
        $this->detalle->removeElement($detalle);
    }

    /**
     * Get detalle
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set idUsuario
     *
     * @param \Mbp\SecurityBundle\Entity\Users $idUsuario
     *
     * @return Cotizacion
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
     * Set total
     *
     * @param string $total
     *
     * @return Cotizacion
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set descuento
     *
     * @param string $descuento
     *
     * @return Cotizacion
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return string
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set inactiva
     *
     * @param boolean $inactiva
     *
     * @return Cotizacion
     */
    public function setInactiva($inactiva)
    {
        $this->inactiva = $inactiva;

        return $this;
    }

    /**
     * Get inactiva
     *
     * @return boolean
     */
    public function getInactiva()
    {
        return $this->inactiva;
    }

    /**
     * Set cliente
     *
     * @param string $cliente
     *
     * @return Cotizacion
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set esExportacion
     *
     * @param boolean $esExportacion
     *
     * @return Cotizacion
     */
    public function setEsExportacion($esExportacion)
    {
        $this->esExportacion = $esExportacion;

        return $this;
    }

    /**
     * Get esExportacion
     *
     * @return boolean
     */
    public function getEsExportacion()
    {
        return $this->esExportacion;
    }
}

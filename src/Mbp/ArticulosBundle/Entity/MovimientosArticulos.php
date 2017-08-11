<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovimientosArticulos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\MovimientosArticulosRepository")
 */
class MovimientosArticulos
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
     * @ORM\Column(name="fechaMovimiento", type="date")
     */
    private $fechaMovimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="decimal")
     */
    private $cantidad;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipoMovimiento", type="boolean")
     */
    private $tipoMovimiento=0; //ENTRADA = 0, SALIDA = 1

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text")
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="comprobanteNum", type="string", length=255)
     */
    private $comprobanteNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="loteNumero", type="integer")
     */
    private $loteNumero;
    
	/** 
     * @var \Mbp\ArticulosBundle\Entity\ConceptosStock
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\ConceptosStock")
     * @ORM\JoinColumn(name="conceptoId", referencedColumnName="id", unique=false, nullable=false)	 
     */
    private $conceptoId;
    
	/** 
     * @var \Mbp\ProveedoresBundle\Entity\Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Proveedor")
     * @ORM\JoinColumn(name="proveedorId", referencedColumnName="id", unique=false, nullable=true)	 
     */
    private $proveedorId;
	
	/** 
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", unique=false, nullable=true)	 
     */
    private $clienteId;
	
	/** 
     * @var \Mbp\ComprasBundle\Entity\OrdenCompra
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ComprasBundle\Entity\OrdenCompra")
     * @ORM\JoinColumn(name="ordenCompraId", referencedColumnName="id", unique=false, nullable=true)	 
     */
    private $ordenCompraId;
    
    /** 
     * @var \Mbp\ArticulosBundle\Entity\DepositoArticulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\DepositoArticulos")
     * @ORM\JoinColumn(name="depositoId", referencedColumnName="id", unique=false, nullable=false)	 
     */
    private $depositoId;
    
    /** 
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos", unique=false, nullable=false)	 
     */
    private $articuloId;
	
	/**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
     */
    private $descripcion;


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
     * Set fechaMovimiento
     *
     * @param \DateTime $fechaMovimiento
     *
     * @return MovimientosArticulos
     */
    public function setFechaMovimiento($fechaMovimiento)
    {
        $this->fechaMovimiento = $fechaMovimiento;

        return $this;
    }

    /**
     * Get fechaMovimiento
     *
     * @return \DateTime
     */
    public function getFechaMovimiento()
    {
        return $this->fechaMovimiento;
    }

    /**
     * Set cantidad
     *
     * @param string $cantidad
     *
     * @return MovimientosArticulos
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return string
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set tipoMovimiento
     *
     * @param boolean $tipoMovimiento
     *
     * @return MovimientosArticulos
	 * 
	 * ENTRADA = 0, SALIDA = 1
     */
    public function setTipoMovimiento($tipoMovimiento)
    {
        $this->tipoMovimiento = $tipoMovimiento;

        return $this;
    }

    /**
     * Get tipoMovimiento
     *
     * @return boolean
     */
    public function getTipoMovimiento()
    {
        return $this->tipoMovimiento;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return MovimientosArticulos
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
     * Set comprobanteNum
     *
     * @param string $comprobanteNum
     *
     * @return MovimientosArticulos
     */
    public function setComprobanteNum($comprobanteNum)
    {
        $this->comprobanteNum = $comprobanteNum;

        return $this;
    }

    /**
     * Get comprobanteNum
     *
     * @return string
     */
    public function getComprobanteNum()
    {
        return $this->comprobanteNum;
    }

    /**
     * Set loteNumero
     *
     * @param integer $loteNumero
     *
     * @return MovimientosArticulos
     */
    public function setLoteNumero($loteNumero)
    {
        $this->loteNumero = $loteNumero;

        return $this;
    }

    /**
     * Get loteNumero
     *
     * @return integer
     */
    public function getLoteNumero()
    {
        return $this->loteNumero;
    }

    /**
     * Set conceptoId
     *
     * @param \Mbp\ArticulosBundle\Entity\ConceptosStock $conceptoId
     *
     * @return MovimientosArticulos
     */
    public function setConceptoId(\Mbp\ArticulosBundle\Entity\ConceptosStock $conceptoId)
    {
        $this->conceptoId = $conceptoId;

        return $this;
    }

    /**
     * Get conceptoId
     *
     * @return \Mbp\ArticulosBundle\Entity\ConceptosStock
     */
    public function getConceptoId()
    {
        return $this->conceptoId;
    }

    /**
     * Set proveedorId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId
     *
     * @return MovimientosArticulos
     */
    public function setProveedorId(\Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId = null)
    {
        $this->proveedorId = $proveedorId;

        return $this;
    }

    /**
     * Get proveedorId
     *
     * @return \Mbp\ProveedoresBundle\Entity\Proveedor
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return MovimientosArticulos
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
     * Set ordenCompraId
     *
     * @param \Mbp\ComprasBundle\Entity\OrdenCompra $ordenCompraId
     *
     * @return MovimientosArticulos
     */
    public function setOrdenCompraId(\Mbp\ComprasBundle\Entity\OrdenCompra $ordenCompraId = null)
    {
        $this->ordenCompraId = $ordenCompraId;

        return $this;
    }

    /**
     * Get ordenCompraId
     *
     * @return \Mbp\ComprasBundle\Entity\OrdenCompra
     */
    public function getOrdenCompraId()
    {
        return $this->ordenCompraId;
    }

    /**
     * Set depositoId
     *
     * @param \Mbp\ArticulosBundle\Entity\DepositoArticulos $depositoId
     *
     * @return MovimientosArticulos
     */
    public function setDepositoId(\Mbp\ArticulosBundle\Entity\DepositoArticulos $depositoId)
    {
        $this->depositoId = $depositoId;

        return $this;
    }

    /**
     * Get depositoId
     *
     * @return \Mbp\ArticulosBundle\Entity\DepositoArticulos
     */
    public function getDepositoId()
    {
        return $this->depositoId;
    }

    /**
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return MovimientosArticulos
     */
    public function setArticuloId(\Mbp\ArticulosBundle\Entity\Articulos $articuloId)
    {
        $this->articuloId = $articuloId;

        return $this;
    }

    /**
     * Get articuloId
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos
     */
    public function getArticuloId()
    {
        return $this->articuloId;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return MovimientosArticulos
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
}

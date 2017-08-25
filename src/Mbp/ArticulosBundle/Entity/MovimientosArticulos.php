<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
	 * @Assert\NotNull()
     */
    private $fechaMovimiento;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipoMovimiento", type="boolean")
	 * @Assert\NotNull()
     */
    private $tipoMovimiento=0; //ENTRADA = 0, SALIDA = 1

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="comprobanteNum", type="string", length=255)
	 * @Assert\NotNull()
     */
    private $comprobanteNum;

        
	/** 
     * @var \Mbp\ArticulosBundle\Entity\ConceptosStock
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\ConceptosStock")
     * @ORM\JoinColumn(name="conceptoId", referencedColumnName="id", unique=false, nullable=false)	 
	 * @Assert\NotNull()
     */
    private $conceptoId;
    
	/** 
     * @var \Mbp\ProveedoresBundle\Entity\Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Proveedor")
     * @ORM\JoinColumn(name="proveedorId", referencedColumnName="id", unique=false, nullable=true)	 
     */
    private $proveedorId=null;
	
	/** 
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", unique=false, nullable=true)	 
     */
    private $clienteId=null;
	
	
	/**
     * @ORM\ManyToMany(targetEntity="DetalleMovArt", inversedBy="movimientoId", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="movimientos_detalles")
	 */
	private $movDetalleId;
	
	/** 
     * @var \Mbp\ArticulosBundle\Entity\DepositoArticulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\DepositoArticulos")
     * @ORM\JoinColumn(name="depositoId", referencedColumnName="id", unique=false, nullable=false)
	 * @Assert\NotNull()	 
     */
    private $depositoId;


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
     * Constructor
     */
    public function __construct()
    {
        $this->movDetalleId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movDetalleId
     *
     * @param \Mbp\ArticulosBundle\Entity\DetalleMovArt $movDetalleId
     *
     * @return MovimientosArticulos
     */
    public function addMovDetalleId(\Mbp\ArticulosBundle\Entity\DetalleMovArt $movDetalleId)
    {
        $this->movDetalleId[] = $movDetalleId;

        return $this;
    }

    /**
     * Remove movDetalleId
     *
     * @param \Mbp\ArticulosBundle\Entity\DetalleMovArt $movDetalleId
     */
    public function removeMovDetalleId(\Mbp\ArticulosBundle\Entity\DetalleMovArt $movDetalleId)
    {
        $this->movDetalleId->removeElement($movDetalleId);
    }

    /**
     * Get movDetalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovDetalleId()
    {
        return $this->movDetalleId;
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
}

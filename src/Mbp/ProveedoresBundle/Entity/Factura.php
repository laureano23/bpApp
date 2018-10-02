<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FacturaProveedor
 *
 * @ORM\Table(name="FacturaProveedor",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unicoComprobante",
 *          columns= {"tipoId", "sucursal", "numFc", "proveedorId"})
 *      })
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\FacturaRepository")
 */
class Factura
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
	 * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Proveedor")
	 * @ORM\JoinColumn(name="proveedorId", referencedColumnName="id")
	 */
	private $proveedorId;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\ImputacionGastos")
	 * @ORM\JoinColumn(name="gastoId", referencedColumnName="id", nullable=true)
	 */
	private $imputacionGasto;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCarga", type="datetime")
     */
    private $fechaCarga;

    /**
     * @var \Date
     *
     * @ORM\Column(name="fechaEmision", type="date")
     */
    private $fechaEmision;

    /**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\TipoComprobante")
	 * @ORM\JoinColumn(name="tipoId", referencedColumnName="id", nullable=false)
	 */
    private $tipoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="sucursal", type="smallint")
     */
    private $sucursal;

    /**
     * @var integer
     *
     * @ORM\Column(name="numFc", type="integer")
     */
    private $numFc;

    /**
     * @var string
     *
     * @ORM\Column(name="neto", type="decimal", precision=11 , scale=2)
     */
    private $neto;

    /**
     * @var string
     *
     * @ORM\Column(name="netoNoGrabado", type="decimal", precision=11 , scale=2, nullable=true)
     */
    private $netoNoGrabado;

    /**
     * @var string
     *
     * @ORM\Column(name="iva21", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $iva21;

    /**
     * @var string
     *
     * @ORM\Column(name="iva27", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $iva27;

    /**
     * @var string
     *
     * @ORM\Column(name="iva10_5", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $iva105;

    /**
     * @var string
     *
     * @ORM\Column(name="perIva5", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $perIva5;

    /**
     * @var string
     *
     * @ORM\Column(name="perIva3", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $perIva3;

    /**
     * @var string
     *
     * @ORM\Column(name="iibbCf", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $iibbCf=0;

    /**
     * @var string
     *
     * @ORM\Column(name="iibbBsas", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $iibbBsas=0;

    /**
     * @var string
     *
     * @ORM\Column(name="iibbOtras", type="decimal", precision=7 , scale=2, nullable=true)
     */
    private $iibbOtras=0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vencimiento", type="date")
     */
    private $vencimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="concepto", type="string", length=150, nullable=true)
     */
    private $concepto;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="totalFc", type="decimal", precision=11 , scale=2)
     */
    private $totalFc;
	
	 /**
     * @var decimal
     *
     * @ORM\Column(name="imputado", type="decimal", precision=11 , scale=2, nullable=true)
     */
    private $imputado=0;

    /**
     * @ORM\OneToMany(targetEntity="TransaccionOPFC", mappedBy="ordenPagoImputada")
     */
    private $ordenPago;
    
	/**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=250, nullable=true)
     */
    private $observaciones;
    
	/**
     * @var boolean
     *
     * @ORM\Column(name="esBalance", type="boolean", nullable=true)
     */
    private $esBalance=0;
	
	/**
	 * @ORM\OneToOne(targetEntity="Mbp\ProveedoresBundle\Entity\CCProv", mappedBy="facturaId", cascade={"remove", "persist"})
	 * @ORM\JoinColumn(name="ccId", referencedColumnName="id", onDelete="SET NULL")
	 */
    private $ccId;

    public function __construct() {
        $this->ordenPago = new ArrayCollection();
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
     * Set fechaCarga
     *
     * @param \DateTime $fechaCarga
     *
     * @return Factura
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
     * Set fechaEmision
     *
     * @param \Date $fechaEmision
     *
     * @return Factura
     */
    public function setFechaEmision($fechaEmision)
    {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    /**
     * Get fechaEmision
     *
     * @return \Date
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

   
    /**
     * Set sucursal
     *
     * @param integer $sucursal
     *
     * @return Factura
     */
    public function setSucursal($sucursal)
    {
        $this->sucursal = $sucursal;

        return $this;
    }

    /**
     * Get sucursal
     *
     * @return integer
     */
    public function getSucursal()
    {
        return $this->sucursal;
    }

    /**
     * Set numFc
     *
     * @param integer $numFc
     *
     * @return Factura
     */
    public function setNumFc($numFc)
    {
        $this->numFc = $numFc;

        return $this;
    }

    /**
     * Get numFc
     *
     * @return integer
     */
    public function getNumFc()
    {
        return $this->numFc;
    }

    /**
     * Set neto
     *
     * @param string $neto
     *
     * @return Factura
     */
    public function setNeto($neto)
    {
        $this->neto = $neto;

        return $this;
    }

    /**
     * Get neto
     *
     * @return string
     */
    public function getNeto()
    {
        return $this->neto;
    }

    /**
     * Set netoNoGrabado
     *
     * @param string $netoNoGrabado
     *
     * @return Factura
     */
    public function setNetoNoGrabado($netoNoGrabado)
    {
        $this->netoNoGrabado = $netoNoGrabado;

        return $this;
    }

    /**
     * Get netoNoGrabado
     *
     * @return string
     */
    public function getNetoNoGrabado()
    {
        return $this->netoNoGrabado;
    }

    /**
     * Set iva21
     *
     * @param string $iva21
     *
     * @return Factura
     */
    public function setIva21($iva21)
    {
        $this->iva21 = $iva21;

        return $this;
    }

    /**
     * Get iva21
     *
     * @return string
     */
    public function getIva21()
    {
        return $this->iva21;
    }

    /**
     * Set iva27
     *
     * @param string $iva27
     *
     * @return Factura
     */
    public function setIva27($iva27)
    {
        $this->iva27 = $iva27;

        return $this;
    }

    /**
     * Get iva27
     *
     * @return string
     */
    public function getIva27()
    {
        return $this->iva27;
    }

    /**
     * Set iva105
     *
     * @param string $iva105
     *
     * @return Factura
     */
    public function setIva105($iva105)
    {
        $this->iva105 = $iva105;

        return $this;
    }

    /**
     * Get iva105
     *
     * @return string
     */
    public function getIva105()
    {
        return $this->iva105;
    }

    /**
     * Set perIva5
     *
     * @param string $perIva5
     *
     * @return Factura
     */
    public function setPerIva5($perIva5)
    {
        $this->perIva5 = $perIva5;

        return $this;
    }

    /**
     * Get perIva5
     *
     * @return string
     */
    public function getPerIva5()
    {
        return $this->perIva5;
    }

    /**
     * Set perIva3
     *
     * @param string $perIva3
     *
     * @return Factura
     */
    public function setPerIva3($perIva3)
    {
        $this->perIva3 = $perIva3;

        return $this;
    }

    /**
     * Get perIva3
     *
     * @return string
     */
    public function getPerIva3()
    {
        return $this->perIva3;
    }

    /**
     * Set iibbCf
     *
     * @param string $iibbCf
     *
     * @return Factura
     */
    public function setIibbCf($iibbCf)
    {
        $this->iibbCf = $iibbCf;

        return $this;
    }

    /**
     * Get iibbCf
     *
     * @return string
     */
    public function getIibbCf()
    {
        return $this->iibbCf;
    }

    /**
     * Set vencimiento
     *
     * @param \DateTime $vencimiento
     *
     * @return Factura
     */
    public function setVencimiento($vencimiento)
    {
        $this->vencimiento = $vencimiento;

        return $this;
    }

    /**
     * Get vencimiento
     *
     * @return \DateTime
     */
    public function getVencimiento()
    {
        return $this->vencimiento;
    }

    /**
     * Set concepto
     *
     * @param string $concepto
     *
     * @return Factura
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set proveedorId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId
     *
     * @return Factura
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
     * Set totalFc
     *
     * @param string $totalFc
     *
     * @return Factura
     */
    public function setTotalFc()
    {
        $this->totalFc = $this->getNeto() + $this->getNetoNoGrabado() + $this->getIva21() + $this->getIibbCf() + $this->getIva105() + $this->getIva27() + $this->getPerIva3() + $this->getPerIva5() + $this->getIibbBsas() + $this->getIibbOtras();

        return $this;
    }

    /**
     * Get totalFc
     *
     * @return string
     */
    public function getTotalFc()
    {
        return $this->totalFc;
    }

    /**
     * Set imputado
     *
     * @param string $imputado
     *
     * @return Factura
     */
    public function setImputado($imputado)
    {
        $this->imputado = $imputado;

        return $this;
    }

    /**
     * Get imputado
     *
     * @return string
     */
    public function getImputado()
    {
        return $this->imputado;
    }

    /**
     * Set imputacionGasto
     *
     * @param \Mbp\ProveedoresBundle\Entity\ImputacionGastos $imputacionGasto
     *
     * @return Factura
     */
    public function setImputacionGasto(\Mbp\ProveedoresBundle\Entity\ImputacionGastos $imputacionGasto = null)
    {
        $this->imputacionGasto = $imputacionGasto;

        return $this; 
    }

    /**
     * Get imputacionGasto
     *
     * @return \Mbp\ProveedoresBundle\Entity\ImputacionGastos
     */
    public function getImputacionGasto()
    {
        return $this->imputacionGasto;
    }

    /**
     * Add ordenPago
     *
     * @param \Mbp\ProveedoresBundle\Entity\TransaccionOPFC $ordenPago
     *
     * @return Factura
     */
    public function addOrdenPago(\Mbp\ProveedoresBundle\Entity\TransaccionOPFC $ordenPago)
    {
        $this->ordenPago[] = $ordenPago;

        return $this;
    }

    /**
     * Remove ordenPago
     *
     * @param \Mbp\ProveedoresBundle\Entity\TransaccionOPFC $ordenPago
     */
    public function removeOrdenPago(\Mbp\ProveedoresBundle\Entity\TransaccionOPFC $ordenPago)
    {
        $this->ordenPago->removeElement($ordenPago);
    }

    /**
     * Get ordenPago
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdenPago()
    {
        return $this->ordenPago;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Factura
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
     * Set esBalance
     *
     * @param boolean $esBalance
     *
     * @return Factura
     */
    public function setEsBalance($esBalance)
    {
        $this->esBalance = $esBalance;

        return $this;
    }

    /**
     * Get esBalance
     *
     * @return boolean
     */
    public function getEsBalance()
    {
        return $this->esBalance;
    }

    /**
     * Set tipoId
     *
     * @param \Mbp\FinanzasBundle\Entity\TipoComprobante $tipoId
     *
     * @return Factura
     */
    public function setTipoId(\Mbp\FinanzasBundle\Entity\TipoComprobante $tipoId)
    {
        $this->tipoId = $tipoId;

        return $this;
    }

    /**
     * Get tipoId
     *
     * @return \Mbp\FinanzasBundle\Entity\TipoComprobante
     */
    public function getTipoId()
    {
        return $this->tipoId;
    }

    /**
     * Set ccId
     *
     * @param \Mbp\ProveedoresBundle\Entity\CCProv $ccId
     *
     * @return Factura
     */
    public function setCcId(\Mbp\ProveedoresBundle\Entity\CCProv $ccId = null)
    {
        $this->ccId = $ccId;

        return $this;
    }

    /**
     * Get ccId
     *
     * @return \Mbp\ProveedoresBundle\Entity\CCProv
     */
    public function getCcId()
    {
        return $this->ccId;
    }

    /**
     * Set iibbBsas
     *
     * @param string $iibbBsas
     *
     * @return Factura
     */
    public function setIibbBsas($iibbBsas)
    {
        $this->iibbBsas = $iibbBsas;

        return $this;
    }

    /**
     * Get iibbBsas
     *
     * @return string
     */
    public function getIibbBsas()
    {
        return $this->iibbBsas;
    }

    /**
     * Set iibbOtras
     *
     * @param string $iibbOtras
     *
     * @return Factura
     */
    public function setIibbOtras($iibbOtras)
    {
        $this->iibbOtras = $iibbOtras;

        return $this;
    }

    /**
     * Get iibbOtras
     *
     * @return string
     */
    public function getIibbOtras()
    {
        return $this->iibbOtras;
    }
}

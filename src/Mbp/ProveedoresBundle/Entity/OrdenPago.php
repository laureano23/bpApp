<?php

namespace Mbp\ProveedoresBundle\Entity; 

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrdenPago
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\OrdenPagoRepository")
 */
class OrdenPago
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
	 * @ORM\ManyToMany(targetEntity="Pago", cascade={"remove", "persist"})
	 * @ORM\JoinTable(name="OrdenDePago_detallesPagos",
	 *  joinColumns={ @ORM\JoinColumn(name = "ordenPago_id", referencedColumnName="id") }),
	 *  inverseJoinColumns={@JoinColumn(name="detallesPagos_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $pagoDetalleId;
	
	/**
	 * @ORM\OneToMany(targetEntity="TransaccionOPFC", mappedBy="facturaImputada")
	 */
	private $facturasImputadas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="datetime")
     */
    private $emision;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="importe", type="decimal", precision=11, scale=2, nullable=false)
     */
    private $importeTotal;

    
    /**
     * @var decimal
     *
     * @ORM\Column(name="alicuotaRetencionIIBB", type="decimal", precision=11, scale=2, nullable=true)
     */
    private $alicuotaRetencionIIBB;
	
	/**
	 * @ORM\OneToOne(targetEntity="Mbp\ProveedoresBundle\Entity\CCProv", mappedBy="factura", cascade={"remove", "persist"})
	 * @ORM\JoinColumn(name="ccId", referencedColumnName="id", onDelete="SET NULL")
	 */
	private $ccId;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="topeRetencionIIBB", type="decimal", precision=11, scale=2, nullable=false)
     */
    private $topeRetencionIIBB=0;
	
	

    public function __construct() {
        $this->facturasImputadas = new ArrayCollection();
        $this->pagoDetalleId = new ArrayCollection();
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
     * @return OrdenPago
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
     * Set proveedorId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId
     *
     * @return OrdenPago
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
     * Add pagoDetalleId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Pago $pagoDetalleId
     *
     * @return OrdenPago
     */
    public function addPagoDetalleId(\Mbp\ProveedoresBundle\Entity\Pago $pagoDetalleId)
    {
        $this->pagoDetalleId[] = $pagoDetalleId;

        return $this;
    }

    /**
     * Remove pagoDetalleId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Pago $pagoDetalleId
     */
    public function removePagoDetalleId(\Mbp\ProveedoresBundle\Entity\Pago $pagoDetalleId)
    {
        $this->pagoDetalleId->removeElement($pagoDetalleId);
    }

    /**
     * Get pagoDetalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagoDetalleId()
    {
        return $this->pagoDetalleId;
    }

    /**
     * Add facturasImputada
     *
     * @param \Mbp\ProveedoresBundle\Entity\TransaccionOPFC $facturasImputada
     *
     * @return OrdenPago
     */
    public function addFacturasImputada(\Mbp\ProveedoresBundle\Entity\TransaccionOPFC $facturasImputada)
    {
        $this->facturasImputadas[] = $facturasImputada;

        return $this;
    }

    /**
     * Remove facturasImputada
     *
     * @param \Mbp\ProveedoresBundle\Entity\TransaccionOPFC $facturasImputada
     */
    public function removeFacturasImputada(\Mbp\ProveedoresBundle\Entity\TransaccionOPFC $facturasImputada)
    {
        $this->facturasImputadas->removeElement($facturasImputada);
    }

    /**
     * Get facturasImputadas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasImputadas()
    {
        return $this->facturasImputadas;
    }

    /**
     * Set importeTotal
     *
     * @param string $importeTotal
     *
     * @return OrdenPago
     */
    public function setImporteTotal($importeTotal)
    {
        $this->importeTotal = $importeTotal;

        return $this;
    }

    /**
     * Get importeTotal
     *
     * @return string
     */
    public function getImporteTotal()
    {
        return $this->importeTotal;
    }

    /**
     * Set ccId
     *
     * @param \Mbp\ProveedoresBundle\Entity\CCProv $ccId
     *
     * @return OrdenPago
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
     * Set topeRetencionIIBB
     *
     * @param string $topeRetencionIIBB
     *
     * @return OrdenPago
     */
    public function setTopeRetencionIIBB($topeRetencionIIBB)
    {
        $this->topeRetencionIIBB = $topeRetencionIIBB;

        return $this;
    }

    /**
     * Get topeRetencionIIBB
     *
     * @return string
     */
    public function getTopeRetencionIIBB()
    {
        return $this->topeRetencionIIBB;
    }

    /**
     * Set alicuotaRetencionIIBB
     *
     * @param string $alicuotaRetencionIIBB
     *
     * @return OrdenPago
     */
    public function setAlicuotaRetencionIIBB($alicuotaRetencionIIBB)
    {
        $this->alicuotaRetencionIIBB = $alicuotaRetencionIIBB;

        return $this;
    }

    /**
     * Get alicuotaRetencionIIBB
     *
     * @return string
     */
    public function getAlicuotaRetencionIIBB()
    {
        return $this->alicuotaRetencionIIBB;
    }
}

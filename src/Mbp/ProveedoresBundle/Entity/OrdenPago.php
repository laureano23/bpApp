<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
	 *  joinColumns={ @ORM\JoinColumn(name = "ordenPago_id", referencedColumnName="id", onDelete="CASCADE") }),
	 *  inverseJoinColumns={@JoinColumn(name="detallesPagos_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $pagoDetalleId;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Factura")
	 * @ORM\JoinTable(name="Pago_FacturaProveedores",
	 *  joinColumns={ @ORM\JoinColumn(name = "pago_id", referencedColumnName="id", onDelete="CASCADE") }),
	 *  inverseJoinColumns={@JoinColumn(name="facturaProveedor_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $facturasImputadas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="datetime")
     */
    private $emision;


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
     * Constructor
     */
    public function __construct()
    {
        $this->pagoDetalleId = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \Mbp\ProveedoresBundle\Entity\Factura $facturasImputada
     *
     * @return OrdenPago
     */
    public function addFacturasImputada(\Mbp\ProveedoresBundle\Entity\Factura $facturasImputada)
    {
        $this->facturasImputadas[] = $facturasImputada;

        return $this;
    }

    /**
     * Remove facturasImputada
     *
     * @param \Mbp\ProveedoresBundle\Entity\Factura $facturasImputada
     */
    public function removeFacturasImputada(\Mbp\ProveedoresBundle\Entity\Factura $facturasImputada)
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
}

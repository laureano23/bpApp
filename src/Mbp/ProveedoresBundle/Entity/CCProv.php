<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CCProv
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\CCProvRepository")
 */
class CCProv
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
     * @var string
     *
     * @ORM\Column(name="debe", type="decimal", precision=11 , scale=2, nullable=false)
     */
    private $debe=0;

    /**
     * @var string
     *
     * @ORM\Column(name="haber", type="decimal", precision=11 , scale=2, nullable=false)
     */
    private $haber=0;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="datetime", nullable=false)
     */
    private $fechaEmision;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaVencimiento", type="date", nullable=false)
     */
    private $fechaVencimiento;
    
    /**
	 * @ORM\OneToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Factura", inversedBy="ccProv")
	 * @ORM\JoinColumn(name="facturaId", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $facturaId;
	
	/**
	 * @ORM\OneToOne(targetEntity="Mbp\ProveedoresBundle\Entity\OrdenPago", inversedBy="ccProv")
	 * @ORM\JoinColumn(name="OrdenPagoId", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $OrdenPagoId;


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
     * Set debe
     *
     * @param string $debe
     *
     * @return CCProv
     */
    public function setDebe($debe)
    {
        $this->debe = $debe;

        return $this;
    }

    /**
     * Get debe
     *
     * @return string
     */
    public function getDebe()
    {
        return $this->debe;
    }

    /**
     * Set haber
     *
     * @param string $haber
     *
     * @return CCProv
     */
    public function setHaber($haber)
    {
        $this->haber = $haber;

        return $this;
    }

    /**
     * Get haber
     *
     * @return string
     */
    public function getHaber()
    {
        return $this->haber;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return CCProv
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return CCProv
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    
    /**
     * Set facturaId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Factura $facturaId
     *
     * @return CCProv
     */
    public function setFacturaId(\Mbp\ProveedoresBundle\Entity\Factura $facturaId = null)
    {
        $this->facturaId = $facturaId;

        return $this;
    }

    /**
     * Get facturaId
     *
     * @return \Mbp\ProveedoresBundle\Entity\Factura
     */
    public function getFacturaId()
    {
        return $this->facturaId;
    }

    /**
     * Set ordenPagoId
     *
     * @param \Mbp\ProveedoresBundle\Entity\OrdenPago $ordenPagoId
     *
     * @return CCProv
     */
    public function setOrdenPagoId(\Mbp\ProveedoresBundle\Entity\OrdenPago $ordenPagoId = null)
    {
        $this->OrdenPagoId = $ordenPagoId;

        return $this;
    }

    /**
     * Get ordenPagoId
     *
     * @return \Mbp\ProveedoresBundle\Entity\OrdenPago
     */
    public function getOrdenPagoId()
    {
        return $this->OrdenPagoId;
    }
}

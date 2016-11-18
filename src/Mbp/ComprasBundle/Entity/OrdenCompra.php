<?php

namespace Mbp\ComprasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdenCompra
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ComprasBundle\Entity\OrdenCompraRepository")
 */
class OrdenCompra
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
     * @var \Mbp\ProveedoresBundle\Entity\Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Proveedor")
     * @ORM\JoinColumn(name="proveedorId", referencedColumnName="id", unique=false)	 
     */
    private $proveedorId;
	
	/**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30)
     */
    private $usuario;
	
	/**
	 * @ORM\ManyToMany(targetEntity="OrdenCompraDetalle", cascade={"remove", "persist"})
	 * @ORM\JoinTable(name="ordenCompra_detallesOrdenCompra",
	 *  joinColumns={ @ORM\JoinColumn(name = "orden_id", referencedColumnName="id") }),
	 *  inverseJoinColumns={@JoinColumn(name="detallesOrden_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $ordenDetalleId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="date")
     */
    private $fechaEmision;

    /**
     * @var boolean
     *
     * @ORM\Column(name="monedaOC", type="boolean")
     */
    private $monedaOC;

    /**
     * @var string
     *
     * @ORM\Column(name="condicionCompra", type="string", length=100)
     */
    private $condicionCompra;

    /**
     * @var string
     *
     * @ORM\Column(name="lugarEntrega", type="string", length=150)
     */
    private $lugarEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=100)
     */
    private $observaciones;

    /**
     * @var decimal
     *
     * @ORM\Column(name="descuentoGral", type="decimal", precision=5, scale=2)
     */
    private $descuentoGral;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="tc", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $tc;


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
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return OrdenCompra
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
     * Set monedaOC
     *
     * @param boolean $monedaOC
     *
     * @return OrdenCompra
     */
    public function setMonedaOC($monedaOC)
    {
        $this->monedaOC = $monedaOC;

        return $this;
    }

    /**
     * Get monedaOC
     *
     * @return boolean
     */
    public function getMonedaOC()
    {
        return $this->monedaOC;
    }

    /**
     * Set condicionCompra
     *
     * @param string $condicionCompra
     *
     * @return OrdenCompra
     */
    public function setCondicionCompra($condicionCompra)
    {
        $this->condicionCompra = $condicionCompra;

        return $this;
    }

    /**
     * Get condicionCompra
     *
     * @return string
     */
    public function getCondicionCompra()
    {
        return $this->condicionCompra;
    }

    /**
     * Set lugarEntrega
     *
     * @param string $lugarEntrega
     *
     * @return OrdenCompra
     */
    public function setLugarEntrega($lugarEntrega)
    {
        $this->lugarEntrega = $lugarEntrega;

        return $this;
    }

    /**
     * Get lugarEntrega
     *
     * @return string
     */
    public function getLugarEntrega()
    {
        return $this->lugarEntrega;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return OrdenCompra
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
     * Set descuentoGral
     *
     * @param string $descuentoGral
     *
     * @return OrdenCompra
     */
    public function setDescuentoGral($descuentoGral)
    {
        $this->descuentoGral = $descuentoGral;

        return $this;
    }

    /**
     * Get descuentoGral
     *
     * @return string
     */
    public function getDescuentoGral()
    {
        return $this->descuentoGral;
    }

    /**
     * Set proveedorId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId
     *
     * @return OrdenCompra
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
     * Constructor
     */
    public function __construct()
    {
        $this->ordenDetalleId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ordenDetalleId
     *
     * @param \Mbp\ComprasBundle\Entity\OrdenCompraDetalle $ordenDetalleId
     *
     * @return OrdenCompra
     */
    public function addOrdenDetalleId(\Mbp\ComprasBundle\Entity\OrdenCompraDetalle $ordenDetalleId)
    {
        $this->ordenDetalleId[] = $ordenDetalleId;

        return $this;
    }

    /**
     * Remove ordenDetalleId
     *
     * @param \Mbp\ComprasBundle\Entity\OrdenCompraDetalle $ordenDetalleId
     */
    public function removeOrdenDetalleId(\Mbp\ComprasBundle\Entity\OrdenCompraDetalle $ordenDetalleId)
    {
        $this->ordenDetalleId->removeElement($ordenDetalleId);
    }

    /**
     * Get ordenDetalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdenDetalleId()
    {
        return $this->ordenDetalleId;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return OrdenCompra
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set tc
     *
     * @param string $tc
     *
     * @return OrdenCompra
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
}

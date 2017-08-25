<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DetalleMovArt
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\DetalleMovArtRepository")
 */
class DetalleMovArt
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
     * @ORM\Column(name="cantidad", type="decimal")
	 * @Assert\NotNull()
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="loteNum", type="integer", nullable=true)
     */
    private $loteNum;
	
	/** 
     * @var \Mbp\ComprasBundle\Entity\OrdenCompra
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ComprasBundle\Entity\OrdenCompra")
     * @ORM\JoinColumn(name="ordenCompraId", referencedColumnName="id", unique=false, nullable=true)	 
     */
    private $ordenCompraId;
    
    /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos", unique=false)	
	 * @Assert\NotNull() 
     */
    private $articuloId;
	
	/**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=false)
	 * @Assert\NotNull()
     */
    private $descripcion;
	
	/**
	 * @ORM\ManyToMany(targetEntity="MovimientosArticulos", mappedBy="movDetalleId")	
	 * @Assert\NotNull()  
	 */
	private $movimientoId;

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
     * Set cantidad
     *
     * @param string $cantidad
     *
     * @return DetalleMovArt
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
     * Set loteNum
     *
     * @param integer $loteNum
     *
     * @return DetalleMovArt
     */
    public function setLoteNum($loteNum)
    {
        $this->loteNum = $loteNum;

        return $this;
    }

    /**
     * Get loteNum
     *
     * @return integer
     */
    public function getLoteNum()
    {
        return $this->loteNum;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return DetalleMovArt
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

    /**
     * Set ordenCompraId
     *
     * @param \Mbp\ComprasBundle\Entity\OrdenCompra $ordenCompraId
     *
     * @return DetalleMovArt
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
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return DetalleMovArt
     */
    public function setArticuloId(\Mbp\ArticulosBundle\Entity\Articulos $articuloId = null)
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
     * Constructor
     */
    public function __construct()
    {
        $this->movimientoId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add movimientoId
     *
     * @param \Mbp\ArticulosBundle\Entity\MovimientosArticulos $movimientoId
     *
     * @return DetalleMovArt
     */
    public function addMovimientoId(\Mbp\ArticulosBundle\Entity\MovimientosArticulos $movimientoId)
    {
        $this->movimientoId[] = $movimientoId;

        return $this;
    }

    /**
     * Remove movimientoId
     *
     * @param \Mbp\ArticulosBundle\Entity\MovimientosArticulos $movimientoId
     */
    public function removeMovimientoId(\Mbp\ArticulosBundle\Entity\MovimientosArticulos $movimientoId)
    {
        $this->movimientoId->removeElement($movimientoId);
    }

    /**
     * Get movimientoId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientoId()
    {
        return $this->movimientoId;
    }
}

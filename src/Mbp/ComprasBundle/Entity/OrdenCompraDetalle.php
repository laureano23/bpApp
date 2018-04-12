<?php

namespace Mbp\ComprasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrdenCompraDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ComprasBundle\Entity\OrdenCompraDetalleRepository")
 */
class OrdenCompraDetalle
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
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos", unique=false)	 
     */
    private $articuloId;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=20)
     */
    private $unidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=9, scale=2)
     */
    private $precio;

    /**
     * @var string
     *
     * @ORM\Column(name="cant", type="decimal", precision=9, scale=2)
     */
    private $cant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEntrega", type="date")
     */
    private $fechaEntrega;

    /**
     * @var string
     *
     * @ORM\Column(name="iva", type="decimal", precision=4, scale=2)
     */
    private $iva;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="ivaCalculado", type="decimal", precision=12, scale=4)
     */
    private $ivaCalculado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="moneda", type="boolean")
     */
    private $moneda;
    
	/**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=250)
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
     * Set unidad
     *
     * @param string $unidad
     *
     * @return OrdenCompraDetalle
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * Get unidad
     *
     * @return string
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return OrdenCompraDetalle
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set cant
     *
     * @param string $cant
     *
     * @return OrdenCompraDetalle
     */
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * Get cant
     *
     * @return string
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return OrdenCompraDetalle
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set iva
     *
     * @param string $iva
     *
     * @return OrdenCompraDetalle
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return string
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set moneda
     *
     * @param boolean $moneda
     *
     * @return OrdenCompraDetalle
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
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return OrdenCompraDetalle
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
     * Set ivaCalculado
     *
     * @param string $ivaCalculado
     *
     * @return OrdenCompraDetalle
     */
    public function setIvaCalculado($ivaCalculado)
    {
        $this->ivaCalculado = $ivaCalculado;

        return $this;
    }

    /**
     * Get ivaCalculado
     *
     * @return string
     */
    public function getIvaCalculado()
    {
        return $this->ivaCalculado;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return OrdenCompraDetalle
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

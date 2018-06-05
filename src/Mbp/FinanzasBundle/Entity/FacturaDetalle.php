<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FacturaDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\FacturaDetalleRepository")
 */
class FacturaDetalle
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
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="decimal", precision=8, scale=2)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision=8, scale=2)
     */
    private $precio;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
	 * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos")
	 */
	private $articuloId;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\RemitosClientesDetalles")
	 * @ORM\JoinColumn(name="remitoId", referencedColumnName="id")
	 */
	private $remitoDetalleId;

     /**
     * @var boolean
     *
     * @ORM\Column(name="ivaGrabado", type="boolean", nullable=false)
     */
    private $ivaGrabado=1;


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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return FacturaDetalle
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
     * Set cantidad
     *
     * @param string $cantidad
     *
     * @return FacturaDetalle
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
     * Set precio
     *
     * @param string $precio
     *
     * @return FacturaDetalle
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
     * Set facturaId
     *
     * @param \Mbp\FinanzasBundle\Entity\Facturas $facturaId
     *
     * @return FacturaDetalle
     */
    public function setFacturaId(\Mbp\FinanzasBundle\Entity\Facturas $facturaId = null)
    {
        $this->facturaId = $facturaId;

        return $this;
    }

    /**
     * Get facturaId
     *
     * @return \Mbp\FinanzasBundle\Entity\Facturas
     */
    public function getFacturaId()
    {
        return $this->facturaId;
    }

    /**
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return FacturaDetalle
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
     * Set remitoDetalleId
     *
     * @param \Mbp\ArticulosBundle\Entity\RemitosClientesDetalles $remitoDetalleId
     *
     * @return FacturaDetalle
     */
    public function setRemitoDetalleId(\Mbp\ArticulosBundle\Entity\RemitosClientesDetalles $remitoDetalleId = null)
    {
        $this->remitoDetalleId = $remitoDetalleId;

        return $this;
    }

    /**
     * Get remitoDetalleId
     *
     * @return \Mbp\ArticulosBundle\Entity\RemitosClientesDetalles
     */
    public function getRemitoDetalleId()
    {
        return $this->remitoDetalleId;
    }

    /**
     * Set ivaGrabado
     *
     * @param boolean $ivaGrabado
     *
     * @return FacturaDetalle
     */
    public function setIvaGrabado($ivaGrabado)
    {
        $this->ivaGrabado = $ivaGrabado;

        return $this;
    }

    /**
     * Get ivaGrabado
     *
     * @return boolean
     */
    public function getIvaGrabado()
    {
        return $this->ivaGrabado;
    }
}

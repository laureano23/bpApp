<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RemitosClientesDetalles
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\RemitosClientesDetallesRepository")
 */
class RemitosClientesDetalles
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="decimal", precision=9, scale=2)
     * @Assert\Range(
     *      min = 0.01,
     *      max = 1000000
     * )
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=50) 
     */
    private $unidad;

    /**
     * @var string
     *
     * @ORM\Column(name="oc", type="string", length=100)
     */
    private $oc;

    /**
     * @var \Mbp\ArticulosBundle\Entity\Articulo
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos", nullable=true)    
     */
    private $articuloId;

    /**
     * @var \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\PedidoClientesDetalle")
     * @ORM\JoinColumn(name="pedidoDetalleId", referencedColumnName="id", nullable=true)    
     */
    private $pedidoDetalleId;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="facturado", type="boolean", nullable=false)
     */
    private $facturado=0; 
	

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
     * @return RemitosClientesDetalles
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
     * @return RemitosClientesDetalles
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
     * Set unidad
     *
     * @param string $unidad
     *
     * @return RemitosClientesDetalles
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
     * Set oc
     *
     * @param string $oc
     *
     * @return RemitosClientesDetalles
     */
    public function setOc($oc)
    {
        $this->oc = $oc;

        return $this;
    }

    /**
     * Get oc
     *
     * @return string
     */
    public function getOc()
    {
        return $this->oc;
    }

    /**
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return RemitosClientesDetalles
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
     * Set facturado
     *
     * @param boolean $facturado
     *
     * @return RemitosClientesDetalles
     */
    public function setFacturado($facturado)
    {
        $this->facturado = $facturado;

        return $this;
    }

    /**
     * Get facturado
     *
     * @return boolean
     */
    public function getFacturado()
    {
        return $this->facturado;
    }

    /**
     * Set pedidoDetalleId
     *
     * @param \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $pedidoDetalleId
     *
     * @return RemitosClientesDetalles
     */
    public function setPedidoDetalleId(\Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $pedidoDetalleId = null)
    {
        $this->pedidoDetalleId = $pedidoDetalleId;

        return $this;
    }

    /**
     * Get pedidoDetalleId
     *
     * @return \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle
     */
    public function getPedidoDetalleId()
    {
        return $this->pedidoDetalleId;
    }
}

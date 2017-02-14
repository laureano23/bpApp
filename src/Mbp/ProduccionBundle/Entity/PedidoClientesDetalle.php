<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedidoClientesDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\PedidoClientesDetalleRepository")
 */
class PedidoClientesDetalle
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
     */
    private $cantidad=0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaProg", type="date")
     */
    private $fechaProg;

    /**
     * @var string
     *
     * @ORM\Column(name="entregado", type="decimal")
     */
    private $entregado=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo=0;

    /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="codigo", referencedColumnName="idArticulos")
     */
    private $codigo;


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
     * @return PedidoClientesDetalle
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
     * Set fechaProg
     *
     * @param \DateTime $fechaProg
     *
     * @return PedidoClientesDetalle
     */
    public function setFechaProg($fechaProg)
    {
        $this->fechaProg = $fechaProg;

        return $this;
    }

    /**
     * Get fechaProg
     *
     * @return \DateTime
     */
    public function getFechaProg()
    {
        return $this->fechaProg;
    }

    /**
     * Set entregado
     *
     * @param string $entregado
     *
     * @return PedidoClientesDetalle
     */
    public function setEntregado($entregado)
    {
        $this->entregado = $entregado;

        return $this;
    }

    /**
     * Get entregado
     *
     * @return string
     */
    public function getEntregado()
    {
        return $this->entregado;
    }

    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return PedidoClientesDetalle
     */
    public function setInactivo($inactivo)
    {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return boolean
     */
    public function getInactivo()
    {
        return $this->inactivo;
    }

    /**
     * Set codigo
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $codigo
     *
     * @return PedidoClientesDetalle
     */
    public function setCodigo(\Mbp\ArticulosBundle\Entity\Articulos $codigo = null)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
}

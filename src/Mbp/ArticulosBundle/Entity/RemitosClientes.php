<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RemitosClientes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\RemitosClientesRepository")
 */
class RemitosClientes
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     * @Assert\DateTime()
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="remitoNum", type="string", length=15)
     */
    private $remitoNum;

    /**
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente")    
     */
    private $clienteId;

    /**
     * @var \Mbp\ArticulosBundle\Entity\Articulo
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos", nullable=true)    
     */
    private $articuloId;



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
     * @return RemitosClientes
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
     * @return RemitosClientes
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
     * @return RemitosClientes
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
     * @return RemitosClientes
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RemitosClientes
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set remitoNum
     *
     * @param string $remitoNum
     *
     * @return RemitosClientes
     */
    public function setRemitoNum($remitoNum)
    {
        $this->remitoNum = $remitoNum;

        return $this;
    }

    /**
     * Get remitoNum
     *
     * @return string
     */
    public function getRemitoNum()
    {
        return $this->remitoNum;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return RemitosClientes
     */
    public function setClienteId(\Mbp\ClientesBundle\Entity\Cliente $clienteId = null)
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return RemitosClientes
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
}

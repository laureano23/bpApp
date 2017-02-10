<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedidoClientes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\PedidoClientesRepository")
 */
class PedidoClientes
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
     * @ORM\JoinColumn(name="codigo", referencedColumnName="idArticulos")
     */
    private $codigo;
	
	/**
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="cliente", referencedColumnName="idCliente")
     */
    private $cliente;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="decimal")
     */
    private $cantidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaProg", type="date")
     */
    private $fechaProg;

    /**
     * @var string
     *
     * @ORM\Column(name="oc", type="string", length=25)
     */
    private $oc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */ 
    private $inactivo=0;


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
     * Set codigo
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $codigo
     * @return PedidosClientes
     */
    public function setCodigo($codigo)
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
    
    /**
     * Set cliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $cliente
     * @return PedidosClientes
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set cantidad
     *
     * @param string $cantidad
     *
     * @return PedidoClientes
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
     * @return PedidoClientes
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
     * Set oc
     *
     * @param string $oc
     *
     * @return PedidoClientes
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
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return PedidoClientes
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
}

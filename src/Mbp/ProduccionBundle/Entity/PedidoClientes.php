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
     * @var \Mbp\ClientesBundle\Entity\Cliente 
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="cliente", referencedColumnName="idCliente")
     */
    private $cliente;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaPedido", type="date")
     */
    private $fechaPedido;

    /**
     * @var string
     *
     * @ORM\Column(name="oc", type="string", length=25)
     */
    private $oc;
	
	/**
     * @var string
     *
     * @ORM\Column(name="autEntrega", type="string", length=25, nullable=true)
     */
    private $autEntrega;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */ 
    private $inactivo=0;

    /**
     * @var \Mbp\SecurityBundle\Entity\Users 
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users") 
     * @ORM\JoinColumn(name="usuarioId", referencedColumnName="id", nullable=false)
     */
    private $usuarioId;

    /**
     * @ORM\ManyToMany(targetEntity="Mbp\ProduccionBundle\Entity\PedidoClientesDetalle", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="pedidoId_detalleId",
     *      joinColumns={@ORM\JoinColumn(name="pedidoId", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="detalleId", referencedColumnName="id")}
     * )
     */
    private $detalleId; 
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="esRepuesto", type="boolean")
     */ 
    private $esRepuesto=0;

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
     * Set fechaPedido
     *
     * @param \DateTime $fechaPedido
     *
     * @return PedidoClientes
     */
    public function setFechaPedido($fechaPedido)
    {
        $this->fechaPedido = $fechaPedido;

        return $this;
    }

    /**
     * Get fechaPedido
     *
     * @return \DateTime
     */
    public function getFechaPedido()
    {
        return $this->fechaPedido;
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

    /**
     * Set cliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $cliente
     *
     * @return PedidoClientes
     */
    public function setCliente(\Mbp\ClientesBundle\Entity\Cliente $cliente = null)
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
     * Set usuarioId
     *
     * @param \Mbp\SecurityBundle\Entity\Users $usuarioId
     *
     * @return PedidoClientes
     */
    public function setUsuarioId(\Mbp\SecurityBundle\Entity\Users $usuarioId)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detalleId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add detalleId
     *
     * @param \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $detalleId
     *
     * @return PedidoClientes
     */
    public function addDetalleId(\Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $detalleId)
    {
        $this->detalleId[] = $detalleId;

        return $this;
    }

    /**
     * Remove detalleId
     *
     * @param \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $detalleId
     */
    public function removeDetalleId(\Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $detalleId)
    {
        $this->detalleId->removeElement($detalleId);
    }

    /**
     * Get detalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalleId()
    {
        return $this->detalleId;
    }

    /**
     * Set autEntrega
     *
     * @param string $autEntrega
     *
     * @return PedidoClientes
     */
    public function setAutEntrega($autEntrega)
    {
        $this->autEntrega = $autEntrega;

        return $this;
    }

    /**
     * Get autEntrega
     *
     * @return string
     */
    public function getAutEntrega()
    {
        return $this->autEntrega;
    }

    /**
     * Set esRepuesto
     *
     * @param boolean $esRepuesto
     *
     * @return PedidoClientes
     */
    public function setEsRepuesto($esRepuesto)
    {
        $this->esRepuesto = $esRepuesto;

        return $this;
    }

    /**
     * Get esRepuesto
     *
     * @return boolean
     */
    public function getEsRepuesto()
    {
        return $this->esRepuesto;
    }
}

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
     * @ORM\Column(name="cantidad", type="decimal", precision=11, scale=2)
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
     * @ORM\Column(name="entregado", type="decimal", precision=11, scale=2)
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
     * @var string
     *
     * @ORM\Column(name="cantAutorizada", type="decimal", precision=11, scale=2, nullable=true)
     */
    private $cantAutorizada=0;

    /**
     * @var \Mbp\SecurityBundle\Entity\Users 
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users") 
     * @ORM\JoinColumn(name="autorizoEntrega", referencedColumnName="id", nullable=true)
     */
    private $autorizoEntrega;
	
	/**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string")
     */
    private $descripcion;

    /**
     * @ORM\ManyToMany(targetEntity="Ot", mappedBy="pedidos")
     * @ORM\JoinTable(name="Ot_Pedidos",
     *      joinColumns={@ORM\JoinColumn(name="otId", referencedColumnName="ot")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pedidoId", referencedColumnName="id", nullable=true)}
     *      )
     */
    private $ots;

    /**
     * @var string
     *
     * @ORM\Column(name="observacionesAutorizacion", type="string", nullable=true)
     */
    private $observacionesAutorizacion;

    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->ots = new \Doctrine\Common\Collections\ArrayCollection();
    } 


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

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return PedidoClientesDetalle
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
     * Set cantAutorizada
     *
     * @param string $cantAutorizada
     *
     * @return PedidoClientesDetalle
     */
    public function setCantAutorizada($cantAutorizada)
    {
        $this->cantAutorizada = $cantAutorizada;

        return $this;
    }

    /**
     * Get cantAutorizada
     *
     * @return string
     */
    public function getCantAutorizada()
    {
        return $this->cantAutorizada;
    }

    /**
     * Add ot
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $ot
     *
     * @return PedidoClientesDetalle
     */
    public function addOt(\Mbp\ProduccionBundle\Entity\Ot $ot)
    {
        $this->ots[] = $ot;

        return $this;
    }

    /**
     * Remove ot
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $ot
     */
    public function removeOt(\Mbp\ProduccionBundle\Entity\Ot $ot)
    {
        $this->ots->removeElement($ot);
    }

    /**
     * Get ots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOts()
    {
        return $this->ots;
    }

    /**
     * Set autorizoEntrega
     *
     * @param \Mbp\SecurityBundle\Entity\Users $autorizoEntrega
     *
     * @return PedidoClientesDetalle
     */
    public function setAutorizoEntrega(\Mbp\SecurityBundle\Entity\Users $autorizoEntrega = null)
    {
        $this->autorizoEntrega = $autorizoEntrega;

        return $this;
    }

    /**
     * Get autorizoEntrega
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getAutorizoEntrega()
    {
        return $this->autorizoEntrega;
    }

    /**
     * Set observacionesAutorizacion
     *
     * @param string $observacionesAutorizacion
     *
     * @return PedidoClientesDetalle
     */
    public function setObservacionesAutorizacion($observacionesAutorizacion)
    {
        $this->observacionesAutorizacion = $observacionesAutorizacion;

        return $this;
    }

    /**
     * Get observacionesAutorizacion
     *
     * @return string
     */
    public function getObservacionesAutorizacion()
    {
        return $this->observacionesAutorizacion;
    }
}

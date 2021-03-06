<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ot
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\OtRepository")
 */
class Ot
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ot", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $ot;

    
     /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="idCodigo", referencedColumnName="idArticulos", nullable=false)	 
     */
    private $idCodigo;

    /**
     * @var decimal
     * @Assert\Range(
     *      min = 0.001,
     *      max = 999999,
     *      minMessage = "La cantidad mínima es {{ limit }}",
     *      maxMessage = "La cantidad máxima es {{ limit }}"
     * )
     * @ORM\Column(name="cantidad", type="decimal", precision=10, scale=3)
     */
    private $cantidad;
	
	/**
     * @var datetime
     * @Assert\DateTime()
     * @ORM\Column(name="fechaEmision", type="datetime")
     */
    private $fechaEmision;
	
	/**
     * @var date
     * @Assert\GreaterThanOrEqual("today")
     * @ORM\Column(name="fechaProg", type="date")
     */
    private $fechaProg;
	
	/**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=250, nullable=true)
     */
    private $observaciones;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="anulada", type="boolean")
     */
    private $anulada=0;
	
	/**
     * @var \Mbp\SecurityBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users")
     * @ORM\JoinColumn(name="idUsuario", referencedColumnName="id", nullable=false)	 
     */
    private $idUsuario;
    
    /**
     * @var \Mbp\ProduccionBundle\Entity\Sectores
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Sectores")
     * @ORM\JoinColumn(name="sectorId", referencedColumnName="id", nullable=false)	 
     */
    private $sectorId;
	
	/**
     * @var \Mbp\ProduccionBundle\Entity\Sectores
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Sectores")
     * @ORM\JoinColumn(name="sectorEmisor", referencedColumnName="id", nullable=false)	 
     */
    private $sectorEmisor;
	
	/**
     * Ordenes referenciadas consigo mismas
     * @ORM\ManyToMany(targetEntity="Ot", mappedBy="misOrdenes")
     */
    private $ordenesConmigo;
	
	/**
     * Ordenes referenciadas consigo mismas
     * @ORM\ManyToMany(targetEntity="Ot", inversedBy="ordenesConmigo", cascade={"merge"})
     * @ORM\JoinTable(name="otRelacionadas",
     *      joinColumns={@ORM\JoinColumn(name="otHijaId", referencedColumnName="ot")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="otPadreId", referencedColumnName="ot", nullable=true)}
     *      )
     */
    private $misOrdenes;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="otExterna", type="integer", nullable=true)
     */
    private $otExterna;
	
	/**
     * @ORM\ManyToMany(targetEntity="PedidoClientesDetalle", inversedBy="ots")
     * @ORM\JoinTable(name="Ot_Pedidos",
     *      joinColumns={@ORM\JoinColumn(name="otId", referencedColumnName="ot")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="pedidoId", referencedColumnName="id", nullable=true)}
     *      ) 
     */
    private $pedidos;

	 
	
	/**
     * @var decimal
     * @Assert\Range(
     *      min = 0.000,
     *      max = 999999,
     *      minMessage = "La cantidad mínima es {{ limit }}",
     *      maxMessage = "La cantidad máxima es {{ limit }}"
     * )
     * @ORM\Column(name="aprobado", type="decimal", precision=10, scale=3)
     */
    private $aprobado=0;
	
	/**
     * @var decimal
     * @Assert\Range(
     *      min = 0.000,
     *      max = 999999,
     *      minMessage = "La cantidad mínima es {{ limit }}",
     *      maxMessage = "La cantidad máxima es {{ limit }}"
     * )
     * @ORM\Column(name="rechazado", type="decimal", precision=10, scale=3)
     */
    private $rechazado=0;
    
	/**
     * @var smallint
     *
     * @ORM\Column(name="estado", type="smallint")
	 * 		
	 * 		0: no comenzado
	 * 		1: en proceso
	 * 		2: terminado
	 * 		3: generado
     */
    private $estado=0;
	
	/**
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=true)	 
     */
    private $clienteId;

    /**
     * Set ot
     *
     * @param integer $ot
     * @return Ot
     */
    public function setOt($ot)
    {
        $this->ot = $ot;

        return $this;
    }

    /**
     * Get ot
     *
     * @return integer 
     */
    public function getOt()
    {
        return $this->ot;
    }

    /**
     * Set idCodigo
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idCodigo
     * @return Ot
     */
    public function setIdCodigo($idCodigo)
    {
        $this->idCodigo = $idCodigo;

        return $this;
    }

    /**
     * Get idCodigo
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos
     */
    public function getIdCodigo()
    {
        return $this->idCodigo;
    }    
	
    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Ot
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return Ot
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Ot
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
     * Set anulada
     *
     * @param boolean $anulada
     *
     * @return Ot
     */
    public function setAnulada($anulada)
    {
        $this->anulada = $anulada;

        return $this;
    }

    /**
     * Get anulada
     *
     * @return boolean
     */
    public function getAnulada()
    {
        return $this->anulada;
    }

    /**
     * Set idUsuario
     *
     * @param \Mbp\SecurityBundle\Entity\Users $idUsuario
     *
     * @return Ot
     */
    public function setIdUsuario(\Mbp\SecurityBundle\Entity\Users $idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set fechaProg
     *
     * @param \DateTime $fechaProg
     *
     * @return Ot
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
     * Set aprobado
     *
     * @param string $aprobado
     *
     * @return Ot
     */
    public function setAprobado($aprobado)
    {
        $this->aprobado = $aprobado;

        return $this;
    }

    /**
     * Get aprobado
     *
     * @return string
     */
    public function getAprobado()
    {
        return $this->aprobado;
    }

    /**
     * Set rechazado
     *
     * @param string $rechazado
     *
     * @return Ot
     */
    public function setRechazado($rechazado)
    {
        $this->rechazado = $rechazado;

        return $this;
    }

    /**
     * Get rechazado
     *
     * @return string
     */
    public function getRechazado()
    {
        return $this->rechazado;
    }

    /**
     * Set sectorId
     *
     * @param \Mbp\ProduccionBundle\Entity\Sectores $sectorId
     *
     * @return Ot
     */
    public function setSectorId(\Mbp\ProduccionBundle\Entity\Sectores $sectorId)
    {
        $this->sectorId = $sectorId;

        return $this;
    }

    /**
     * Get sectorId
     *
     * @return \Mbp\ProduccionBundle\Entity\Sectores
     */
    public function getSectorId()
    {
        return $this->sectorId;
    }

    /**
     * Set sectorEmisor
     *
     * @param \Mbp\ProduccionBundle\Entity\Sectores $sectorEmisor
     *
     * @return Ot
     */
    public function setSectorEmisor(\Mbp\ProduccionBundle\Entity\Sectores $sectorEmisor)
    {
        $this->sectorEmisor = $sectorEmisor;

        return $this;
    }

    /**
     * Get sectorEmisor
     *
     * @return \Mbp\ProduccionBundle\Entity\Sectores
     */
    public function getSectorEmisor()
    {
        return $this->sectorEmisor;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return Ot
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer
     */
    public function getEstado()
    {
    	switch ($this->estado) {
			case '0':
				return "No comenzada";
				break;
			
			case '1':
				return "En proceso";
				break;
			
			case '2':
				return "Terminada";
				break;
			default:
				return "No comenzada";
				break;
		}
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    	$this->ordenesConmigo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->misOrdenes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }    

    /**
     * Add ordenesConmigo
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $ordenesConmigo
     *
     * @return Ot
     */
    public function addOrdenesConmigo(\Mbp\ProduccionBundle\Entity\Ot $ordenesConmigo)
    {
        $this->ordenesConmigo[] = $ordenesConmigo;

        return $this;
    }

    /**
     * Remove ordenesConmigo
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $ordenesConmigo
     */
    public function removeOrdenesConmigo(\Mbp\ProduccionBundle\Entity\Ot $ordenesConmigo)
    {
        $this->ordenesConmigo->removeElement($ordenesConmigo);
    }

    /**
     * Get ordenesConmigo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdenesConmigo()
    {
        return $this->ordenesConmigo;
    }

    /**
     * Add misOrdene
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $misOrdene
     *
     * @return Ot
     */
    public function addMisOrdenes(\Mbp\ProduccionBundle\Entity\Ot $misOrdene)
    {
        $this->misOrdenes[] = $misOrdene;

        return $this;
    }

    /**
     * Remove misOrdene
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $misOrdene
     */
    public function removeMisOrdenes(\Mbp\ProduccionBundle\Entity\Ot $misOrdene)
    {
        $this->misOrdenes->removeElement($misOrdene);
    }

    /**
     * Get misOrdenes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMisOrdenes()
    {
        return $this->misOrdenes;
    }

    /**
     * Set otExterna
     *
     * @param integer $otExterna
     *
     * @return Ot
     */
    public function setOtExterna($otExterna)
    {
        $this->otExterna = $otExterna;

        return $this;
    }

    /**
     * Get otExterna
     *
     * @return integer
     */
    public function getOtExterna()
    {
        return $this->otExterna;
    }

    /**
     * Remove misOrdene
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $misOrdene
     */
    public function removeMisOrdene(\Mbp\ProduccionBundle\Entity\Ot $misOrdene)
    {
        $this->misOrdenes->removeElement($misOrdene);
    }

    /**
     * Add misOrdene
     *
     * @param \Mbp\ProduccionBundle\Entity\Ot $misOrdene
     *
     * @return Ot
     */
    public function addMisOrdene(\Mbp\ProduccionBundle\Entity\Ot $misOrdene)
    {
        $this->misOrdenes[] = $misOrdene;

        return $this;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return Ot
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
     * Add pedido
     *
     * @param \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $pedido
     *
     * @return Ot
     */
    public function addPedido(\Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $pedido)
    {
        $this->pedidos[] = $pedido;

        return $this;
    }

    /**
     * Remove pedido
     *
     * @param \Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $pedido
     */
    public function removePedido(\Mbp\ProduccionBundle\Entity\PedidoClientesDetalle $pedido)
    {
        $this->pedidos->removeElement($pedido);
    }

    /**
     * Get pedidos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidos()
    {
        return $this->pedidos;
    }
}

<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cobranzas
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\CobranzasRepository")
 */
class Cobranzas
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
     * @var integer
     *
     * @ORM\Column(name="ptoVenta", type="smallint")
     */
    private $ptoVenta;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="numRecibo", type="integer")
     */
    private $numRecibo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="emision", type="datetime")
     */
    private $emision;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaRecibo", type="date")
     */
    private $fechaRecibo;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	 * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=false)
	 */
	private $clienteId;
	
	/**
	 * @ORM\ManyToMany(targetEntity="CobranzasDetalle", cascade={"persist", "remove"})
	 * @ORM\JoinTable(name="cobranza_detallesCobranzas",
	 *  joinColumns={ @ORM\JoinColumn(name = "cobranza_id", referencedColumnName="id") }),
	 *  inverseJoinColumns={@JoinColumn(name="detallesCobranza_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $cobranzaDetalleId;	
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="totalCobranza", type="decimal", precision=10, scale=2)
     */
    private $importe;
	
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
     * Set emision
     *
     * @param \DateTime $emision
     *
     * @return Cobranzas
     */
    public function setEmision($emision)
    {
        $this->emision = $emision;

        return $this;
    }

    /**
     * Get emision
     *
     * @return \DateTime
     */
    public function getEmision()
    {
        return $this->emision;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cobranzaDetalleId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return Cobranzas
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
     * Add cobranzaDetalleId
     *
     * @param \Mbp\FinanzasBundle\Entity\CobranzasDetalle $cobranzaDetalleId
     *
     * @return Cobranzas
     */
    public function addCobranzaDetalleId(\Mbp\FinanzasBundle\Entity\CobranzasDetalle $cobranzaDetalleId)
    {
        $this->cobranzaDetalleId[] = $cobranzaDetalleId;

        return $this;
    }

    /**
     * Remove cobranzaDetalleId
     *
     * @param \Mbp\FinanzasBundle\Entity\CobranzasDetalle $cobranzaDetalleId
     */
    public function removeCobranzaDetalleId(\Mbp\FinanzasBundle\Entity\CobranzasDetalle $cobranzaDetalleId)
    {
        $this->cobranzaDetalleId->removeElement($cobranzaDetalleId);
    }

    /**
     * Get cobranzaDetalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCobranzaDetalleId()
    {
        return $this->cobranzaDetalleId;
    }
   

    /**
     * Set ptoVenta
     *
     * @param integer $ptoVenta
     *
     * @return Cobranzas
     */
    public function setPtoVenta($ptoVenta)
    {
        $this->ptoVenta = $ptoVenta;

        return $this;
    }

    /**
     * Get ptoVenta
     *
     * @return integer
     */
    public function getPtoVenta()
    {
        return $this->ptoVenta;
    }

    /**
     * Set numRecibo
     *
     * @param integer $numRecibo
     *
     * @return Cobranzas
     */
    public function setNumRecibo($numRecibo)
    {
        $this->numRecibo = $numRecibo;

        return $this;
    }

    /**
     * Get numRecibo
     *
     * @return integer
     */
    public function getNumRecibo()
    {
        return $this->numRecibo;
    }

    /**
     * Set fechaRecibo
     *
     * @param \DateTime $fechaRecibo
     *
     * @return Cobranzas
     */
    public function setFechaRecibo($fechaRecibo)
    {
        $this->fechaRecibo = $fechaRecibo;

        return $this;
    }

    /**
     * Get fechaRecibo
     *
     * @return \DateTime
     */
    public function getFechaRecibo()
    {
        return $this->fechaRecibo;
    }
	
	/**
     * Set importe
     *
     * @param string $importe
     *
     * @return CobranzasDetalle
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return string
     */
    public function getImporte()
    {
        return $this->importe;
    }
}

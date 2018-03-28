<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CCClientes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\CCClientesRepository")
 */
class CCClientes
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
     * @ORM\Column(name="debe", type="decimal", precision=11, scale=2)
     */
    private $debe=0;

    /**
     * @var string
     *
     * @ORM\Column(name="haber", type="decimal", precision=11, scale=2)
     */
    private $haber=0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="datetime")
     */
    private $fechaEmision;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaVencimiento", type="date")
     */
    private $fechaVencimiento;
	
	/**
	 * @ORM\OneToOne(targetEntity="Mbp\FinanzasBundle\Entity\Facturas", inversedBy="ccId")
	 * @ORM\JoinColumn(name="facturaId", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $facturaId;
	
	/**
	 * @ORM\OneToOne(targetEntity="Mbp\FinanzasBundle\Entity\Cobranzas", inversedBy="ccId")
	 * @ORM\JoinColumn(name="cobranzaId", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $cobranzaId;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	 * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=false)
	 */
	private $clienteId;


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
     * Set debe
     *
     * @param string $debe
     *
     * @return CCClientes
     */
    public function setDebe($debe)
    {
        $this->debe = $debe;

        return $this;
    }

    /**
     * Get debe
     *
     * @return string
     */
    public function getDebe()
    {
        return $this->debe;
    }

    /**
     * Set haber
     *
     * @param string $haber
     *
     * @return CCClientes
     */
    public function setHaber($haber)
    {
        $this->haber = $haber;

        return $this;
    }

    /**
     * Get haber
     *
     * @return string
     */
    public function getHaber()
    {
        return $this->haber;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return CCClientes
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return CCClientes
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set facturaId
     *
     * @param \Mbp\FinanzasBundle\Entity\Facturas $facturaId
     *
     * @return CCClientes
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
     * Set cobranzaId
     *
     * @param \Mbp\FinanzasBundle\Entity\Cobranzas $cobranzaId
     *
     * @return CCClientes
     */
    public function setCobranzaId(\Mbp\FinanzasBundle\Entity\Cobranzas $cobranzaId = null)
    {
        $this->cobranzaId = $cobranzaId;

        return $this;
    }

    /**
     * Get cobranzaId
     *
     * @return \Mbp\FinanzasBundle\Entity\Cobranzas
     */
    public function getCobranzaId()
    {
        return $this->cobranzaId;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return CCClientes
     */
    public function setClienteId(\Mbp\ClientesBundle\Entity\Cliente $clienteId)
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
}

<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InteresesResarcitorios
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\InteresesResarcitoriosRepository")
 */
class InteresesResarcitorios
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
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	 * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=false)
	 */
	private $clienteId;

    /**
     * @var string
     *
     * @ORM\Column(name="cbte", type="string", length=30)
     */
    private $cbte;

    /**
     * @var string
     *
     * @ORM\Column(name="monto", type="decimal", scale=2, nullable=false)
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(name="tasa", type="decimal", scale=2, nullable=false)
     */
    private $tasa;

    /**
     * @var string
     *
     * @ORM\Column(name="interes", type="decimal", scale=2, nullable=false)
     */
    private $interes;
	
	/**
     * @var string
     *
     * @ORM\Column(name="chequeNum", type="string", length=30)
     */
    private $chequeNum;
	
	/**
     * @var string
     *
     * @ORM\Column(name="banco", type="string", length=30)
     */
    private $banco;
    
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="diferidoValor", type="date", nullable=true)
     */
    private $diferidoValor;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Cobranzas")
	 * @ORM\JoinColumn(name="cobranzaId", referencedColumnName="id", nullable=true)
	 */
	private $cobranzaId;

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
     * Set cbte
     *
     * @param string $cbte
     *
     * @return InteresesResarcitorios
     */
    public function setCbte($cbte)
    {
        $this->cbte = $cbte;

        return $this;
    }

    /**
     * Get cbte
     *
     * @return string
     */
    public function getCbte()
    {
        return $this->cbte;
    }

    /**
     * Set monto
     *
     * @param string $monto
     *
     * @return InteresesResarcitorios
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set tasa
     *
     * @param string $tasa
     *
     * @return InteresesResarcitorios
     */
    public function setTasa($tasa)
    {
        $this->tasa = $tasa;

        return $this;
    }

    /**
     * Get tasa
     *
     * @return string
     */
    public function getTasa()
    {
        return $this->tasa;
    }

    /**
     * Set interes
     *
     * @param string $interes
     *
     * @return InteresesResarcitorios
     */
    public function setInteres($interes)
    {
        $this->interes = $interes;

        return $this;
    }

    /**
     * Get interes
     *
     * @return string
     */
    public function getInteres()
    {
        return $this->interes;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return InteresesResarcitorios
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

    /**
     * Set chequeNum
     *
     * @param string $chequeNum
     *
     * @return InteresesResarcitorios
     */
    public function setChequeNum($chequeNum)
    {
        $this->chequeNum = $chequeNum;

        return $this;
    }

    /**
     * Get chequeNum
     *
     * @return string
     */
    public function getChequeNum()
    {
        return $this->chequeNum;
    }

    /**
     * Set banco
     *
     * @param string $banco
     *
     * @return InteresesResarcitorios
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return string
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set diferidoValor
     *
     * @param \DateTime $diferidoValor
     *
     * @return InteresesResarcitorios
     */
    public function setDiferidoValor($diferidoValor)
    {
        $this->diferidoValor = $diferidoValor;

        return $this;
    }

    /**
     * Get diferidoValor
     *
     * @return \DateTime
     */
    public function getDiferidoValor()
    {
        return $this->diferidoValor;
    }

    /**
     * Set cobranzaId
     *
     * @param \Mbp\FinanzasBundle\Entity\Cobranzas $cobranzaId
     *
     * @return InteresesResarcitorios
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
}

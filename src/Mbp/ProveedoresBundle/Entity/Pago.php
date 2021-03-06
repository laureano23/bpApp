<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pago
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\PagoRepository")
 */
class Pago
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
	 * @var \Mbp\FinanzasBundle\Entity\FormasPagos
	 * 
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\FormasPagos")
	 * @ORM\JoinColumn(name="idFormaPago", referencedColumnName="id")
	 */
	private $idFormaPago;
	
	/**
     * @var string
     *
     * @ORM\Column(name="banco", type="string", length=50, nullable=true)
     */
    private $banco;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="emision", type="datetime")
     */
    private $emision;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=50, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="importe", type="decimal", precision=11, scale=2)
     */
    private $importe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="diferido", type="datetime", nullable=true)
     */
    private $diferido;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\CuentasBancarias")
	 * @ORM\JoinColumn(name="cuentaId", referencedColumnName="id")
	 * */
	private $cuentaId;
	
	/**
	 * @ORM\OneToOne(targetEntity="Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos", cascade={"remove"})
	 * @ORM\JoinColumn(name="movBancoId", referencedColumnName="id", nullable=true)
	 * */
	private $movBancoId;
	
	


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
     * @return Pago
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
     * Set numero
     *
     * @param string $numero
     *
     * @return Pago
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return Pago
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

    /**
     * Set diferido
     *
     * @param \DateTime $diferido
     *
     * @return Pago
     */
    public function setDiferido($diferido)
    {
        $this->diferido = $diferido;

        return $this;
    }

    /**
     * Get diferido
     *
     * @return \DateTime
     */
    public function getDiferido()
    {
        return $this->diferido;
    }
    
    /**
     * Set idFormaPago
     *
     * @param \Mbp\FinanzasBundle\Entity\FormasPagos $idFormaPago
     *
     * @return Pago
     */
    public function setIdFormaPagos(\Mbp\FinanzasBundle\Entity\FormasPagos $idFormaPago = null)
    {
        $this->idFormaPago = $idFormaPago;

        return $this;
    }

    /**
     * Get idFormaPago
     *
     * @return \Mbp\FinanzasBundle\Entity\FormasPagos
     */
    public function getIdFormaPagos()
    {
        return $this->idFormaPago;
    }

    /**
     * Set banco
     *
     * @param string $banco
     *
     * @return Pago
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
     * Set idFormaPago
     *
     * @param \Mbp\FinanzasBundle\Entity\FormasPagos $idFormaPago
     *
     * @return Pago
     */
    public function setIdFormaPago(\Mbp\FinanzasBundle\Entity\FormasPagos $idFormaPago = null)
    {
        $this->idFormaPago = $idFormaPago;

        return $this;
    }

    /**
     * Get idFormaPago
     *
     * @return \Mbp\FinanzasBundle\Entity\FormasPagos
     */
    public function getIdFormaPago()
    {
        return $this->idFormaPago;
    }

    /**
     * Set cuentaId
     *
     * @param \Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentaId
     *
     * @return Pago
     */
    public function setCuentaId(\Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentaId = null)
    {
        $this->cuentaId = $cuentaId;

        return $this;
    }

    /**
     * Get cuentaId
     *
     * @return \Mbp\FinanzasBundle\Entity\CuentasBancarias
     */
    public function getCuentaId()
    {
        return $this->cuentaId;
    }

    /**
     * Set movBancoId
     *
     * @param \Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos $movBancoId
     *
     * @return Pago
     */
    public function setMovBancoId(\Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos $movBancoId = null)
    {
        $this->movBancoId = $movBancoId;

        return $this;
    }

    /**
     * Get movBancoId
     *
     * @return \Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos
     */
    public function getMovBancoId()
    {
        return $this->movBancoId;
    }
}

<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CobranzasDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\CobranzasDetalleRepository")
 */
class CobranzasDetalle
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
     * @ORM\Column(name="importe", type="decimal", precision=8, scale=4)
     */
    private $importe;
	
	/**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=10)
     */
    private $numero;
	
	/**
     * @var string
     *
     * @ORM\Column(name="banco", type="string", length=40)
     */
    private $banco;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vencimiento", type="date")
     */
    private $vencimiento;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\FormasPagos")
	 * @ORM\JoinColumn(name="formaPagoId", referencedColumnName="id")
	 */
	private $formaPagoId;
	
	/*
	 * IDENTIFICA EL ESTADO DE UN CHEQUE
	 * 0: EN CARTERA
	 * 1: ENTREGADO A PROVEEDOR
	 * 2: DEPOSITADO EN BANCO
	 * */
	/**
     * @var smallint
     *
     * @ORM\Column(name="estado", type="smallint")
     */
    private $estado=0;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\CuentasBancarias")
	 * @ORM\JoinColumn(name="cuentaId", referencedColumnName="id")
	 * */
	private $cuentaId;


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
	 * @ORM\OneToOne(targetEntity="Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos", cascade={"remove"})
	 * @ORM\JoinColumn(name="movBancoId", referencedColumnName="id", nullable=true)
	 * */
	private $movBancoId;
    

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

    /**
     * Set vencimiento
     *
     * @param \DateTime $vencimiento
     *
     * @return CobranzasDetalle
     */
    public function setVencimiento($vencimiento)
    {
        $this->vencimiento = $vencimiento;

        return $this;
    }

    /**
     * Get vencimiento
     *
     * @return \DateTime
     */
    public function getVencimiento()
    {
        return $this->vencimiento;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return CobranzasDetalle
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
     * Set banco
     *
     * @param string $banco
     *
     * @return CobranzasDetalle
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
     * Set formaPagoId
     *
     * @param \Mbp\FinanzasBundle\Entity\FormasPagos $formaPagoId
     *
     * @return CobranzasDetalle
     */
    public function setFormaPagoId(\Mbp\FinanzasBundle\Entity\FormasPagos $formaPagoId = null)
    {
        $this->formaPagoId = $formaPagoId;

        return $this;
    }

    /**
     * Get formaPagoId
     *
     * @return \Mbp\FinanzasBundle\Entity\FormasPago
     */
    public function getFormaPagoId()
    {
        return $this->formaPagoId;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return CobranzasDetalle
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
        return $this->estado;
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

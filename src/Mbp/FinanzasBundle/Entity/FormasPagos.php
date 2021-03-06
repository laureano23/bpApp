<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormasPagos
 * 
 * @ORM\Table()
 * @ORM\Entity
 */
class FormasPagos
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
     * @ORM\Column(name="descripcion", type="string", length=35, nullable=false)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo=0; 
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="retencionIIBB", type="boolean")
     */
    private $retencionIIBB=0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="retencionIVA21", type="boolean")
     */
    private $retencionIVA21=0; 
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="chequeTerceros", type="boolean")
     */
    private $chequeTerceros=0; 
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="esChequePropio", type="boolean")
     */
    private $esChequePropio=0; 
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="depositaEnCuenta", type="boolean")
     */
    private $depositaEnCuenta=0; 
	
	/**
	  * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\ConceptosBanco")
	  * @ORM\JoinColumn(name="ceonceptoBancoId", referencedColumnName="id", nullable=true)
	  */
	 private $conceptoBancoId;


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
     * @return FormasPago
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
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return FormasPago
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
     * Set retencionIIBB
     *
     * @param boolean $retencionIIBB
     *
     * @return FormasPago
     */
    public function setRetencionIIBB($retencionIIBB)
    {
        $this->retencionIIBB = $retencionIIBB;

        return $this;
    }

    /**
     * Get retencionIIBB
     *
     * @return boolean
     */
    public function getRetencionIIBB()
    {
        return $this->retencionIIBB;
    }

    /**
     * Set retencionIVA21
     *
     * @param boolean $retencionIVA21
     *
     * @return FormasPago
     */
    public function setRetencionIVA21($retencionIVA21)
    {
        $this->retencionIVA21 = $retencionIVA21;

        return $this;
    }

    /**
     * Get retencionIVA21
     *
     * @return boolean
     */
    public function getRetencionIVA21()
    {
        return $this->retencionIVA21;
    }

    
    /**
     * Set chequeTerceros
     *
     * @param boolean $chequeTerceros
     *
     * @return FormasPago
     */
    public function setChequeTerceros($chequeTerceros)
    {
        $this->chequeTerceros = $chequeTerceros;

        return $this;
    }

    /**
     * Get chequeTerceros
     *
     * @return boolean
     */
    public function getChequeTerceros()
    {
        return $this->chequeTerceros;
    }

    /**
     * Set esChequePropio
     *
     * @param boolean $esChequePropio
     *
     * @return FormasPagos
     */
    public function setEsChequePropio($esChequePropio)
    {
        $this->esChequePropio = $esChequePropio;

        return $this;
    }

    /**
     * Get esChequePropio
     *
     * @return boolean
     */
    public function getEsChequePropio()
    {
        return $this->esChequePropio;
    }

    /**
     * Set conceptoBancoId
     *
     * @param \Mbp\FinanzasBundle\Entity\ConceptosBanco $conceptoBancoId
     *
     * @return FormasPagos
     */
    public function setConceptoBancoId(\Mbp\FinanzasBundle\Entity\ConceptosBanco $conceptoBancoId = null)
    {
        $this->conceptoBancoId = $conceptoBancoId;

        return $this;
    }

    /**
     * Get conceptoBancoId
     *
     * @return \Mbp\FinanzasBundle\Entity\ConceptosBanco
     */
    public function getConceptoBancoId()
    {
        return $this->conceptoBancoId;
    }

    /**
     * Set depositaEnCuenta
     *
     * @param boolean $depositaEnCuenta
     *
     * @return FormasPagos
     */
    public function setDepositaEnCuenta($depositaEnCuenta)
    {
        $this->depositaEnCuenta = $depositaEnCuenta;

        return $this;
    }

    /**
     * Get depositaEnCuenta
     *
     * @return boolean
     */
    public function getDepositaEnCuenta()
    {
        return $this->depositaEnCuenta;
    }
}

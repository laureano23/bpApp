<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoComprobante
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TipoComprobante
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
     * @var boolean
     *
     * @ORM\Column(name="esNegro", type="boolean")
     */
    private $esNegro;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esFactura", type="boolean")
     */
    private $esFactura;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esNotaCredito", type="boolean")
     */
    private $esNotaCredito;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esNotaDebito", type="boolean")
     */
    private $esNotaDebito;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="esBalance", type="boolean")
     */
    private $esBalance;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="subTipoA", type="boolean")
     */
    private $subTipoA=0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="subTipoB", type="boolean")
     */
    private $subTipoB=0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="subTipoE", type="boolean")
     */
    private $subTipoE=0;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="codigoAfip", type="smallint", nullable=true)
     */
    private $codigoAfip=0;
	


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
     * Set esFactura
     *
     * @param boolean $esFactura
     *
     * @return TipoComprobante
     */
    public function setEsFactura($esFactura)
    {
        $this->esFactura = $esFactura;

        return $this;
    }

    /**
     * Get esFactura
     *
     * @return boolean
     */
    public function getEsFactura()
    {
        return $this->esFactura;
    }

    /**
     * Set esNotaCredito
     *
     * @param boolean $esNotaCredito
     *
     * @return TipoComprobante
     */
    public function setEsNotaCredito($esNotaCredito)
    {
        $this->esNotaCredito = $esNotaCredito;

        return $this;
    }

    /**
     * Get esNotaCredito
     *
     * @return boolean
     */
    public function getEsNotaCredito()
    {
        return $this->esNotaCredito;
    }

    /**
     * Set esNotaDebito
     *
     * @param boolean $esNotaDebito
     *
     * @return TipoComprobante
     */
    public function setEsNotaDebito($esNotaDebito)
    {
        $this->esNotaDebito = $esNotaDebito;

        return $this;
    }

    /**
     * Get esNotaDebito
     *
     * @return boolean
     */
    public function getEsNotaDebito()
    {
        return $this->esNotaDebito;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return TipoComprobante
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
     * Set esBalance
     *
     * @param boolean $esBalance
     *
     * @return TipoComprobante
     */
    public function setEsBalance($esBalance)
    {
        $this->esBalance = $esBalance;

        return $this;
    }

    /**
     * Get esBalance
     *
     * @return boolean
     */
    public function getEsBalance()
    {
        return $this->esBalance;
    }

    /**
     * Set subTipoA
     *
     * @param boolean $subTipoA
     *
     * @return TipoComprobante
     */
    public function setSubTipoA($subTipoA)
    {
        $this->subTipoA = $subTipoA;

        return $this;
    }

    /**
     * Get subTipoA
     *
     * @return boolean
     */
    public function getSubTipoA()
    {
        return $this->subTipoA;
    }

    /**
     * Set subTipoB
     *
     * @param boolean $subTipoB
     *
     * @return TipoComprobante
     */
    public function setSubTipoB($subTipoB)
    {
        $this->subTipoB = $subTipoB;

        return $this;
    }

    /**
     * Get subTipoB
     *
     * @return boolean
     */
    public function getSubTipoB()
    {
        return $this->subTipoB;
    }

    /**
     * Set subTipoE
     *
     * @param boolean $subTipoE
     *
     * @return TipoComprobante
     */
    public function setSubTipoE($subTipoE)
    {
        $this->subTipoE = $subTipoE;

        return $this;
    }

    /**
     * Get subTipoE
     *
     * @return boolean
     */
    public function getSubTipoE()
    {
        return $this->subTipoE;
    }

    /**
     * Set codigoAfip
     *
     * @param smallint $codigoAfip
     *
     * @return TipoComprobante
     */
    public function setCodigoAfip($codigoAfip)
    {
        $this->codigoAfip = $codigoAfip;

        return $this;
    }

    /**
     * Get codigoAfip
     *
     * @return smallint
     */
    public function getCodigoAfip()
    {
        return $this->codigoAfip;
    }

    /**
     * Set esNegro
     *
     * @param boolean $esNegro
     *
     * @return TipoComprobante
     */
    public function setEsNegro($esNegro)
    {
        $this->esNegro = $esNegro;

        return $this;
    }

    /**
     * Get esNegro
     *
     * @return boolean
     */
    public function getEsNegro()
    {
        return $this->esNegro;
    }
}

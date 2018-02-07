<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormasPago
 * 
 * @ORM\Table()
 * @ORM\Entity
 */
class FormasPago
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
     * @ORM\Column(name="descripcion", type="string", length=35)
     */
    private $descripcion;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ConceptosBanco")
	 * @ORM\JoinColumn(name="ConceptoBanco_id", referencedColumnName="id", nullable=true)
	 */
	private $conceptoBancario;

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
     * Set conceptoBancario
     *
     * @param boolean $conceptoBancario
     *
     * @return FormasPago
     */
    public function setConceptoBancario($conceptoBancario)
    {
        $this->conceptoBancario = $conceptoBancario;

        return $this;
    }

    /**
     * Get conceptoBancario
     *
     * @return boolean
     */
    public function getConceptoBancario()
    {
        return $this->conceptoBancario;
    }

    /**
     * Set conceptoBancoId
     *
     * @param \Mbp\FinanzasBundle\Entity\ConceptosBanco $conceptoBancoId
     *
     * @return FormasPago
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
}

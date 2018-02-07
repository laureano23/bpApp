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
     * @ORM\Column(name="esBancario", type="boolean")
     */
    private $esBancario=0; 


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
     * Set esBancario
     *
     * @param boolean $esBancario
     *
     * @return FormasPago
     */
    public function setEsBancario($esBancario)
    {
        $this->esBancario = $esBancario;

        return $this;
    }

    /**
     * Get esBancario
     *
     * @return boolean
     */
    public function getEsBancario()
    {
        return $this->esBancario;
    }
}

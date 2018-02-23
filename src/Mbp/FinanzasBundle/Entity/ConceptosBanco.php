<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConceptosBanco
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\ConceptosBancoRepository")
 */
class ConceptosBanco
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
     * @ORM\Column(name="concepto", type="string", length=255)
     */
    private $concepto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="imputaDebe", type="boolean")
     */
    private $imputaDebe;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo=0;	


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
     * Set concepto
     *
     * @param string $concepto
     *
     * @return ConceptosBanco
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set imputaDebe
     *
     * @param boolean $imputaDebe
     *
     * @return ConceptosBanco
     */
    public function setImputaDebe($imputaDebe)
    {
        $this->imputaDebe = $imputaDebe;

        return $this;
    }

    /**
     * Get imputaDebe
     *
     * @return boolean
     */
    public function getImputaDebe()
    {
        return $this->imputaDebe;
    }

    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return ConceptosBanco
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
}

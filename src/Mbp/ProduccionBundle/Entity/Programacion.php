<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Programacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\ProgramacionRepository")
 */
class Programacion
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
     * @var OperacionesFormula
     * @ORM\ManyToOne(targetEntity="OperacionesFormula")
	 * @ORM\JoinColumn(name="idOperacionFormula", referencedColumnName="id")
     * 
     */
    private $idOperacionFormula;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="datetime")
     */
    private $fechaInicio;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFin", type="datetime")
     */
    private $fechaFin;


	/**
     * @var OperacionesFormula
     * @ORM\ManyToOne(targetEntity="Maquinas")
	 * @ORM\JoinColumn(name="idRecursoMaquina", referencedColumnName="id")
     * 
     */
    private $idRecursoMaquina;
	
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
     * Set idOperacionFormula
     *
     * @param integer $idOperacionFormula
     *
     * @return Programacion
     */
    public function setidOperacionFormula($idOperacionFormula)
    {
        $this->idOperacionFormula = $idOperacionFormula;

        return $this;
    }

    /**
     * Get idOperacionFormula
     *
     * @return integer
     */
    public function getidOperacionFormula()
    {
        return $this->idOperacionFormula;
    }

    /**
     * Set fechaEstimada
     *
     * @param \DateTime $fechaEstimada
     *
     * @return Programacion
     */
    public function setfechaEstimada($fechaEstimada)
    {
        $this->fechaEstimada = $fechaEstimada;

        return $this;
    }

    /**
     * Get fechaEstimada
     *
     * @return \DateTime
     */
    public function getfechaEstimada()
    {
        return $this->fechaEstimada;
    }
	
	/**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Programacion
     */
    public function setfechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getfechaInicio()
    {
        return $this->fechaInicio;
    }
	
	/**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Programacion
     */
    public function setfechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getfechaFin()
    {
        return $this->fechaFin;
    }
	
	 /**
     * Set idRecursoMaquina
     *
     * @param integer $idRecursoMaquina
     *
     * @return Programacion
     */
    public function setidRecursoMaquina($idRecursoMaquina)
    {
        $this->idRecursoMaquina = $idRecursoMaquina;

        return $this;
    }

    /**
     * Get idRecursoMaquina
     *
     * @return integer
     */
    public function getidRecursoMaquina()
    {
        return $this->idRecursoMaquina;
    }
}


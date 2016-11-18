<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorias
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\CategoriasRepository")
 */
class Categorias
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
     * @var \Mbp\PersonalBundle\Entity\Sindicatos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Sindicatos")
     * @ORM\JoinColumn(name="idSindicato", referencedColumnName="id", nullable=false)	
     */
    private $idSindicato;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=50)
     */
    private $categoria;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="salario", type="decimal", precision=8, scale=2)
     */
    private $salario;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo = 0;


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
     * Set idSindicato
     *
     * @param \Mbp\PersonalBundle\Entity\Sindicatos $idSindicato
     *
     * @return Categorias
     */
    public function setIdSindicato($idSindicato)
    {
        $this->idSindicato = $idSindicato;

        return $this;
    }

    /**
     * Get idSindicato
     *
     * @return \Mbp\PersonalBundle\Entity\Sindicatos
     */
    public function getIdSindicato()
    {
        return $this->idSindicato;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     *
     * @return Categorias
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
	
	/**
     * Set salario
     *
     * @param decimal $salario
     *
     * @return Categorias
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return decimal
     */
    public function getSalario()
    {
        return $this->salario;
    }
	
	/**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Categorias
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
    public function getinactivo()
    {
        return $this->inactivo;
    }
}


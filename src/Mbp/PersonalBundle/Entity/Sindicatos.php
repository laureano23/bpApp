<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sindicatos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\SindicatosRepository")
 */
class Sindicatos
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
     * @ORM\Column(name="sindicato", type="string", length=30)
     */
    private $sindicato;
	
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
     * Set sindicato
     *
     * @param string $sindicato
     *
     * @return Sindicatos
     */
    public function setSindicato($sindicato)
    {
        $this->sindicato = $sindicato;

        return $this;
    }

    /**
     * Get sindicato
     *
     * @return string
     */
    public function getSindicato()
    {
        return $this->sindicato;
    }
	
	/**
     * Set inactivo
     *
     * @param string $inactivo
     *
     * @return Sindicatos
     */
    public function setInactivo($inactivo)
    {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return string
     */
    public function getInactivo()
    {
        return $this->inactivo;
    }
}


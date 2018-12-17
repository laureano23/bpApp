<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periodos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\PeriodosRepository")
 */
class Periodos
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
     * @ORM\Column(name="descripcion", type="string", length=25)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esSAC", type="integer", nullable=true)
     */
    private $esSAC=0;


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
     * @return Periodos
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return Periodos
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set esSAC
     *
     * @param integer $esSAC
     *
     * @return Periodos
     */
    public function setEsSAC($esSAC)
    {
        $this->esSAC = $esSAC;

        return $this;
    }

    /**
     * Get esSAC
     *
     * @return integer
     */
    public function getEsSAC()
    {
        return $this->esSAC;
    }
}

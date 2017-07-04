<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enfriadores
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\EnfriadoresRepository")
 */
class Enfriadores extends Articulos 
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
     * @ORM\Column(name="caudal", type="decimal")
     */
    private $caudal;

    /**
     * @var string
     *
     * @ORM\Column(name="peso", type="decimal")
     */
    private $peso;

    /**
     * @var string
     *
     * @ORM\Column(name="voltage", type="decimal")
     */
    private $voltage;

    /**
     * @var string
     *
     * @ORM\Column(name="corriente", type="decimal")
     */
    private $corriente;

    /**
     * @var string
     *
     * @ORM\Column(name="potencia", type="decimal")
     */
    private $potencia;

    /**
     * @var string
     *
     * @ORM\Column(name="presion", type="decimal")
     */
    private $presion;


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
     * Set caudal
     *
     * @param string $caudal
     *
     * @return PosEnfriadores
     */
    public function setCaudal($caudal)
    {
        $this->caudal = $caudal;

        return $this;
    }

    /**
     * Get caudal
     *
     * @return string
     */
    public function getCaudal()
    {
        return $this->caudal;
    }

    /**
     * Set peso
     *
     * @param string $peso
     *
     * @return PosEnfriadores
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return string
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set voltage
     *
     * @param string $voltage
     *
     * @return PosEnfriadores
     */
    public function setVoltage($voltage)
    {
        $this->voltage = $voltage;

        return $this;
    }

    /**
     * Get voltage
     *
     * @return string
     */
    public function getVoltage()
    {
        return $this->voltage;
    }

    /**
     * Set corriente
     *
     * @param string $corriente
     *
     * @return PosEnfriadores
     */
    public function setCorriente($corriente)
    {
        $this->corriente = $corriente;

        return $this;
    }

    /**
     * Get corriente
     *
     * @return string
     */
    public function getCorriente()
    {
        return $this->corriente;
    }

    /**
     * Set potencia
     *
     * @param string $potencia
     *
     * @return PosEnfriadores
     */
    public function setPotencia($potencia)
    {
        $this->potencia = $potencia;

        return $this;
    }

    /**
     * Get potencia
     *
     * @return string
     */
    public function getPotencia()
    {
        return $this->potencia;
    }

    /**
     * Set presion
     *
     * @param string $presion
     *
     * @return PosEnfriadores
     */
    public function setPresion($presion)
    {
        $this->presion = $presion;

        return $this;
    }

    /**
     * Get presion
     *
     * @return string
     */
    public function getPresion()
    {
        return $this->presion;
    }
}


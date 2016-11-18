<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CentroCostos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\CentroCostosRepository")
 */
class CentroCostos
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
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="costo", type="decimal", precision=7, scale=4)
     */
    private $costo;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="moneda", type="boolean")
     */
    private $moneda=0;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CentroCostos
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set costo
     *
     * @param string $costo
     *
     * @return CentroCostos
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return string
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set moneda
     *
     * @param boolean $moneda
     *
     * @return CentroCostos
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda
     *
     * @return boolean
     */
    public function getMoneda()
    {
        return $this->moneda;
    }
}

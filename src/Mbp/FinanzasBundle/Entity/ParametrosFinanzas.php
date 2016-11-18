<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParametrosFinanzas
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\ParametrosFinanzasRepository")
 */
class ParametrosFinanzas
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
     * @ORM\Column(name="iva", type="decimal", precision=4, scale=2)
     */
    private $iva;

    /**
     * @var string
     *
     * @ORM\Column(name="dolarOficial", type="decimal")
     */
    private $dolarOficial;

    /**
     * @var string
     *
     * @ORM\Column(name="dolarBlue", type="decimal")
     */
    private $dolarBlue;


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
     * Set iva
     *
     * @param string $iva
     *
     * @return ParametrosFinanzas
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return string
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set dolarOficial
     *
     * @param string $dolarOficial
     *
     * @return ParametrosFinanzas
     */
    public function setDolarOficial($dolarOficial)
    {
        $this->dolarOficial = $dolarOficial;

        return $this;
    }

    /**
     * Get dolarOficial
     *
     * @return string
     */
    public function getDolarOficial()
    {
        return $this->dolarOficial;
    }

    /**
     * Set dolarBlue
     *
     * @param string $dolarBlue
     *
     * @return ParametrosFinanzas
     */
    public function setDolarBlue($dolarBlue)
    {
        $this->dolarBlue = $dolarBlue;

        return $this;
    }

    /**
     * Get dolarBlue
     *
     * @return string
     */
    public function getDolarBlue()
    {
        return $this->dolarBlue;
    }
}


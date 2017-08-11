<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DepositoArticulos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\DepositoArticulosRepository")
 */
class DepositoArticulos
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
     * @ORM\Column(name="deposito", type="string", length=255)
     */
    private $deposito;


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
     * Set deposito
     *
     * @param string $deposito
     *
     * @return DepositoArticulos
     */
    public function setDeposito($deposito)
    {
        $this->deposito = $deposito;

        return $this;
    }

    /**
     * Get deposito
     *
     * @return string
     */
    public function getDeposito()
    {
        return $this->deposito;
    }
}


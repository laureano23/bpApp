<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransaccionCobranzaFactura
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\TransaccionCobranzaFacturaRepository")
 */
class TransaccionCobranzaFactura
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
     * @ORM\Column(name="aplicado", type="decimal")
     */
    private $aplicado;

    /**
     * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Facturas")
     * @ORM\JoinColumn(name="facturaId", referencedColumnName="id")
     */
    private $facturaImputada;

    /**
     * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Cobranzas")
     * @ORM\JoinColumn(name="cobranzaId", referencedColumnName="id")
     */
    private $cobranzaImputada;

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
     * Set aplicado
     *
     * @param string $aplicado
     *
     * @return TransaccionCobranzaFactura
     */
    public function setAplicado($aplicado)
    {
        $this->aplicado = $aplicado;

        return $this;
    }

    /**
     * Get aplicado
     *
     * @return string
     */
    public function getAplicado()
    {
        return $this->aplicado;
    }
}


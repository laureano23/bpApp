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
     * @ORM\JoinColumn(name="facturaId", referencedColumnName="id", onDelete="CASCADE")
     */
    private $facturaImputada;

    /**
     * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Cobranzas")
     * @ORM\JoinColumn(name="cobranzaId", referencedColumnName="id", onDelete="CASCADE")
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

    /**
     * Set facturaImputada
     *
     * @param \Mbp\FinanzasBundle\Entity\Facturas $facturaImputada
     *
     * @return TransaccionCobranzaFactura
     */
    public function setFacturaImputada(\Mbp\FinanzasBundle\Entity\Facturas $facturaImputada = null)
    {
        $this->facturaImputada = $facturaImputada;

        return $this;
    }

    /**
     * Get facturaImputada
     *
     * @return \Mbp\FinanzasBundle\Entity\Facturas
     */
    public function getFacturaImputada()
    {
        return $this->facturaImputada;
    }

    /**
     * Set cobranzaImputada
     *
     * @param \Mbp\FinanzasBundle\Entity\Cobranzas $cobranzaImputada
     *
     * @return TransaccionCobranzaFactura
     */
    public function setCobranzaImputada(\Mbp\FinanzasBundle\Entity\Cobranzas $cobranzaImputada = null)
    {
        $this->cobranzaImputada = $cobranzaImputada;

        return $this;
    }

    /**
     * Get cobranzaImputada
     *
     * @return \Mbp\FinanzasBundle\Entity\Cobranzas
     */
    public function getCobranzaImputada()
    {
        return $this->cobranzaImputada;
    }
}

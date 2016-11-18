<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TransaccionOPFC
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\TransaccionOPFCRepository")
 */
class TransaccionOPFC
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
	 * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Factura")
	 * @ORM\JoinColumn(name="facturaId", referencedColumnName="id")
	 */
	private $facturaImputada;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\OrdenPago", cascade={"persist"})
	 * @ORM\JoinColumn(name="ordenPagoId", referencedColumnName="id")
	 */
	private $ordenPagoImputada;


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
     * @return TransaccionOPFC
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
     * @param \Mbp\ProveedoresBundle\Entity\Factura $facturaImputada
     *
     * @return TransaccionOPFC
     */
    public function setFacturaImputada(\Mbp\ProveedoresBundle\Entity\Factura $facturaImputada = null)
    {
        $this->facturaImputada = $facturaImputada;

        return $this;
    }

    /**
     * Get facturaImputada
     *
     * @return \Mbp\ProveedoresBundle\Entity\Factura
     */
    public function getFacturaImputada()
    {
        return $this->facturaImputada;
    }

    /**
     * Set ordenPagoImputada
     *
     * @param \Mbp\ProveedoresBundle\Entity\OrdenPago $ordenPagoImputada
     *
     * @return TransaccionOPFC
     */
    public function setOrdenPagoImputada(\Mbp\ProveedoresBundle\Entity\OrdenPago $ordenPagoImputada = null)
    {
        $this->ordenPagoImputada = $ordenPagoImputada;

        return $this;
    }

    /**
     * Get ordenPagoImputada
     *
     * @return \Mbp\ProveedoresBundle\Entity\OrdenPago
     */
    public function getOrdenPagoImputada()
    {
        return $this->ordenPagoImputada;
    }
}

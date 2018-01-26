<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InteresesResarcitorios
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\InteresesResarcitoriosRepository")
 */
class InteresesResarcitorios
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
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	 * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=false)
	 */
	private $clienteId;

    /**
     * @var string
     *
     * @ORM\Column(name="cbte", type="string", length=30)
     */
    private $cbte;

    /**
     * @var string
     *
     * @ORM\Column(name="monto", type="decimal")
     */
    private $monto;

    /**
     * @var string
     *
     * @ORM\Column(name="tasa", type="decimal")
     */
    private $tasa;

    /**
     * @var string
     *
     * @ORM\Column(name="interes", type="decimal")
     */
    private $interes;


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
     * Set cbte
     *
     * @param string $cbte
     *
     * @return InteresesResarcitorios
     */
    public function setCbte($cbte)
    {
        $this->cbte = $cbte;

        return $this;
    }

    /**
     * Get cbte
     *
     * @return string
     */
    public function getCbte()
    {
        return $this->cbte;
    }

    /**
     * Set monto
     *
     * @param string $monto
     *
     * @return InteresesResarcitorios
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set tasa
     *
     * @param string $tasa
     *
     * @return InteresesResarcitorios
     */
    public function setTasa($tasa)
    {
        $this->tasa = $tasa;

        return $this;
    }

    /**
     * Get tasa
     *
     * @return string
     */
    public function getTasa()
    {
        return $this->tasa;
    }

    /**
     * Set interes
     *
     * @param string $interes
     *
     * @return InteresesResarcitorios
     */
    public function setInteres($interes)
    {
        $this->interes = $interes;

        return $this;
    }

    /**
     * Get interes
     *
     * @return string
     */
    public function getInteres()
    {
        return $this->interes;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return InteresesResarcitorios
     */
    public function setClienteId(\Mbp\ClientesBundle\Entity\Cliente $clienteId)
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }
}

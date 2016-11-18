<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pago
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\PagoRepository")
 */
class Pago
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
	 * @var \Mbp\FinanzasBundle\Entity\FormasPago
	 * 
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\FormasPago")
	 * @ORM\JoinColumn(name="idFormaPago", referencedColumnName="id")
	 */
	private $idFormaPago;
	
	/**
     * @var string
     *
     * @ORM\Column(name="banco", type="string", length=50)
     */
    private $banco;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="emision", type="datetime")
     */
    private $emision;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=50)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="importe", type="decimal", precision=11, scale=2)
     */
    private $importe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="diferido", type="datetime")
     */
    private $diferido;


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
     * Set emision
     *
     * @param \DateTime $emision
     *
     * @return Pago
     */
    public function setEmision($emision)
    {
        $this->emision = $emision;

        return $this;
    }

    /**
     * Get emision
     *
     * @return \DateTime
     */
    public function getEmision()
    {
        return $this->emision;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Pago
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return Pago
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return string
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set diferido
     *
     * @param \DateTime $diferido
     *
     * @return Pago
     */
    public function setDiferido($diferido)
    {
        $this->diferido = $diferido;

        return $this;
    }

    /**
     * Get diferido
     *
     * @return \DateTime
     */
    public function getDiferido()
    {
        return $this->diferido;
    }
    
    /**
     * Set idFormaPago
     *
     * @param \Mbp\FinanzasBundle\Entity\FormasPago $idFormaPago
     *
     * @return Pago
     */
    public function setIdFormaPago(\Mbp\FinanzasBundle\Entity\FormasPago $idFormaPago = null)
    {
        $this->idFormaPago = $idFormaPago;

        return $this;
    }

    /**
     * Get idFormaPago
     *
     * @return \Mbp\FinanzasBundle\Entity\FormasPago
     */
    public function getIdFormaPago()
    {
        return $this->idFormaPago;
    }

    /**
     * Set banco
     *
     * @param string $banco
     *
     * @return Pago
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return string
     */
    public function getBanco()
    {
        return $this->banco;
    }
}

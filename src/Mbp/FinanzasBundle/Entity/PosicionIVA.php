<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PosicionIVA
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\PosicionIVARepository")
 */
class PosicionIVA
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
     * @ORM\Column(name="posicion", type="string", length=255)
     */
    private $posicion;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="esResponsableInscripto", type="boolean", nullable=false)
     */
    private $esResponsableInscripto=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esResponsableNoInscripto", type="boolean", nullable=false)
     */
    private $esResponsableNoInscripto=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esExento", type="boolean", nullable=false)
     */
    private $esExento=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esResponsableMonotributo", type="boolean", nullable=false)
     */
    private $esResponsableMonotributo=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esConsumidorFinal", type="boolean", nullable=false)
     */
    private $esConsumidorFinal=0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="esExportacion", type="boolean", nullable=false)
     */
    private $esExportacion=0;
	
	 /**
     * @ORM\OneToMany(targetEntity="Mbp\FinanzasBundle\Entity\Facturas", mappedBy="tipoIva")
     */
    private $facturas;
    
    /**
     * @ORM\OneToMany(targetEntity="Mbp\ClientesBundle\Entity\Cliente", mappedBy="iva")
     */
    private $clientes;

    /**
     * @var smallint
     *
     * @ORM\Column(name="tipoDocAfip", type="smallint", nullable=false)
     * dato que debe sacarse de tablas de AFIP
     */
    private $tipoDocAfip=0;


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
     * Set posicion
     *
     * @param string $posicion
     *
     * @return PosicionIVA
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return string
     */
    public function getPosicion()
    {
        return $this->posicion;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set esResponsableInscripto
     *
     * @param boolean $esResponsableInscripto
     *
     * @return PosicionIVA
     */
    public function setEsResponsableInscripto($esResponsableInscripto)
    {
        $this->esResponsableInscripto = $esResponsableInscripto;

        return $this;
    }

    /**
     * Get esResponsableInscripto
     *
     * @return boolean
     */
    public function getEsResponsableInscripto()
    {
        return $this->esResponsableInscripto;
    }

    /**
     * Set esResponsableNoInscripto
     *
     * @param boolean $esResponsableNoInscripto
     *
     * @return PosicionIVA
     */
    public function setEsResponsableNoInscripto($esResponsableNoInscripto)
    {
        $this->esResponsableNoInscripto = $esResponsableNoInscripto;

        return $this;
    }

    /**
     * Get esResponsableNoInscripto
     *
     * @return boolean
     */
    public function getEsResponsableNoInscripto()
    {
        return $this->esResponsableNoInscripto;
    }

    /**
     * Set esExento
     *
     * @param boolean $esExento
     *
     * @return PosicionIVA
     */
    public function setEsExento($esExento)
    {
        $this->esExento = $esExento;

        return $this;
    }

    /**
     * Get esExento
     *
     * @return boolean
     */
    public function getEsExento()
    {
        return $this->esExento;
    }

    /**
     * Set esResponsableMonotributo
     *
     * @param boolean $esResponsableMonotributo
     *
     * @return PosicionIVA
     */
    public function setEsResponsableMonotributo($esResponsableMonotributo)
    {
        $this->esResponsableMonotributo = $esResponsableMonotributo;

        return $this;
    }

    /**
     * Get esResponsableMonotributo
     *
     * @return boolean
     */
    public function getEsResponsableMonotributo()
    {
        return $this->esResponsableMonotributo;
    }

    /**
     * Set esConsumidorFinal
     *
     * @param boolean $esConsumidorFinal
     *
     * @return PosicionIVA
     */
    public function setEsConsumidorFinal($esConsumidorFinal)
    {
        $this->esConsumidorFinal = $esConsumidorFinal;

        return $this;
    }

    /**
     * Get esConsumidorFinal
     *
     * @return boolean
     */
    public function getEsConsumidorFinal()
    {
        return $this->esConsumidorFinal;
    }

    /**
     * Set esExportacion
     *
     * @param boolean $esExportacion
     *
     * @return PosicionIVA
     */
    public function setEsExportacion($esExportacion)
    {
        $this->esExportacion = $esExportacion;

        return $this;
    }

    /**
     * Get esExportacion
     *
     * @return boolean
     */
    public function getEsExportacion()
    {
        return $this->esExportacion;
    }

    /**
     * Add factura
     *
     * @param \Mbp\FinanzasBundle\Entity\Facturas $factura
     *
     * @return PosicionIVA
     */
    public function addFactura(\Mbp\FinanzasBundle\Entity\Facturas $factura)
    {
        $this->facturas[] = $factura;

        return $this;
    }

    /**
     * Remove factura
     *
     * @param \Mbp\FinanzasBundle\Entity\Facturas $factura
     */
    public function removeFactura(\Mbp\FinanzasBundle\Entity\Facturas $factura)
    {
        $this->facturas->removeElement($factura);
    }

    /**
     * Get facturas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturas()
    {
        return $this->facturas;
    }

    /**
     * Add cliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $cliente
     *
     * @return PosicionIVA
     */
    public function addCliente(\Mbp\ClientesBundle\Entity\Cliente $cliente)
    {
        $this->clientes[] = $cliente;

        return $this;
    }

    /**
     * Remove cliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $cliente
     */
    public function removeCliente(\Mbp\ClientesBundle\Entity\Cliente $cliente)
    {
        $this->clientes->removeElement($cliente);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientes()
    {
        return $this->clientes;
    }

    /**
     * Set tipoDocAfip
     *
     * @param integer $tipoDocAfip
     *
     * @return PosicionIVA
     */
    public function setTipoDocAfip($tipoDocAfip)
    {
        $this->tipoDocAfip = $tipoDocAfip;

        return $this;
    }

    /**
     * Get tipoDocAfip
     *
     * @return integer
     */
    public function getTipoDocAfip()
    {
        return $this->tipoDocAfip;
    }
}

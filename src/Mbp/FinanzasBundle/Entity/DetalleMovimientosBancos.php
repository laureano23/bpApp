<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleMovimientosBancos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\DetalleMovimientosBancosRepository")
 */
class DetalleMovimientosBancos
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
     * @ORM\Column(name="numComprobante", type="string", length=50)
     */
    private $numComprobante;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaDiferida", type="date")
     */
    private $fechaDiferida;

    /**
     * @var string
     *
     * @ORM\Column(name="importe", type="decimal", precision=10, scale=2)
     */
    private $importe;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;
	
	/**
	  * @ORM\OneToOne(targetEntity="CobranzasDetalle")
	  * @ORM\JoinColumn(name="ChequeTerceros_id", referencedColumnName="id", nullable=true)
	  * */
	 private $chequeTerceros;
	 
	 /**
	  * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Proveedor")
	  * @ORM\JoinColumn(name="Proveedor_id", referencedColumnName="id", nullable=true)
	  * */
	 private $proveedorId;
	 
	 /**
	  * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	  * @ORM\JoinColumn(name="idCliente", referencedColumnName="idCliente", nullable=true)
	  * */
	 private $idCliente;


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
     * Set numComprobante
     *
     * @param string $numComprobante
     *
     * @return DetalleMovimientosBancos
     */
    public function setNumComprobante($numComprobante)
    {
        $this->numComprobante = $numComprobante;

        return $this;
    }

    /**
     * Get numComprobante
     *
     * @return string
     */
    public function getNumComprobante()
    {
        return $this->numComprobante;
    }

    /**
     * Set fechaDiferida
     *
     * @param \DateTime $fechaDiferida
     *
     * @return DetalleMovimientosBancos
     */
    public function setFechaDiferida($fechaDiferida)
    {
        $this->fechaDiferida = $fechaDiferida;

        return $this;
    }

    /**
     * Get fechaDiferida
     *
     * @return \DateTime
     */
    public function getFechaDiferida()
    {
        return $this->fechaDiferida;
    }

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return DetalleMovimientosBancos
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return DetalleMovimientosBancos
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set chequeTerceros
     *
     * @param \Mbp\FinanzasBundle\Entity\CobranzasDetalle $chequeTerceros
     *
     * @return DetalleMovimientosBancos
     */
    public function setChequeTerceros(\Mbp\FinanzasBundle\Entity\CobranzasDetalle $chequeTerceros = null)
    {
        $this->chequeTerceros = $chequeTerceros;

        return $this;
    }

    /**
     * Get chequeTerceros
     *
     * @return \Mbp\FinanzasBundle\Entity\CobranzasDetalle
     */
    public function getChequeTerceros()
    {
        return $this->chequeTerceros;
    }

    /**
     * Set proveedorId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId
     *
     * @return DetalleMovimientosBancos
     */
    public function setProveedorId(\Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId = null)
    {
        $this->proveedorId = $proveedorId;

        return $this;
    }

    /**
     * Get proveedorId
     *
     * @return \Mbp\ProveedoresBundle\Entity\Proveedor
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Set idCliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $idCliente
     *
     * @return DetalleMovimientosBancos
     */
    public function setIdCliente(\Mbp\ClientesBundle\Entity\Cliente $idCliente = null)
    {
        $this->idCliente = $idCliente;

        return $this;
    }

    /**
     * Get idCliente
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }
}

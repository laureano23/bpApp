<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovimientosBancos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\MovimientosBancosRepository")
 */
class MovimientosBancos
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
     * @var \DateTime
     *
     * @ORM\Column(name="fechaMovimiento", type="datetime")
     */
    private $fechaMovimiento;
	
	/**
	 * @ORM\ManyToMany(targetEntity="DetalleMovimientosBancos", cascade={"persist"})
	 * @ORM\JoinTable(name="MovimientoBanco_Detalle",
	 * 	joinColumns={ @ORM\JoinColumn(name = "Movimiento_id", referencedColumnName="id") }),
	 *  inverseJoinColumns={@JoinColumn(name="Detalle_id", referencedColumnName="id", unique=true)})
	 * )
	 * */
	 private $detallesMovimientos;
	 
	 /**
	  * @ORM\ManyToOne(targetEntity="CuentasBancarias")
	  * @ORM\JoinColumn(name="cuentaId", referencedColumnName="id")
	  * */
	 private $cuentaBancaria;
	 
	 /**
	  * @ORM\ManyToOne(targetEntity="ConceptosBanco")
	  * @ORM\JoinColumn(name="ceonceptoBancoId", referencedColumnName="id", nullable=true)
	  */
	 private $conceptoBancoId;
	 
	 /**
     * @var \boolean
     *
     * @ORM\Column(name="anulado", type="boolean")
     */
    private $anulado=0;


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
     * Set fechaMovimiento
     *
     * @param \DateTime $fechaMovimiento
     *
     * @return MovimientosBancos
     */
    public function setFechaMovimiento($fechaMovimiento)
    {
        $this->fechaMovimiento = $fechaMovimiento;

        return $this;
    }

    /**
     * Get fechaMovimiento
     *
     * @return \DateTime
     */
    public function getFechaMovimiento()
    {
        return $this->fechaMovimiento;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detallesMovimientos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add detallesMovimiento
     *
     * @param \Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos $detallesMovimiento
     *
     * @return MovimientosBancos
     */
    public function addDetallesMovimiento(\Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos $detallesMovimiento)
    {
        $this->detallesMovimientos[] = $detallesMovimiento;

        return $this;
    }

    /**
     * Remove detallesMovimiento
     *
     * @param \Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos $detallesMovimiento
     */
    public function removeDetallesMovimiento(\Mbp\FinanzasBundle\Entity\DetalleMovimientosBancos $detallesMovimiento)
    {
        $this->detallesMovimientos->removeElement($detallesMovimiento);
    }

    /**
     * Get detallesMovimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetallesMovimientos()
    {
        return $this->detallesMovimientos;
    }

    
    /**
     * Set conceptoBancoId
     *
     * @param \Mbp\FinanzasBundle\Entity\ConceptosBanco $conceptoBancoId
     *
     * @return MovimientosBancos
     */
    public function setConceptoBancoId(\Mbp\FinanzasBundle\Entity\ConceptosBanco $conceptoBancoId = null)
    {
        $this->conceptoBancoId = $conceptoBancoId;

        return $this;
    }

    /**
     * Get conceptoBancoId
     *
     * @return \Mbp\FinanzasBundle\Entity\ConceptosBanco
     */
    public function getConceptoBancoId()
    {
        return $this->conceptoBancoId;
    }

    /**
     * Set cuentaBancaria
     *
     * @param \Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentaBancaria
     *
     * @return MovimientosBancos
     */
    public function setCuentaBancaria(\Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentaBancaria = null)
    {
        $this->cuentaBancaria = $cuentaBancaria;

        return $this;
    }

    /**
     * Get cuentaBancaria
     *
     * @return \Mbp\FinanzasBundle\Entity\CuentasBancarias
     */
    public function getCuentaBancaria()
    {
        return $this->cuentaBancaria;
    }

    /**
     * Set anulado
     *
     * @param boolean $anulado
     *
     * @return MovimientosBancos
     */
    public function setAnulado($anulado)
    {
        $this->anulado = $anulado;

        return $this;
    }

    /**
     * Get anulado
     *
     * @return boolean
     */
    public function getAnulado()
    {
        return $this->anulado;
    }
}

<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecibosDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\RecibosDetalleRepository")
 */
class RecibosDetalle
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
     * @ORM\Column(name="cantConceptoVar", type="decimal", scale=2, precision=6)
     */
    private $cantConceptoVar;

    /**
     * @var string
     *
     * @ORM\Column(name="valorConceptoHist", type="decimal", precision=15, scale=2)
     */
    private $valorConceptoHist;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="valorCompensatorioHist", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $valorCompensatorioHist;

    /**
     * @var string
     *
     * @ORM\Column(name="remunerativo", type="decimal", precision=15, scale=2)
     */
    private $remunerativo=0;
	
    /**
     * @var string
     *
     * @ORM\Column(name="exento", type="decimal", precision=15, scale=2)
     */
    private $exento=0;

    /**
     * @var string
     *
     * @ORM\Column(name="descuento", type="decimal", precision=15, scale=2)
     */
    private $descuento=0;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Mbp\PersonalBundle\Entity\CodigoSueldos")
	 * @ORM\JoinTable(name="RecibosDetalles_CodigoSueldos", 
	 * 					joinColumns={@ORM\JoinColumn(name="recibosDetalles_id", referencedColumnName="id")},
	 * 					inverseJoinColumns={@ORM\JoinColumn(name="codigoSueldos_id", referencedColumnName="id", unique=false)}
	 * )
	 */
	private $codigoSueldos;

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
     * Set cantConceptoVar
     *
     * @param string $cantConceptoVar
     *
     * @return RecibosDetalle
     */
    public function setCantConceptoVar($cantConceptoVar)
    {
        $this->cantConceptoVar = $cantConceptoVar;

        return $this;
    }

    /**
     * Get cantConceptoVar
     *
     * @return string
     */
    public function getCantConceptoVar()
    {
        return $this->cantConceptoVar;
    }

    /**
     * Set valorConceptoHist
     *
     * @param string $valorConceptoHist
     *
     * @return RecibosDetalle
     */
    public function setValorConceptoHist($valorConceptoHist)
    {
        $this->valorConceptoHist = $valorConceptoHist;

        return $this;
    }

    /**
     * Get valorConceptoHist
     *
     * @return string
     */
    public function getValorConceptoHist()
    {
        return $this->valorConceptoHist;
    }

    /**
     * Set remunerativo
     *
     * @param string $remunerativo
     *
     * @return RecibosDetalle
     */
    public function setRemunerativo($remunerativo)
    {
        $this->remunerativo = $remunerativo;

        return $this;
    }

    /**
     * Get remunerativo
     *
     * @return string
     */
    public function getRemunerativo()
    {
        return $this->remunerativo;
    }

    /**
     * Set exento
     *
     * @param string $exento
     *
     * @return RecibosDetalle
     */
    public function setExento($exento)
    {
        $this->exento = $exento;

        return $this;
    }

    /**
     * Get exento
     *
     * @return string
     */
    public function getExento()
    {
        return $this->exento;
    }

    /**
     * Set descuento
     *
     * @param string $descuento
     *
     * @return RecibosDetalle
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return string
     */
    public function getDescuento()
    {
        return $this->descuento;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codigoSueldos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add codigoSueldo
     *
     * @param \Mbp\PersonalBundle\Entity\CodigoSueldos $codigoSueldo
     *
     * @return RecibosDetalle
     */
    public function addCodigoSueldo(\Mbp\PersonalBundle\Entity\CodigoSueldos $codigoSueldo)
    {
        $this->codigoSueldos[] = $codigoSueldo;

        return $this;
    }

    /**
     * Remove codigoSueldo
     *
     * @param \Mbp\PersonalBundle\Entity\CodigoSueldos $codigoSueldo
     */
    public function removeCodigoSueldo(\Mbp\PersonalBundle\Entity\CodigoSueldos $codigoSueldo)
    {
        $this->codigoSueldos->removeElement($codigoSueldo);
    }

    /**
     * Get codigoSueldos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCodigoSueldos()
    {
        return $this->codigoSueldos;
    }

    /**
     * Set valorCompensatorioHist
     *
     * @param string $valorCompensatorioHist
     *
     * @return RecibosDetalle
     */
    public function setValorCompensatorioHist($valorCompensatorioHist)
    {
        $this->valorCompensatorioHist = $valorCompensatorioHist;

        return $this;
    }

    /**
     * Get valorCompensatorioHist
     *
     * @return string
     */
    public function getValorCompensatorioHist()
    {
        return $this->valorCompensatorioHist;
    }
}

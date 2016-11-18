<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Recibos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\RecibosRepository")
 */
class Recibos
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
     * @var boolean
     *
     * @ORM\Column(name="compensatorio", type="boolean")
     */
    private $compensatorio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaPago", type="date")	 * 
     */
    private $fechaPago;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="periodo", type="smallint")
     */
    private $periodo;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes", type="smallint")
	 * @Assert\Range(
	 * 	min=1,
	 * 	max=12
	 * )
     */
    private $mes;

    /**
     * @var integer
     *
     * @ORM\Column(name="anio", type="smallint")
	 * @Assert\Range(
	 * 	min=1990,
	 * 	max=3000
	 * )
     */
    private $anio;
	
	/**
     * @var string
     *
     * @ORM\Column(name="tipoPago", type="string", length=70)
     */
    private $tipoPago;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Mbp\PersonalBundle\Entity\Personal")
	 * @ORM\JoinTable(name="RecibosPersonal", 
	 * 					joinColumns={@ORM\JoinColumn(name="recibos_id", referencedColumnName="id")},
	 * 					inverseJoinColumns={@ORM\JoinColumn(name="personal_id", referencedColumnName="idP", unique=false)}
	 * )
	 */
	private $personal;
	
	/**
	 * @ORM\ManyToMany(targetEntity="RecibosDetalle", cascade={"remove", "persist"})
	 * @ORM\JoinTable(name="recibo_detallesRecibos",
	 *  joinColumns={ @ORM\JoinColumn(name = "recibo_id", referencedColumnName="id") }),
	 *  inverseJoinColumns={@JoinColumn(name="detallesRecibo_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $reciboDetalleId;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Bancos")
	 * @ORM\JoinColumn(name="banco_id", referencedColumnName="id")
	 */
	private $banco;
	
	/**
     * @var string
     *
     * @ORM\Column(name="basicoHist", type="decimal", precision=15, scale=2)
     */
    private $basicoHist=0;
	
	/**
     * @var string
     *
     * @ORM\Column(name="categoriaHist", type="string", length=70)
     */
    private $categoriaHist;
	
	/**
     * @var string
     *
     * @ORM\Column(name="sindicatoHist", type="string", length=20)
     */
    private $sindicatoHist;
	
	/**
     * @var string
     *
     * @ORM\Column(name="tarea", type="string", length=20)
     */
    private $tarea;
    
	/**
     * @var smallint
     *
     * @ORM\Column(name="antiguedad", type="smallint")
	 * @Assert\Range(
	 * 	min=0,
	 * 	max=100)
     */
    private $antiguedad;
    
	/**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=50)
     */
    private $domicilio;
	
	/**
     * @var string
     *
     * @ORM\Column(name="eCivil", type="string", length=20)
     */
    private $eCivil;
	
	/**
     * @var string
     *
     * @ORM\Column(name="obraSocial", type="string", length=15, nullable=true)
     */
    private $obraSocial;


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
     * Set compensatorio
     *
     * @param boolean $compensatorio
     *
     * @return Recibos
     */
    public function setCompensatorio($compensatorio)
    {
        $this->compensatorio = $compensatorio;

        return $this;
    }

    /**
     * Get compensatorio
     *
     * @return boolean
     */
    public function getCompensatorio()
    {
        return $this->compensatorio;
    }

    /**
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return Recibos
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->codigoSueldos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personal = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reciboDetalleId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set periodo
     *
     * @param integer $periodo
     *
     * @return Recibos
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return Recibos
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return Recibos
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set tipoPago
     *
     * @param string $tipoPago
     *
     * @return Recibos
     */
    public function setTipoPago($tipoPago)
    {
        $this->tipoPago = $tipoPago;

        return $this;
    }

    /**
     * Get tipoPago
     *
     * @return string
     */
    public function getTipoPago()
    {
        return $this->tipoPago;
    }

    /**
     * Add personal
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $personal
     *
     * @return Recibos
     */
    public function addPersonal(\Mbp\PersonalBundle\Entity\Personal $personal)
    {
        $this->personal[] = $personal;

        return $this;
    }

    /**
     * Remove personal
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $personal
     */
    public function removePersonal(\Mbp\PersonalBundle\Entity\Personal $personal)
    {
        $this->personal->removeElement($personal);
    }

    /**
     * Get personal
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Add reciboDetalleId
     *
     * @param \Mbp\PersonalBundle\Entity\RecibosDetalle $reciboDetalleId
     *
     * @return Recibos
     */
    public function addReciboDetalleId(\Mbp\PersonalBundle\Entity\RecibosDetalle $reciboDetalleId)
    {
        $this->reciboDetalleId[] = $reciboDetalleId;

        return $this;
    }

    /**
     * Remove reciboDetalleId
     *
     * @param \Mbp\PersonalBundle\Entity\RecibosDetalle $reciboDetalleId
     */
    public function removeReciboDetalleId(\Mbp\PersonalBundle\Entity\RecibosDetalle $reciboDetalleId)
    {
        $this->reciboDetalleId->removeElement($reciboDetalleId);
    }

    /**
     * Get reciboDetalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReciboDetalleId()
    {
        return $this->reciboDetalleId;
    }

    /**
     * Set banco
     *
     * @param \Mbp\FinanzasBundle\Entity\Bancos $banco
     *
     * @return Recibos
     */
    public function setBanco(\Mbp\FinanzasBundle\Entity\Bancos $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \Mbp\FinanzasBundle\Entity\Bancos
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set basicoHist
     *
     * @param string $basicoHist
     *
     * @return Recibos
     */
    public function setBasicoHist($basicoHist)
    {
        $this->basicoHist = $basicoHist;

        return $this;
    }

    /**
     * Get basicoHist
     *
     * @return string
     */
    public function getBasicoHist()
    {
        return $this->basicoHist;
    }

    /**
     * Set categoriaHist
     *
     * @param string $categoriaHist
     *
     * @return Recibos
     */
    public function setCategoriaHist($categoriaHist)
    {
        $this->categoriaHist = $categoriaHist;

        return $this;
    }

    /**
     * Get categoriaHist
     *
     * @return string
     */
    public function getCategoriaHist()
    {
        return $this->categoriaHist;
    }

    /**
     * Set sindicatoHist
     *
     * @param string $sindicatoHist
     *
     * @return Recibos
     */
    public function setSindicatoHist($sindicatoHist)
    {
        $this->sindicatoHist = $sindicatoHist;

        return $this;
    }

    /**
     * Get sindicatoHist
     *
     * @return string
     */
    public function getSindicatoHist()
    {
        return $this->sindicatoHist;
    }

    /**
     * Set tarea
     *
     * @param string $tarea
     *
     * @return Recibos
     */
    public function setTarea($tarea)
    {
        $this->tarea = $tarea;

        return $this;
    }

    /**
     * Get tarea
     *
     * @return string
     */
    public function getTarea()
    {
        return $this->tarea;
    }

    /**
     * Set antiguedad
     *
     * @param integer $antiguedad
     *
     * @return Recibos
     */
    public function setAntiguedad($antiguedad)
    {
        $this->antiguedad = $antiguedad;

        return $this;
    }

    /**
     * Get antiguedad
     *
     * @return integer
     */
    public function getAntiguedad()
    {
        return $this->antiguedad;
    }

    /**
     * Set domicilio
     *
     * @param string $domicilio
     *
     * @return Recibos
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio
     *
     * @return string
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set eCivil
     *
     * @param string $eCivil
     *
     * @return Recibos
     */
    public function setECivil($eCivil)
    {
        $this->eCivil = $eCivil;

        return $this;
    }

    /**
     * Get eCivil
     *
     * @return string
     */
    public function getECivil()
    {
        return $this->eCivil;
    }

    /**
     * Set obraSocial
     *
     * @param string $obraSocial
     *
     * @return Recibos
     */
    public function setObraSocial($obraSocial)
    {
        $this->obraSocial = $obraSocial;

        return $this;
    }

    /**
     * Get obraSocial
     *
     * @return string
     */
    public function getObraSocial()
    {
        return $this->obraSocial;
    }
}

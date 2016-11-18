<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * CuentaEmpleados
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\CuentaEmpleadosRepository")
 */
class CuentaEmpleados
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
     * @var date
     *
     * @ORM\Column(name="fechaEmision", type="date")
     */
    private $fechaEmision;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="periodo", type="smallint")
     */
    private $periodo=0;

    /**
     * @var integer
     *
     * @ORM\Column(name="mes", type="smallint")
     */
    private $mes;

    /**
     * @var integer
     *
     * @ORM\Column(name="anio", type="smallint")
     */
    private $anio;

    /**
     * @var decimal
     *
     * @ORM\Column(name="neto", type="decimal", precision=11, scale=4)
     */
    private $neto=0;

    /**
     * @var decimal
     *
     * @ORM\Column(name="pagado", type="decimal", precision=11, scale=4)
     */
    private $pagado=0;
	
	/**
     * @var string
     *
     * @ORM\Column(name="concepto", type="string")
     */
    private $concepto;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="aplica", type="decimal", precision=11, scale=4)
     */
    private $aplica=0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="compensatorio")
     */
    private $compensatorio=0;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Personal")
	 * @ORM\JoinColumn(name="idPersonal", referencedColumnName="idP")
	 */
	private $idPersonal;
	
	public function __construct()
	{
		$this->fechaEmision = new DateTime();
	}

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
     * Set periodo
     *
     * @param integer $periodo
     *
     * @return CuentaEmpleados
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
     * @return CuentaEmpleados
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
     * @return CuentaEmpleados
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
     * Set neto
     *
     * @param string $neto
     *
     * @return CuentaEmpleados
     */
    public function setNeto($neto)
    {
        $this->neto = $neto;

        return $this;
    }

    /**
     * Get neto
     *
     * @return string
     */
    public function getNeto()
    {
        return $this->neto;
    }

    /**
     * Set pagado
     *
     * @param string $pagado
     *
     * @return CuentaEmpleados
     */
    public function setPagado($pagado)
    {
        $this->pagado = $pagado;

        return $this;
    }

    /**
     * Get pagado
     *
     * @return string
     */
    public function getPagado()
    {
        return $this->pagado;
    }

    /**
     * Set idPersonal
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $idPersonal
     *
     * @return CuentaEmpleados
     */
    public function setIdPersonal(\Mbp\PersonalBundle\Entity\Personal $idPersonal = null)
    {
        $this->idPersonal = $idPersonal;

        return $this;
    }

    /**
     * Get idPersonal
     *
     * @return \Mbp\PersonalBundle\Entity\Personal
     */
    public function getIdPersonal()
    {
        return $this->idPersonal;
    }

    /**
     * Set concepto
     *
     * @param string $concepto
     *
     * @return CuentaEmpleados
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set compensatorio
     *
     * @param string $compensatorio
     *
     * @return CuentaEmpleados
     */
    public function setCompensatorio($compensatorio)
    {
        $this->compensatorio = $compensatorio;

        return $this;
    }

    /**
     * Get compensatorio
     *
     * @return string
     */
    public function getCompensatorio()
    {
        return $this->compensatorio;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     *
     * @return CuentaEmpleados
     */
    public function setFechaEmision($fechaEmision)
    {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * Set aplica
     *
     * @param string $aplica
     *
     * @return CuentaEmpleados
     */
    public function setAplica($aplica)
    {
        $this->aplica = $aplica;

        return $this;
    }

    /**
     * Get aplica
     *
     * @return string
     */
    public function getAplica()
    {
        return $this->aplica;
    }
}

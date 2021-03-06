<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CodigoSueldos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\CodigoSueldosRepository")
 */
class CodigoSueldos
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
     * @ORM\Column(name="descripcion", type="string", length=50)
     */
    private $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="remunerativo", type="boolean")
     */
    private $remunerativo = 0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="noRemunerativo", type="boolean")
     */
    private $noRemunerativo = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="descuento", type="boolean")
     */
    private $descuento = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="asignacion", type="boolean")
     */
    private $asignacion = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fijo", type="boolean")
     */
    private $fijo = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo = 0;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Mbp\PersonalBundle\Entity\Periodos")
	 * @ORM\JoinTable(name="PeriodosConceptos", 
	 * 					joinColumns={@ORM\JoinColumn(name="concepto_id", referencedColumnName="id")},
	 * 					inverseJoinColumns={@ORM\JoinColumn(name="periodo_id", referencedColumnName="id", unique=false)}
	 * )
	 */
    private $periodo;
	
	 /**
     * @var boolean
     *
     * @ORM\Column(name="porcentaje", type="boolean")
     */	
	private $porcentaje=0;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="importe", type="decimal", precision=15, scale=4)
     */
    private $importe=0;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="codigoCalculo", type="integer")
     */
    private $codigoCalculo;
	
	 /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=10)
     */
    private $unidad;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="codigoObservacion", type="smallint", nullable=true)
	 * 
	 * Para liquidar en lotes, es el codigo del reloj
     */
    private $codigoObservacion;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="cuentaAsistencia", type="boolean")
     */	
	private $cuentaAsistencia=0;
	
	public function __construct()
	{
		$this->personal = new ArrayCollection();
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return CodigoSueldos
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set remunerativo
     *
     * @param boolean $remunerativo
     *
     * @return CodigoSueldos
     */
    public function setRemunerativo($remunerativo)
    {
        $this->remunerativo = $remunerativo;

        return $this;
    }

    /**
     * Get remunerativo
     *
     * @return boolean
     */
    public function getRemunerativo()
    {
        return $this->remunerativo;
    }

    /**
     * Set descuento
     *
     * @param boolean $descuento
     *
     * @return CodigoSueldos
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return boolean
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set asignacion
     *
     * @param boolean $asignacion
     *
     * @return CodigoSueldos
     */
    public function setAsignacion($asignacion)
    {
        $this->asignacion = $asignacion;

        return $this;
    }

    /**
     * Get asignacion
     *
     * @return boolean
     */
    public function getAsignacion()
    {
        return $this->asignacion;
    }
    
    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return CodigoSueldos
     */
    public function setInactivo($inactivo)
    {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return boolean
     */
    public function getInactivo()
    {
        return $this->inactivo;
    }

    /**
     * Set noRemunerativo
     *
     * @param boolean $noRemunerativo
     *
     * @return CodigoSueldos
     */
    public function setNoRemunerativo($noRemunerativo)
    {
        $this->noRemunerativo = $noRemunerativo;

        return $this;
    }

    /**
     * Get noRemunerativo
     *
     * @return boolean
     */
    public function getNoRemunerativo()
    {
        return $this->noRemunerativo;
    }

    /**
     * Set personal
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $personal
     *
     * @return CodigoSueldos
     */
    public function setPersonal(\Mbp\PersonalBundle\Entity\Personal $personal = null)
    {
        $this->personal = $personal;

        return $this;
    }

    /**
     * Get personal
     *
     * @return \Mbp\PersonalBundle\Entity\Personal
     */
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Set importe
     *
     * @param string $importe
     *
     * @return CodigoSueldos
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
     * Set porcentaje
     *
     * @param boolean $porcentaje
     *
     * @return CodigoSueldos
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return boolean
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set fijo
     *
     * @param boolean $fijo
     *
     * @return CodigoSueldos
     */
    public function setFijo($fijo)
    {
        $this->fijo = $fijo;

        return $this;
    }

    /**
     * Get fijo
     *
     * @return boolean
     */
    public function getFijo()
    {
        return $this->fijo;
    }

    /**
     * Set codigoCalculo
     *
     * @param integer $codigoCalculo
     *
     * @return CodigoSueldos
     */
    public function setCodigoCalculo($codigoCalculo)
    {
        $this->codigoCalculo = $codigoCalculo;

        return $this;
    }

    /**
     * Get codigoCalculo
     *
     * @return integer
     */
    public function getCodigoCalculo()
    {
        return $this->codigoCalculo;
    }

    /**
     * Add periodo
     *
     * @param \Mbp\PersonalBundle\Entity\Periodos $periodo
     *
     * @return CodigoSueldos
     */
    public function addPeriodo(\Mbp\PersonalBundle\Entity\Periodos $periodo)
    {
        $this->periodo[] = $periodo;

        return $this;
    }

    /**
     * Remove periodo
     *
     * @param \Mbp\PersonalBundle\Entity\Periodos $periodo
     */
    public function removePeriodo(\Mbp\PersonalBundle\Entity\Periodos $periodo)
    {
        $this->periodo->removeElement($periodo);
    }

    /**
     * Get periodo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set unidad
     *
     * @param string $unidad
     *
     * @return CodigoSueldos
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * Get unidad
     *
     * @return string
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set codigoObservacion
     *
     * @param integer $codigoObservacion
     *
     * @return CodigoSueldos
     */
    public function setCodigoObservacion($codigoObservacion)
    {
        $this->codigoObservacion = $codigoObservacion;

        return $this;
    }

    /**
     * Get codigoObservacion
     *
     * @return integer
     */
    public function getCodigoObservacion()
    {
        return $this->codigoObservacion;
    }

    /**
     * Set cuentaAsistencia
     *
     * @param boolean $cuentaAsistencia
     *
     * @return CodigoSueldos
     */
    public function setCuentaAsistencia($cuentaAsistencia)
    {
        $this->cuentaAsistencia = $cuentaAsistencia;

        return $this;
    }

    /**
     * Get cuentaAsistencia
     *
     * @return boolean
     */
    public function getCuentaAsistencia()
    {
        return $this->cuentaAsistencia;
    }
}

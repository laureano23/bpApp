<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Personal
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\PersonalRepository")
 */
class Personal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idP", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idP;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50, nullable=false)
     */
    private $nombre;
	
	/**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=50, nullable=true)
     */
    private $apellido;
	
	/**
	 * @var \Mbp\ProduccionBundle\Entity\Sectores
	 * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Sectores", inversedBy="personal")
	 * @ORM\JoinColumn(name="sector", referencedColumnName="id")
	 * @Assert\NotBlank()
	 */
	private $sector;
	
	/**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=100, nullable=false)
	 * @Assert\NotBlank()
     */
    private $direccion;
	
	/**
     * @var \Mbp\PersonalBundle\Entity\Localidades
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Localidades")
	 * @ORM\JoinColumn(name="localidad", referencedColumnName="id")
     */
    private $localidad;
	
	/**
     * @var string
     *
     * @ORM\Column(name="telefonos", type="string", length=50)
     */
    private $telefonos;
	
	/**
     * @var string
     *
     * @ORM\Column(name="cPostal", type="string", length=10, nullable=true)
     */
    private $cPostal;
	
	/**
     * @var string
     *
     * @ORM\Column(name="documentoTipo", type="string", length=20, nullable=false)
	 * @Assert\NotBlank()
     */
    private $documentoTipo;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="documentoNum", type="integer", nullable=false, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Range(
     *      min = 7000000,
     *      max = 100000000,
     * )
     */
    private $documentoNum;
	
	/**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=20, nullable=true)
	 * @Assert\Choice(
	 * 		choices={"Soltero/a", "Casado/a", "Divorciado/a", "Union de hecho", "Viudo/a"}
	 * )
     */
    private $estado;
	
	/**
     * @var \Mbp\PersonalBundle\Entity\Categorias
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Categorias")
	 * @ORM\JoinColumn(name="categoria", referencedColumnName="id", nullable=false)	 
     */
    private $categoria;
	
	/**
     * @var date
     *
     * @ORM\Column(name="fechaIngreso", type="date", nullable=false)
	 * @Assert\Date()
     */
    private $fechaIngreso;
	
	/**
     * @var date
     *
     * @ORM\Column(name="fechaEgreso", type="date", nullable=true)
	 * @Assert\Date()
     */
    private $fechaEgreso;
	
	/**
     * @var date
     *
     * @ORM\Column(name="fechaNacimiento", type="date", nullable=true)
	 * @Assert\Date()
     */
    private $fechaNacimiento;
	
	/**
     * @var string
     *
     * @ORM\Column(name="obraSocial", type="string", length=15, nullable=true)
     */
    private $obraSocial;
    
	/**
     * @var string
     *
     * @ORM\Column(name="nacionalidad", type="string", length=20, nullable=true)
     */
    private $nacionalidad;
	
	/**
     * @var string
     *
     * @ORM\Column(name="tarea", type="string", length=30, nullable=true)
     */
    private $tarea;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="periodo", type="integer", nullable=false)
     */
    private $periodo;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="compensatorio", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $compensatorio;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo = 0;
	
	/**
	 * @ORM\OneToMany(targetEntity="\Mbp\PersonalBundle\Entity\PersonalConceptosSueldo", mappedBy="personal_id")
	 * 
	 */
	private $datosFijos;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="cuil", type="bigint", nullable=true)
	 * @Assert\Type(
     *     type="integer"
     * )
     */
    private $cuil;
	
	/**
     * @var string
     *
     * @ORM\Column(name="tipoContratacion", type="string", length=40, nullable=true)
     */
    private $tipoContratacion;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="antiguedad", type="boolean")
	 * @Assert\Type(
     *     type="bool"
     * )
     */
    private $antiguedad = 1;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="antPorcentaje", type="smallint")
	 * @Assert\Range(
     *      min = 0,
     *      max = 99
     * )
     */
    private $antPorcentaje = 0;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="tallePantalon", type="smallint", nullable=true)
	 * @Assert\Range(
     *      min = 20,
     *      max = 99
     * )
     */
    private $tallePantalon;
    
    /**
     * @var smallint
     *
     * @ORM\Column(name="talleCamisa", type="smallint", nullable=true)
	 * @Assert\Range(
     *      min = 20,
     *      max = 99
     * )
     */
    private $talleCamisa;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="talleCalzado", type="smallint", nullable=true)
	 * @Assert\Range(
     *      min = 20,
     *      max = 99
     * )
     */
    private $talleCalzado;
	
	/**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=250, nullable=false)
     */
    private $observaciones;
	
	/**
     * @var smallint
     *
     * @ORM\Column(name="legajo", type="smallint", nullable=true)
	 * 
	 * Es el legajo del reloj para liquidaciones por lote
     */
    private $legajo;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="liquidaPorLote", type="boolean", nullable=true)
	 * @Assert\Type(
     *     type="bool"
     * )
     */
    private $liquidaPorLote = false;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="liquidaPremio", type="boolean", nullable=false)
	 * @Assert\Type(
     *     type="bool"
     * )
     */
    private $liquidaPremio = false;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="liquidaCalorias", type="boolean", nullable=false)
	 * @Assert\Type(
     *     type="bool"
     * )
     */
    private $liquidaCalorias = false;
	
    
    public function __construct()
    {
        $this->datosFijos = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getIdP()
    {
        return $this->idP;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Personal
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
	
	/**
     * Set sector
     *
     * @param \Mbp\ProduccionBundle\Entity\Sectores sector
     * @return Personal
     */
    public function setSector($sector)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \Mbp\ProduccionBundle\Entity\Sectores 
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Personal
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefonos
     *
     * @param string $telefonos
     *
     * @return Personal
     */
    public function setTelefonos($telefonos)
    {
        $this->telefonos = $telefonos;

        return $this;
    }

    /**
     * Get telefonos
     *
     * @return string
     */
    public function getTelefonos()
    {
        return $this->telefonos;
    }

    /**
     * Set cPostal
     *
     * @param string $cPostal
     *
     * @return Personal
     */
    public function setCPostal($cPostal)
    {
        $this->cPostal = $cPostal;

        return $this;
    }

    /**
     * Get cPostal
     *
     * @return string
     */
    public function getCPostal()
    {
        return $this->cPostal;
    }

    /**
     * Set documentoTipo
     *
     * @param string $documentoTipo
     *
     * @return Personal
     */
    public function setDocumentoTipo($documentoTipo)
    {
        $this->documentoTipo = $documentoTipo;

        return $this;
    }

    /**
     * Get documentoTipo
     *
     * @return string
     */
    public function getDocumentoTipo()
    {
        return $this->documentoTipo;
    }

    /**
     * Set documentoNum
     *
     * @param integer $documentoNum
     *
     * @return Personal
     */
    public function setDocumentoNum($documentoNum)
    {
        $this->documentoNum = $documentoNum;

        return $this;
    }

    /**
     * Get documentoNum
     *
     * @return integer
     */
    public function getDocumentoNum()
    {
        return $this->documentoNum;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Personal
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Personal
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set fechaEgreso
     *
     * @param \DateTime $fechaEgreso
     *
     * @return Personal
     */
    public function setFechaEgreso($fechaEgreso)
    {
        $this->fechaEgreso = $fechaEgreso;

        return $this;
    }

    /**
     * Get fechaEgreso
     *
     * @return \DateTime
     */
    public function getFechaEgreso()
    {
        return $this->fechaEgreso;
    }

    /**
     * Set tarea
     *
     * @param string $tarea
     *
     * @return Personal
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
     * Set periodo
     *
     * @param integer $periodo
     *
     * @return Personal
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
     * Set compensatorio
     *
     * @param string $compensatorio
     *
     * @return Personal
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
     * Set localidad
     *
     * @param \Mbp\PersonalBundle\Entity\Localidades $localidad
     *
     * @return Personal
     */
    public function setLocalidad(\Mbp\PersonalBundle\Entity\Localidades $localidad = null)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return \Mbp\PersonalBundle\Entity\Localidades
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set categoria
     *
     * @param \Mbp\PersonalBundle\Entity\Categorias $categoria
     *
     * @return Personal
     */
    public function setCategoria(\Mbp\PersonalBundle\Entity\Categorias $categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Mbp\PersonalBundle\Entity\Categorias
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
	
	/**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Personal
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
     * Add datosFijo
     *
     * @param \Mbp\PersonalBundle\Entity\CodigoSueldos $datosFijo
     *
     * @return Personal
     */
    public function addDatosFijo(\Mbp\PersonalBundle\Entity\CodigoSueldos $datosFijo)
    {
        $this->datosFijos[] = $datosFijo;

        return $this;
    }

    /**
     * Remove datosFijo
     *
     * @param \Mbp\PersonalBundle\Entity\CodigoSueldos $datosFijo
     */
    public function removeDatosFijo(\Mbp\PersonalBundle\Entity\CodigoSueldos $datosFijo)
    {
        $this->datosFijos->removeElement($datosFijo);
    }

    /**
     * Get datosFijos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatosFijos()
    {
        return $this->datosFijos;
    }

    /**
     * Set cuil
     *
     * @param integer $cuil
     *
     * @return Personal
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;

        return $this;
    }

    /**
     * Get cuil
     *
     * @return integer
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * Set tipoContratacion
     *
     * @param string $tipoContratacion
     *
     * @return Personal
     */
    public function setTipoContratacion($tipoContratacion)
    {
        $this->tipoContratacion = $tipoContratacion;

        return $this;
    }

    /**
     * Get tipoContratacion
     *
     * @return string
     */
    public function getTipoContratacion()
    {
        return $this->tipoContratacion;
    }

    /**
     * Set antiguedad
     *
     * @param boolean $antiguedad
     *
     * @return Personal
     */
    public function setAntiguedad($antiguedad)
    {
        $this->antiguedad = $antiguedad;

        return $this;
    }

    /**
     * Get antiguedad
     *
     * @return boolean
     */
    public function getAntiguedad()
    {
        return $this->antiguedad;
    }

    /**
     * Set antPorcentaje
     *
     * @param integer $antPorcentaje
     *
     * @return Personal
     */
    public function setAntPorcentaje($antPorcentaje)
    {
        $this->antPorcentaje = $antPorcentaje;

        return $this;
    }

    /**
     * Get antPorcentaje
     *
     * @return integer
     */
    public function getAntPorcentaje()
    {
        return $this->antPorcentaje;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Personal
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set obraSocial
     *
     * @param string $obraSocial
     *
     * @return Personal
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

    /**
     * Set nacionalidad
     *
     * @param string $nacionalidad
     *
     * @return Personal
     */
    public function setNacionalidad($nacionalidad)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return string
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }
	
	/**
     * Get sueldo blanco
     *
     * @return string
     */
    public function getSueldoBlanco()
    {
        $categoria = $this->getCategoria();
		return $categoria->getSalario();
    }

    /**
     * Set tallePantalon
     *
     * @param integer $tallePantalon
     *
     * @return Personal
     */
    public function setTallePantalon($tallePantalon)
    {
        $this->tallePantalon = $tallePantalon;

        return $this;
    }

    /**
     * Get tallePantalon
     *
     * @return integer
     */
    public function getTallePantalon()
    {
        return $this->tallePantalon;
    }

    /**
     * Set talleCamisa
     *
     * @param integer $talleCamisa
     *
     * @return Personal
     */
    public function setTalleCamisa($talleCamisa)
    {
        $this->talleCamisa = $talleCamisa;

        return $this;
    }

    /**
     * Get talleCamisa
     *
     * @return integer
     */
    public function getTalleCamisa()
    {
        return $this->talleCamisa;
    }

    /**
     * Set talleCalzado
     *
     * @param integer $talleCalzado
     *
     * @return Personal
     */
    public function setTalleCalzado($talleCalzado)
    {
        $this->talleCalzado = $talleCalzado;

        return $this;
    }

    /**
     * Get talleCalzado
     *
     * @return integer
     */
    public function getTalleCalzado()
    {
        return $this->talleCalzado;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Personal
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
     * Set legajo
     *
     * @param integer $legajo
     *
     * @return Personal
     */
    public function setLegajo($legajo)
    {
        $this->legajo = $legajo;

        return $this;
    }

    /**
     * Get legajo
     *
     * @return integer
     */
    public function getLegajo()
    {
        return $this->legajo;
    }

    /**
     * Set liquidaPorLote
     *
     * @param boolean $liquidaPorLote
     *
     * @return Personal
     */
    public function setLiquidaPorLote($liquidaPorLote)
    {
        $this->liquidaPorLote = $liquidaPorLote;

        return $this;
    }

    /**
     * Get liquidaPorLote
     *
     * @return boolean
     */
    public function getLiquidaPorLote()
    {
        return $this->liquidaPorLote;
    }

    /**
     * Set liquidaCalorias
     *
     * @param boolean $liquidaCalorias
     *
     * @return Personal
     */
    public function setLiquidaCalorias($liquidaCalorias)
    {
        $this->liquidaCalorias = $liquidaCalorias;

        return $this;
    }

    /**
     * Get liquidaCalorias
     *
     * @return boolean
     */
    public function getLiquidaCalorias()
    {
        return $this->liquidaCalorias;
    }

    /**
     * Set liquidaPremio
     *
     * @param boolean $liquidaPremio
     *
     * @return Personal
     */
    public function setLiquidaPremio($liquidaPremio)
    {
        $this->liquidaPremio = $liquidaPremio;

        return $this;
    }

    /**
     * Get liquidaPremio
     *
     * @return boolean
     */
    public function getLiquidaPremio()
    {
        return $this->liquidaPremio;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Personal
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }
}

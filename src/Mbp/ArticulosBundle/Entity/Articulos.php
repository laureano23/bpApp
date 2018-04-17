<?php
namespace Mbp\ArticulosBundle\Entity; 

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Articulos
 *
 * @ORM\Table(name="articulos")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\ArticulosRepository")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
class Articulos
{
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=50, nullable=false, unique=true)
	 * @Assert\NotNull()
	 * @Assert\Length(
     *      max = 50
	 * )
     */
    private $codigo;
 
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=250, nullable=false)
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 * @Assert\Length(
     *      max = 250
	 * )
     */
    private $descripcion;
	
	/**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=25, nullable=true)
	 * @Assert\NotNull()
	 * @Assert\Length(
     *      max = 25
	 * )
     */
    private $unidad;

    /**
     * @var string 
     *
     * @ORM\Column(name="presentacion", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50
     * )
     */
    private $presentacion;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="costo", type="decimal", precision=11, scale=4, nullable=false)
	 * @Assert\Range(
     *      min = 0,
	 * )
     */
    private $costo=0;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="peso", type="decimal", precision=11, scale=4, nullable=true)
	 * @Assert\Range(
     *      min = 0,
	 * )
     */
    private $peso=0;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="iva", type="decimal", precision=4, scale=2, nullable=true)
	 * @Assert\Range(
     *      min = 0,
	 * 		max = 100
	 * )
     */
    private $iva;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="moneda", type="boolean")
	 * @Assert\Range(
     *      min = 0,
     *      max = 1
	 * )
     */
    private $moneda=0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="monedaPrecio", type="boolean", nullable=true)
	 * @Assert\Range(
     *      min = 0,
     *      max = 1
	 * )
     */
    private $monedaPrecio=0;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="precio", type="decimal", precision=11, scale=4, nullable=true)
	 * @Assert\Range(
     *      min = 0,
	 * )
     */
    private $precio=0;

    /**
     * @var decimal
     *
     * @ORM\Column(name="utilidadPretendida", type="decimal", precision=6, scale=2, nullable=true)
     */
    private $utilidadPretendida=0;

    /**
     * @var date
     *
     * @ORM\Column(name="fechaPrecio", type="date", nullable=true)
     * @Assert\DateTime()
     */
    private $fechaPrecio; 
 
    /** 
     * @var integer
     *
     * @ORM\Column(name="idArticulos", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
	
	/** 
     * @var \Mbp\ArticulosBundle\Entity\Familia
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Familia")
     * @ORM\JoinColumn(name="familiaId", referencedColumnName="id", unique=false, nullable=true)	 
     */
    private $familiaId;
	
	/**
     * @var \Mbp\ArticulosBundle\Entity\SubFamilia
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\SubFamilia")
     * @ORM\JoinColumn(name="subFamiliaId", referencedColumnName="id", unique=false, nullable=true)	 
     */
    private $subFamiliaId;

    /**
     * @var decimal
     *
     * @ORM\Column(name="stock", type="decimal", precision=11, scale=2, nullable=false)
     */
    private $stock=0;
	
	/**
     * @var string
     *
     * @ORM\Column(name="nombreImagen", type="string", nullable=true, length=250,)
     */
    private $nombreImagen=null;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean", nullable=false)
     */
    private $inactivo=0;
	
	/**
     * @var text
     *
     * @ORM\Column(name="rutaServer", type="text", nullable=true)
     */
    private $rutaServer;


    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Articulos
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Articulos
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
     * Set unidad
     *
     * @param string $unidad
     * @return Articulos
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
     * Set costo
     *
     * @param float $costo
     * @return Articulos
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Get id
     *
     * @return float 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set moneda
     *
     * @param boolean $moneda
     *
     * @return Articulos
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get monedaPrecio
     *
     * @return boolean
     */
    public function getMonedaPrecio()
    {
        return $this->monedaPrecio;
    }
	
	/**
     * Set monedaPrecio
     *
     * @param boolean $monedaPrecio
     *
     * @return Articulos
     */
    public function setMonedaPrecio($monedaPrecio)
    {
        $this->monedaPrecio = $monedaPrecio;

        return $this;
    }

    /**
     * Get moneda
     *
     * @return boolean
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return Articulos
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set familiaId
     *
     * @param \Mbp\ArticulosBundle\Entity\Familia $familiaId
     *
     * @return Articulos
     */
    public function setFamiliaId(\Mbp\ArticulosBundle\Entity\Familia $familiaId = null)
    {
        $this->familiaId = $familiaId;

        return $this;
    }

    /**
     * Get familiaId
     *
     * @return \Mbp\ArticulosBundle\Entity\Familia
     */
    public function getFamiliaId()
    {
        return $this->familiaId;
    }

    /**
     * Set subFamiliaId
     *
     * @param \Mbp\ArticulosBundle\Entity\SubFamilia $subFamiliaId
     *
     * @return Articulos
     */
    public function setSubFamiliaId(\Mbp\ArticulosBundle\Entity\SubFamilia $subFamiliaId = null)
    {
        $this->subFamiliaId = $subFamiliaId;

        return $this;
    }

    /**
     * Get subFamiliaId
     *
     * @return \Mbp\ArticulosBundle\Entity\SubFamilia
     */
    public function getSubFamiliaId()
    {
        return $this->subFamiliaId;
    }

    /**
     * Set iva
     *
     * @param string $iva
     *
     * @return Articulos
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return string
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set stock
     *
     * @param string $stock
     *
     * @return Articulos
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return string
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set fechaPrecio
     *
     * @param \DateTime $fechaPrecio
     *
     * @return Articulos
     */
    public function setFechaPrecio($fechaPrecio)
    {
        $this->fechaPrecio = $fechaPrecio;

        return $this;
    }

    /**
     * Get fechaPrecio
     *
     * @return \DateTime
     */
    public function getFechaPrecio()
    {
        return $this->fechaPrecio;
    }

    /**
     * Set presentacion
     *
     * @param string $presentacion
     *
     * @return Articulos
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = $presentacion;

        return $this;
    }

    /**
     * Get presentacion
     *
     * @return string
     */
    public function getPresentacion()
    {
        return $this->presentacion;
    }

    /**
     * Set utilidadPretendida
     *
     * @param string $utilidadPretendida
     *
     * @return Articulos
     */
    public function setUtilidadPretendida($utilidadPretendida)
    {
        $this->utilidadPretendida = $utilidadPretendida;

        return $this;
    }

    /**
     * Get utilidadPretendida
     *
     * @return string
     */
    public function getUtilidadPretendida()
    {
        return $this->utilidadPretendida;
    }

    /**
     * Set nombreImagen
     *
     * @param string $nombreImagen
     *
     * @return Articulos
     */
    public function setNombreImagen($nombreImagen)
    {
        $this->nombreImagen = $nombreImagen;

        return $this;
    }

    /**
     * Get nombreImagen
     *
     * @return string
     */
    public function getNombreImagen()
    {
        return $this->nombreImagen;
    }

    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Articulos
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
     * Set rutaServer
     *
     * @param string $rutaServer
     *
     * @return Articulos
     */
    public function setRutaServer($rutaServer)
    {
        $this->rutaServer = $rutaServer;

        return $this;
    }

    /**
     * Get rutaServer
     *
     * @return string
     */
    public function getRutaServer()
    {
        return $this->rutaServer;
    }

    /**
     * Set peso
     *
     * @param string $peso
     *
     * @return Articulos
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return string
     */
    public function getPeso()
    {
        return $this->peso;
    }
}

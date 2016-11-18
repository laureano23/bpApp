<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maquinas
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\MaquinasRepository")
 */
class Maquinas
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
     * @ORM\Column(name="descripcion", type="string", length=100)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=30, nullable=true)
     */
    private $marca;

    /**
     * @var string
     *
     * @ORM\Column(name="modelo", type="string", length=30, nullable=true)
     */
    private $modelo;

    /**
     * @var integer
     *
     * @ORM\Column(name="peso", type="integer", nullable=true)
     */
    private $peso;

    /**
     * @var \Mbp\ProduccionBundle\Entity\Sectores
	 * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Sectores", inversedBy="maquinarias")
	 * @ORM\JoinColumn(name="sector", referencedColumnName="id")
     */
    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="piso", type="string", length=5, nullable=true)
     */
    private $piso;

    /**
     * @var integer
     *
     * @ORM\Column(name="nave", type="smallint", nullable=true)
     */
    private $nave;

    /**
     * @var string
     *
     * @ORM\Column(name="origen", type="string", length=50, nullable=true)
     */
    private $origen;

    /**
     * @var integer
     *
     * @ORM\Column(name="anoOrigen", type="smallint", nullable=true)
     */
    private $anoOrigen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="anoCompra", type="date", nullable=true)
     */
    private $anoCompra;

    /**
     * @var string
     *
     * @ORM\Column(name="valorCompra", type="decimal", nullable=true)
     */
    private $valorCompra;

    /**
     * @var integer
     *
     * @ORM\Column(name="vidaUtil", type="smallint", nullable=true)
     */
    private $vidaUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="criticidad", type="string", length=2, nullable=true)
     */
    private $criticidad;

    /**
     * @var string
     *
     * @ORM\Column(name="notas", type="string", length=255, nullable=true)
     */
    private $notas;


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
     * @return Maquinas
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
     * Set marca
     *
     * @param string $marca
     *
     * @return Maquinas
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string $modelo
     *
     * @return Maquinas
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set peso
     *
     * @param integer $peso
     *
     * @return Maquinas
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return integer
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set sector
     *
     * @param \Mbp\ProduccionBundle\Entity\Sectores sectores
	 * @return maquinas
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
     * Set piso
     *
     * @param string $piso
     *
     * @return Maquinas
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get piso
     *
     * @return string
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set nave
     *
     * @param integer $nave
     *
     * @return Maquinas
     */
    public function setNave($nave)
    {
        $this->nave = $nave;

        return $this;
    }

    /**
     * Get nave
     *
     * @return integer
     */
    public function getNave()
    {
        return $this->nave;
    }

    /**
     * Set origen
     *
     * @param string $origen
     *
     * @return Maquinas
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;

        return $this;
    }

    /**
     * Get origen
     *
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set anoOrigen
     *
     * @param integer $anoOrigen
     *
     * @return Maquinas
     */
    public function setAnoOrigen($anoOrigen)
    {
        $this->anoOrigen = $anoOrigen;

        return $this;
    }

    /**
     * Get anoOrigen
     *
     * @return integer
     */
    public function getAnoOrigen()
    {
        return $this->anoOrigen;
    }

    /**
     * Set anoCompra
     *
     * @param \DateTime $anoCompra
     *
     * @return Maquinas
     */
    public function setAnoCompra($anoCompra)
    {
        $this->anoCompra = $anoCompra;

        return $this;
    }

    /**
     * Get anoCompra
     *
     * @return \DateTime
     */
    public function getAnoCompra()
    {
        return $this->anoCompra;
    }

    /**
     * Set valorCompra
     *
     * @param string $valorCompra
     *
     * @return Maquinas
     */
    public function setValorCompra($valorCompra)
    {
        $this->valorCompra = $valorCompra;

        return $this;
    }

    /**
     * Get valorCompra
     *
     * @return string
     */
    public function getValorCompra()
    {
        return $this->valorCompra;
    }

    /**
     * Set vidaUtil
     *
     * @param integer $vidaUtil
     *
     * @return Maquinas
     */
    public function setVidaUtil($vidaUtil)
    {
        $this->vidaUtil = $vidaUtil;

        return $this;
    }

    /**
     * Get vidaUtil
     *
     * @return integer
     */
    public function getVidaUtil()
    {
        return $this->vidaUtil;
    }

    /**
     * Set criticidad
     *
     * @param string $criticidad
     *
     * @return Maquinas
     */
    public function setCriticidad($criticidad)
    {
        $this->criticidad = $criticidad;

        return $this;
    }

    /**
     * Get criticidad
     *
     * @return string
     */
    public function getCriticidad()
    {
        return $this->criticidad;
    }

    /**
     * Set notas
     *
     * @param string $notas
     *
     * @return Maquinas
     */
    public function setNotas($notas)
    {
        $this->notas = $notas;

        return $this;
    }

    /**
     * Get notas
     *
     * @return string
     */
    public function getNotas()
    {
        return $this->notas;
    }
}

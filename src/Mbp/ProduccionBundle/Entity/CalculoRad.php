<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalculoRad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\CalculoRadRepository")
 */
class CalculoRad
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
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
	 * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
	 * @ORM\JoinColumn(name="cod", referencedColumnName="idArticulos")
     */
    private $cod;

    /**
     * @var integer
     *
     * @ORM\Column(name="apoyoTapas", type="integer")
     */
    private $apoyoTapas;

    /**
     * @var integer
     *
     * @ORM\Column(name="prof", type="integer")
     */
    private $prof;

    /**
     * @var integer
     *
     * @ORM\Column(name="ancho", type="integer")
     */
    private $ancho;

    /**
     * @var boolean
     *
     * @ORM\Column(name="chapaPiso", type="boolean")
     */
    private $chapaPiso;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantAdic", type="integer")
     */
    private $cantAdic;

    /**
     * @var boolean
     *
     * @ORM\Column(name="perfilInt", type="boolean")
     */
    private $perfilInt;

    /**
     * @var string
     *
     *@ORM\Column(name="aletaTipo", type="string", length=10, nullable=false)
     */
    private $aletaTipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="pisosManual", type="integer")
     */
    private $pisosManual;

    /**
     * @var integer
     *
     * @ORM\Column(name="pisosManual7", type="integer")
     */
    private $pisosManual7;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo", type="boolean")
     */
    private $tipo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aletaFluA", type="boolean")
     */
    private $aletaFluA;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aletaVenA", type="boolean")
     */
    private $aletaVenA;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="maxAlt", type="integer")
     */
    private $maxAlt;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cantPaneles", type="integer")
     */
    private $cantPaneles;


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
     * Set cod
     *
     * @param string $cod
     * @return CalculoRad
     */
    public function setCod($cod)
    {
        $this->cod = $cod;

        return $this;
    }

    /**
     * Get cod
     *
     * @return string 
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * Set apoyoTapas
     *
     * @param integer $apoyoTapas
     * @return CalculoRad
     */
    public function setApoyoTapas($apoyoTapas)
    {
        $this->apoyoTapas = $apoyoTapas;

        return $this;
    }

    /**
     * Get apoyoTapas
     *
     * @return integer 
     */
    public function getApoyoTapas()
    {
        return $this->apoyoTapas;
    }

    /**
     * Set prof
     *
     * @param integer $prof
     * @return CalculoRad
     */
    public function setProf($prof)
    {
        $this->prof = $prof;

        return $this;
    }

    /**
     * Get prof
     *
     * @return integer 
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * Set ancho
     *
     * @param integer $ancho
     * @return CalculoRad
     */
    public function setAncho($ancho)
    {
        $this->ancho = $ancho;

        return $this;
    }

    /**
     * Get ancho
     *
     * @return integer 
     */
    public function getAncho()
    {
        return $this->ancho;
    }

    /**
     * Set chapaPiso
     *
     * @param boolean $chapaPiso
     * @return CalculoRad
     */
    public function setChapaPiso($chapaPiso)
    {
        $this->chapaPiso = $chapaPiso;

        return $this;
    }

    /**
     * Get chapaPiso
     *
     * @return boolean 
     */
    public function getChapaPiso()
    {
        return $this->chapaPiso;
    }

    /**
     * Set cantAdic
     *
     * @param integer $cantAdic
     * @return CalculoRad
     */
    public function setCantAdic($cantAdic)
    {
        $this->cantAdic = $cantAdic;

        return $this;
    }

    /**
     * Get cantAdic
     *
     * @return integer 
     */
    public function getCantAdic()
    {
        return $this->cantAdic;
    }

    /**
     * Set perfilInt
     *
     * @param boolean $perfilInt
     * @return CalculoRad
     */
    public function setPerfilInt($perfilInt)
    {
        $this->perfilInt = $perfilInt;

        return $this;
    }

    /**
     * Get perfilInt
     *
     * @return boolean 
     */
    public function getPerfilInt()
    {
        return $this->perfilInt;
    }

    /**
     * Set aletaTipo
     *
     * @param boolean $aletaTipo
     * @return CalculoRad
     */
    public function setAletaTipo($aletaTipo)
    {
        $this->aletaTipo = $aletaTipo;

        return $this;
    }

    /**
     * Get aletaTipo
     *
     * @return boolean 
     */
    public function getAletaTipo()
    {
        return $this->aletaTipo;
    }

    /**
     * Set pisosManual
     *
     * @param integer $pisosManual
     * @return CalculoRad
     */
    public function setPisosManual($pisosManual)
    {
        $this->pisosManual = $pisosManual;

        return $this;
    }

    /**
     * Get pisosManual
     *
     * @return integer 
     */
    public function getPisosManual()
    {
        return $this->pisosManual;
    }

    /**
     * Set pisosManual7
     *
     * @param integer $pisosManual7
     * @return CalculoRad
     */
    public function setPisosManual7($pisosManual7)
    {
        $this->pisosManual7 = $pisosManual7;

        return $this;
    }

    /**
     * Get pisosManual7
     *
     * @return integer 
     */
    public function getPisosManual7()
    {
        return $this->pisosManual7;
    }

    /**
     * Set tipo
     *
     * @param boolean $tipo
     * @return CalculoRad
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return boolean 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set aletaFluA
     *
     * @param boolean $aletaFluA
     * @return CalculoRad
     */
    public function setAletaFluA($aletaFluA)
    {
        $this->aletaFluA = $aletaFluA;

        return $this;
    }

    /**
     * Get aletaFluA
     *
     * @return boolean 
     */
    public function getAletaFluA()
    {
        return $this->aletaFluA;
    }

    /**
     * Set aletaVenA
     *
     * @param boolean $aletaVenA
     * @return CalculoRad
     */
    public function setAletaVenA($aletaVenA)
    {
        $this->aletaVenA = $aletaVenA;

        return $this;
    }

    /**
     * Get aletaVenA
     *
     * @return boolean 
     */
    public function getAletaVenA()
    {
        return $this->aletaVenA;
    }
    
    /**
     * Set maxAlt
     *
     * @param boolean $maxAlt
     * @return CalculoRad
     */
    public function setMaxAlt($maxAlt)
    {
        $this->maxAlt = $maxAlt;

        return $this;
    }

    /**
     * Get maxAlt
     *
     * @return boolean 
     */
    public function getMaxAlt()
    {
        return $this->maxAlt;
    }
    
    /**
     * Set cantPaneles
     *
     * @param boolean $cantPaneles
     * @return CalculoRad
     */
    public function setCantPaneles($cantPaneles)
    {
        $this->cantPaneles = $cantPaneles;

        return $this;
    }

    /**
     * Get cantPaneles
     *
     * @return boolean 
     */
    public function getcantPaneles()
    {
        return $this->cantPaneles;
    }
}

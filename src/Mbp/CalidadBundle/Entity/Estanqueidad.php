<?php

namespace Mbp\CalidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Estanqueidad
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\CalidadBundle\Entity\EstanqueidadRepository")
 */
class Estanqueidad
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
     * @var \Date
     *
     * @ORM\Column(name="fechaPrueba", type="date")
	 * @Assert\Date()
     */
    private $fechaPrueba;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProduccionBundle\Entity\Ot")
     * @ORM\JoinColumn(name="ot", referencedColumnName="ot", nullable=false)
     */
    private $ot;

    /**
     * @var integer
     *
     * @ORM\Column(name="pruebaNum", type="integer", unique=true)
     */
    private $pruebaNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;
		
    /**
     * @var boolean
     *
     * @ORM\Column(name="mChapa", type="boolean")
     */
    private $mChapa;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mBagueta", type="boolean")
     */
    private $mBagueta;
	
	 /**
     * @var boolean
     *
     * @ORM\Column(name="mAnulado", type="boolean")
     */
    private $mAnulado;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="mCiba", type="boolean")
     */
    private $mCiba;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mPerfil", type="boolean")
     */
    private $mPerfil;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mPisoDesp", type="boolean")
     */
    private $mPisoDesp;
	
	 /**
     * @var boolean
     *
     * @ORM\Column(name="mChapaColectora", type="boolean")
     */
    private $mChapaColectora;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tRosca", type="boolean")
     */
    private $tRosca;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tPoros", type="boolean")
     */
    private $tPoros;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="tConector", type="boolean")
     */
    private $tConector;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="tFijacion", type="boolean")
     */
    private $tFijacion;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="sConector", type="boolean")
     */
    private $sConector;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sTapaPanel", type="boolean")
     */
    private $sTapaPanel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sPlanchuelas", type="boolean")
     */
    private $sPlanchuelas;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="sPuntera", type="boolean")
     */
    private $sPuntera;

    /**
     * @var \Mbp\PersonalBundle\Entity\Personal
     * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Personal")
	 * @ORM\JoinColumns({
	 * 		@ORM\JoinColumn(name="soldador", referencedColumnName="idP", nullable=true)
	 * })     
     */
    private $soldador;

    /**
     * @var \Mbp\PersonalBundle\Entity\Personal
     * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Personal")
	 * @ORM\JoinColumns({
	 * 		@ORM\JoinColumn(name="probador", referencedColumnName="idP")
	 * })     
     */
    private $probador;
	
	/**
	 * @var integer
	 * 
	 * @ORM\Column(name="presion", type="integer")  
	 */
	 private $presion;


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
     * Set fechaPrueba
     *
     * @param \DateTime $fechaPrueba
     * @return Estanqueidad
     */
    public function setFechaPrueba($fechaPrueba)
    {
        $this->fechaPrueba = $fechaPrueba;

        return $this;
    }

    /**
     * Get fechaPrueba
     *
     * @return \DateTime 
     */
    public function getFechaPrueba()
    {
        return $this->fechaPrueba;
    }

    /**
     * Set ot
     *
     * @param integer $ot
     * @return Estanqueidad
     */
    public function setOt($ot)
    {
        $this->ot = $ot;

        return $this;
    }

    /**
     * Get ot
     *
     * @return integer 
     */
    public function getOt()
    {
        return $this->ot;
    }

    /**
     * Set pruebaNum
     *
     * @param integer $pruebaNum
     * @return Estanqueidad
     */
    public function setPruebaNum($pruebaNum)
    {
        $this->pruebaNum = $pruebaNum;

        return $this;
    }

    /**
     * Get pruebaNum
     *
     * @return integer 
     */
    public function getPruebaNum()
    {
        return $this->pruebaNum;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return Estanqueidad
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }
		
    /**
     * Set mChapa
     *
     * @param boolean $mChapa
     * @return Estanqueidad
     */
    public function setMChapa($mChapa)
    {
        $this->mChapa = $mChapa;

        return $this;
    }

    /**
     * Get mChapa
     *
     * @return boolean 
     */
    public function getMChapa()
    {
        return $this->mChapa;
    }

    /**
     * Set mBagueta
     *
     * @param boolean $mBagueta
     * @return Estanqueidad
     */
    public function setMBagueta($mBagueta)
    {
        $this->mBagueta = $mBagueta;

        return $this;
    }

    /**
     * Get mBagueta
     *
     * @return boolean 
     */
    public function getMBagueta()
    {
        return $this->mBagueta;
    }

    /**
     * Set mPerfil
     *
     * @param boolean $mPerfil
     * @return Estanqueidad
     */
    public function setMPerfil($mPerfil)
    {
        $this->mPerfil = $mPerfil;

        return $this;
    }

    /**
     * Get mPerfil
     *
     * @return boolean 
     */
    public function getMPerfil()
    {
        return $this->mPerfil;
    }

    /**
     * Set mPisoDesp
     *
     * @param boolean $mPisoDesp
     * @return Estanqueidad
     */
    public function setMPisoDesp($mPisoDesp)
    {
        $this->mPisoDesp = $mPisoDesp;

        return $this;
    }

    /**
     * Get mPisoDesp
     *
     * @return boolean 
     */
    public function getMPisoDesp()
    {
        return $this->mPisoDesp;
    }

    /**
     * Set tRosca
     *
     * @param boolean $tRosca
     * @return Estanqueidad
     */
    public function setTRosca($tRosca)
    {
        $this->tRosca = $tRosca;

        return $this;
    }

    /**
     * Get tRosca
     *
     * @return boolean 
     */
    public function getTRosca()
    {
        return $this->tRosca;
    }

    /**
     * Set tPoros
     *
     * @param boolean $tPoros
     * @return Estanqueidad
     */
    public function setTPoros($tPoros)
    {
        $this->tPoros = $tPoros;

        return $this;
    }

    /**
     * Get tPoros
     *
     * @return boolean 
     */
    public function getTPoros()
    {
        return $this->tPoros;
    }

    /**
     * Set sConector
     *
     * @param boolean $sConector
     * @return Estanqueidad
     */
    public function setSConector($sConector)
    {
        $this->sConector = $sConector;

        return $this;
    }

    /**
     * Get sConector
     *
     * @return boolean 
     */
    public function getSConector()
    {
        return $this->sConector;
    }

    /**
     * Set sTapaPanel
     *
     * @param boolean $sTapaPanel
     * @return Estanqueidad
     */
    public function setSTapaPanel($sTapaPanel)
    {
        $this->sTapaPanel = $sTapaPanel;

        return $this;
    }

    /**
     * Get sTapaPanel
     *
     * @return boolean 
     */
    public function getSTapaPanel()
    {
        return $this->sTapaPanel;
    }

    /**
     * Set sPlanchuelas
     *
     * @param boolean $sPlanchuelas
     * @return Estanqueidad
     */
    public function setSPlanchuelas($sPlanchuelas)
    {
        $this->sPlanchuelas = $sPlanchuelas;

        return $this;
    }

    /**
     * Get sPlanchuelas
     *
     * @return boolean 
     */
    public function getSPlanchuelas()
    {
        return $this->sPlanchuelas;
    }

    /**
     * Set soldador
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $soldador
     * @return Estanqueidad
     */
    public function setSoldador($soldador)
    {
        $this->soldador = $soldador;

        return $this;
    }

    /**
     * Get soldador
     *
     * @return \Mbp\PersonalBundle\Entity\Personal 
     */
    public function getSoldador()
    {
        return $this->soldador;
    }

    /**
     * Set probador
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $probador
     * @return Estanqueidad
     */
    public function setProbador($probador)
    {
        $this->probador = $probador;

        return $this;
    }

    /**
     * Get probador
     *
     * @return \Mbp\PersonalBundle\Entity\Personal 
     */
    public function getProbador()
    {
        return $this->probador;
    }
	
	/**
     * Set presion
     *
     * @param integer $presion
     * @return Estanqueidad
     */
    public function setPresion($presion)
    {
        $this->presion = $presion;

        return $this;
    }
	
	/**
	 * Get presion
	 * 
	 * @return integer
	 */
	public function getPresion()
    {
        return $this->presion;
    }

    /**
     * Set mAnulado
     *
     * @param boolean $mAnulado
     *
     * @return Estanqueidad
     */
    public function setMAnulado($mAnulado)
    {
        $this->mAnulado = $mAnulado;

        return $this;
    }

    /**
     * Get mAnulado
     *
     * @return boolean
     */
    public function getMAnulado()
    {
        return $this->mAnulado;
    }

    /**
     * Set mCiba
     *
     * @param boolean $mCiba
     *
     * @return Estanqueidad
     */
    public function setMCiba($mCiba)
    {
        $this->mCiba = $mCiba;

        return $this;
    }

    /**
     * Get mCiba
     *
     * @return boolean
     */
    public function getMCiba()
    {
        return $this->mCiba;
    }

    /**
     * Set tFijacion
     *
     * @param boolean $tFijacion
     *
     * @return Estanqueidad
     */
    public function setTFijacion($tFijacion)
    {
        $this->tFijacion = $tFijacion;

        return $this;
    }

    /**
     * Get tFijacion
     *
     * @return boolean
     */
    public function getTFijacion()
    {
        return $this->tFijacion;
    }

    /**
     * Set mChapaColectora
     *
     * @param boolean $mChapaColectora
     *
     * @return Estanqueidad
     */
    public function setMChapaColectora($mChapaColectora)
    {
        $this->mChapaColectora = $mChapaColectora;

        return $this;
    }

    /**
     * Get mChapaColectora
     *
     * @return boolean
     */
    public function getMChapaColectora()
    {
        return $this->mChapaColectora;
    }

    /**
     * Set sPuntera
     *
     * @param boolean $sPuntera
     *
     * @return Estanqueidad
     */
    public function setSPuntera($sPuntera)
    {
        $this->sPuntera = $sPuntera;

        return $this;
    }

    /**
     * Get sPuntera
     *
     * @return boolean
     */
    public function getSPuntera()
    {
        return $this->sPuntera;
    }

    /**
     * Set tConector
     *
     * @param boolean $tConector
     *
     * @return Estanqueidad
     */
    public function setTConector($tConector)
    {
        $this->tConector = $tConector;

        return $this;
    }

    /**
     * Get tConector
     *
     * @return boolean
     */
    public function getTConector()
    {
        return $this->tConector;
    }
}

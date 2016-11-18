<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Sectores
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\SectoresRepository")
 */
class Sectores
{
	public function __construct(){
		$this->personal = new ArrayCollection();
		$this->maquinarias = new ArrayCollection();
	}
	
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
     * @ORM\Column(name="costoMin", type="decimal")
     */
    private $costoMin;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=30)
     */
    private $descripcion;
	
	/**
     * @var \string
     *
     * @ORM\Column(name="tiempo", type="decimal")
     */
    private $tiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="piso", type="string", length=5)
     */
    private $piso;

    /**
     * @var string
     *
     * @ORM\Column(name="nave", type="string", length=5)
     */
    private $nave;

    /**  
	 *  @ORM\OneToMany(targetEntity="Mbp\PersonalBundle\Entity\Personal", mappedBy="sector")
     */
    private $personal;
	

    /**
	 * @ORM\OneToMany(targetEntity="Mbp\ProduccionBundle\Entity\Maquinas", mappedBy="sector")
     */
    private $maquinarias;
	
	
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
     * Set costoMin
     *
     * @param string $costoMin
     *
     * @return Sectores
     */
    public function setCostoMin($costoMin)
    {
        $this->costoMin = $costoMin;

        return $this;
    }

    /**
     * Get costoMin
     *
     * @return string
     */
    public function getCostoMin()
    {
        return $this->costoMin;
    }
	
	/**
     * Set tiempo
     *
     * @param string $tiempo
     *
     * @return Sectores
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return string
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Sectores
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
     * Set piso
     *
     * @param string $piso
     *
     * @return Sectores
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
     * @param string $nave
     *
     * @return Sectores
     */
    public function setNave($nave)
    {
        $this->nave = $nave;

        return $this;
    }

    /**
     * Get nave
     *
     * @return string
     */
    public function getNave()
    {
        return $this->nave;
    }

    /**
     * Set maquinarias
     *
     * @param \Mbp\ProduccionBundle\Entity\Maquinas maquinas
     * @return maquinas
     */
    public function setMaquinarias($maquinarias)
    {
        $this->maquinarias = $maquinarias;

        return $this;
    }

    /**
     * Get maquinarias
     *
     * @return \Mbp\ProduccionBundle\Entity\Maquinas
     */
    public function getMaquinarias()
    {
        return $this->maquinarias;
    }
	
	/**
     * Set personal
     *
     * @param \Mbp\PersonalBundle\Entity\Personal personal
     * @return Sectores
     */
    public function setPersonal($personal)
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
     * Add personal
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $personal
     *
     * @return Sectores
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
     * Add maquinaria
     *
     * @param \Mbp\ProduccionBundle\Entity\Maquinas $maquinaria
     *
     * @return Sectores
     */
    public function addMaquinaria(\Mbp\ProduccionBundle\Entity\Maquinas $maquinaria)
    {
        $this->maquinarias[] = $maquinaria;

        return $this;
    }

    /**
     * Remove maquinaria
     *
     * @param \Mbp\ProduccionBundle\Entity\Maquinas $maquinaria
     */
    public function removeMaquinaria(\Mbp\ProduccionBundle\Entity\Maquinas $maquinaria)
    {
        $this->maquinarias->removeElement($maquinaria);
    }
}

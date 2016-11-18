<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Brazing
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\BrazingRepository")
 */
class Brazing
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
     * @ORM\Column(name="tipoCarga", type="boolean")
     */
    private $tipoCarga;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempoCarga", type="time")
     */
    private $tiempoCarga;

    /**
     * @var integer
     *
     * @ORM\Column(name="ciclos", type="integer")
     */
    private $ciclos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="intervalos", type="time")
     */
    private $intervalos;

    /**
     * @var integer
     *
     * @ORM\Column(name="tclEnfSup", type="integer")
     */
    private $tclEnfSup;

    /**
     * @var integer
     *
     * @ORM\Column(name="tclPurgaInf", type="integer")
     */
    private $tclPurgaInf;

    /**
     * @var integer
     *
     * @ORM\Column(name="tclEnfInf", type="integer")
     */
    private $tclEnfInf;

    /**
     * @var integer
     *
     * @ORM\Column(name="tclPurgaSup", type="integer")
     */
    private $tclPurgaSup;

    /**
     * @var integer
     *
     * @ORM\Column(name="tPrecalentado", type="integer")
     */
    private $tPrecalentado;

    /**
     * @var integer
     *
     * @ORM\Column(name="caudalPrecamara", type="integer")
     */
    private $caudalPrecamara;

    /**
     * @var integer
     *
     * @ORM\Column(name="caudalHorno", type="integer")
     */
    private $caudalHorno;


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
     * Set tipoCarga
     *
     * @param boolean $tipoCarga
     * @return Brazing
     */
    public function setTipoCarga($tipoCarga)
    {
        $this->tipoCarga = $tipoCarga;

        return $this;
    }

    /**
     * Get tipoCarga
     *
     * @return boolean 
     */
    public function getTipoCarga()
    {
        return $this->tipoCarga;
    }

    /**
     * Set tiempoCarga
     *
     * @param \DateTime $tiempoCarga
     * @return Brazing
     */
    public function setTiempoCarga($tiempoCarga)
    {
        $this->tiempoCarga = $tiempoCarga;

        return $this;
    }

    /**
     * Get tiempoCarga
     *
     * @return \DateTime 
     */
    public function getTiempoCarga()
    {
        return $this->tiempoCarga;
    }
    
    /**
     * Set ciclos
     *
     * @param integer $ciclos
     * @return Brazing
     */
    public function setCiclos($ciclos)
    {
        $this->ciclos = $ciclos;

        return $this;
    }

    /**
     * Get ciclos
     *
     * @return integer 
     */
    public function getCiclos()
    {
        return $this->ciclos;
    }

    /**
     * Set intervalos
     *
     * @param \DateTime $intervalos
     * @return Brazing
     */
    public function setIntervalos($intervalos)
    {
        $this->intervalos = $intervalos;

        return $this;
    }

    /**
     * Get intervalos
     *
     * @return \DateTime 
     */
    public function getIntervalos()
    {
        return $this->intervalos;
    }

    /**
     * Set tclEnfSup
     *
     * @param integer $tclEnfSup
     * @return Brazing
     */
    public function setTclEnfSup($tclEnfSup)
    {
        $this->tclEnfSup = $tclEnfSup;

        return $this;
    }

    /**
     * Get tclEnfSup
     *
     * @return integer 
     */
    public function getTclEnfSup()
    {
        return $this->tclEnfSup;
    }

    /**
     * Set tclPurgaInf
     *
     * @param integer $tclPurgaInf
     * @return Brazing
     */
    public function setTclPurgaInf($tclPurgaInf)
    {
        $this->tclPurgaInf = $tclPurgaInf;

        return $this;
    }

    /**
     * Get tclPurgaInf
     *
     * @return integer 
     */
    public function getTclPurgaInf()
    {
        return $this->tclPurgaInf;
    }

    /**
     * Set tclEnfInf
     *
     * @param integer $tclEnfInf
     * @return Brazing
     */
    public function setTclEnfInf($tclEnfInf)
    {
        $this->tclEnfInf = $tclEnfInf;

        return $this;
    }

    /**
     * Get tclEnfInf
     *
     * @return integer 
     */
    public function getTclEnfInf()
    {
        return $this->tclEnfInf;
    }

    /**
     * Set tclPurgaSup
     *
     * @param integer $tclPurgaSup
     * @return Brazing
     */
    public function setTclPurgaSup($tclPurgaSup)
    {
        $this->tclPurgaSup = $tclPurgaSup;

        return $this;
    }

    /**
     * Get tclPurgaSup
     *
     * @return integer 
     */
    public function getTclPurgaSup()
    {
        return $this->tclPurgaSup;
    }

    /**
     * Set tPrecalentado
     *
     * @param integer $tPrecalentado
     * @return Brazing
     */
    public function setTPrecalentado($tPrecalentado)
    {
        $this->tPrecalentado = $tPrecalentado;

        return $this;
    }

    /**
     * Get tPrecalentado
     *
     * @return integer 
     */
    public function getTPrecalentado()
    {
        return $this->tPrecalentado;
    }

    /**
     * Set caudalPrecamara
     *
     * @param integer $caudalPrecamara
     * @return Brazing
     */
    public function setCaudalPrecamara($caudalPrecamara)
    {
        $this->caudalPrecamara = $caudalPrecamara;

        return $this;
    }

    /**
     * Get caudalPrecamara
     *
     * @return integer 
     */
    public function getCaudalPrecamara()
    {
        return $this->caudalPrecamara;
    }

    /**
     * Set caudalHorno
     *
     * @param integer $caudalHorno
     * @return Brazing
     */
    public function setCaudalHorno($caudalHorno)
    {
        $this->caudalHorno = $caudalHorno;

        return $this;
    }

    /**
     * Get caudalHorno
     *
     * @return integer 
     */
    public function getCaudalHorno()
    {
        return $this->caudalHorno;
    }
}

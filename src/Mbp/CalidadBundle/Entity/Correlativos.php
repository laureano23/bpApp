<?php

namespace Mbp\CalidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Correlativos
 *
 * @ORM\Table(name="correlativos")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Mbp\CalidadBundle\Entity\CorrelativosRepository")
 */
class Correlativos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="numCorrelativo", type="integer", nullable=false, unique=true)
     */
    private $numCorrelativo;    

    /**
     * @var integer
     *
     * @ORM\Column(name="cant", type="integer", nullable=false)
     */
    private $cant;

    /**
     * @var \Date
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="ot_enf", type="integer", nullable=false)
     */
    private $otEnf;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="ot1panel", type="integer", nullable=false)
     */
    private $ot1panel;

    /**
     * @var integer
     *
     * @ORM\Column(name="ot2panel", type="integer", nullable=true)
     */
    private $ot2panel;

    /**
     * @var integer
     *
     * @ORM\Column(name="ot3panel", type="integer", nullable=true)
     */
    private $ot3panel;

    /**
     * @var integer
     *
     * @ORM\Column(name="ot4panel", type="integer", nullable=true)
     */
    private $ot4panel;

    /**
     * @var string
     *
     * @ORM\Column(name="obs", type="text", nullable=true)
     */
    private $obs;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_correlativos", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCorrelativos;

   
    public function setNumEstanqueidad($numEstanqueidad)
    {
        $this->numEstanqueidad = $numEstanqueidad;

        return $this;
    }

    /**
     * Get numEstanqueidad
     *
     * @return integer 
     */
    public function getNumEstanqueidad()
    {
        return $this->numEstanqueidad;
    }

    /**
     * Set cant
     *
     * @param integer $cant
     * @return Correlativos
     */
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * Get cant
     *
     * @return integer 
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Correlativos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set otEnf
     *
     * @param integer $otEnf
     * @return Correlativos
     */
    public function setOtEnf($otEnf)
    {
        $this->otEnf = $otEnf;

        return $this;
    }

    /**
     * Get otEnf
     *
     * @return integer 
     */
    public function getOtEnf()
    {
        return $this->otEnf;
    }   
    
    public function setOt1panel($ot1panel)
    {
        $this->ot1panel = $ot1panel;

        return $this;
    }

    /**
     * Get ot1panel
     *
     * @return integer 
     */
    public function getOt1panel()
    {
        return $this->ot1panel;
    }

    /**
     * Set ot2panel
     *
     * @param integer $ot2panel
     * @return Correlativos
     */
    public function setOt2panel($ot2panel)
    {
        $this->ot2panel = $ot2panel;

        return $this;
    }

    /**
     * Get ot2panel
     *
     * @return integer 
     */
    public function getOt2panel()
    {
        return $this->ot2panel;
    }

    /**
     * Set ot3panel
     *
     * @param integer $ot3panel
     * @return Correlativos
     */
    public function setOt3panel($ot3panel)
    {
        $this->ot3panel = $ot3panel;

        return $this;
    }

    /**
     * Get ot3panel
     *
     * @return integer 
     */
    public function getOt3panel()
    {
        return $this->ot3panel;
    }

    /**
     * Set ot4panel
     *
     * @param integer $ot4panel
     * @return Correlativos
     */
    public function setOt4panel($ot4panel)
    {
        $this->ot4panel = $ot4panel;

        return $this;
    }

    /**
     * Get ot4panel
     *
     * @return integer 
     */
    public function getOt4panel()
    {
        return $this->ot4panel;
    }

    /**
     * Set obs
     *
     * @param string $obs
     * @return Correlativos
     */
    public function setObs($obs)
    {
        $this->obs = $obs;

        return $this;
    }

    /**
     * Get obs
     *
     * @return string 
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * Get idCorrelativos
     *
     * @return integer 
     */
    public function getIdCorrelativos()
    {
        return $this->idCorrelativos;
    }
    
	/**
     * Set numCorrelativo
     *
     * @param integer $numCorrelativo
     * @return Correlativos
     */
    public function setNumCorrelativo($numCorrelativo)
    {
        $this->numCorrelativo = $numCorrelativo;

        return $this;
    }

    /**
     * Get numCorrelativo
     *
     * @return integer 
     */
    public function getNumCorrelativo()
    {
        return $this->numCorrelativo;
    }
}

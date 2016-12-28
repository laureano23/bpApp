<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AplicacionesRadiadores
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\AplicacionesRadiadoresRepository")
 */
class AplicacionesRadiadores
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
     * @ORM\Column(name="aplicacion", type="string", length=100)
     */
    private $aplicacion;

     /**
     * @ORM\OneToMany(targetEntity="Mbp\WebBundle\Entity\RadiadoresComerciales", mappedBy="aplicacionId", cascade={"all"})
     */
    private $radiadoresComerciales;

    /**
     * @var \Mbp\WebBundle\Entity\TiposRadiadores
     *
     * @ORM\ManyToOne(targetEntity="Mbp\WebBundle\Entity\TiposRadiadores", inversedBy="aplicacionId")
     * @ORM\JoinColumn(name="tipoId", referencedColumnName="id") 
     */ 
    private $tipoId; 

    public function __construct()
    {
        $this->radiadoresComerciales = new ArrayCollection();   
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
     * Set aplicacion
     *
     * @param string $aplicacion
     *
     * @return AplicacionesRadiadores
     */
    public function setAplicacion($aplicacion)
    {
        $this->aplicacion = $aplicacion;

        return $this;
    }

    /**
     * Get aplicacion
     *
     * @return string
     */
    public function getAplicacion()
    {
        return $this->aplicacion;
    }
}


<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RadiadoresComerciales
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\RadiadoresComercialesRepository")
 */
class RadiadoresComerciales
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="oem", type="string", length=35)
     */
    private $oem;

    /**
     * @var string
     *
     * @ORM\Column(name="codigoBp", type="string", length=50)
     */
    private $codigoBp;

    /**
     * @var \Mbp\WebBundle\Entity\MarcasRadiadores
     *
     * @ORM\ManyToOne(targetEntity="Mbp\WebBundle\Entity\MarcasRadiadores", inversedBy="radiadoresComerciales")
     * @ORM\JoinColumn(name="marcaId", referencedColumnName="id") 
     */ 
    private $marcaId; 

    /**
     * @var \Mbp\WebBundle\Entity\AplicacionesRadiadores
     *
     * @ORM\ManyToOne(targetEntity="Mbp\WebBundle\Entity\AplicacionesRadiadores", inversedBy="radiadoresComerciales")
     * @ORM\JoinColumn(name="aplicacionId", referencedColumnName="id") 
     */ 
    private $aplicacionId; 

     /**
     * @var string
     *
     * @ORM\Column(name="imagenCatalogo", type="string", length=200)
     */
    private $imagenCatalogo;


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
     * @return RadiadoresComerciales
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
     * Set oem
     *
     * @param string $oem
     *
     * @return RadiadoresComerciales
     */
    public function setOem($oem)
    {
        $this->oem = $oem;

        return $this;
    }

    /**
     * Get oem
     *
     * @return string
     */
    public function getOem()
    {
        return $this->oem;
    }

    /**
     * Set codigoBp
     *
     * @param string $codigoBp
     *
     * @return RadiadoresComerciales
     */
    public function setCodigoBp($codigoBp)
    {
        $this->codigoBp = $codigoBp;

        return $this;
    }

    /**
     * Get codigoBp
     *
     * @return string
     */
    public function getCodigoBp()
    {
        return $this->codigoBp;
    }

    /**
     * Set imagenCatalogo
     *
     * @param string $imagenCatalogo
     *
     * @return RadiadoresComerciales
     */
    public function setImagenCatalogo($imagenCatalogo)
    {
        $this->imagenCatalogo = $imagenCatalogo;

        return $this;
    }

    /**
     * Get imagenCatalogo
     *
     * @return string
     */
    public function getImagenCatalogo()
    {
        return $this->imagenCatalogo;
    }

    /**
     * Set marcaId
     *
     * @param \Mbp\WebBundle\Entity\MarcasRadiadores $marcaId
     *
     * @return RadiadoresComerciales
     */
    public function setMarcaId(\Mbp\WebBundle\Entity\MarcasRadiadores $marcaId = null)
    {
        $this->marcaId = $marcaId;

        return $this;
    }

    /**
     * Get marcaId
     *
     * @return \Mbp\WebBundle\Entity\MarcasRadiadores
     */
    public function getMarcaId()
    {
        return $this->marcaId;
    }

    /**
     * Set aplicacionId
     *
     * @param \Mbp\WebBundle\Entity\AplicacionesRadiadores $aplicacionId
     *
     * @return RadiadoresComerciales
     */
    public function setAplicacionId(\Mbp\WebBundle\Entity\AplicacionesRadiadores $aplicacionId = null)
    {
        $this->aplicacionId = $aplicacionId;

        return $this;
    }

    /**
     * Get aplicacionId
     *
     * @return \Mbp\WebBundle\Entity\AplicacionesRadiadores
     */
    public function getAplicacionId()
    {
        return $this->aplicacionId;
    }
}

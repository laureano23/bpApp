<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MarcasRadiadores
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\MarcasRadiadoresRepository")
 */
class MarcasRadiadores
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
     * @ORM\Column(name="marca", type="string", length=75)
     */
    private $marca;

    /**
     * @ORM\OneToMany(targetEntity="Mbp\WebBundle\Entity\RadiadoresComerciales", mappedBy="marcaId", cascade={"all"})
     */
    private $radiadoresComerciales;

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
     * Set marca
     *
     * @param string $marca
     *
     * @return MarcasRadiadores
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
     * Add articulo
     *
     * @param \Mbp\WebBundle\Entity\Articulos $articulo
     *
     * @return MarcasRadiadores
     */
    public function addArticulo(\Mbp\WebBundle\Entity\Articulos $articulo)
    {
        $this->articulos[] = $articulo;

        return $this;
    }

    /**
     * Remove articulo
     *
     * @param \Mbp\WebBundle\Entity\Articulos $articulo
     */
    public function removeArticulo(\Mbp\WebBundle\Entity\Articulos $articulo)
    {
        $this->articulos->removeElement($articulo);
    }

    /**
     * Get articulos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticulos()
    {
        return $this->articulos;
    }
}

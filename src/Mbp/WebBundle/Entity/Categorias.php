<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Categorias
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\CategoriasRepository")
 */
class Categorias
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
     * @ORM\Column(name="descripcion", type="string", length=80)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="resenia", type="string", length=250)
     */
    private $resenia;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=250)
     */
    private $imagen;

    
	
	/**
     * @var \Mbp\WebBundle\Entity\SubCategoria
     *
     * @ORM\OneToMany(targetEntity="Mbp\WebBundle\Entity\SubCategoria", mappedBy="categoria", cascade={"all"})
     * @ORM\JoinColumn(name="subCategoriaId", referencedColumnName="id")
     */
    private $subCategoria; 

    public function __construct()
    {
		$this->subCategoria = new ArrayCollection();
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Categorias
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
     * Set resenia
     *
     * @param string $resenia
     *
     * @return Categorias
     */
    public function setResenia($resenia)
    {
        $this->resenia = $resenia;

        return $this;
    }

    /**
     * Get resenia
     *
     * @return string
     */
    public function getResenia()
    {
        return $this->resenia;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Categorias
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Add articulo
     *
     * @param \Mbp\WebBundle\Entity\Articulos $articulo
     *
     * @return Categorias
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

    /**
     * Add subCategorium
     *
     * @param \Mbp\WebBundle\Entity\SubCategoria $subCategorium
     *
     * @return Categorias
     */
    public function addSubCategorium(\Mbp\WebBundle\Entity\SubCategoria $subCategorium)
    {
        $this->subCategoria[] = $subCategorium;

        return $this;
    }

    /**
     * Remove subCategorium
     *
     * @param \Mbp\WebBundle\Entity\SubCategoria $subCategorium
     */
    public function removeSubCategorium(\Mbp\WebBundle\Entity\SubCategoria $subCategorium)
    {
        $this->subCategoria->removeElement($subCategorium);
    }

    /**
     * Get subCategoria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubCategoria()
    {
        return $this->subCategoria;
    }
}

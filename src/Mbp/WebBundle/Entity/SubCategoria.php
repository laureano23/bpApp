<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * SubCategoria
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\SubCategoriaRepository")
 */
class SubCategoria implements Translatable
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
     * @Gedmo\Translatable
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;
	
	/**
     * @var \Mbp\WebBundle\Entity\Categorias
     *
     * @ORM\ManyToOne(targetEntity="Mbp\WebBundle\Entity\Categorias", inversedBy="subCategoria")
     * @ORM\JoinColumn(name="categoria", referencedColumnName="id") 
     */
    private $categoria;  
	
	/**
     * @ORM\OneToMany(targetEntity="Mbp\WebBundle\Entity\Articulos", mappedBy="subCategoriaId", cascade={"all"})
     */
    private $articulos;

	/**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

	 public function __construct()
    {
        $this->articulos = new ArrayCollection(); 	
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
     * @return SubCategoria
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
     * Set categoria
     *
     * @param \Mbp\WebBundle\Entity\Categorias $categoria
     *
     * @return SubCategoria
     */
    public function setCategoria(\Mbp\WebBundle\Entity\Categorias $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Mbp\WebBundle\Entity\Categorias
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add articulo
     *
     * @param \Mbp\WebBundle\Entity\Articulos $articulo
     *
     * @return SubCategoria
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

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}

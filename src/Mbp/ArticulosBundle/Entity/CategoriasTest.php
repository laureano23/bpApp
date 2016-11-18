<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CategoriasTest
 * @Gedmo\Tree(type="nested")
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class CategoriasTest
{
	
    /**
     * @var integer
	 * 
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
	 * 
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title=0;
	
	/**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="CategoriasTest")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="CategoriasTest", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="CategoriasTest", mappedBy="parent", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
	
	/**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos", fetch="EAGER")
     * @ORM\JoinColumn(name="idArt", referencedColumnName="idArticulos")	 
     */
    private $idArt;

    /**
	 * @ORM\ManyToMany(targetEntity="Mbp\ArticulosBundle\Entity\Articulos", fetch="EAGER")
	 * @ORM\JoinTable(name="CategoriasTest_Articulos", 
	 * 					joinColumns={@ORM\JoinColumn(name="categoria_id", referencedColumnName="id")},
	 * 					inverseJoinColumns={@ORM\JoinColumn(name="articulo_id", referencedColumnName="idArticulos", unique=false)}
	 * )
	 */
    private $idForm;

    /**
     * @var float
     *
     * @ORM\Column(name="cant", type="float")
     */
    private $cant=0;


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
     * Set title
     *
     * @param string $title
     *
     * @return CategoriasTest
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
	
	public function getRoot()
    {
        return $this->root;
    }

    public function setParent(CategoriasTest $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }
	
	/**
     * Set idArt
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idArt
     * @return Formulas
     */
    public function setIdArt($idArt)
    {
        $this->idArt = $idArt;

        return $this;
    }

    /**
     * Get idArt
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos 
     */
    public function getIdArt()
    {
        return $this->idArt;
    }

    /**
     * Set idForm
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idArt
     * @return Formulas
     */
    public function setIdForm($idForm)
    {
        $this->idForm = $idForm;

        return $this;
    }

    /**
     * Get idForm
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos 
     */
    public function getIdForm()
    {
        return $this->idForm;
    }

    /**
     * Set cant
     *
     * @param float $cant
     * @return Formulas
     */
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * Get cant
     *
     * @return float 
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return CategoriasTest
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return CategoriasTest
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return CategoriasTest
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param \Mbp\ArticulosBundle\Entity\CategoriasTest $root
     *
     * @return CategoriasTest
     */
    public function setRoot(\Mbp\ArticulosBundle\Entity\CategoriasTest $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Add child
     *
     * @param \Mbp\ArticulosBundle\Entity\CategoriasTest $child
     *
     * @return CategoriasTest
     */
    public function addChild(\Mbp\ArticulosBundle\Entity\CategoriasTest $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Mbp\ArticulosBundle\Entity\CategoriasTest $child
     */
    public function removeChild(\Mbp\ArticulosBundle\Entity\CategoriasTest $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    } 
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idForm = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add idForm
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idForm
     *
     * @return CategoriasTest
     */
    public function addIdForm(\Mbp\ArticulosBundle\Entity\Articulos $idForm)
    {
        $this->idForm[] = $idForm;

        return $this;
    }

    /**
     * Remove idForm
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idForm
     */
    public function removeIdForm(\Mbp\ArticulosBundle\Entity\Articulos $idForm)
    {
        $this->idForm->removeElement($idForm);
    }
}

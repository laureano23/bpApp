<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="closure")
 * @Gedmo\TreeClosure(class="Mbp\ArticulosBundle\Entity\FormulasClosure")
 * @ORM\Entity(repositoryClass="FormulasCRepository")
 */
class FormulasC
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="cantidad", type="decimal", precision= 10, scale= 3)
     */
    private $cantidad;

    /**
     * @ORM\Column(name="unidad", type="string", length=50, nullable=true)
     */
    private $unidad;

    /**
     * This parameter is optional for the closure strategy
     *
     * @ORM\Column(name="level", type="integer", nullable=true)
     * @Gedmo\TreeLevel
     */
    private $level;

    /**
     * @Gedmo\TreeParent
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="FormulasC", inversedBy="children")
     */
    private $parent;

     /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos", fetch="EAGER", cascade={"refresh", "persist", "merge"})
     * @ORM\JoinColumn(name="idArt", referencedColumnName="idArticulos", unique=false)   
     */
    private $idArt;

    public function getId()
    {
        return $this->id;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    

    public function addClosure(FormulasClosure $closure)
    {
        $this->closures[] = $closure;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set parent
     *
     * @param \Mbp\ArticulosBundle\Entity\FormulasC $parent
     *
     * @return FormulasC
     */
    public function setParent(\Mbp\ArticulosBundle\Entity\FormulasC $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Mbp\ArticulosBundle\Entity\FormulasC
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set unidad
     *
     * @param string $unidad
     *
     * @return FormulasC
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * Get unidad
     *
     * @return string
     */
    public function getUnidad()
    {
        return $this->unidad;
    }


    /**
     * Set idArt
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idArt
     *
     * @return FormulasC
     */
    public function setIdArt(\Mbp\ArticulosBundle\Entity\Articulos $idArt = null)
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
}

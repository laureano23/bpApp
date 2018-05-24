<?php

namespace Mbp\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Tree(type="closure")
 * @Gedmo\TreeClosure(class="Mbp\TestBundle\Entity\FormulasClosure")
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
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(name="letra", type="string", length=5)
     */
    private $letra;

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
     * @param \Mbp\TestBundle\Entity\FormulasC $parent
     *
     * @return FormulasC
     */
    public function setParent(\Mbp\TestBundle\Entity\FormulasC $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Mbp\TestBundle\Entity\FormulasC
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set letra
     *
     * @param string $letra
     *
     * @return FormulasC
     */
    public function setLetra($letra)
    {
        $this->letra = $letra;

        return $this;
    }

    /**
     * Get letra
     *
     * @return string
     */
    public function getLetra()
    {
        return $this->letra;
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

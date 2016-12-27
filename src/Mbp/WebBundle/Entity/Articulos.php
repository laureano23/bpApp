<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articulos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\ArticulosRepository")
 */
class Articulos
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
     * @ORM\Column(name="descripcion", type="string", length=250)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="rutaHojaDatos", type="string", length=200)
     */
    private $rutaHojaDatos;

    /**
     * @var \Mbp\WebBundle\Entity\SubCategoria
     *
     * @ORM\ManyToOne(targetEntity="Mbp\WebBundle\Entity\SubCategoria", inversedBy="articulos")
     * @ORM\JoinColumn(name="subCategoriaId", referencedColumnName="id") 
     */ 
    private $subCategoriaId; 

    

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
     * @return Articulos
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
     * Set rutaHojaDatos
     *
     * @param string $rutaHojaDatos
     *
     * @return Articulos
     */
    public function setRutaHojaDatos($rutaHojaDatos)
    {
        $this->rutaHojaDatos = $rutaHojaDatos;

        return $this;
    }

    /**
     * Get rutaHojaDatos
     *
     * @return string
     */
    public function getRutaHojaDatos()
    {
        return $this->rutaHojaDatos;
    }
}
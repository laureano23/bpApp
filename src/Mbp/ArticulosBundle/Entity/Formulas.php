<?php
namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formulas 
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\FormulasRepository")
 */
class Formulas
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
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos", fetch="EAGER", cascade={"refresh", "persist", "merge"})
     * @ORM\JoinColumn(name="idArt", referencedColumnName="idArticulos", unique=false)	 
     */
    private $idArt;

    /**
     * @var float
     *
     * @ORM\Column(name="cant", type="float")
     */
    private $cant=0;	
    
    /**
     * @var integer
     *
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft=0;
	
	/**
     * @var integer
     *
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt=0;

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
     * @return Formulas
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
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return Formulas
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
}

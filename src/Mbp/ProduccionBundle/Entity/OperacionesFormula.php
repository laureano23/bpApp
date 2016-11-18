<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperacionesFormula
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\OperacionesFormulaRepository")
 */
class OperacionesFormula
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
     * @var Operaciones
     * @ORM\ManyToOne(targetEntity="Operaciones")
	 * @ORM\JoinColumn(name="idOperacion", referencedColumnName="id")
	 * 
     */
    private $idOperacion;

    /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     * @ORM\ManyToOne(targetEntity="\Mbp\ArticulosBundle\Entity\Articulos")
	 * @ORM\JoinColumn(name="idArticulo", referencedColumnName="idArticulos")
     */
    private $idArticulo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="time")
     */
    private $tiempo;


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
     * Set idOperacion
     *
     * @param integer $idOperacion
     *
     * @return OperacionesFormula
     */
    public function setIdOperacion($idOperacion)
    {
        $this->idOperacion = $idOperacion;

        return $this;
    }

    /**
     * Get idOperacion
     *
     * @return integer
     */
    public function getIdOperacion()
    {
        return $this->idOperacion;
    }

    /**
     * Set idArticulo
     *
     * @param integer $idArticulo
     *
     * @return OperacionesFormula
     */
    public function setIdArticulo($idArticulo)
    {
        $this->idArticulo = $idArticulo;

        return $this;
    }

    /**
     * Get idArticulo
     *
     * @return integer
     */
    public function getIdArticulo()
    {
        return $this->idArticulo;
    }

    /**
     * Set tiempo
     *
     * @param \DateTime $tiempo
     *
     * @return OperacionesFormula
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return \DateTime
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }
}


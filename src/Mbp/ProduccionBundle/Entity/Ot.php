<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ot
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\OtRepository")
 */
class Ot
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
     * @var integer
     *
     * @ORM\Column(name="ot", type="integer", unique=true, nullable=false)     
     */
    private $ot;

     /**
     * @var \Mbp\ArticulosBundle\Entity\Articulos
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
     * @ORM\JoinColumn(name="idCodigo", referencedColumnName="idArticulos")	 
     */
    private $idCodigo;
	
	/**
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="idCliente", referencedColumnName="idCliente")	 
     */
    private $idCliente;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;


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
     * Set ot
     *
     * @param integer $ot
     * @return Ot
     */
    public function setOt($ot)
    {
        $this->ot = $ot;

        return $this;
    }

    /**
     * Get ot
     *
     * @return integer 
     */
    public function getOt()
    {
        return $this->ot;
    }

    /**
     * Set idCodigo
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $idCodigo
     * @return Ot
     */
    public function setIdCodigo($idCodigo)
    {
        $this->idCodigo = $idCodigo;

        return $this;
    }

    /**
     * Get idCodigo
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos
     */
    public function getIdCodigo()
    {
        return $this->idCodigo;
    }
    
	/**
     * Set idCliente
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $idCliente
     * @return Ot
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;

        return $this;
    }

    /**
     * Get idCliente
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Ot
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
}

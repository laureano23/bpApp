<?php

namespace Mbp\ClientesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clientes_Familias
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ClientesBundle\Entity\Clientes_FamiliasRepository")
 */
class Clientes_Familias
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
     * @ORM\Column(name="descuento", type="decimal")
     */
    private $descuento;
	
	/**
     * @var \Mbp\ArticulosBundle\Entity\Familia
	 * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Familia")
	 * @ORM\JoinColumn(name="familia_id", referencedColumnName="id", nullable=false)
     */
    private $familia_id;
	
	/**
     * @var \Mbp\ArticulosBundle\Entity\SubFamilia
	 * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\SubFamilia")
	 * @ORM\JoinColumn(name="subFamilia_id", referencedColumnName="id", nullable=false)
     */
    private $subFamilia_id;
	
	/**
     * @var \Mbp\ClientesBundle\Entity\Cliente
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
	 * @ORM\JoinColumn(name="cliente_id", referencedColumnName="idCliente", nullable=false)
     */
    private $cliente_id;


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
     * Set descuento
     *
     * @param string $descuento
     *
     * @return Clientes_Familias
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return string
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set familiaId
     *
     * @param \Mbp\ArticulosBundle\Entity\Familia $familiaId
     *
     * @return Clientes_Familias
     */
    public function setFamiliaId(\Mbp\ArticulosBundle\Entity\Familia $familiaId)
    {
        $this->familia_id = $familiaId;

        return $this;
    }

    /**
     * Get familiaId
     *
     * @return \Mbp\ArticulosBundle\Entity\Familia
     */
    public function getFamiliaId()
    {
        return $this->familia_id;
    }

    /**
     * Set subFamiliaId
     *
     * @param \Mbp\ArticulosBundle\Entity\SubFamilia $subFamiliaId
     *
     * @return Clientes_Familias
     */
    public function setSubFamiliaId(\Mbp\ArticulosBundle\Entity\SubFamilia $subFamiliaId)
    {
        $this->subFamilia_id = $subFamiliaId;

        return $this;
    }

    /**
     * Get subFamiliaId
     *
     * @return \Mbp\ArticulosBundle\Entity\SubFamilia
     */
    public function getSubFamiliaId()
    {
        return $this->subFamilia_id;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return Clientes_Familias
     */
    public function setClienteId(\Mbp\ClientesBundle\Entity\Cliente $clienteId)
    {
        $this->cliente_id = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getClienteId()
    {
        return $this->cliente_id;
    }
}

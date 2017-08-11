<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operaciones
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\OperacionesRepository")
 */
class Operaciones
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
     * @ORM\Column(name="codigo", type="integer")
     */
    private $codigo;
	
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=100)
     */
    private $descripcion;

	/**
	 * @var \Mbp\FinanzasBundle\Entity\CentroCostos
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\CentroCostos")
	 * @ORM\JoinColumn(name="centroCostosId", referencedColumnName="id")
	 */
	 private $centroCosto;

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
     * @return Operaciones
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
     * Set centroCosto
     *
     * @param \Mbp\FinanzasBundle\Entity\CentroCostos $centroCosto
     *
     * @return Operaciones
     */
    public function setCentroCosto(\Mbp\FinanzasBundle\Entity\CentroCostos $centroCosto = null)
    {
        $this->centroCosto = $centroCosto;

        return $this;
    }

    /**
     * Get centroCosto
     *
     * @return \Mbp\FinanzasBundle\Entity\CentroCostos
     */
    public function getCentroCosto()
    {
        return $this->centroCosto;
    }

    /**
     * Set codigo
     *
     * @param integer $codigo
     *
     * @return Operaciones
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
}

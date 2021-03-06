<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImputacionGastos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\ImputacionGastosRepository")
 */
class ImputacionGastos
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
     * @ORM\Column(name="descripcion", type="string", length=40)
     */
    private $descripcion;

    /**
     * @var bool
     *
     * @ORM\Column(name="esGastoRepresentacion", type="boolean", nullable=true)
     */
    private $esGastoRepresentacion=0;


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
     * @return ImputacionGastos
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
     * Set esGastoRepresentacion
     *
     * @param boolean $esGastoRepresentacion
     *
     * @return ImputacionGastos
     */
    public function setEsGastoRepresentacion($esGastoRepresentacion)
    {
        $this->esGastoRepresentacion = $esGastoRepresentacion;

        return $this;
    }

    /**
     * Get esGastoRepresentacion
     *
     * @return boolean
     */
    public function getEsGastoRepresentacion()
    {
        return $this->esGastoRepresentacion;
    }
}

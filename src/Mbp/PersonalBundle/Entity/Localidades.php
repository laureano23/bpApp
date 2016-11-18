<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Localidades
 *
 * @ORM\Table(name="localidades")
 * @ORM\Entity
 */
class Localidades
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Mbp\PersonalBundle\Entity\Departamentos
     *
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Departamentos")
     * @ORM\JoinColumn(name="departamento_id", referencedColumnName="id")	
     */
    private $departamentoId;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;



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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Localidades
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set departamentoId
     *
     * @param \Mbp\PersonalBundle\Entity\Departamentos $departamentoId
     *
     * @return Localidades
     */
    public function setDepartamentoId(\Mbp\PersonalBundle\Entity\Departamentos $departamentoId = null)
    {
        $this->departamentoId = $departamentoId;

        return $this;
    }

    /**
     * Get departamentoId
     *
     * @return \Mbp\PersonalBundle\Entity\Departamentos
     */
    public function getDepartamentoId()
    {
        return $this->departamentoId;
    }
}

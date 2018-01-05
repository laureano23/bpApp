<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departamentos
 *
 * @ORM\Table(name="departamentos")
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\DepartamentosRepository")
 */
class Departamentos
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
     * @var \Mbp\PersonalBundle\Entity\Provincia
     *
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Provincia")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id")	
     */
    private $provinciaId;

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
     * @return Departamentos
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
     * Set provinciaId
     *
     * @param \Mbp\PersonalBundle\Entity\Provincias $provinciaId
     *
     * @return Departamentos
     */
    public function setProvinciaId(\Mbp\PersonalBundle\Entity\Provincia $provinciaId = null)
    {
        $this->provinciaId = $provinciaId;

        return $this;
    }

    /**
     * Get provinciaId
     *
     * @return \Mbp\PersonalBundle\Entity\Provincia
     */
    public function getProvinciaId()
    {
        return $this->provinciaId;
    }
}

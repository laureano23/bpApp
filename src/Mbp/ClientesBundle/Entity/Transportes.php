<?php

namespace Mbp\ClientesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transportes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ClientesBundle\Entity\TransportesRepository")
 */
class Transportes
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="contacto", type="string", length=255)
     */
    private $contacto;

    /**
     * @var string
     *
     * @ORM\Column(name="horarios", type="string", length=255)
     */
    private $horarios;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255)
     */
    private $telefono;
	
	/**
     * @var \Mbp\PersonalBundle\Entity\Localidades
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Departamentos")
	 * @ORM\JoinColumn(name="departamento", referencedColumnName="id")
     */
    private $departamento;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo=0;


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
     * @return Transportes
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Transportes
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return Transportes
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set horarios
     *
     * @param string $horarios
     *
     * @return Transportes
     */
    public function setHorarios($horarios)
    {
        $this->horarios = $horarios;

        return $this;
    }

    /**
     * Get horarios
     *
     * @return string
     */
    public function getHorarios()
    {
        return $this->horarios;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Transportes
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    
    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Transportes
     */
    public function setInactivo($inactivo)
    {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return boolean
     */
    public function getInactivo()
    {
        return $this->inactivo;
    }

    /**
     * Set departamento
     *
     * @param \Mbp\PersonalBundle\Entity\Departamentos $departamento
     *
     * @return Transportes
     */
    public function setDepartamento(\Mbp\PersonalBundle\Entity\Departamentos $departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return \Mbp\PersonalBundle\Entity\Departamentos
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }
}
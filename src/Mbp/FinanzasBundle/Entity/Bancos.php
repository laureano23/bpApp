<?php

namespace Mbp\FinanzasBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Bancos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\BancosRepository")
 */
class Bancos
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
     * @ORM\Column(name="nombre", type="string", length=25, nullable=false)
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
     */
    private $nombre;
	
	/**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=50, nullable=true)
	 * @Assert\NotBlank()
     */
    private $direccion;
	
	/**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=10, nullable=true)
     */
    private $cp;
	
	/**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", length=30, nullable=true)
     */
    private $localidad;
	
	/**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;
	
	/**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=true)
     */
    private $email;
	
	/**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=15, nullable=true)
     */
    private $cuit;
	
	/**
     * @var string
     *
     * @ORM\Column(name="contacto", type="string", length=30, nullable=true)
     */
    private $contacto;
	
	/**
     * @var string
     *
     * @ORM\Column(name="cargo", type="string", length=30, nullable=true)
     */
    private $cargo;
	
	/**
     * @var string
     * 
     * @ORM\Column(name="telContacto", type="string", length=30, nullable=true)
     */
    private $telContacto;
	
	/**
     * @var string
     *
     * @ORM\Column(name="emailContacto", type="string", length=30, nullable=true)
     */
    private $emailContacto;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo=0;
	
	/** 
     * @ORM\OneToMany(targetEntity="Mbp\FinanzasBundle\Entity\CuentasBancarias", mappedBy="banco", cascade={"persist"})
     */
    private $cuentasBancarias;


   

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Bancos
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Bancos
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
     * Set cp
     *
     * @param string $cp
     *
     * @return Bancos
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     *
     * @return Bancos
     */
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Bancos
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
     * Set email
     *
     * @param string $email
     *
     * @return Bancos
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     *
     * @return Bancos
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return Bancos
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
     * Set cargo
     *
     * @param string $cargo
     *
     * @return Bancos
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set telContacto
     *
     * @param string $telContacto
     *
     * @return Bancos
     */
    public function setTelContacto($telContacto)
    {
        $this->telContacto = $telContacto;

        return $this;
    }

    /**
     * Get telContacto
     *
     * @return string
     */
    public function getTelContacto()
    {
        return $this->telContacto;
    }

    /**
     * Set emailContacto
     *
     * @param string $emailContacto
     *
     * @return Bancos
     */
    public function setEmailContacto($emailContacto)
    {
        $this->emailContacto = $emailContacto;

        return $this;
    }

    /**
     * Get emailContacto
     *
     * @return string
     */
    public function getEmailContacto()
    {
        return $this->emailContacto;
    }

    /**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Bancos
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
     * Constructor
     */
    public function __construct()
    {
        $this->cuentasBancarias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cuentasBancaria
     *
     * @param \Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentasBancaria
     *
     * @return Bancos
     */
    public function addCuentasBancaria(\Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentasBancaria)
    {
        $this->cuentasBancarias[] = $cuentasBancaria;

        return $this;
    }

    /**
     * Remove cuentasBancaria
     *
     * @param \Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentasBancaria
     */
    public function removeCuentasBancaria(\Mbp\FinanzasBundle\Entity\CuentasBancarias $cuentasBancaria)
    {
        $this->cuentasBancarias->removeElement($cuentasBancaria);
    }

    /**
     * Get cuentasBancarias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasBancarias()
    {
        return $this->cuentasBancarias;
    }
}

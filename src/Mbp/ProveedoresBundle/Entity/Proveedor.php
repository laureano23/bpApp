<?php

namespace Mbp\ProveedoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProveedoresBundle\Entity\ProveedorRepository")
 */
class Proveedor
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
     * @ORM\Column(name="rsocial", type="string", length=35)
     */
    private $rsocial;
	
	/**
     * @var \Mbp\PersonalBundle\Entity\Localidades
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Localidades")
	 * @ORM\JoinColumn(name="localidad", referencedColumnName="id", nullable=true)
     */
    private $localidad;
    
    /**
     * @var \Mbp\PersonalBundle\Entity\Provincias
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Provincias")
	 * @ORM\JoinColumn(name="provincia", referencedColumnName="id", nullable=true)
     */
    private $provincia;
	
	/**
     * @var \Mbp\ProveedoresBundle\Entity\ImputacionGastos
	 * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\ImputacionGastos")
	 * @ORM\JoinColumn(name="imputacionGastos", referencedColumnName="id", nullable=false)
     */
    private $imputacionGastos;

    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=50, nullable=true)
     */
    private $denominacion;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=50, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=11, nullable=true)
     */
    private $cuit;

    /**
     * @var string
     *
     * @ORM\Column(name="cPostal", type="string", length=15, nullable=true)
     */
    private $cPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono1", type="string", length=20, nullable=true)
     */
    private $telefono1;

    /**
     * @var string
     *
     * @ORM\Column(name="contacto1", type="string", length=30, nullable=true)
     */
    private $contacto1;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono2", type="string", length=20, nullable=true)
     */
    private $telefono2;

    /**
     * @var string
     *
     * @ORM\Column(name="contacto2", type="string", length=30, nullable=true)
     */
    private $contacto2;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono3", type="string", length=20, nullable=true)
     */
    private $telefono3;

    /**
     * @var string
     *
     * @ORM\Column(name="contacto3", type="string", length=30, nullable=true)
     */
    private $contacto3;

    /**
     * @var string
     *
     * @ORM\Column(name="condCompra", type="string", length=150, nullable=true)
     */
    private $condCompra;

    /**
     * @var integer
     *
     * @ORM\Column(name="vencimientoFc", type="integer", nullable=true)
     */
    private $vencimientoFc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aplicaRetencion", type="boolean", nullable=true)
     */
    private $aplicaRetencion;

    /**
     * @var string
     *
     * @ORM\Column(name="porcentajeRetencion", type="decimal", nullable=true)
     */
    private $porcentajeRetencion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="cuentaCerrada", type="boolean")
     */
    private $cuentaCerrada;


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
     * Set rsocial
     *
     * @param string $rsocial
     *
     * @return Proveedor
     */
    public function setRsocial($rsocial)
    {
        $this->rsocial = $rsocial;

        return $this;
    }

    /**
     * Get rsocial
     *
     * @return string
     */
    public function getRsocial()
    {
        return $this->rsocial;
    }

    /**
     * Set denominacion
     *
     * @param string $denominacion
     *
     * @return Proveedor
     */
    public function setDenominacion($denominacion)
    {
        $this->denominacion = $denominacion;

        return $this;
    }

    /**
     * Get denominacion
     *
     * @return string
     */
    public function getDenominacion()
    {
        return $this->denominacion;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Proveedor
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
     * Set email
     *
     * @param string $email
     *
     * @return Proveedor
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
     * @return Proveedor
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
     * Set cPostal
     *
     * @param string $cPostal
     *
     * @return Proveedor
     */
    public function setCPostal($cPostal)
    {
        $this->cPostal = $cPostal;

        return $this;
    }

    /**
     * Get cPostal
     *
     * @return string
     */
    public function getCPostal()
    {
        return $this->cPostal;
    }

    /**
     * Set telefono1
     *
     * @param string $telefono1
     *
     * @return Proveedor
     */
    public function setTelefono1($telefono1)
    {
        $this->telefono1 = $telefono1;

        return $this;
    }

    /**
     * Get telefono1
     *
     * @return string
     */
    public function getTelefono1()
    {
        return $this->telefono1;
    }

    /**
     * Set contacto1
     *
     * @param string $contacto1
     *
     * @return Proveedor
     */
    public function setContacto1($contacto1)
    {
        $this->contacto1 = $contacto1;

        return $this;
    }

    /**
     * Get contacto1
     *
     * @return string
     */
    public function getContacto1()
    {
        return $this->contacto1;
    }

    /**
     * Set telefono2
     *
     * @param string $telefono2
     *
     * @return Proveedor
     */
    public function setTelefono2($telefono2)
    {
        $this->telefono2 = $telefono2;

        return $this;
    }

    /**
     * Get telefono2
     *
     * @return string
     */
    public function getTelefono2()
    {
        return $this->telefono2;
    }

    /**
     * Set contacto2
     *
     * @param string $contacto2
     *
     * @return Proveedor
     */
    public function setContacto2($contacto2)
    {
        $this->contacto2 = $contacto2;

        return $this;
    }

    /**
     * Get contacto2
     *
     * @return string
     */
    public function getContacto2()
    {
        return $this->contacto2;
    }

    /**
     * Set telefono3
     *
     * @param string $telefono3
     *
     * @return Proveedor
     */
    public function setTelefono3($telefono3)
    {
        $this->telefono3 = $telefono3;

        return $this;
    }

    /**
     * Get telefono3
     *
     * @return string
     */
    public function getTelefono3()
    {
        return $this->telefono3;
    }

    /**
     * Set contacto3
     *
     * @param string $contacto3
     *
     * @return Proveedor
     */
    public function setContacto3($contacto3)
    {
        $this->contacto3 = $contacto3;

        return $this;
    }

    /**
     * Get contacto3
     *
     * @return string
     */
    public function getContacto3()
    {
        return $this->contacto3;
    }

    /**
     * Set condCompra
     *
     * @param string $condCompra
     *
     * @return Proveedor
     */
    public function setCondCompra($condCompra)
    {
        $this->condCompra = $condCompra;

        return $this;
    }

    /**
     * Get condCompra
     *
     * @return string
     */
    public function getCondCompra()
    {
        return $this->condCompra;
    }

    /**
     * Set vencimientoFc
     *
     * @param integer $vencimientoFc
     *
     * @return Proveedor
     */
    public function setVencimientoFc($vencimientoFc)
    {
        $this->vencimientoFc = $vencimientoFc;

        return $this;
    }

    /**
     * Get vencimientoFc
     *
     * @return integer
     */
    public function getVencimientoFc()
    {
        return $this->vencimientoFc;
    }

    /**
     * Set aplicaRetencion
     *
     * @param boolean $aplicaRetencion
     *
     * @return Proveedor
     */
    public function setAplicaRetencion($aplicaRetencion)
    {
        $this->aplicaRetencion = $aplicaRetencion;

        return $this;
    }

    /**
     * Get aplicaRetencion
     *
     * @return boolean
     */
    public function getAplicaRetencion()
    {
        return $this->aplicaRetencion;
    }

    /**
     * Set porcentajeRetencion
     *
     * @param string $porcentajeRetencion
     *
     * @return Proveedor
     */
    public function setPorcentajeRetencion($porcentajeRetencion)
    {
        $this->porcentajeRetencion = $porcentajeRetencion;

        return $this;
    }

    /**
     * Get porcentajeRetencion
     *
     * @return string
     */
    public function getPorcentajeRetencion()
    {
        return $this->porcentajeRetencion;
    }

    /**
     * Set cuentaCerrada
     *
     * @param boolean $cuentaCerrada
     *
     * @return Proveedor
     */
    public function setCuentaCerrada($cuentaCerrada)
    {
        $this->cuentaCerrada = $cuentaCerrada;

        return $this;
    }

    /**
     * Get cuentaCerrada
     *
     * @return boolean
     */
    public function getCuentaCerrada()
    {
        return $this->cuentaCerrada;
    }

    /**
     * Set localidad
     *
     * @param \Mbp\PersonalBundle\Entity\Localidades $localidad
     *
     * @return Proveedor
     */
    public function setLocalidad(\Mbp\PersonalBundle\Entity\Localidades $localidad = null)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return \Mbp\PersonalBundle\Entity\Localidades
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set provincia
     *
     * @param \Mbp\PersonalBundle\Entity\Provincias $provincia
     *
     * @return Proveedor
     */
    public function setProvincia(\Mbp\PersonalBundle\Entity\Provincias $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return \Mbp\PersonalBundle\Entity\Provincias
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set imputacionGastos
     *
     * @param \Mbp\ProveedoresBundle\Entity\ImputacionGastos $imputacionGastos
     *
     * @return Proveedor
     */
    public function setImputacionGastos(\Mbp\ProveedoresBundle\Entity\ImputacionGastos $imputacionGastos)
    {
        $this->imputacionGastos = $imputacionGastos;

        return $this;
    }

    /**
     * Get imputacionGastos
     *
     * @return \Mbp\ProveedoresBundle\Entity\ImputacionGastos
     */
    public function getImputacionGastos()
    {
        return $this->imputacionGastos;
    }
}

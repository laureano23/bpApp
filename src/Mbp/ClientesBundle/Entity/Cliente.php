<?php

namespace Mbp\ClientesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Mbp\ClientesBundle\Entity\ClienteRepository")
 */
class Cliente
{
    /**
     * @var string
     *
     * @ORM\Column(name="rsocial", type="string", length=35, nullable=false)
     */
    private $rsocial;

    /**
     * @var integer
     *
     * @ORM\Column(name="idCliente", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=250, nullable=true)
     */
    private $denominacion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=250, nullable=true)
     */
    private $direccion;
    
    /**
     * @var \Mbp\PersonalBundle\Entity\Departamentos
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Departamentos")
	 * @ORM\JoinColumn(name="departamento", referencedColumnName="id", nullable=true)
     */
    private $departamento;
    
    /**
     * @var \Mbp\PersonalBundle\Entity\Provincia
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Provincia")
	 * @ORM\JoinColumn(name="provincia", referencedColumnName="id", nullable=true)
     */
    private $provincia;
	
	/**
     * @var \Mbp\PersonalBundle\Entity\Localidades
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Localidades")
	 * @ORM\JoinColumn(name="localidad", referencedColumnName="id", nullable=true)
     */
    private $localidad;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=11, nullable=true, unique=true)
     */
    private $cuit;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cPostal", type="string", length=11, nullable=true)
     */
    private $cPostal;
    
    /**
     * @var \Mbp\FinanzasBundle\Entity\PosicionIVA
     * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\PosicionIVA")
     * @ORM\JoinColumn(name="iva", referencedColumnName="id", nullable=false)
     */
    private $iva;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefono1", type="string", length=50, nullable=true)
     */
    private $telefono1;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contacto1", type="string", length=50, nullable=true)
     */
    private $contacto1;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefono2", type="string", length=50, nullable=true)
     */
    private $telefono2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contacto2", type="string", length=50, nullable=true)
     */
    private $contacto2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="telefono3", type="string", length=50, nullable=true)
     */
    private $telefono3;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contacto3", type="string", length=50, nullable=true)
     */
    private $contacto3;
    
    /**
     * @var string
     *
     * @ORM\Column(name="condVenta", type="string", length=250, nullable=true)
     */
    private $condVenta;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vencimientoFc", type="integer", nullable=true)
     */
    private $vencimientoFc;
    

	/**
     * @var boolean
     *
     * @ORM\Column(name="cuentaCerrada", type="boolean", nullable=true)
     */
    private $cuentaCerrada=0;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="intereses", type="boolean", nullable=true)
     */
    private $intereses=0;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="tasaInt", type="decimal", scale=2, nullable=true)
     */
    private $tasaInt;
	
	/**
     * @var \Mbp\ClientseBundle\Entity\Transportes
	 * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Transportes")
	 * @ORM\JoinColumn(name="transporteId", referencedColumnName="id", nullable=true)
     */
    private $transporteId;
	
	/**
     * @var text
     *
     * @ORM\Column(name="notasCC", type="text", nullable=true)
     */
    private $notasCC;
	
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="descuentoFijo", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $descuentoFijo;
	
	/**    
     * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Vendedor", inversedBy="clientes")
     * @ORM\JoinColumn(name="vendedorId", referencedColumnName="id", nullable=true)
     */
    private $vendedor;
	
	/**
     * @var decimal
     *
     * @ORM\Column(name="comision", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $comision;

    /**
     * @var boolean
     *
     * @ORM\Column(name="noAplicaPercepcion", type="boolean", nullable=false)
     */
    private $noAplicaPercepcion=0;

    /**
     * Set rsocial
     *
     * @param string $rsocial
     * @return Cliente
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	/**
     * Set denominacion
     *
     * @param string $denominacion
     * @return Cliente
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
     * @return Cliente
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
     * Set provincia
     *
     * @param string $provincia
     * @return Cliente
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
	
	
	/**
     * Set email
     *
     * @param string $email
     * @return Cliente
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
     * @return Cliente
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
     * @return Cliente
     */
    public function setcPostal($cPostal)
    {
        $this->cPostal = $cPostal;

        return $this;
    }

    /**
     * Get cPostal
     *
     * @return string 
     */
    public function getcPostal()
    {
        return $this->cPostal;
    }
	
		
	/**
     * Set telefono1
     *
     * @param string $telefono1
     * @return Cliente
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
     * Set telefono2
     *
     * @param string $telefono2
     * @return Cliente
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
     * Set telefono3
     *
     * @param string $telefono3
     * @return Cliente
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
     * Set contacto1
     *
     * @param string $contacto1
     * @return Cliente
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
     * Set contacto2
     *
     * @param string $contacto2
     * @return Cliente
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
     * Set contacto3
     *
     * @param string $contacto3
     * @return Cliente
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
     * Set condVenta
     *
     * @param string $condVenta
     * @return Cliente
     */
    public function setCondVenta($condVenta)
    {
        $this->condVenta = $condVenta;

        return $this;
    }

    /**
     * Get condVenta
     *
     * @return string 
     */
    public function getCondVenta()
    {
        return $this->condVenta;
    }
	
	/**
     * Set vencimientoFc
     *
     * @param integer $vencimientoFc
     * @return Cliente
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
     * Set cuentaCerrada
     *
     * @param boolean $cuentaCerrada
     *
     * @return Cliente
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
     * Set departamento
     *
     * @param \Mbp\PersonalBundle\Entity\Departamentos $departamento
     *
     * @return Cliente
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

    /**
     * Set transporteId
     *
     * @param \Mbp\ClientesBundle\Entity\Transportes $transporteId
     *
     * @return Cliente
     */
    public function setTransporteId(\Mbp\ClientesBundle\Entity\Transportes $transporteId = null)
    {
        $this->transporteId = $transporteId;

        return $this;
    }

    /**
     * Get transporteId
     *
     * @return \Mbp\ClientesBundle\Entity\Transportes
     */
    public function getTransporteId()
    {
        return $this->transporteId;
    }

    /**
     * Set intereses
     *
     * @param boolean $intereses
     *
     * @return Cliente
     */
    public function setIntereses($intereses)
    {
        $this->intereses = $intereses;

        return $this;
    }

    /**
     * Get intereses
     *
     * @return boolean
     */
    public function getIntereses()
    {
        return $this->intereses;
    }

    /**
     * Set tasaInt
     *
     * @param decimal $tasaInt
     *
     * @return Cliente
     */
    public function setTasaInt($tasaInt)
    {
        $this->tasaInt = $tasaInt;

        return $this;
    }

    /**
     * Get tasaInt
     *
     * @return decimal
     */
    public function getTasaInt()
    {
        return $this->tasaInt;
    }

    /**
     * Set localidad
     *
     * @param \Mbp\PersonalBundle\Entity\Localidades $localidad
     *
     * @return Cliente
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
     * Set descuentoFijo
     *
     * @param string $descuentoFijo
     *
     * @return Cliente
     */
    public function setDescuentoFijo($descuentoFijo)
    {
        $this->descuentoFijo = $descuentoFijo;

        return $this;
    }

    /**
     * Get descuentoFijo
     *
     * @return string
     */
    public function getDescuentoFijo()
    {
        return $this->descuentoFijo;
    }

    /**
     * Set notasCC
     *
     * @param string $notasCC
     *
     * @return Cliente
     */
    public function setNotasCC($notasCC)
    {
        $this->notasCC = $notasCC;

        return $this;
    }

    /**
     * Get notasCC
     *
     * @return string
     */
    public function getNotasCC()
    {
        return $this->notasCC;
    }

    /**
     * Set vendedor
     *
     * @param \Mbp\FinanzasBundle\Entity\Vendedor $vendedor
     *
     * @return Cliente
     */
    public function setVendedor(\Mbp\FinanzasBundle\Entity\Vendedor $vendedor = null)
    {
        $this->vendedor = $vendedor;

        return $this;
    }

    /**
     * Get vendedor
     *
     * @return \Mbp\FinanzasBundle\Entity\Vendedor
     */
    public function getVendedor()
    {
        return $this->vendedor;
    }

    /**
     * Set comision
     *
     * @param string $comision
     *
     * @return Cliente
     */
    public function setComision($comision)
    {
        $this->comision = $comision;

        return $this;
    }

    /**
     * Get comision
     *
     * @return string
     */
    public function getComision()
    {
        return $this->comision;
    }

    /**
     * Set iva
     *
     * @param \Mbp\FinanzasBundle\Entity\PosicionIVA $iva
     *
     * @return Cliente
     */
    public function setIva(\Mbp\FinanzasBundle\Entity\PosicionIVA $iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return \Mbp\FinanzasBundle\Entity\PosicionIVA
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set noAplicaPercepcion
     *
     * @param boolean $noAplicaPercepcion
     *
     * @return Cliente
     */
    public function setNoAplicaPercepcion($noAplicaPercepcion)
    {
        $this->noAplicaPercepcion = $noAplicaPercepcion;

        return $this;
    }

    /**
     * Get noAplicaPercepcion
     *
     * @return boolean
     */
    public function getNoAplicaPercepcion()
    {
        return $this->noAplicaPercepcion;
    }
}

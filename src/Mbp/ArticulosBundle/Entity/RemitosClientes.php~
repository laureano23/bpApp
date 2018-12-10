<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RemitosClientes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\RemitosClientesRepository")
 */
class RemitosClientes
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     * @Assert\DateTime()
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="remitoNum", type="string", length=15)
     */
    private $remitoNum;

    /**
     * @var \Mbp\ClientesBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ClientesBundle\Entity\Cliente")
     * @ORM\JoinColumn(name="clienteId", referencedColumnName="idCliente", nullable=true)    
     */
    private $clienteId;
	
	/**
     * @var \Mbp\ProveedoresBundle\Entity\Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Mbp\ProveedoresBundle\Entity\Proveedor")
     * @ORM\JoinColumn(name="proveedorId", referencedColumnName="id", nullable=true)    
     */
    private $proveedorId;

    /**
     * @var \Mbp\ArticulosBundle\Entity\RemitosClientesDetalles
     * 
     * @ORM\ManyToMany(targetEntity="Mbp\ArticulosBundle\Entity\RemitosClientesDetalles", cascade={"persist"})
     * @ORM\JoinTable(name="RemitoClientes_detalle")
     *      joinColumns={@ORM\JoinColumn(name="idRemito", referencedColumnName="id")},
     *      inverseJoinConlumns={@ORM\JoinColumn(name="idDetalleRemito", referencedColumnName="id", unique=true)}
     */ 
    private $detalleRemito;
	
	/**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", nullable=true)
     */
    private $domicilio;
    
    /**
     * @var string
     *
     * @ORM\Column(name="provincia", type="string", nullable=true)
     */
    private $provincia;
	
	/**
     * @var string
     *
     * @ORM\Column(name="localidad", type="string", nullable=true)
     */
    private $localidad;

    /**
     * @var boolean
     *
     * @ORM\Column(name="anulado", type="boolean", nullable=true)
     */
    private $anulado=0;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RemitosClientes
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set remitoNum
     *
     * @param string $remitoNum
     *
     * @return RemitosClientes
     */
    public function setRemitoNum($remitoNum)
    {
        $this->remitoNum = $remitoNum;

        return $this;
    }

    /**
     * Get remitoNum
     *
     * @return string
     */
    public function getRemitoNum()
    {
        return $this->remitoNum;
    }

    /**
     * Set clienteId
     *
     * @param \Mbp\ClientesBundle\Entity\Cliente $clienteId
     *
     * @return RemitosClientes
     */
    public function setClienteId(\Mbp\ClientesBundle\Entity\Cliente $clienteId = null)
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return \Mbp\ClientesBundle\Entity\Cliente
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detalleRemito = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add detalleRemito
     *
     * @param \Mbp\ArticulosBundle\Entity\RemitosClientesDetalles $detalleRemito
     *
     * @return RemitosClientes
     */
    public function addDetalleRemito(\Mbp\ArticulosBundle\Entity\RemitosClientesDetalles $detalleRemito)
    {
        $this->detalleRemito[] = $detalleRemito;

        return $this;
    }

    /**
     * Remove detalleRemito
     *
     * @param \Mbp\ArticulosBundle\Entity\RemitosClientesDetalles $detalleRemito
     */
    public function removeDetalleRemito(\Mbp\ArticulosBundle\Entity\RemitosClientesDetalles $detalleRemito)
    {
        $this->detalleRemito->removeElement($detalleRemito);
    }

    /**
     * Get detalleRemito
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalleRemito()
    {
        return $this->detalleRemito;
    }

    /**
     * Set proveedorId
     *
     * @param \Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId
     *
     * @return RemitosClientes
     */
    public function setProveedorId(\Mbp\ProveedoresBundle\Entity\Proveedor $proveedorId = null)
    {
        $this->proveedorId = $proveedorId;

        return $this;
    }

    /**
     * Get proveedorId
     *
     * @return \Mbp\ProveedoresBundle\Entity\Proveedor
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Set domicilio
     *
     * @param string $domicilio
     *
     * @return RemitosClientes
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio
     *
     * @return string
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     *
     * @return RemitosClientes
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
     * Set localidad
     *
     * @param string $localidad
     *
     * @return RemitosClientes
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
     * Set anulado
     *
     * @param boolean $anulado
     *
     * @return RemitosClientes
     */
    public function setAnulado($anulado)
    {
        $this->anulado = $anulado;

        return $this;
    }

    /**
     * Get anulado
     *
     * @return boolean
     */
    public function getAnulado()
    {
        return $this->anulado;
    }
}

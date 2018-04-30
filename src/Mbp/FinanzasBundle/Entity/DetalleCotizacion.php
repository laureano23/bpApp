<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleCotizacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\DetalleCotizacionRepository")
 */
class DetalleCotizacion
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="cant", type="decimal", precision= 11, scale=2)
     */
    private $cant;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad", type="string", length=20)
     */
    private $unidad;

    /**
     * @var string
     *
     * @ORM\Column(name="precio", type="decimal", precision= 11, scale=2)
     */
    private $precio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entrega", type="date", nullable=true)
     */
    private $entrega;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Cotizacion", inversedBy="detalle")
	 * @ORM\JoinColumn(name="cotizacionId", referencedColumnName="id", nullable=true)
	 */
	private $cotizacion;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\ArticulosBundle\Entity\Articulos")
	 * @ORM\JoinColumn(name="articuloId", referencedColumnName="idArticulos", nullable=false)
	 */
	private $articuloId;
	


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
     * @return DetalleCotizacion
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
     * Set cant
     *
     * @param string $cant
     *
     * @return DetalleCotizacion
     */
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * Get cant
     *
     * @return string
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set unidad
     *
     * @param string $unidad
     *
     * @return DetalleCotizacion
     */
    public function setUnidad($unidad)
    {
        $this->unidad = $unidad;

        return $this;
    }

    /**
     * Get unidad
     *
     * @return string
     */
    public function getUnidad()
    {
        return $this->unidad;
    }

    /**
     * Set precio
     *
     * @param string $precio
     *
     * @return DetalleCotizacion
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set entrega
     *
     * @param \DateTime $entrega
     *
     * @return DetalleCotizacion
     */
    public function setEntrega($entrega)
    {
        $this->entrega = $entrega;

        return $this;
    }

    /**
     * Get entrega
     *
     * @return \DateTime
     */
    public function getEntrega()
    {
        return $this->entrega;
    }

    /**
     * Set cotizacion
     *
     * @param \Mbp\FinanzasBundle\Entity\Cotizacion $cotizacion
     *
     * @return DetalleCotizacion
     */
    public function setCotizacion(\Mbp\FinanzasBundle\Entity\Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion;

        return $this;
    }

    /**
     * Get cotizacion
     *
     * @return \Mbp\FinanzasBundle\Entity\Cotizacion
     */
    public function getCotizacion()
    {
        return $this->cotizacion;
    }

    /**
     * Set articuloId
     *
     * @param \Mbp\ArticulosBundle\Entity\Articulos $articuloId
     *
     * @return DetalleCotizacion
     */
    public function setArticuloId(\Mbp\ArticulosBundle\Entity\Articulos $articuloId)
    {
        $this->articuloId = $articuloId;

        return $this;
    }

    /**
     * Get articuloId
     *
     * @return \Mbp\ArticulosBundle\Entity\Articulos
     */
    public function getArticuloId()
    {
        return $this->articuloId;
    }
}

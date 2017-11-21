<?php

namespace Mbp\ComprasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedidosInternos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ComprasBundle\Entity\PedidosInternosRepository")
 */
class PedidosInternos
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
     * @ORM\Column(name="emision", type="date")
     */
    private $emision;
		
	/**
	 * @ORM\ManyToMany(targetEntity="PedidoInternoDetalle", cascade={"remove", "persist"})
	 * @ORM\JoinTable(name="PedidoInterno_Detalles",
	 *  joinColumns={ @ORM\JoinColumn(name = "pedido_id", referencedColumnName="id") }),
	 *  inverseJoinColumns={@JoinColumn(name="detalle_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	private $detalleId;
	
	/**
     * @var \Mbp\SecurityBundle\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Mbp\SecurityBundle\Entity\Users")
     * @ORM\JoinColumn(name="usuarioId", referencedColumnName="id", unique=false)	 
     */
    private $usuarioId;


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
     * Set emision
     *
     * @param \DateTime $emision
     *
     * @return PedidosInternos
     */
    public function setEmision($emision)
    {
        $this->emision = $emision;

        return $this;
    }

    /**
     * Get emision
     *
     * @return \DateTime
     */
    public function getEmision()
    {
        return $this->emision;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detalleId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add detalleId
     *
     * @param \Mbp\ComprasBundle\Entity\PedidoInternoDetalle $detalleId
     *
     * @return PedidosInternos
     */
    public function addDetalleId(\Mbp\ComprasBundle\Entity\PedidoInternoDetalle $detalleId)
    {
        $this->detalleId[] = $detalleId;

        return $this;
    }

    /**
     * Remove detalleId
     *
     * @param \Mbp\ComprasBundle\Entity\PedidoInternoDetalle $detalleId
     */
    public function removeDetalleId(\Mbp\ComprasBundle\Entity\PedidoInternoDetalle $detalleId)
    {
        $this->detalleId->removeElement($detalleId);
    }

    /**
     * Get detalleId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalleId()
    {
        return $this->detalleId;
    }

    /**
     * Set usuarioId
     *
     * @param \Mbp\SecurityBundle\Entity\Users $usuarioId
     *
     * @return PedidosInternos
     */
    public function setUsuarioId(\Mbp\SecurityBundle\Entity\Users $usuarioId = null)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }

    /**
     * Get usuarioId
     *
     * @return \Mbp\SecurityBundle\Entity\Users
     */
    public function getUsuarioId()
    {
        return $this->usuarioId;
    }
}

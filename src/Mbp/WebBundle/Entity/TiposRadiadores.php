<?php

namespace Mbp\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposRadiadores
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\WebBundle\Entity\TiposRadiadoresRepository")
 */
class TiposRadiadores
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
     * @ORM\Column(name="tipo", type="string", length=100)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="Mbp\WebBundle\Entity\RadiadoresComerciales", mappedBy="tipoId", cascade={"all"})
     */
    private $radId;

    public function __construct()
    {
        $this->aplicacionId = new ArrayCollection();   
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return TiposRadiadores
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}


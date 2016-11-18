<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Familia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\FamiliaRepository")
 */
class Familia
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
     * @ORM\Column(name="familia", type="string", length=80)
     */
    private $familia;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive=1;


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
     * Set familia
     *
     * @param string $familia
     *
     * @return Familia
     */
    public function setFamilia($familia)
    {
        $this->familia = $familia;

        return $this;
    }

    /**
     * Get familia
     *
     * @return string
     */
    public function getFamilia()
    {
        return $this->familia;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Familia
     */
    public function setIsActive($isActive=0)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
}

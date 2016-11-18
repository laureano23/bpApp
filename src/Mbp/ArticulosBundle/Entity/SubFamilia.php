<?php

namespace Mbp\ArticulosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubFamilia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ArticulosBundle\Entity\SubFamiliaRepository")
 */
class SubFamilia
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
     * @ORM\Column(name="subFamilia", type="string", length=200)
     */
    private $subFamilia;

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
     * Set subFamilia
     *
     * @param string $subFamilia
     *
     * @return SubFamilia
     */
    public function setSubFamilia($subFamilia)
    {
        $this->subFamilia = $subFamilia;

        return $this;
    }

    /**
     * Get subFamilia
     *
     * @return string
     */
    public function getSubFamilia()
    {
        return $this->subFamilia;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return SubFamilia
     */
    public function setIsActive($isActive)
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


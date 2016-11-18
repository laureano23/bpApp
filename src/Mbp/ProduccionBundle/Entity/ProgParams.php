<?php

namespace Mbp\ProduccionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProgParams
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\ProduccionBundle\Entity\ProgParamsRepository")
 */
class ProgParams
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
     * @ORM\Column(name="hsInicio", type="time")
     */
    private $hsInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hsFin", type="time")
     */
    private $hsFin;


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
     * Set hsInicio
     *
     * @param \DateTime $hsInicio
     *
     * @return ProgParams
     */
    public function setHsInicio($hsInicio)
    {
        $this->hsInicio = $hsInicio;

        return $this;
    }

    /**
     * Get hsInicio
     *
     * @return \DateTime
     */
    public function getHsInicio()
    {
        return $this->hsInicio;
    }

    /**
     * Set hsFin
     *
     * @param \DateTime $hsFin
     *
     * @return ProgParams
     */
    public function setHsFin($hsFin)
    {
        $this->hsFin = $hsFin;

        return $this;
    }

    /**
     * Get hsFin
     *
     * @return \DateTime
     */
    public function getHsFin()
    {
        return $this->hsFin;
    }
}


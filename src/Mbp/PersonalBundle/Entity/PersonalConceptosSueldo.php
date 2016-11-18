<?php

namespace Mbp\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PersonalConceptosSueldo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\PersonalBundle\Entity\PersonalConceptosSueldoRepository")
 */
class PersonalConceptosSueldo
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
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\Personal")
	 * @ORM\JoinColumn(name="personal_id", referencedColumnName="idP")
	 */
	private $personal_id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Mbp\PersonalBundle\Entity\CodigoSueldos")
	 * @ORM\JoinColumn(name="codigo_id", referencedColumnName="id")
	 */
	private $codigo_id;


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
     * Set personalId
     *
     * @param \Mbp\PersonalBundle\Entity\Personal $personalId
     *
     * @return PersonalConceptosSueldo
     */
    public function setPersonalId(\Mbp\PersonalBundle\Entity\Personal $personalId = null)
    {
        $this->personal_id = $personalId;

        return $this;
    }

    /**
     * Get personalId
     *
     * @return \Mbp\PersonalBundle\Entity\Personal
     */
    public function getPersonalId()
    {
        return $this->personal_id;
    }

    /**
     * Set codigoId
     *
     * @param \Mbp\PersonalBundle\Entity\CodigoSueldos $codigoId
     *
     * @return PersonalConceptosSueldo
     */
    public function setCodigoId(\Mbp\PersonalBundle\Entity\CodigoSueldos $codigoId = null)
    {
        $this->codigo_id = $codigoId;

        return $this;
    }

    /**
     * Get codigoId
     *
     * @return \Mbp\PersonalBundle\Entity\CodigoSueldos
     */
    public function getCodigoId()
    {
        return $this->codigo_id;
    }
}

<?php

namespace Mbp\CalidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indices
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Indices
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}


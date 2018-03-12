<?php

namespace Mbp\FinanzasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CuentasBancarias
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mbp\FinanzasBundle\Entity\CuentasBancariasRepository")
 */
class CuentasBancarias
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
     * @ORM\Column(name="tipo", type="string", length=50, nullable=false)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=20, nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="cbu", type="string", length=25, nullable=false)
     */
    private $cbu;
	
	 /**
     * @ORM\ManyToOne(targetEntity="Mbp\FinanzasBundle\Entity\Bancos", inversedBy="cuentasBancarias")
     * @ORM\JoinColumn(name="bancoId", referencedColumnName="id", nullable=false)
     */
    private $banco;
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="inactivo", type="boolean")
     */
    private $inactivo=0;


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
     * @return CuentasBancarias
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

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return CuentasBancarias
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set cbu
     *
     * @param string $cbu
     *
     * @return CuentasBancarias
     */
    public function setCbu($cbu)
    {
        $this->cbu = $cbu;

        return $this;
    }

    /**
     * Get cbu
     *
     * @return string
     */
    public function getCbu()
    {
        return $this->cbu;
    }

    /**
     * Set banco
     *
     * @param \Mbp\FinanzasBundle\Entity\Bancos $banco
     *
     * @return CuentasBancarias
     */
    public function setBanco(\Mbp\FinanzasBundle\Entity\Bancos $banco = null)
    {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Get banco
     *
     * @return \Mbp\FinanzasBundle\Entity\Bancos
     */
    public function getBanco()
    {
        return $this->banco;
    }
	
	/**
     * Set inactivo
     *
     * @param boolean $inactivo
     *
     * @return Bancos
     */
    public function setInactivo($inactivo)
    {
        $this->inactivo = $inactivo;

        return $this;
    }

    /**
     * Get inactivo
     *
     * @return boolean
     */
    public function getInactivo()
    {
        return $this->inactivo;
    }
}

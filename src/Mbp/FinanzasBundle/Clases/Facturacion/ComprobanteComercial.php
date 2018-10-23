<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;

abstract class ComprobanteComercial{
    private $numero;
    private $fechaEmision;
    private $total;
    private $moneda;
    private $tipoCambio;
    private $descripcionCbte;
    
    public function __construct($tipoCambio, $moneda, $descripcionCbte){
        if($moneda==0){
            $this->tipoCambio=1;
        }else{
            $this->tipoCambio=$tipoCambio;
        }
        
        $this->fechaEmision=\date("Ymd");        
        $this->moneda=$moneda;
        $this->descripcionCbte=$descripcionCbte;
    }

    public function getFechaEmision(){
        return $this->fechaEmision;
    }

    public function getMoneda(){
        if($this->moneda==0){
            return 'PES';
        }else{
            return 'DOL';
        }
    }

    public function getCotizacionMoneda(){
        return $this->tipoCambio;        
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of descripcionCbte
     */ 
    public function getDescripcionCbte()
    {
        return $this->descripcionCbte;
    }

    /**
     * Set the value of descripcionCbte
     *
     * @return  self
     */ 
    public function setDescripcionCbte($descripcionCbte)
    {
        $this->descripcionCbte = $descripcionCbte;

        return $this;
    }
}
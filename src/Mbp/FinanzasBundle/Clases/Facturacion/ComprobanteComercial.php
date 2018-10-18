<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;

abstract class ComprobanteComercial{
    private $numero;
    private $fechaEmision;
    private $total;
    private $moneda;
    private $tipoCambio;
    
    public function __construct($tipoCambio, $moneda){
        $this->tipoCambio=$tipoCambio;
        $this->fechaEmision=\date("Ymd");        
        $this->moneda=$moneda;
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
        if($this->moneda==0){
            return 1;
        }else{
            return $this->tipoCambio;
        }
    }
}
<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;

abstract class ComprobanteComercial{
    private $numero;
    private $fechaEmision;
    private $total;
    private $moneda;
    private $tipoCambio;
    
    public function __construct($tipoCambio, $fechaEmision, $moneda){
        $this->tipoCambio=$tipoCambio;
        $this->fechaEmision=$fechaEmision;        
        $this->moneda=$moneda;
    }
}
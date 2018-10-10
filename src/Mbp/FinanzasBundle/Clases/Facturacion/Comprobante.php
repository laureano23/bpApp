<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;

abstract class Comprobante{
    private $numero;
    private $puntoVenta;
    private $fechaEmision;
    private $total;
    private $moneda;
    
    public function __construct($numero, $puntoVenta, $fechaEmision, $total, $moneda){
        $this->numero=$numero;
        $this->puntoVenta=$puntoVenta;
        $this->fechaEmision=$fechaEmision;
        $this->total=$total;
        $this->moneda=$moneda;
    }
}
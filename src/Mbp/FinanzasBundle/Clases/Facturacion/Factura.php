<?php

namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\Comprobante;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class Factura extends Comprobante{
    private $fechaVencimiento;
    private $repoFactura;
    private $detalleVentas;

    public function __construct($numero, $puntoVenta, $fechaEmision, $total, $moneda, $fechaVencimiento, $repoFactura){
        parent::__construct($numero, $puntoVenta, $fechaEmision, $total, $moneda);
        $this->fechaVencimiento=$fechaVencimiento;
        $this->repoFactura=$repoFactura;
    }

    public function getFechaVencimiento(){
        return $this->getFechaVencimiento;
    }


}
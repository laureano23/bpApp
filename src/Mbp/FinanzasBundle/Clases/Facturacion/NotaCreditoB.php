<?php

namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class NotaCreditoB extends ComprobanteVenta{
    private $facturasAsociadas;

    public function __construct($numero, $tipoCambio, $fechaEmision, $total,
        $moneda, $cliente, $fechaVencimiento, $puntoVenta,
        $detallesVenta, $domicilio, $partido, $digitoVerificador,
        $provincia, $CAE, $condicionImpositiva, $localidad, $facturasAsociadas){

        parent::__construct($numero, $tipoCambio, $fechaEmision, $total,
        $moneda, $cliente, $fechaVencimiento, $puntoVenta,
        $detallesVenta, $domicilio, $partido, $digitoVerificador,
        $provincia, $CAE, $condicionImpositiva, $localidad);

        $this->facturasAsociadas=$facturasAsociadas;
    }
}
<?php

namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class Presupuesto extends ComprobanteVenta{

    private static $sinIVA=true;

    public function __construct($tipoCambio, $moneda,
    $cliente, $detallesVenta, $descuento, $percepcionIIBB,
    $faeleService, $repoFactura, $repoCliente, $condicionVenta){

        parent::__construct($tipoCambio, 
        $moneda, $cliente, $detallesVenta, $descuento, $percepcionIIBB,     
        $faeleService, $repoFactura, $repoCliente, 'PRESUPUESTO', $condicionVenta);

        $this->setNumero(0); //para los presupuestos el numero de comprobante es 0
        $this->setCAE(0);
        $this->setVencimientoCAE(new \DateTime);
        $this->setDigitoVerificador(0);
        $this->setIva21(0);

    }

    /**
     * Get the value of sinIVA
     */ 
    public function getSinIVA()
    {
        return Presupuesto::$sinIva;
    }

    public function sosPresupuesto(){
        return true;
    }

    public function getTotalIVA(){
        return 0;
    }
}
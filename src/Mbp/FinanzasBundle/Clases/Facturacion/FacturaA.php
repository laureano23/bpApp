<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class FacturaA extends ComprobanteVenta{    
    private $faeleService;
    private $CUITCliente;
    private $montoPercepcionIIBB;
    private $IVA;
    private $percepcionIIBB;
    

    public function __construct($tipoCambio, $fechaEmision,
        $moneda, $cliente, $detallesVenta, $descuento, $percepcionIIBB,
        $faeleService, $repoFactura, $repoCliente, $repoFinanzas){

        parent::__construct($tipoCambio, $fechaEmision, 
        $moneda, $cliente, $detallesVenta, $descuento,        
        $faeleService, $repoFactura, $repoCliente, $repoFinanzas);

        $this->percepcionIIBB=$percepcionIIBB;

    }
}
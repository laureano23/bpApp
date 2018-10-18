<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class FacturaA extends ComprobanteVenta{    
    private $CUITCliente;
    private $montoPercepcionIIBB;
    private $IVA;
    private $percepciones;

    private static $tipoComprobante; //SEGUN TABLAS GENERALES DE AFIP
    private static $idOtrosTributos; //SEGUN TABLAS GENERALES DE AFIP


    public function __construct($tipoCambio, $moneda,
        $cliente, $detallesVenta, $descuento, $percepcionIIBB,
        $faeleService, $repoFactura, $repoCliente){

        parent::__construct($tipoCambio, 
        $moneda, $cliente, $detallesVenta, $descuento,        
        $faeleService, $repoFactura, $repoCliente);

        self::$tipoComprobante=1; //1= factura A
        self::$idOtrosTributos=2; //2= impuestos provinciales
        $this->percepciones=[];
        if($this->montoPercepcionIIBB!=0){
            array_push($percepciones, $this->montoPercepcionIIBB);
        }

        $this->percepcionIIBB=$percepcionIIBB;
        $this->generarFcElectronica();
        
    }

    public function getIdOtrosTributos(){
        return self::$idOtrosTributos;
    }

    public function generarFcElectronica(){
        $this->getFaeleService()->generarFc(
            self::$tipoComprobante, 
            self::$concepto, //1 es el concepto de productos, se puede implementar para servicios o productos y servicios
            $this->CUITCliente,
            $this->getFechaEmision(),
            $this->getImporteNetoGrabado(),
            $this->getTotalComprobante(),
            $this->getTotalIVA(),
            $this->getTotalPercepciones(),
            self::$impExcento,
            $this->getTotalComprobante(),
            $this->getFchServDesde(),
            $this->getFchServHasta(),
            $this->getFchVtoPago(),
            $this->getMoneda(),
            $this->getCotizacionMoneda(),
            $this->getIdOtrosTributos(),
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );
        
    }
    
    public function getTotalComprobante(){
        return $this->getTotalDetallesGrabados() + $this->getTotalDetallesNoGrabados() + $this->getTotalIVA() + $this->montoPercepcionIIBB - $this->getDescuento();
    }

    public function getTotalPercepciones(){
        $total=0;
        foreach ($this->percepciones as $per) {
            $total+=$per;
        }
    }

    
}
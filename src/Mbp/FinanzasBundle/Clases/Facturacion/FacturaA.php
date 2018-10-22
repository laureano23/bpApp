<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class FacturaA extends ComprobanteVenta{        
    private $montoPercepcionIIBB;
    private $IVA;

    //VARIABLES ESTATICAS
    private static $tipoComprobante=1; //SEGUN TABLAS GENERALES DE AFIP 1= factura A
    private static $idOtrosTributos=2; //SEGUN TABLAS GENERALES DE AFIP 2= impuestos provinciales
    private static $alicIVA21=0.21;
    private static $idIVA=5;             //SEGUN TABLAS GENERALES DE AFIP 5=21%


    public function __construct($tipoCambio, $moneda,
        $cliente, $detallesVenta, $descuento, $percepcionIIBB,
        $faeleService, $repoFactura, $repoCliente){

        parent::__construct($tipoCambio, 
        $moneda, $cliente, $detallesVenta, $descuento,        
        $faeleService, $repoFactura, $repoCliente);

        $this->montoPercepcionIIBB=$percepcionIIBB;

        $res=$this->generarFcElectronica();    
        $this->setCAE($res['cae']['cae']);
        $this->setVencimientoCAE(\DateTime::createFromFormat('Ymd', $res['cae']['fecha_vencimiento']));
    }

    

    public function getIdOtrosTributos(){
        return self::$idOtrosTributos;
    }

    public function getTotalIVA(){
        return $this->getImporteNetoGrabado() * self::$alicIVA21;
    }


    public function generarFcElectronica(){
        
        $res=$this->getFaeleService()->generarFc(
            self::$tipoComprobante, 
            self::$concepto, //1 es el concepto de productos, se puede implementar para servicios o productos y servicios
            $this->getDocCliente(),
            $this->getFechaEmision(),
            $this->getImporteNetoGrabado(),
            $this->getImporteNetoNoGrabado(),
            $this->getTotalIVA(),
            $this->getMontoPercepcion(),
            self::$impExcento,
            $this->getTotalComprobante(),
            $this->getFchServDesde(),
            $this->getFchServHasta(),
            $this->getFchVtoPago(),
            $this->getMoneda(),
            $this->getCotizacionMoneda(),
            $this->getIdOtrosTributos(),
            null,
            $this->getImporteNetoGrabado(),
            $this->getAlicuotaPercepcion(),
            $this->getMontoPercepcion(),
            self::$idIVA,
            $this->getImporteNetoGrabado(),
            $this->getTotalIVA()
        );

        return $res;
    }

    public function getMontoPercepcion(){
        return $this->montoPercepcionIIBB;
    }

    public function getAlicuotaPercepcion(){
        return round(($this->montoPercepcionIIBB * 100 / $this->getImporteNetoGrabado()), 2);
    }
    
    public function getTotalComprobante(){
        return parent::getTotalComprobante() + $this->getTotalIVA() + $this->getMontoPercepcion();
    }
    
}
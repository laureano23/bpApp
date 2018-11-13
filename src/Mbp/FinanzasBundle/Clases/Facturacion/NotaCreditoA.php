<?php

namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class NotaCreditoA extends ComprobanteVenta{
    private $facturasAsociadas;

    //VARIABLES ESTATICAS
    private static $tipoComprobante=3; //SEGUN TABLAS GENERALES DE AFIP 3= nota de credito A
    private static $idOtrosTributos=2; //SEGUN TABLAS GENERALES DE AFIP 2= impuestos provinciales    
    private static $idIVA=5;             //SEGUN TABLAS GENERALES DE AFIP 5=21%

    public function __construct($tipoCambio, $moneda,
        $cliente, $detallesVenta, $descuento, $percepcionIIBB,
        $faeleService, $repoFactura, $repoCliente, $facturasAsociadas){

        parent::__construct($tipoCambio, 
        $moneda, $cliente, $detallesVenta, $descuento, $percepcionIIBB, 
        $faeleService, $repoFactura, $repoCliente, 'NOTA CREDITO A');

        $this->repoFactura=$repoFactura;
        $this->montoPercepcionIIBB=$percepcionIIBB;
        
        $this->facturasAsociadas=$this->buscarCbtesAsociados($facturasAsociadas);

        $this->montoPercepcionIIBB=$percepcionIIBB;

        $res=$this->generarNCAElectronica(); 
        $this->setCAE($res['cae']['cae']);
        $this->setDigitoVerificador($res['digitoVerificador']);
        $this->setVencimientoCAE(\DateTime::createFromFormat('Ymd', $res['cae']['fecha_vencimiento']));
    }

    public function generarNCAElectronica(){
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
            $this->getTotalIVA(),
            $this->getCbtesAsociados()
        );


        return $res;
    }
    
    public function cargarParametrosFacturacion(){
        parent::cargarParametrosFacturacion();
        $res=$this->getFaeleService()->ultimoNroComp(self::$tipoComprobante);
        $this->setNumero($res['nro']++);
    }

    public function getIdOtrosTributos(){
        return self::$idOtrosTributos;
    }

    public function getCbtesAsociados(){
        return $this->facturasAsociadas;
    }

    //CbtesAsoc: Detalle de los comprobantes relacionados con el comprobante que se solicita autorizar
    public function buscarCbtesAsociados($facturasAsociadas){
        $compAsociados=$this->repoFactura->buscarCbtesAsociados($facturasAsociadas);
        $this->validarTotales($compAsociados);
        return $compAsociados;
    }

    public function validarTotales($compAsociados){
        $total=0;
        foreach ($compAsociados as $c) {
            $total+=$c['total'];
        }
        
        if(\bccomp($total, round($this->getTotalComprobante(), 2), 2) != 0 && $total!=0){
            throw new \Exception("El total de la NC debe ser igual al total de los comprobantes imputados", 1);            
        }        
    }

    public function getFacturasAsociadas(){
        return $this->facturasAsociadas;
    }
    
    public function getTotalComprobante(){
        return parent::getTotalComprobante() + $this->getTotalIVA() + $this->getMontoPercepcion();
    }

    public function sosNotaCreditoA(){
        return true;
    }
}
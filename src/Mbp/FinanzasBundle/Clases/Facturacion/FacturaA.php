<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteVenta;
use Mbp\FinanzasBundle\Clases\Facturacion\DetalleVentas;

class FacturaA extends ComprobanteVenta{        
    private $numero;

    //VARIABLES ESTATICAS
    private static $tipoComprobante=1; //SEGUN TABLAS GENERALES DE AFIP 1= factura A
    private static $idOtrosTributos=2; //SEGUN TABLAS GENERALES DE AFIP 2= impuestos provinciales
    private static $alicIVA21=0.21;
    private static $idIVA=5;             //SEGUN TABLAS GENERALES DE AFIP 5=21%


    public function __construct($tipoCambio, $moneda,
        $cliente, $detallesVenta, $descuento, $percepcionIIBB,
        $faeleService, $repoFactura, $repoCliente){

        parent::__construct($tipoCambio, 
        $moneda, $cliente, $detallesVenta, $descuento, $percepcionIIBB,     
        $faeleService, $repoFactura, $repoCliente, 'FACTURA A');

        $this->montoPercepcionIIBB=$percepcionIIBB;

        $res=$this->generarFcElectronica();   
        
        $this->setCAE($res['cae']['cae']);
        $this->setDigitoVerificador($res['digitoVerificador']);
        $this->setVencimientoCAE(\DateTime::createFromFormat('Ymd', $res['cae']['fecha_vencimiento']));
    }

    public function getTotalComprobante(){
        return parent::getTotalComprobante() + $this->getTotalIVA() + $this->getMontoPercepcion();
    }

    public function getIdOtrosTributos(){
        return self::$idOtrosTributos;
    }

    public function cargarParametrosFacturacion(){
        parent::cargarParametrosFacturacion();
        $res=$this->getFaeleService()->ultimoNroComp(self::$tipoComprobante);
        if(\array_key_exists('nro', $res)){
            $this->numero=$res['nro'];
        }
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
            $this->getTotalIVA(),
            null
        );

        return $res;
    }

    
    

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }
}
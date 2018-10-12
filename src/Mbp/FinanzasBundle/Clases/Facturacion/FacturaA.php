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
    

    public function __construct($tipoCambio, $fechaEmision,
        $moneda, $cliente, $detallesVenta, $descuento, $percepcionIIBB,
        $faeleService, $repoFactura, $repoCliente){

        parent::__construct($tipoCambio, $fechaEmision, 
        $moneda, $cliente, $detallesVenta, $descuento,        
        $faeleService, $repoFactura, $repoCliente);

        self::$tipoComprobante=1;
        $percepciones=[];
        if($percepcionIIBB!=0){
            array_push($percepciones, $percepcionIIBB);
        }

        $this->percepcionIIBB=$percepcionIIBB;
        $this->cargarDatosFcElectronica();
    }

    public function generarFcElectronica(){
        $this->faeleService->generarFc(
            self::$tipoComprobante, 
            1, //1 es el concepto de productos, se puede implementar para servicios o productos y servicios
            $this->CUITCliente,
            $this->fechaEmision,
            $this->getImporteNeto(),
            $this->getTotalComprobante(),
            $this->getTotalIVA(),
            $this->getTotalPercepciones(),
            self::$impExcento,
            $this->getTotalComprobante()
        );
    }
    
    public function getTotalComprobante(){
        return $this->getTotalDetallesGrabados() + $this->getTotalDetallesNoGrabados() + $this->getTotalIVA() + $this->montoPercepcionIIBB - $this->descuento;
    }

    public function getTotalPercepciones(){
        $total=0;
        foreach ($this->percepciones as $per) {
            $total+=$per;
        }
    }
}
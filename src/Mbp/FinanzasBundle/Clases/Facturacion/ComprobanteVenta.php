<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteComercial;

abstract class ComprobanteVenta extends ComprobanteComercial{
    private $cliente;
    private $clienteNombre;
    private $fechaVencimiento;
    private $puntoVenta;
    private $detallesVenta;
    private $domicilio;
    private $partido;
    private $digitoVerificador;
    private $provincia;
    private $CAE;
    private $condicionImpositiva;
    private $localidad;
    private $descuento;
    protected static $concepto;//ESTE DATO DEBE VENIR DEL CLIENTE 1=PRODUCTOS, 2=SERVICIOS, 3=PRODUCTOS Y SERVICIOS
    private static $alicIVA21;
    private static $impExcento; //no implementado, = 0

    private $repoFactura;
    private $repoCliente;
    private $faeleService;


    
    public function __construct($tipoCambio, $fechaEmision,
    $moneda, $cliente, $detallesVenta, $descuento,
    $faeleService, $repoFactura, $repoCliente){
            
        parent::__construct($tipoCambio, $fechaEmision, $moneda);

        self::$concepto=1;
        self::$alicIVA21=0.21;
        self::$impExcento=0;

        $this->repoFactura=$repoFactura;
        $this->repoCliente=$repoCliente;
        $this->faeleService=$faeleService;
        $this->detallesVenta=[];
        $this->descuento=$descuento;

        $this->cargarInfoCliente($cliente);        
        $this->cargarDetallesVenta($detallesVenta);
    }

    private function cargarDatosCbteElectronico(){        
        
    }


    protected function cargarParametrosFacturacion(){
        $this->puntoVenta=$faeleService->getPuntoVenta();

    }

    protected function cargarDetallesVenta($detallesVenta){
        if(!is_array($detallesVenta)) throw new Exception("Formato de detalles de venta incorrecto", 1);                
        if(empty($detallesVenta)) throw new Exception("No se enviaron productos para facturar", 1);
        
        
        foreach ($detallesVenta as $d) {
            array_push($this->detallesVenta, $d);
        }
    }

    protected function cargarInfoCliente($cliente){
        $clienteInfo=$this->repoCliente->getInfoClienteFacturacion($cliente);

        if(empty($clienteInfo)) throw new Exception("Cliente no encontrado", 1);

        $this->localidad=$clienteInfo['localidad'];
        $this->partido=$clienteInfo['partido'];
        $this->provincia=$clienteInfo['provincia'];
        $this->clienteNombre=$clienteInfo['nombre'];
        $this->condicionImpositiva=$clienteInfo['posicion'];

        if(empty($clienteInfo['vencimientoFc'])){
            $this->fechaVencimiento=new \DateTime();
        }else{
            $this->fechaVencimiento=new \DateTime() + $clienteInfo['vencimientoFc'];
        }        
    }

    protected function getTotalDetallesGrabados(){
        $total=0;
        foreach ($this->detallesVenta as $det) {
            if($det->ivaGrabado){
                $total+=$det->precio * $det->cantidad;
            }            
        }
        return $total;
    }

    protected function getTotalDetallesNoGrabados(){
        $total=0;
        foreach ($this->detallesVenta as $det) {
            if(!$det->ivaGrabado){
                $total+=$det->precio * $det->cantidad;
            } 
        }
        return $total;
    }

    
    protected function getImporteNetoGrabado(){
        return $this->getTotalDetallesGrabados()-$this->descuento;
    }

    protected function getTotalComprobante(){
        return $this->getTotalDetallesGrabados() + $this->getTotalDetallesNoGrabados() + $this->getTotalIVA() - $this->descuento;
    }

    protected function getTotalIVA(){
        $this->getImporteNetoGrabado * self::$alicIVA21;
    }
}
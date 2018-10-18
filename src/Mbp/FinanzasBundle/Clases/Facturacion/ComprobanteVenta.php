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
    public static $alicIVA21;
    public static $impExcento; //no implementado, = 0
    private $fchServDesde;
    private $fchServHasta;

    private $repoFactura;
    private $repoCliente;
    private $faeleService;


    
    public function __construct($tipoCambio,
    $moneda, $cliente, $detallesVenta, $descuento,
    $faeleService, $repoFactura, $repoCliente){
            
        parent::__construct($tipoCambio, $moneda);

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

    public function getFaeleService(){
        return $this->faeleService;
    }

    protected function getFchServDesde(){
        if(self::$concepto==1){
            return null;
            
        }else{
            return date("Ymd");
        }
    }

    protected function getFchServHasta(){
        if(self::$concepto==1){
            return null;
            
        }else{
            return date("Ymd");
        }
    }

    protected function getFchVtoPago(){
        if(self::$concepto==1){
            return null;
            
        }else{
            return date("Ymd");
        }
    }

    protected function cargarDatosCbteElectronico(){        
        
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
        $this->getImporteNetoGrabado() * self::$alicIVA21;
    }

    public function getDescuento(){
        return $this->descuento;
    }

    //se pued ampliar implementarcion si posteriormente se facturan distintos tipos de IVA
    //se deja 21% por default
    public function getIdIVA(){
        $resIVA=$this->getFaeleService()->FEParamGetTiposIva();
        $i=0;
        while($i < sizeof($resIVA) && $resIVA[$i]->Desc=='21%'){            
            $i++;
        }

        if($i >= sizeof($resIVA)){
            throw new Exception("No se encontró el ID del IVA", 1);            
        }else{
            return $resIVA[$i]->Id;
        }
    }
}
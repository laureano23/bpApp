<?php
namespace Mbp\FinanzasBundle\Clases\Facturacion;
use Mbp\FinanzasBundle\Clases\Facturacion\ComprobanteComercial;

abstract class ComprobanteVenta extends ComprobanteComercial{
    private $cliente;
    private $clienteNombre;
    private $fechaVencimiento;
    private $puntoVenta;    
    private $numero;
    private $detallesVenta;
    private $domicilio;
    private $partido;
    private $digitoVerificador;
    private $provincia;
    private $CAE;
    private $vencimientoCAE;
    private $condicionImpositiva;
    private $localidad;
    private $descuento;    
    private $fchServDesde;
    private $fchServHasta;
    private $docCliente;
    private $montoPercepcionIIBB;
    private $condicionVenta;

    //VARIABLES ESTATICAS
    protected static $concepto=1;//ESTE DATO DEBE VENIR DEL CLIENTE 1=PRODUCTOS, 2=SERVICIOS, 3=PRODUCTOS Y SERVICIOS    
    public static $impExcento=0; //no implementado, = 0
    private static $alicIVA21=0.21;
    //TABLAS IVA AFIP
    private static $noGrabado=1;
    private static $excento=2;
    private static $sinIva=3;
    private static $iva10=4;
    private static $iva21=5;
    private static $iva27=6;

    private $repoFactura;
    private $repoCliente;
    private $faeleService;


    
    public function __construct($tipoCambio,
    $moneda, $cliente, $detallesVenta, $descuento, $percepcionIIBB,
    $faeleService, $repoFactura, $repoCliente, $descripcionCbte, $condicionVenta){
            
        parent::__construct($tipoCambio, $moneda, $descripcionCbte);
        $this->repoFactura=$repoFactura;
        $this->repoCliente=$repoCliente;
        $this->faeleService=$faeleService;
        $this->detallesVenta=[];
        $this->descuento=$descuento;
        $this->montoPercepcionIIBB=$percepcionIIBB;
        $this->cliente=$cliente;
        $this->condicionVenta=$condicionVenta;

        $this->cargarParametrosFacturacion();
        $this->cargarInfoCliente($cliente);        
        $this->cargarDetallesVenta($detallesVenta);
    }

    public function getTotalIVA(){
        return $this->getImporteNetoGrabado() * self::$alicIVA21;
    }

    public function getFaeleService(){
        return $this->faeleService;
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
        $this->puntoVenta=$this->faeleService->getPuntoVenta();

    }

    protected function cargarDetallesVenta($detallesVenta){
        if(!is_array($detallesVenta)) throw new \Exception("Formato de detalles de venta incorrecto", 1);                
        if(empty($detallesVenta)) throw new \Exception("No se enviaron productos para facturar", 1);
        
        
        foreach ($detallesVenta as $d) {
            array_push($this->detallesVenta, $d);
        }
    }

    protected function cargarInfoCliente($cliente){
        $clienteInfo=$this->repoCliente->getInfoClienteFacturacion($cliente);
        if(empty($clienteInfo)) throw new \Exception("Cliente no encontrado", 1);
        

        $this->localidad=$clienteInfo['localidad'];
        $this->partido=$clienteInfo['partido'];
        $this->provincia=$clienteInfo['provincia'];
        $this->clienteNombre=$clienteInfo['nombre'];
        $this->condicionImpositiva=$clienteInfo['posicion'];
        $this->docCliente=$clienteInfo['cuit'];

        $fecha=new \DateTime();
        if(empty($clienteInfo['vencimientoFc'])){
            $this->fechaVencimiento=$fecha;
        }else{
            $this->fechaVencimiento=$fecha->modify("+".$clienteInfo['vencimientoFc']."day");
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

    protected function getImporteNetoNoGrabado(){
        if($this->getTotalDetallesNoGrabados()==0){
            return $this->getTotalDetallesNoGrabados();
        }else{
            return $this->getTotalDetallesNoGrabados()-$this->getDescuento();
        }
    }
    
    protected function getImporteNetoGrabado(){
        return $this->getTotalDetallesGrabados()-$this->getDescuento();
    }

    protected function getTotalComprobante(){
        return $this->getTotalDetallesGrabados() + $this->getTotalDetallesNoGrabados() - $this->getDescuento();
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
            throw new \Exception("No se encontró el ID del IVA", 1);            
        }else{
            return $resIVA[$i]->Id;
        }
    }

    public function getMontoPercepcion(){
        return $this->montoPercepcionIIBB;
    }

    public function getAlicuotaPercepcion(){
        if($this->getImporteNetoGrabado() > 0){
            return round(($this->montoPercepcionIIBB * 100 / $this->getImporteNetoGrabado()), 2);
        }
        return 0;
    }
    
    
    public function sosFacturaA(){
        return false;
    }
    
    public function sosNotaCreditoA(){
        return false;
    }

    public function sosNotaDebitoA(){
        return false;
    }

    /**
     * Get the value of cliente
     */ 
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set the value of cliente
     *
     * @return  self
     */ 
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get the value of clienteNombre
     */ 
    public function getClienteNombre()
    {
        return $this->clienteNombre;
    }

    /**
     * Set the value of clienteNombre
     *
     * @return  self
     */ 
    public function setClienteNombre($clienteNombre)
    {
        $this->clienteNombre = $clienteNombre;

        return $this;
    }

    /**
     * Get the value of fechaVencimiento
     */ 
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set the value of fechaVencimiento
     *
     * @return  self
     */ 
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get the value of puntoVenta
     */ 
    public function getPuntoVenta()
    {
        return $this->puntoVenta;
    }

    /**
     * Set the value of puntoVenta
     *
     * @return  self
     */ 
    public function setPuntoVenta($puntoVenta)
    {
        $this->puntoVenta = $puntoVenta;

        return $this;
    }

    /**
     * Get the value of detallesVenta
     */ 
    public function getDetallesVenta()
    {
        return $this->detallesVenta;
    }

    /**
     * Set the value of detallesVenta
     *
     * @return  self
     */ 
    public function setDetallesVenta($detallesVenta)
    {
        $this->detallesVenta = $detallesVenta;

        return $this;
    }

    /**
     * Get the value of domicilio
     */ 
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set the value of domicilio
     *
     * @return  self
     */ 
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get the value of partido
     */ 
    public function getPartido()
    {
        return $this->partido;
    }

    /**
     * Set the value of partido
     *
     * @return  self
     */ 
    public function setPartido($partido)
    {
        $this->partido = $partido;

        return $this;
    }

    /**
     * Get the value of digitoVerificador
     */ 
    public function getDigitoVerificador()
    {
        return $this->digitoVerificador;
    }

    /**
     * Set the value of digitoVerificador
     *
     * @return  self
     */ 
    public function setDigitoVerificador($digitoVerificador)
    {
        $this->digitoVerificador = $digitoVerificador;

        return $this;
    }

    /**
     * Get the value of provincia
     */ 
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set the value of provincia
     *
     * @return  self
     */ 
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get the value of CAE
     */ 
    public function getCAE()
    {
        return $this->CAE;
    }

    /**
     * Set the value of CAE
     *
     * @return  self
     */ 
    public function setCAE($CAE)
    {
        $this->CAE = $CAE;

        return $this;
    }

    /**
     * Get the value of vencimientoCAE
     */ 
    public function getVencimientoCAE()
    {
        return $this->vencimientoCAE;
    }

    /**
     * Set the value of vencimientoCAE
     *
     * @return  self
     */ 
    public function setVencimientoCAE($vencimientoCAE)
    {
        $this->vencimientoCAE = $vencimientoCAE;

        return $this;
    }

    /**
     * Get the value of condicionImpositiva
     */ 
    public function getCondicionImpositiva()
    {
        return $this->condicionImpositiva;
    }

    /**
     * Set the value of condicionImpositiva
     *
     * @return  self
     */ 
    public function setCondicionImpositiva($condicionImpositiva)
    {
        $this->condicionImpositiva = $condicionImpositiva;

        return $this;
    }

    /**
     * Get the value of localidad
     */ 
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set the value of localidad
     *
     * @return  self
     */ 
    public function setLocalidad($localidad)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get the value of descuento
     */ 
    public function getDescuento()
    {
        return ($this->getTotalDetallesGrabados()+$this->getTotalDetallesNoGrabados()) * ($this->descuento/100);
    }

    /**
     * Set the value of descuento
     *
     * @return  self
     */ 
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get the value of fchServDesde
     */ 
    public function getFchServDesde(){
        if(self::$concepto==1){
            return null;
            
        }else{
            return date("Ymd");
        }
    }

    /**
     * Set the value of fchServDesde
     *
     * @return  self
     */ 
    public function setFchServDesde($fchServDesde)
    {
        $this->fchServDesde = $fchServDesde;

        return $this;
    }

    /**
     * Get the value of fchServHasta
     */ 
    public function getFchServHasta(){
        if(self::$concepto==1){
            return null;
            
        }else{
            return date("Ymd");
        }
    }

    /**
     * Set the value of fchServHasta
     *
     * @return  self
     */ 
    public function setFchServHasta($fchServHasta)
    {
        $this->fchServHasta = $fchServHasta;

        return $this;
    }

    /**
     * Get the value of docCliente
     */ 
    public function getDocCliente()
    {
        return $this->docCliente;
    }

    /**
     * Set the value of docCliente
     *
     * @return  self
     */ 
    public function setDocCliente($docCliente)
    {
        $this->docCliente = $docCliente;

        return $this;
    }

    /**
     * Get the value of repoFactura
     */ 
    public function getRepoFactura()
    {
        return $this->repoFactura;
    }

    /**
     * Set the value of repoFactura
     *
     * @return  self
     */ 
    public function setRepoFactura($repoFactura)
    {
        $this->repoFactura = $repoFactura;

        return $this;
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

    /**
     * Get the value of sinIva
     */ 
    public function getSinIva()
    {
        return self::$sinIva;
    }

    /**
     * Set the value of sinIva
     *
     * @return  self
     */ 
    public function setSinIva($sinIva)
    {
        self::$sinIva = $sinIva;

        return $this;
    }

    /**
     * Get the value of iva21
     */ 
    public function getIva21()
    {
        return self::$iva21;
    }

    /**
     * Set the value of iva21
     *
     * @return  self
     */ 
    public function setIva21($iva21)
    {
        self::$iva21 = $iva21;

        return $this;
    }

    /**
     * Get the value of condicionVenta
     */ 
    public function getCondicionVenta()
    {
        return $this->condicionVenta;
    }

    /**
     * Set the value of condicionVenta
     *
     * @return  self
     */ 
    public function setCondicionVenta($condicionVenta)
    {
        $this->condicionVenta = $condicionVenta;

        return $this;
    }
}
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

    private $repoFactura;
    private $repoCliente;
    private $repoFinanzas;
    private $faeleService;
    
    public function __construct($tipoCambio, $fechaEmision,
    $moneda, $cliente, $detallesVenta, $descuento,
    $faeleService, $repoFactura, $repoCliente, $repoFinanzas){
            
        parent::__construct($tipoCambio, $fechaEmision, $moneda);
        $this->repoFactura=$repoFactura;
        $this->repoCliente=$repoCliente;
        $this->repoFinanzas=$repoFinanzas;
        $this->faeleService=$faeleService;

        $datosCliente=$this->cargarInfoCliente($cliente);

        $this->detallesVenta=$detallesVenta;

        
    }

    protected function cargarInfoCliente($cliente){
        \print_r($this->cliente);
        \var_dump($this->cliente);
        echo \get_class($this->cliente);
        $clienteInfo=$this->repoCliente->getInfoClienteFacturacion($cliente);

        if(empty($clienteInfo)) throw new Exception("Cliente no encontrado", 1);

        $this->localidad=$clienteInfo['localidad'];
        $this->partido=$clienteInfo['partido'];
        $this->provincia=$clienteInfo['provincia'];
        $this->clienteNombre=$clienteInfo['nombre'];
        
    }
}
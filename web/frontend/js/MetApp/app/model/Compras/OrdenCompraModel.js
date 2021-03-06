Ext.define('MetApp.model.Compras.OrdenCompraModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idDetalleOrden',
	fields: [
		{ name: 'idOc', type: 'int' },
		{ name: 'referenciaOc', type: 'int' },
		{ name: 'idDetalleOrden', type: 'int' },
		{ name: 'fecha', type: 'datetime' },
		{ name: 'id', type: 'int' },
		{ name: 'codigo', type: 'string' },
		{ name: 'proveedor', type: 'string' },
		{ name: 'descripcion', type: 'string'},
		{ name: 'cant', type: 'float' },
		{ name: 'cumplido', type: 'int' },
		{ name: 'unidad', type: 'string' },
		{ name: 'precio', type: 'float' },
		{ name: 'pendiente', type: 'float' },
		{ name: 'costo', type: 'float' },
		{ name: 'moneda', type: 'string' },
		{ name: 'iva', type: 'float' },
		{ name: 'entrega', type: 'datetime' },
		{ name: 'actCosto', type: 'bool', defaultValue: 1 },
		{ name: 'observaciones', type: 'string'},
		{ name: 'descuentoGral', type: 'float' },
		{ name: 'loteNum', type: 'int' },
		{ name: 'pedido', type: 'int' },
		{ name: 'estadoCalidad', type: 'string' },
		{ name: 'certificadoNum', type: 'string' },
		{ name: 'detalleControl', type: 'string' },
		{ name: 'comprobante', type: 'string' },				
	]
});

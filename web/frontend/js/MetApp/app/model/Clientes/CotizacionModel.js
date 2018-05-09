Ext.define('MetApp.model.Clientes.CotizacionModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'idCoti', type: 'int'},
		{name: 'id', type: 'int'},				
		{name: 'observaciones', type: 'string'},
		{name: 'direccion', type: 'string'},
		{name: 'cuit', type: 'string'},
		{name: 'condVenta', type: 'string'},
		{name: 'monedaCoti', type: 'string'},
		{name: 'tc', type: 'string'},
		{ name: 'idOc', type: 'int' },
		{ name: 'fecha', type: 'datetime' },
		{ name: 'id', type: 'int' },
		{ name: 'codigo', type: 'string' },
		{ name: 'cliente', type: 'string' },
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
	],
	idProperty: 'idCoti',
	proxy: {		
		type: 'ajax',
		filterParam: 'filter',	
		api: {
			create: Routing.generate("mbp_finanzas_cotizaciones_nueva"),
			read: Routing.generate("mbp_finanzas_cotizaciones_nueva"),		
			destroy: Routing.generate("mbp_finanzas_cotizaciones_nueva")		
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'idCoti',
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});

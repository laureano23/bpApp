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
	],
	idProperty: 'idCoti',
	proxy: {		
		type: 'ajax',
		filterParam: 'filter',	
		api: {
			create: Routing.generate("mbp_finanzas_cotizaciones_nueva"),
			read: Routing.generate("mbp_finanzas_cotizaciones_nueva"),		
			update: Routing.generate("mbp_finanzas_cotizaciones_nueva"),
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

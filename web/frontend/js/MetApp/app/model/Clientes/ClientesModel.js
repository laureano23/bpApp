Ext.define('MetApp.model.Clientes.ClientesModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'rsocial', type: 'string'},
		{name: 'denominacion', type: 'string'},
		{name: 'direccion', type: 'string'},
		{name: 'email', type: 'string'},
		{name: 'cuit', type: 'int'},
		{name: 'cPostal', type: 'string'},
		{name: 'iva', type: 'int'},
		{name: 'telefono1', type: 'string'},
		{name: 'contacto1', type: 'string'},
		{name: 'telefono2', type: 'string'},
		{name: 'contacto2', type: 'string'},
		{name: 'telefono3', type: 'string'},
		{name: 'contacto3', type: 'string'},
		{name: 'condVenta', type: 'string'},
		{name: 'vencimientoFc', type: 'int'},
		{name: 'netoPercepcion', type: 'int'},
		{name: 'porcentajePercepcion', type: 'float'},
		{name: 'localidad', type: 'int'},
		{name: 'departamento', type: 'int'},
		{name: 'provincia', type: 'string'},
		{name: 'cuentaCerrada', type: 'boolean'},
		{name: 'transporte', type: 'int'},
		{name: 'intereses', type: 'boolean'},
		{name: 'tasa', type: 'string'},
		{name: 'descuentoFijo', type: 'string'},
		{name: 'notasCC', type: 'string'},
		{name: 'vendedor', type: 'int'},
		{name: 'comision', type: 'float'},
		{name: 'posicion', type: 'string'},
		{name: 'noAplicaPercepcion', type: 'boolean'},						
		{name: 'observaciones', type: 'string'}
	],
	proxy: {
		type: 'ajax',
		filterParam: 'filter',	
		api: {
			create: Routing.generate('mbp_clientes_new'),
			read: Routing.generate('mbp_clientes_search'),			
			update: Routing.generate('mbp_clientes_new'),
			destroy: Routing.generate('mbp_clientes_new')			
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'id',
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});

Ext.define('MetApp.model.Proveedores.ProveedoresModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'rSocial', type: 'string'},
		{name: 'rsocial', type: 'string'},
		{name: 'denominacion', type: 'string'},
		{name: 'direccion', type: 'string'},
		{name: 'email', type: 'string'},
		{name: 'cuit', type: 'int'},
		{name: 'cPostal', type: 'string'},
		{name: 'telefono1', type: 'string'},
		{name: 'contacto1', type: 'string'},
		{name: 'telefono2', type: 'string'},
		{name: 'contacto2', type: 'string'},
		{name: 'telefono3', type: 'string'},
		{name: 'contacto3', type: 'string'},
		{name: 'condCompra', type: 'string'},
		{name: 'vencimientoFc', type: 'int'},
		{name: 'noAplicaRetencion', type: 'bool'},
		{name: 'porcentajeRetencion', type: 'float'},
		{name: 'tipoGasto', type: 'int'},
		{name: 'localidad', type: 'int'},
		{name: 'departamento', type: 'int'},
		{name: 'provincia', type: 'string'},
		{name: 'cuentaCerrada', type: 'boolean'},
		{name: 'notasCC', type: 'string'},
	],
	
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		api: {
			read: Routing.generate('mbp_proveedores_listar'),
			create: Routing.generate('mbp_proveedores_nuevo'),
			update: Routing.generate('mbp_proveedores_nuevo'),			
		},
		
		reader: {
			type: 'json',
			root: 'data'
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});

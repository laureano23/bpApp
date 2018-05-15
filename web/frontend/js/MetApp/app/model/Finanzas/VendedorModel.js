Ext.define('MetApp.model.Finanzas.VendedorModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idVendedorModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'nombre', type: 'string'},
		{ name: 'apellido', type: 'string'},
		{ name: 'telefono', type: 'string'},
		{ name: 'inactivo', type: 'string'},		
	],
	
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_finanzas_listarVendedores'),			
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

Ext.define('MetApp.model.Clientes.TransportesModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'nombre', type: 'string'},
		{name: 'direccion', type: 'string'},
		{name: 'contacto', type: 'string'},
		{name: 'horarios', type: 'string'},
		{name: 'telefono', type: 'string'},
		{name: 'departamento', type: 'string'},
		{name: 'provincia', type: 'string'},	
	]
});

Ext.define('MetApp.model.Articulos.Articulos',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'codigo', type: 'string'},
		{name: 'descripcion', type: 'string'},
		{name: 'unidad', type: 'string'},
		{name: 'costo', type: 'float'},
		{name: 'iva', type: 'float'},
		{name: 'precio', type: 'float'},
		{name: 'monedaPrecio', type: 'string'},
		{
			name: 'moneda',
		 	type: 'string',
			convert: function(val){
				if(val == false || val=='p'){
					return 'p';
				}else{
					return 'd';
				}
			}
		},
		{name: 'familia', type: 'string'},
		{name: 'subFamilia', type: 'string'},
		{name: 'nombreImagen', type: 'string'},
		{name: 'rutaServer', type: 'string'},
		{name: 'peso', type: 'string'},
		{name: 'requiereControl', type: 'string'},
		{name: 'vigenciaPrecio', type: 'datetime', dateFormat: 'c' },			
	]
});


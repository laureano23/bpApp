Ext.define('MetApp.model.Articulos.RemitosModel',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'fecha', type: 'date', dateFormat: 'd/m/Y'},
		{name: 'remitoNum', type: 'string'},
		{name: 'cliente', type: 'string'},
		{name: 'proveedor', type: 'string'},
		{name: 'descripcion', type: 'string'},
		{name: 'cantidad', type: 'string'},
		{name: 'unidad', type: 'string'},
		{name: 'oc', type: 'string'},
		{name: 'codigo', type: 'string'},
		{name: 'pedido', type: 'string'},
		{name: 'facturado', type: 'bool'},
		{name: 'costo', type: 'float'},
		{name: 'precio', type: 'float'},
		{name: 'parcial', type: 'float'},
		{name: 'monedaPrecio', type: 'bool'},
	]
});

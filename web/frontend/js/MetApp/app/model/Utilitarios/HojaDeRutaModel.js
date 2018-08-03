Ext.define('MetApp.model.Utilitarios.HojaDeRutaModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idViaje',
	fields: [
		{name: 'idViaje', type: 'int'},
		{name: 'fechaEmision', type: 'datetime'},
		{name: 'fechaDesde', type: 'datetime'},
		{name: 'fechaHasta', type: 'datetime'},
		{name: 'nombre', type: 'string'},
		{name: 'domicilio', type: 'string'},
		{name: 'horarios', type: 'string'},
		{name: 'descripcion', type: 'string'},
		{name: 'emisor', type: 'string'},
		{name: 'estado', type: 'string'}, //no comenzado, terminado, etc
		{name: 'autorizado', type: 'bool'}
	]
});

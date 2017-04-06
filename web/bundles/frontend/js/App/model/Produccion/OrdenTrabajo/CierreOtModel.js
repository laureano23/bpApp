Ext.define('MetApp.model.Produccion.OrdenTrabajo.CierreOtModel', {
	extend: 'Ext.data.Model',
	idProperty : 'otNum',
	clientIdProperty: 'cierreOtIdModel',
	fields: [
		{ name: 'otNum', type: 'int' },
		{ name: 'otEmision', type: 'datetime', },
		{ name: 'cliente', type: 'string' },
		{ name: 'codigo', type: 'string' },
		{ name: 'descripcion', type: 'string' },
		{ name: 'totalOt', type: 'float' },
		{ name: 'programado', type: 'datetime' },
		{ name: 'pendiente', type: 'float' },
		{ name: 'aprobado', type: 'float' },
		{ name: 'rechazado', type: 'float' },
	]
});
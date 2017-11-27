Ext.define('MetApp.model.Produccion.CalculoRadiadores.OtModel', {
	extend: 'Ext.data.Model',
	idProperty : 'id',
	fields: [
		{ name: 'id', type: 'int' },				//Id del articulos a calcular
		{ name: 'ot', type: 'int' },
		{ name: 'idCodigo', type: 'int' },	
		{ name: 'idCliente', type: 'int' },
		{ name: 'codigo', type: 'string' },
		{ name: 'descripcion', type: 'string' },
		{ name: 'rsocial', type: 'string' },
		{ name: 'cantidad', type: 'int' },
		{ name: 'apoyoTapas', type: 'int' },
		{ name: 'prof', type: 'int' },
		{ name: 'ancho', type: 'int' },		
	]
});
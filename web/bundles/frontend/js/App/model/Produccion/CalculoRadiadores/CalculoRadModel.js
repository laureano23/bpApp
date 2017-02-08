Ext.define('MetApp.model.Produccion.CalculoRadiadores.CalculoRadModel', {
	extend: 'Ext.data.Model',
	idProperty : 'idCalculo',
	clientIdProperty: 'clientId',
	fields: [
		{ name: 'id', type: 'int' },				//Id del articulos a calcular
		{ name: 'aletaTipo', type: 'string' },		//Define si es abierta o cerrada
		{ name: 'clientId', type: 'int' },
		{ name: 'idCalculo', type: 'int' },
		{ name: 'descripcion', type: 'string' },
		{ name: 'maxAlt', type: 'int' },
		{ name: 'cantPaneles', type: 'int' },
		{ name: 'pisosManual7', type: 'int' },
		{ name: 'cantAdicional', type: 'int' },
		{ name: 'chapaPiso', type: 'int' },
		{ name: 'perfilIntermedio', type: 'int' },
		{ name: 'pisosManual', type: 'int' },
		{ name: 'ancho', type: 'string' },
		{ name: 'prof', type: 'string' },
		{ name: 'apoyoTapas', type: 'string' },
		{ name: 'aletaFluA', type: 'string' },
		{ name: 'aletaVenA', type: 'string' },
		{ name: 'tipo', type: 'string' },
		{ name: 'largo', type: 'int' },
		{ name: 'ancho', type: 'int' },
		{ name: 'espesor', type: 'float' },
		{ name: 'pisosp1', type: 'int' },
		{ name: 'cantp1', type: 'int' },
		{ name: 'pisosp2', type: 'int' },
		{ name: 'cantp2', type: 'int' },
		{ name: 'pisosp3', type: 'int' },
		{ name: 'cantp3', type: 'int' },
		{ name: 'pisosp4', type: 'int' },
		{ name: 'cantp4', type: 'int' }			
	]
});

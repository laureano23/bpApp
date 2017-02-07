Ext.define('MetApp.model.Produccion.Programacion.FormulasMoModel', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'idOperacion', type: 'auto' },
		{ name: 'codigo', type: 'string' },
		{ 
			name: 'tiempo',
		 	type: 'auto',
		}
	]
});
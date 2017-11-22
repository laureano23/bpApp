Ext.define('MetApp.model.Produccion.Programacion.ProgramacionModel', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'int' },
		{ 
			name: 'idOperacion',
			type: 'auto',			
		},
		{ name: 'codigo', type: 'string' },
		{ 
			name: 'tiempo',
		 	type: 'auto',
		 	
		},
		{ name: 'idProgramacion', type: 'auto' },
		{ name: 'maquina', type: 'auto' },
		{ name: 'idMaquina', type: 'auto' }
	]
});
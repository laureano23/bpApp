Ext.define('MetApp.model.Personal.ConceptosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'descripcion', type: 'string'},
		{ 
			name: 'remunerativo',
			type: 'int'
		},
		{ 
			name: 'descuento',
			type: 'int'
		},
		{ 
			name: 'asignacion',
			type: 'int'
		},
		{ 
			name: 'fijo',
			type: 'int'
		},
		{ 
			name: 'noRemunerativo',
			type: 'int'
		},
		{ name: 'inactivo', type: 'boolean'},
		{ name: 'quincena1', type: 'auto'},
		{ name: 'quincena2', type: 'auto'},
		{ name: 'mes', type: 'auto'},
		{ name: 'vacaciones', type: 'auto'},
		{ name: 'sac', type: 'auto'},
		{ name: 'otros', type: 'auto'},
		{ name: 'premios1', type: 'auto'},
		{ name: 'premios2', type: 'auto'},
		{ name: 'liquidacionFinal', type: 'auto'},
		{ name: 'calculoTipo', type: 'string'},
		{ name: 'importe', type: 'float'},
		{ name: 'porcentaje', type: 'boolean'},
		{ name: 'empleadoSueldo', type: 'float'},
		{ name: 'codigoCalculo', type: 'int'},
		{ name: 'unidad', type: 'string'},
		{ name: 'codigoObservacion', type: 'string'},		
	]
});

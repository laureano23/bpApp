Ext.define('MetApp.model.Produccion.CalculoRadiadores.BrazingParams', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'int' },	
		{ 
			name: 'tiempoCarga',
			type: 'datetime',
			dateFormat: 'g:i:s',
			convert: function(v, rec){
				newDate = new Date(v.date);
				return newDate;
			} 
		},
		{ name: 'ciclos', type: 'int' },
		{ 
			name: 'intervalos',
			type: 'datetime',
			dateFormat: 'g:i:s',
			convert: function(v, rec){
				newDate = new Date(v.date);
				return newDate;
			} 
		},
		{ name: 'tclEnfSup', type: 'int' },
		{ name: 'tclPurgaInf', type: 'int' },
		{ name: 'tclEnfInf', type: 'int' },
		{ name: 'tclPurgaSup', type: 'int' },
		{ name: 'tPrecalentado', type: 'int' },
		{ name: 'caudalPrecamara', type: 'int' },
		{ name: 'caudalHorno', type: 'int' }
	]
});

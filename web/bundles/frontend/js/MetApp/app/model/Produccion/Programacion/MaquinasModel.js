Ext.define('MetApp.model.Produccion.Programacion.MaquinasModel', {
	extend: 'Ext.data.Model',
	idProperty : 'id',
	clientIdProperty: 'maquinasId',
	fields: [
		{ name: 'id', type: 'int' },
		{ 
			name: 'sector',
			type: 'string',
			convert: function(v, rec){
				if(v){
					sec = v.descripcion;	
					return sec;
				}else{
					return '';
				}				
			} 
		},
		{ name: 'descripcion', type: 'string' },
		{ name: 'marca', type: 'string' },
		{ name: 'modelo', type: 'string' },
		{ name: 'peso', type: 'int' },
		{ name: 'piso', type: 'string' },
		{ name: 'nave', type: 'int' },
		{ name: 'peso', type: 'int' },
		{ name: 'origen', type: 'string' },
		{ name: 'anoOrigen', type: 'int' },
		{ 
			name: 'anoCompra',
			type: 'datetime',
			dateFormat: 'c',
			convert: function(v, rec){
				newDate = new Date(v.date);
				return newDate;
			} 
		},
		{ name: 'valorCompra', type: 'decimal' },
		{ name: 'vidaUtil', type: 'int' },
		{ name: 'criticidad', type: 'string' },
		{ name: 'notas', type: 'string' },
	]
});
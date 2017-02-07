Ext.define('MetApp.model.Articulos.Formulas',{
	extend: 'Ext.data.Model',
	fields: [
		{name: 'idFormula', type: 'int'},
		{name: 'id', type: 'int'},
		{name: 'depth', type: 'int'},
		{name: 'codigo', type: 'string'},
		{name: 'descripcion', type: 'string'},		
		{name: 'cant', type: 'float'},
		{name: 'unidad', type: 'string'},
		{name: 'costo', type: 'float'},
		{name: 'subTotal', type: 'float'},
		{
			name: 'moneda',
			type: 'string',
			convert: function(val){
				if(val == false){
					return '$'
				}else{
					return 'U$D'
				}
			}
		},
	]
});

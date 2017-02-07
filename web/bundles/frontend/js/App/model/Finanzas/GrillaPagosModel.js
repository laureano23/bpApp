Ext.define('MetApp.model.Finanzas.GrillaPagosModel',{
	extend: 'Ext.data.Model',
	idProperty: 'idGrillaPagosModel',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'formaPago', type: 'string'},
		{ name: 'numero', type: 'string'},
		{ name: 'banco', type: 'string'},
		{ name: 'importe', type: 'float' },
		{ 
			name: 'diferido',
			type: 'string',
			/*convert: function(val){
				console.log(val);
				var dt = new Date();
				dt = Ext.Date.parse(val, 'd/m/Y');
				return dt;
			}*/
		},
	]
});

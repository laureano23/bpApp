Ext.define('MetApp.store.Produccion.FormulasMo.GridFormulaMoStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.Programacion.FormulasMoModel',
	alias: 'formulasMoStore',	
	id: 'formulasMoStore',
	autoLoad: false,
	autoSync: true,	
	remoteFilter: false,
			
	proxy: {
		type: 'ajax',
		api: {
			read: Routing.generate('mbp_produccion_formulasMo_list'),
		},
				
		reader: {
			type: 'json',		
		},
		
		extraParams: {
			codigo: '',
			cantidad: ''
		},
				
		writer: {
			type: 'json',
			root: 'pedidos',
			encode: true
		}
	}
});

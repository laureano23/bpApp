Ext.define('MetApp.store.Articulos.Formula',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.Formulas',	
	id: 'formulaStore',
	autoLoad: false,
	autoSync: true,
		
	proxy: {
		type: 'ajax',
		filterParam: 'filter',		
		
		api: {
			create: Routing.generate('mbp_formulas_InsertNodo'),
			read: Routing.generate('mbp_formulas_read'),
			update: Routing.generate('mbp_formulas_updateNodo'),
			destroy: Routing.generate('mbp_formulas_BorrarNodo'),
		},
		
		reader: {
			type: 'json',
			root: 'data',
			totalProperty: ''
		},
		
		extraParams: {
			art: ''
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: true
		}
	}
});

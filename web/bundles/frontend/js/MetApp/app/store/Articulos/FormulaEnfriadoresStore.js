Ext.define('MetApp.store.Articulos.FormulaEnfriadoresStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Articulos.Formulas',
	alias: 'store.FormulaEnfriadoresStore',
	autoSync: true,
	autoLoad: false,
    id: 'FormulaEnfriadoresStore',
    storeId: 'FormulaEnfriadoresStore',
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
            root: 'items'
        },
        
        /*writer: {
			type: 'json',
			root: 'items',
			encode: true
		}*/
    }
});
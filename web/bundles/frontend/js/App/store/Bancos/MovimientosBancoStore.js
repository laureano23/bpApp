Ext.define('MetApp.store.Bancos.MovimientosBancoStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Bancos.MovimientosBancoModel',
	alias: 'store.MovimientosBancosStore',
	autoSync: true,
	autoLoad: false,
    id: 'MovimientosBancosStore',
    storeId: 'MovimientosBancosStore',
	
	proxy: {
        type: 'memory',
        
        reader: {
            type: 'json',
           // root: 'items'
        },
        
        /*writer: {
			type: 'json',
			root: 'items',
			encode: true
		}*/
    }
});
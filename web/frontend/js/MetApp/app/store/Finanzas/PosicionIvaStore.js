Ext.define('MetApp.store.Finanzas.PosicionIvaStore',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Finanzas.PosicionIvaModel',
	alias: 'store.PosicionIvaStore',
	autoSync: true,
	autoLoad: false,
	remoteFilter: false,
    storeId: 'PosicionIvaStore',
	
});
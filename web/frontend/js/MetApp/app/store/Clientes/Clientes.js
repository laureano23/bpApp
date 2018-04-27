Ext.define('MetApp.store.Clientes.Clientes',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Clientes.ClientesModel',
	alias: 'clientesStore',
	autoSync: false,
	autoLoad: false,
	remoteFilter: false,	  
    pageSize: 1000,
    storeId: 'clientesStore',
	
	
});

Ext.define('MetApp.model.Parametros.UserParamsModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{ name: 'id', type: 'int' },
		{ name: 'nombre', type: 'string'},		
		{ name: 'itemId', type: 'string'},
	],
	proxy: {
        type: 'localstorage',
        id  : 'id'
    }
});
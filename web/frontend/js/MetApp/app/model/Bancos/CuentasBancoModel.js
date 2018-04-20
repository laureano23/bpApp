Ext.define('MetApp.model.Bancos.CuentasBancoModel',{
	extend: 'Ext.data.Model',
	idProperty: 'id',
	fields: [
		{name: 'idCuenta', type: 'int'},
		{name: 'cuenta', type: 'string'},		
	]
});


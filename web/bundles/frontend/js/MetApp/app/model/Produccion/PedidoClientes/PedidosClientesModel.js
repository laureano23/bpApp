Ext.define('MetApp.model.Produccion.PedidoClientes.PedidosClientesModel', {
	extend: 'Ext.data.Model',
	idProperty : 'id',
	clientIdProperty: 'clientId',
	fields: [
		{ name: 'id', type: 'int' },				//Id del articulos a calcular
		{ name: 'idPedido', type: 'int' },
		{ name: 'idDetalle', type: 'int' },
		{ name: 'codigo', type: 'string' },
		{ name: 'descripcion', type: 'string' },	
		{ name: 'cantidad', type: 'int' },
		{ name: 'unidad', type: 'string' },
		{ name: 'precio', type: 'int' },
		{ name: 'fechaProgramacion', type: 'date', dateFormat: 'd/m/Y' },
		{ name: 'cliente', type: 'int' },
		{ name: 'clienteDesc', type: 'string' },
		{ name: 'oc', type: 'string' },
		{ name: 'autEntrega', type: 'string' },		
	]
});
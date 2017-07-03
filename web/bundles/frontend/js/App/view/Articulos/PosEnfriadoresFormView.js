Ext.define('MetApp.view.Articulos.PosEnfriadoresFormView', {
	extend: 'MetApp.view.Articulos.ArticulosForm',
	alias: 'widget.PosEnfriadoresFormView',
	id: 'PosEnfriadoresFormView',
	constructor: function(cfg){
		console.log(this);
		var me = this;
		me.callParent(cfg);
	}
});

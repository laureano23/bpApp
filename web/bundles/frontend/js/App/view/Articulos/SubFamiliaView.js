Ext.define('MetApp.view.Articulos.SubFamiliaView',{
	extend: 'Ext.window.Window',
	width: 490,
	modal: true,
	layout: 'anchor',
	alias: 'widget.SubFamiliaView',
	itemId: 'SubFamiliaView',
	autoShow: true,
	title: 'Tabla de Sub Familia',
	
	initComponent: function(){		
		var me = this;
		var botonera = Ext.create('MetApp.resources.ux.BtnABMC');
		botonera.queryById('btnReset').hide();
	
		Ext.applyIf(me, {
			items: [
				{
					xtype: 'form',
					anchorSize: '100%',	
					border: false,
					margin: '5 5 5 5',
					defaults: {
						disabled: true,
						disabledCls: 'myDisabledClass'
					},
					items: [
						{
							xtype: 'numberfield',
							itemId: 'idSubFamilia',
							fieldLabel: 'Id',
							name: 'idSubFamilia',
							hidden: true
						},						
						{
							xtype: 'textfield',
							name: 'subFamilia',
							itemId: 'subFamilia',
							fieldLabel: 'Sub Familia',
							width: 450
						},
						{
							xtype: 'grid',
							height: 200,
							margin: '0 0 5 0',
							store: 'MetApp.store.Articulos.SubFamiliaStore',
							disabled: false,
							columns: [
								{ text: 'Id', dataIndex: 'idSubFamilia' },
								{ text: 'Sub Familia', dataIndex: 'subFamilia', flex: 1 }
							],
							itemId: 'gridSubFamilia',
							width: 450
						},
						botonera
					]
				}
			]
		});
	this.callParent();
	},
});



Ext.define('MetApp.view.Articulos.FamiliaView',{
	extend: 'Ext.window.Window',
	width: 490,
	modal: true,
	layout: 'anchor',
	alias: 'widget.FamiliaView',
	itemId: 'FamiliaView',
	autoShow: true,
	title: 'Tabla de Familia',
	
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
							itemId: 'idFamilia',
							fieldLabel: 'Id',
							name: 'idFamilia',
							hidden: true
						},						
						{
							xtype: 'textfield',
							name: 'familia',
							itemId: 'familia',
							fieldLabel: 'Familia',
							width: 450
						},
						{
							xtype: 'grid',
							height: 200,
							margin: '0 0 5 0',
							store: 'MetApp.store.Articulos.FamiliaStore',
							disabled: false,
							columns: [
								{ text: 'Id', dataIndex: 'idFamilia' },
								{ text: 'Familia', dataIndex: 'familia', flex: 1 }
							],
							itemId: 'gridFamilia',
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



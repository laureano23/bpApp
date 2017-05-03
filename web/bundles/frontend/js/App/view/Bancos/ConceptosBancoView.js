Ext.define('MetApp.view.Bancos.ConceptosBancoView',{
	extend: 'Ext.window.Window',
	width: 490,
	modal: true,
	layout: 'anchor',
	alias: 'widget.ConceptosBancoView',
	itemId: 'ConceptosBancoView',
	autoShow: true,
	title: 'Tabla de Conceptos bancarios',
	
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
						readOnly: true,
						disabledCls: 'myDisabledClass'
					},
					items: [
						{
							xtype: 'numberfield',
							itemId: 'id',
							fieldLabel: 'Id',
							name: 'id',
							hidden: true
						},						
						{
							xtype: 'textfield',
							name: 'concepto',
							itemId: 'concepto',
							fieldLabel: 'Concepto',
							width: 450
						},
						{
							xtype: 'grid',
							height: 200,
							margin: '0 0 5 0',
							store: 'MetApp.store.Bancos.ConceptosBancoStore',
							disabled: false,
							columns: [
								{ text: 'Id', dataIndex: 'id' },
								{ text: 'Concepto', dataIndex: 'concepto', flex: 1 }
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



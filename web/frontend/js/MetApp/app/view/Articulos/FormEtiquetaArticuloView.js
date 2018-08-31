Ext.define('MetApp.view.Articulos.FormEtiquetaArticuloView', {
	extend: 'Ext.window.Window',
	alias: 'widget.FormEtiquetaArticuloView',
	id: 'FormEtiquetaArticuloView',
	itemId: 'FormEtiquetaArticuloView',
	//width: 900,
	//height: 470,
	layout: 'fit',
	autoShow: true,
	title: 'Etiqueta de articulos',
	modal: true,
	
	initComponent: function(){
        var me=this;
        Ext.applyIf(me, {
			items: [
                {
                    xtype: 'form',
                    border: false,
                    layout: 'vbox',
                    margins: '5 5 5 5',
                    items: [
                        {
                            xtype: 'textfield',
                            name: 'codigo',
                            itemId: 'codigo',
                            fieldLabel: 'Codigo',
                            readOnly: true,
                            disabledCls: 'myDisabledClass'
                        },
                        {
                            xtype: 'textfield',
                            name: 'cliente',
                            itemId: 'cliente',
                            fieldLabel: 'Cliente/Proveedor'
                        },
                        {
                            xtype: 'textfield',
                            name: 'numSerie',
                            itemId: 'numSeria',
                            fieldLabel: 'Numero de serie'
                        },
                        {
                            xtype: 'button',
                            itemId: 'imprimir',
                            text: 'Imprimir'
                        }
                    ]
                }
            ]
        });
		
		this.callParent();
	}
});

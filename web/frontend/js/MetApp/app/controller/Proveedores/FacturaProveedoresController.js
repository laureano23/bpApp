Ext.define('MetApp.controller.Proveedores.FacturaProveedoresController',{
	extend: 'Ext.app.Controller',
	stores: [
		'MetApp.store.Proveedores.FacturaProveedoresStore',
	],
	views: [
		'MetApp.view.CCProveedores.FacturaProveedor',
	],
	refs:[
		{
			ref:'CCProveedores',
			selector: 'CCProveedores'
		},
	],
	
	init: function(){
		var me = this;
		me.control({
			'FacturaProveedor button[itemId=saveFcProv]': {
				click: this.GuardarFc
			},
			'FacturaProveedor datefield[itemId=fechaEmision]': {
				blur: this.CalculaFechaVencimiento
			},
			'FacturaProveedor numberfield': {
				change: this.CalculaTotal
			},
			'FacturaProveedor numberfield[itemId=numFc]': {
				blur: this.ValidarFactura
			},	
			'FacturaProveedor combobox[itemId=tipo]': {
				change: this.AsociarFacturaNC
			},
		});
	},

	AsociarFacturaNC: function(combo){
		var win=combo.up('window');
		var grid=win.down('grid');
		var formCC = this.getCCProveedores();
		var idProv = formCC.queryById('id').getValue();
		if(combo.valueModels[0].data.esNotaCredito){
			grid.show(true);
			var form=win.down('form').getForm();
			form.submit({
				url: Routing.generate('mbp_proveedores_asociarNC'),
				clientValidation: false,
				params: {
					idProv: idProv
				},
				success: function(form, action){
					var jsonResp=Ext.JSON.decode(action.response.responseText);
					grid.getStore().loadRawData(jsonResp);
				}
			});

		}else{
			grid.hide(true);
		}
	},

	ValidarFactura: function(txt){
		var form=txt.up('form').getForm();
		var formCC = this.getCCProveedores();
		var idProv = formCC.queryById('id').getValue();

		form.submit({
			clientValidation: false,
			url: Routing.generate('mbp_proveedores_validarComprobante'),
			params: {
				idProv: idProv
			},
			success: function(form, action){
			}
		})
	},
	
	CalculaFechaVencimiento: function(btn){
		var me = this;
		var win = btn.up('window');
		var vencimientoFc = win.queryById('vencimiento');
		var ccProv = me.getCCProveedores();
		var diasVencimiento = ccProv.queryById('vencimientoFc').getValue();
		var dt = win.queryById('fechaEmision').getValue();
		dt.setSeconds(diasVencimiento * 86400);
		vencimientoFc.setValue(dt);
	},
	
	GuardarFc: function(btn){
		var me = this;
		var win = btn.up('window');
		var comboTipoGasto = win.queryById('tipoGasto');
		var form = win.down('form');
		var store = me.getStore('Proveedores.FacturaProveedoresStore');
		var values = form.getValues();
		var fcModel = Ext.create('MetApp.model.Proveedores.FacturaProveedoresModel');
		var formCC = this.getCCProveedores();
		var storeCC = formCC.down('grid').getStore();
		var idProv = formCC.queryById('id').getValue();
		
		values.idF = values.id;
		values.id = 0;
		fcModel.set(values);
		fcModel.set('idProv', idProv);
		store.on('write', function(store, operation, eOpts){
			var jsonResp = Ext.JSON.decode(operation.response.responseText);
			if(jsonResp.success == true){
				storeCC.load({
					callback: function(){
						win.focus(10, true);	
					}
				});
				win.close();
			}			
		});
		
		var proxy = store.getProxy();
		proxy.on('exception', function(proxy, response, operation){
			store.rejectChanges();
		});
		
		if(form.isValid()==true){			
			var storeCbteAsociado=win.down('grid').getStore();
			var rec=storeCbteAsociado.getModifiedRecords();
			var fcsAsociadas=[];
			rec.forEach(element => {
				fcsAsociadas.push(element.getData().idFcAsociada);
			});
			fcModel.set('idFcAsociada', fcsAsociadas);
			store.add(fcModel);
			var idTipoGasto = formCC.queryById('tipoGasto').getValue();				
			comboTipoGasto.select(idTipoGasto);
		}		
	},
	
	CalculaTotal: function(btn){
		var win = btn.up('window');
		var neto = win.queryById('neto').getValue(),
			netoNoGrabado = win.queryById('netoNoGrabado').getValue(),
			iva21 = win.queryById('iva21').getValue(),
			iva27 = win.queryById('iva27').getValue(),
			iva10_5 = win.queryById('iva10_5').getValue(),
			perIva5 = win.queryById('perIva5').getValue(),
			perIva3 = win.queryById('perIva3').getValue(),
			iibbCf = win.queryById('iibbCf').getValue();
			iibbBsas = win.queryById('iibbBsas').getValue();
			iibbOtras = win.queryById('iibbOtras').getValue();
		var total = neto + netoNoGrabado + iva21 + iva27 + iva10_5 + perIva5 + perIva3 + iibbCf + iibbBsas + iibbOtras;
		var txtTotal = win.queryById('total');
		txtTotal.setValue(total);
	},
})






































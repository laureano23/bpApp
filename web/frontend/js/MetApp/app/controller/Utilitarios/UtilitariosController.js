Ext.define('MetApp.controller.Utilitarios.UtilitariosController',{
	extend: 'Ext.app.Controller',
	stores: [
		'MetApp.store.Clientes.CotizacionStore',
		'MetApp.store.Clientes.CotizacionDetalleStore'
	],
	views: [
		'MetApp.view.Utilitarios.TxtRetencionesView',
		'MetApp.view.Utilitarios.CotizacionView'
	],
	
	refs:[
		{
			ref:'CotizacionView',
			selector: 'CotizacionView'
		},
	],
	
	init: function(){
		var me = this;
		
		me.control({
			'viewport menuitem[itemId=tbRetenciones]': {
				click: this.AddRetencionesWin
			},
			'TxtRetencionesView button[itemId=descargar]': {
				click: this.DescargarTxtRetencion
			},
			'viewport menuitem[itemId=tbCotizaciones]': {
				click: this.AddCotizacionesWin
			},
			'CotizacionView button[itemId=buscaCliente]': {
				click: this.BuscarClienteCoti
			},
			'CotizacionView button[itemId=buscarArt]': {
				click: this.BuscarArticulo
			},
			'CotizacionView button[itemId=insert]': {
				click: this.InsertarArticulo
			},
			'CotizacionView button[itemId=guardar]': {
				click: this.GuardarCoti
			},			
			'CotizacionView textfield[itemId=desc]': {
				change: this.AplicarDescuento
			},
		});
	},
	
	AplicarDescuento: function(txt){
		var win=txt.up('window');
		var total=win.queryById('total');
		
		var store=win.down('grid').getStore();
		var total=0;
		store.each(function(rec){
			var data = rec.getData();
			total += data.cant * data.precio;			
		});						
		var desc = win.queryById('desc').getValue();
		total = total - desc * total / 100;
		win.queryById('total').setValue(total);
			
	},
	
	GuardarCoti: function(btn){
		var win=btn.up('window');
		var form=win.queryById('formCotizacion');
		var formArt=win.queryById('formArticulos')
		
		if(form.isValid()){
			var values=form.getForm().getValues();
			var storeDetalles=win.down('grid').getStore();
			var data = new Array(); 
			var i = 0;
			
			if(storeDetalles.count() == 0){
				Ext.Msg.alert("Atención", "Debe ingresar al menos un artículo");
			}
			storeDetalles.each(function(rec){
				data[i] = rec.getData();
				i++;	
			});
			
			var model=Ext.create('MetApp.model.Clientes.CotizacionModel');
			model.set(values);
			var proxy=model.getProxy();
			
			proxy.setExtraParam('items', Ext.JSON.encode(data));
			proxy.setExtraParam('descuento', win.queryById('desc').getValue());
			proxy.setExtraParam('total', win.queryById('total').getValue());
			
			model.save({
				callback: function(records, op, success){
					console.log(op);
					var resp=Ext.JSON.decode(op.response.responseText);
					if(resp.success==true){
						form.getForm().reset();
						storeDetalles.removeAll();
						formArt.getForm().reset();
					}
	       		}
			});	
		}
		
		
	},
	
	InsertarArticulo: function(btn){
		var win=btn.up('window');
		var formArt=win.queryById('formArticulos');		
		var formCliente=win.queryById('formCotizacion');
		var values=formArt.getForm().getValues();
		var grid=win.down('grid');
		var store=grid.getStore();
		
		
		
		var modelDetalle=Ext.create('MetApp.model.Compras.OrdenCompraModel'); //usamos el mismo modelo de OC
		modelDetalle.set(values);		
		store.add(modelDetalle);
		formArt.getForm().reset();
		
		win.queryById('buscarArt').focus("", 30);
	},
	
	BuscarArticulo: function(btn){
		var formArt=btn.up('window').queryById('formArticulos');
		var win=Ext.widget('winarticulosearch');
		
		win.down('button').on('click', function(btn){
			var sel=win.down('grid').getSelectionModel().getSelection()[0];
			formArt.loadRecord(sel);
			win.close();
		});
	},
	
	BuscarClienteCoti: function(btn){
		var win=btn.up('window');
		var formCoti=btn.up('window').queryById('formCotizacion');
		var win=Ext.widget('clientesSearchGrid');
		
		win.down('button').on('click', function(btn){
			var sel=win.down('grid').getSelectionModel().getSelection()[0];			
			formCoti.loadRecord(sel);
			win.close();
			win.queryById('buscarArt').focus("", 30);
		});
	},
	
	AddCotizacionesWin: function(btn){
		var win=Ext.widget('CotizacionView');
		
		var store=win.down('grid').getStore();
		store.on({
			'datachanged':function(st, opts){	
				console.log(st);			
				var total=0;
				st.each(function(rec){
					var data = rec.getData();
					total += data.cant * data.precio;			
				});						
				var desc = win.queryById('desc').getValue();
				total = total - desc * total / 100;
				win.queryById('total').setValue(total);
			}
		});
	},
	
	AddRetencionesWin: function(btn){
		Ext.widget('TxtRetencionesView');
	},
	
	DescargarTxtRetencion: function(btn){
		var win=btn.up('window');
		var form=win.down('form');
		var values=form.getForm().getValues();
		
		if(values.tipo == 2){//tipo 2 son percepciones, tipo 1 son retenciones
			form.submit({
				clientValidation: true,
				method: 'POST',
				url: Routing.generate('mbp_finanzas_txt_percepciones'),
				
				success: function(form, action){
					var jsonResp=Ext.JSON.decode(action.response.responseText);
					var ruta = Routing.generate('mbp_finanzas_txt_retenciones_percepciones_servir', {nombreArchivo: jsonResp.nombreArchivo});
			    	window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				},
				
				failure: function(res){
					
				}
			})
		}else{
			form.submit({
				clientValidation: true,
				method: 'POST',
				url: Routing.generate('mbp_finanzas_txt_retenciones'),
				
				success: function(form, action){
					var jsonResp=Ext.JSON.decode(action.response.responseText);
					var ruta = Routing.generate('mbp_finanzas_txt_retenciones_percepciones_servir', {nombreArchivo: jsonResp.nombreArchivo});
			    	window.open(ruta, '_blank, location=yes,height=800,width=1200,scrollbars=yes,status=yes');
				},
				
				failure: function(res){
					
				}
			})	
		}
		
		
		
	}
	
	
})






































Ext.define('MetApp.controller.Produccion.CalculoRadiadores.CalculoController',{
	extend: 'Ext.app.Controller',
	
	views: [
		'Produccion.CalculoRadiadores.WinCalculo',
		'Produccion.CalculoRadiadores.CalculoRadAceiteForm',
	],
	
	stores: ['Produccion.CalculoRadiadores.Calculo'],
	
	init: function(){
		var me = this;
		me.control({
			'#calculoRad': {
				click: this.AddWin 
			},
			'wincalculo numberfield[itemId=ancho]': {
				blur: this.outFocus
			},
			'wincalculo numberfield[itemId=maxAlt]': {
				blur: this.outFocus
			},
			'wincalculo button[action=panelToForm]': {
				click: this.BuscaPanel
			},
			'wincalculo button[action=btnCalculo]': {
				click: this.CalculaPanel
			},
			'wincalculo button[action=SaveCalculo]': {
				click: this.SaveCalculo
			},
			'wincalculo textfield[itemId=aletaTipo]' : {
				blur: this.StatusAleta
			},
			'wincalculo radiogroup[itemId=aletaVenA]' : {
				change: this.StatusAleta
			}					
		});
	},
	
	/*
	 * Insertamos la ventana
	 */
	AddWin: function(){
		var win = Ext.widget('wincalculo');
	},
	
	outFocus: function(){
		var ancho = Ext.ComponentQuery.query('#fCalculoAiAc numberfield[itemId=ancho]')[0].value;
		var maxAlt = Ext.ComponentQuery.query('#fCalculoAiAc numberfield[itemId=maxAlt]')[0].value;
		var paneles = Ext.ComponentQuery.query('#fCalculoAiAc numberfield[itemId=cantPaneles]')[0];
		
		var res = Math.ceil(ancho / maxAlt);
		paneles.setValue(res);
	},
	
	BuscaPanel: function(btn){
		var win = btn.up('window');
		var searchForm = Ext.widget('winarticulosearch');
		searchForm.down('grid').getStore().clearFilter(true);
		var button = Ext.ComponentQuery.query('#insertArt')[0];
		var btnCalculo = win.queryById('btnCalculo');
		var btnSaveCalculo = win.queryById('btnSaveCalculo');
		
		
		/*
		 * Escuchamos al boton de la ventana de busqueda
		 */
		button.on('click',function(){
			var grid = button.up('grid');
			var selection = grid.getSelectionModel().getSelection()[0];		
											
			formArt = Ext.ComponentQuery.query('#calculoRadWin')[0].down('form');					
			formArt.loadRecord(selection);
			
			button.up('window').close();
			Ext.Ajax.request({
				url: Routing.generate('mbp_produccion_verificacalculo'),
				params: { cod: selection.get('id') },
				
				success: function(response, options){
					var data = Ext.JSON.decode(response.responseText);
					var record = data.record;
					
					if(data.success == true){
						btnCalculo.setDisabled(false);
						btnSaveCalculo.setDisabled(false);
						formArt.queryById('apoyoTapas').setValue(record.apoyoTapas);
						formArt.queryById('prof').setValue(record.prof);
						formArt.queryById('ancho').setValue(record.ancho);
						formArt.queryById('chapaPiso').setValue(record.chapaPiso);
						formArt.queryById('cantAdicional').setValue(record.cantAdic);
						formArt.queryById('perfilIntermedio').setValue(record.perfilIntermedio);
						formArt.queryById('aletaTipo').setValue(record.aletaTipo);
						formArt.queryById('pisosManual').setValue(record.pisosManual);
						formArt.queryById('pisosManual7').setValue(record.pisosManual7);						
						formArt.queryById('tipo').setValue({tipo: record.tipo});
						formArt.queryById('aletaVenA').setValue({aletaVenA: record.aletaVenA});
						formArt.queryById('aletaFluA').setValue({aletaFluA: record.aletaFluA});
						
						formArt.queryById('btnCalculo').fireEvent('click', btn);
					}
					if(data.success == false){
						Ext.Msg.show({
							title: 'Atencion',
							msg: 'Este panel no tiene un calculo guardado.',
							buttons: Ext.Msg.OK,
		    				icon: Ext.Msg.WARNING
						});
						formArt.getForm().reset();
						formArt.loadRecord(selection);
						btnCalculo.setDisabled(false);
						btnSaveCalculo.setDisabled(false);
					}					
				}
			});			
		});
	},
	
	CalculaPanel: function(btn){
		var win = btn.up('window');
		var grid = win.down('grid');
		var cntRes = win.queryById('panelResultados');
		var form = btn.up('form');
		var store = grid.getStore();		
		var values = form.getValues();
		
		//Seteamos todas las variables del proxy
		var proxy = store.getProxy();
		proxy.setExtraParam('aletaFluA', values.aletaFluA);
		proxy.setExtraParam('aletaVenA', values.aletaVenA);
		proxy.setExtraParam('ancho', values.ancho);
		proxy.setExtraParam('apoyoTapas', values.apoyoTapas);
		proxy.setExtraParam('cantAdicional', values.cantAdicional);			
		proxy.setExtraParam('chapaPiso', values.chapaPiso);
		proxy.setExtraParam('maxAlt', values.maxAlt);
		proxy.setExtraParam('perfilIntermedio', values.perfilIntermedio);
		proxy.setExtraParam('pisosManual', values.pisosManual);
		proxy.setExtraParam('pisosManual7', values.pisosManual7);
		proxy.setExtraParam('prof', values.prof);
		proxy.setExtraParam('tipo', values.tipo);
		proxy.setExtraParam('cantPaneles', values.cantPaneles);
		proxy.setExtraParam('aletaTipo', values.aletaTipo);
				
		store.load({
			callback: function(records, operation, success) {
		       resp = Ext.JSON.decode(operation.response.responseText);
		       console.log(resp);
		       ancho = form.queryById('anchoCalc');
		       ancho.setValue(resp.anchoCalc.anchoTotal);
		    }
		});		
	},
	
	/*
	 * Comprueba si existe error en la formulacion del panel con la altura de la aleta y el tipo (abierta/cerrada)
	 */
	StatusAleta: function(field){
		var win = field.up('window');
		var aleta = win.queryById('aletaVenA').getValue().aletaVenA;
		var aletaEstado = win.queryById('aletaTipo').getValue();		
		
		aletaEstado == 'Cerrada' & aleta == 0 ? this.ErrorAleta(win) : this.AletaOk(win);
	},
	
	ErrorAleta: function(win){		
		var status = win.queryById('statusAleta');
		status.setValue('No OK');
		status.setFieldStyle('background: red');
		Ext.MessageBox.show({
	       title : 'Error',
		   buttons : Ext.MessageBox.OK,
		   msg : 'No es posible formular aleta de 7 cerrada',
		   icon : Ext.Msg.WARNING,	       	
	    });
	},
	
	AletaOk: function(win){
		var status = win.queryById('statusAleta');
		status.setFieldStyle('background: green');
		status.setValue('OK');
	},
	
	SaveCalculo: function(btn){
		var form = btn.up('form');
		var values = form.getValues();
		var store = this.getProduccionCalculoRadiadoresCalculoStore();
		var record = Ext.create('MetApp.model.Produccion.CalculoRadiadores.CalculoRadModel');
		record.set(values);
		
		store.add(record);
	}	
});





















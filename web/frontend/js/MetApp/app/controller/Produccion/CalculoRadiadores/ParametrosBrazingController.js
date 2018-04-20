Ext.define('MetApp.controller.Produccion.CalculoRadiadores.ParametrosBrazingController', {
	extend: 'Ext.app.Controller',
	views: [
		'Produccion.CalculoRadiadores.ParametrosBrazing'
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#parametrosBrazing': {
				click: this.AddWin 
			},	
			'parametrosbrazing button[action=edit]': {
				click: this.EditaBrazingParams
			},
			'parametrosbrazing combobox[itemId=tipoCarga]': {
				change: this.ReloadBrazingParams
			},
			'parametrosbrazing button[action=save]': {
				click: this.SaveBrazingParams
			},
		});
	},
	
	AddWin: function(btn){
		var brazing = Ext.widget('parametrosbrazing');
		
		store = this.getStore('Produccion.CalculoRadiadores.BrazingParams');
		store.load({
			callback: function(rec, op, success){
				form = Ext.ComponentQuery.query('#brazingParamsForm')[0].down('form');
				form.loadRecord(rec[0]);
			}
			
		});
	},
	
	ReloadBrazingParams: function(btn){
		store = this.getStore('Produccion.CalculoRadiadores.BrazingParams');
		proxy = store.getProxy();
		carga = btn.getValue();
		/*
		 * Setea el tipo de carga al proxy
		 */
		proxy.extraParams.tipoCarga = carga;
		
		store.load({
			callback: function(rec, op, success){
				form = Ext.ComponentQuery.query('#brazingParamsForm')[0].down('form');
				form.loadRecord(rec[0]);
			}
		});
	},
	
	EditaBrazingParams: function(btn){
		win = btn.up('window');
		form = win.down('form');
		items = form.items;
		i = 0;
		
		for(i = 0; i < items.length; i += 1){
			items.items[i].setDisabled(false);
		}
		
		win.queryById('edit').setDisabled(true);
		win.queryById('save').setDisabled(false);
	},
	
	SaveBrazingParams: function(btn){
		win = btn.up('window');
		form = win.down('form');		
		values = form.getValues();
		//CONFIGURO FECHAS
		values.intervalos = ({date: Ext.Date.parse(values.intervalos, 'H:i')});
		values.tiempoCarga = ({date: Ext.Date.parse(values.tiempoCarga, 'H:i')});
		//ENVIO A BACKEND
		store = this.getStore('Produccion.CalculoRadiadores.BrazingParams');
		record = form.getRecord();
		record.set(values);
		
		//RESET FORM		
		form.getForm().reset();
		items = form.items;		
		
		for(i = 0; i < items.length; i += 1){
			items.items[i].setDisabled(true);
		}
		form.loadRecord(record);
		
		//EXCLUIR TIPO DE CARGA
		form.queryById('tipoCarga').setDisabled(false);
		
		//RESET BTN CFG
		win.queryById('save').setDisabled(true);
		win.queryById('edit').setDisabled(false);
		
	}
});




























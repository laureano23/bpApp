Ext.define('MetApp.controller.Produccion.CalculoRadiadores.CalculoSeleccionController', {
	extend: 'Ext.app.Controller',
	views: [
		'Produccion.RadiadoresAgua.CalculoSeleccionWin'
	],
	
	
	init: function(){
		var me = this;
		me.control({
			'#calculoSeleccionAgua': {
				click: this.AddCalculoSeleccionAguaWin
			},
			'calculoSeleccion button[action=calculoSeleccion]': {
				click: this.CalculoSeleccion
			}
		});
	},
	
	AddCalculoSeleccionAguaWin: function(btn){
		Ext.widget('calculoSeleccion');
	},
	
	/*
	 * ENVIA LOS DATOS DE ENTRADA DEL FORMULARIO A LA PLANILLA EXCEL Y LEE LOS RESULTADO VIA AJAX
	 */
	
	CalculoSeleccion: function(btn){
		form = btn.up('form');
		values = form.getForm().getValues();
		win = form.up('window');
		
		
		
		if(form.isValid()){
			
			wait = Ext.Msg.show({
				title: 'Cargando...',
				width: 350,
				wait: true,
				waitConfig: {
					text: 'Esta operacion puede demorar mas de 3 minutos'
				}
				
			});
			
			//AJAX REQUEST
			Ext.Ajax.request({
				timeout: 120000,
				url: Routing.generate('mbp_produccion_calculoagua'),
				
				params: values,
				
				failure: function(resp){
					console.log(resp);
					wait.hide();
				},
							
				success: function(response, options){
					//SACAMOS LA MASCARA
					wait.hide();
					var respJson = Ext.JSON.decode(response.responseText);
					
										
					//MOSTRAMOS LOS RESULTADOS
					console.log(response);
					data = Ext.JSON.decode(response.responseText);
					win.queryById('potenciaSolicitada').setValue(data.potenciaSolicitada);
					win.queryById('equipo').setValue(data.equipo);
					win.queryById('potenciaDisipada').setValue(data.potenciaDisipada);
					win.queryById('tSalidaRtdo').setValue(data.tSalidaRtdo);
					win.queryById('tReservaPotencia').setValue(data.tReservaPotencia);
					win.queryById('perdidaCarga').setValue(data.perdidaCarga);
				},
				
				
			});
			
			console.log(values);
		}
	}
});
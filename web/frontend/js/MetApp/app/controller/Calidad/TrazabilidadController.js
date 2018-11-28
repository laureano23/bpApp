Ext.define('MetApp.controller.Calidad.TrazabilidadController',{
	extend: 'Ext.app.Controller',
	views: [
		'MetApp.view.Calidad.AsociarTrazabilidadView'
		],
	stores: [],
	
	init: function(){
		var me = this;
		me.control({
			'#verCargaCorrelativos': {
				click: this.AddFormTrazabilidad
			},
			'AsociarTrazabilidadView button[itemId=guardar]': {
				click: this.GuardarForm
			},		
		})		
	},
	
	AddFormTrazabilidad: function(){
		Ext.widget('AsociarTrazabilidadView');
	},

	GuardarForm: function(btn){
		var form=btn.up('form');
		form.getForm().submit({
			url: Routing.generate('mbp_calidad_asociarCorrelativo'),

			success: function(form, action){
				form.reset();
			}
		});
	}
});















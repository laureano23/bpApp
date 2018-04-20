Ext.define('MetApp.store.Produccion.CalculoRadiadores.Calculo',{
	extend: 'Ext.data.Store',
	model: 'MetApp.model.Produccion.CalculoRadiadores.CalculoRadModel',
	
	alias: 'calculoRadStore',
	autoSync: true,
	autoLoad: false,
	autoSave: true,
	
	
	proxy: {
		type: 'ajax',
		api: {
			create: Routing.generate('mbp_produccion_savecalculo'),
			read: Routing.generate('mbp_produccion_calculoaireaceite')					
		},
		
		reader: {
			type: 'json',
			root: 'data',
			idProperty: 'idCalculo',
		},
		
		extraParams: {
			tipo: '',
			aletaVenA: '',
			aletaFluA: '',
			apoyoTapas: '',
			prof: '',
			ancho: '',
			pisosManual: '',
			perfilIntermedio: '',
			chapaPiso: '',
			cantAdicional: '',
			pisosManual7: '',
			maxAlt: ''
		},
		
		writer: {
			type: 'json',
			root: 'data',
			encode: 'true'
		},
		
		afterRequest: function(req, success){
			console.log(req);
			if(success == true & req.action == 'create'){
				Ext.Msg.show({
							title: 'Atencion',
							msg: 'El calculo se ha guardado exitosamente',
							buttons: Ext.Msg.OK,
		    				icon: Ext.Msg.INFO
						});
			};
			if(success == false){
				Ext.Msg.show({
							title: 'Atencion',
							msg: 'El calculo NO se guardo correctamente',
							buttons: Ext.Msg.OK,
		    				icon: Ext.Msg.ERROR
						});
			}
		}
	}
});
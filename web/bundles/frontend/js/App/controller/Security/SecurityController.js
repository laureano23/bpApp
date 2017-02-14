Ext.define('MetApp.controller.Security.SecurityController',{	
	extend: 'Ext.app.Controller',
	id: 'securityController',
	store: ['Parametros.UserParamsStore'],
	
	/*
	 * Realizo la consulta al servidor para obtener los roles
	 */
	init: function(){
		Ext.Ajax.request({
			url: Routing.generate('sesion'),
			params: {
				action: 'read'
			},
			method: 'GET',
			success: function(result, request){				
				var data = Ext.decode(result.responseText);				
				var user = Ext.create('MetApp.controller.Security.Roles', {					
					role: data.role,
					user: data.nombre
				});	
				/*
				 * role1 = ROLE_ADMIN
				 * role2 = ROLE_USER
				 * Creo el objeto rol para las vistas segun el rol que me brinda el servidor
				 */
				if(user.role == 'ROLE_ADMIN'){
						MetApp.User = Ext.create('MetApp.controller.Security.Autorizaciones');
						MetApp.User.roles({
							role1: true,
							role2: true
						});		
						MetApp.User.name({
							name: data.nombre,
						});									
					}else{
						MetApp.User = Ext.create('MetApp.controller.Security.Autorizaciones');
						MetApp.User.roles({
							role1: false,
							role2: true
						});
						MetApp.User.name({
							name: data.nombre,
						});
					}											
			},
			callback: function(){
				var store = Ext.create('MetApp.store.Parametros.UserParamsStore');
				//store.add({id: 1, nombre: 'lalo'});
				//store.add({id: 2, nombre: 'juan'});

				var items=[];
				store.load({
					callback: function(){
						for (var i = 0; i < store.getCount(); i++) {
							console.log("entramos");
							items[i] = {
								xtype: 'button',
								text: store.data.items[i].data.nombre,
								margin: '5 5 5 5',
								width: 100,
								height: 50,
								iconCls: 'favoritos'
							}
						}
					}
				})
					
				var viewport = Ext.create('MetApp.view.Principal.MyViewport', {params: items});

				/*CONFIGURACION DRAG AND DROP*/

				
							

				var overrides = {
				     startDrag: function(e) {
				         console.log('startDrag');
				     },
				     onDrag: function() {
				         console.log('onDrag');
				     },
				     onDragEnter: function(e, id) {
				         console.log('onDragEnter');
				     },
				     onDragOver: function(e, id) {
				         console.log('onDragOver');
				     },
				     onDragOut: function(e, id) {
				         console.log('onDragOut');
				     },
				     onDragDrop: function(e, id) {
				         console.log('onDragDrop');
				     },
				     onInvalidDrop: function() {
				         console.log('onInvalidDrop');
				     },
				     endDrag: function(e, id) {
				       console.log('endDrag');
				     }
				};

				var buttons = viewport.query('.button');

				Ext.each(buttons, function(el){
					var dd = Ext.create('Ext.dd.DD', el, 'tablesDDGroup', {
		                isTarget: false
		            });
		            Ext.apply(dd, overrides);
				});

				//var target = viewport.queryById('panelFavoritos');

				var mainTarget = Ext.create('Ext.dd.DDTarget', 'panelFavoritos', 'tablesDDGroup', {
				     ignoreSelf: false
				 });

				/*//CONFIGURACION DRAG AND DROP*/				
				
			}			
		});		
	}
});


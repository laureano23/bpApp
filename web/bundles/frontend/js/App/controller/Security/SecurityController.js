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
				var items=[];
				store.load({
					callback: function(){
						for (var i = 0; i < store.getCount(); i++) {
							items[i] = {
								xtype: 'button',
								idrecord: store.data.items[i].data.id,
								text: store.data.items[i].data.nombre,
								margin: '5 5 5 5',
								width: 200,
								height: 50,
								iconCls: 'favoritos',
								itemId: store.data.items[i].data.itemId,
								listeners: {
									afterrender: function(e){
										var me = this;
										var el = this.getEl();
										el.on('contextmenu', function(ev){
											ev.preventDefault();
											var rec = store.findRecord('itemId', me.itemId);
											store.remove(rec);
											store.sync();
											this.destroy();
										});
									}
								}
									
							}
						}

						//PARA BORRAR TODOS LOS FAVORITOS
						//store.getProxy().clear();

						var viewport = Ext.create('MetApp.view.Principal.MyViewport', {params: items});
					}
				})			
				
			}			
		});		
	}
});


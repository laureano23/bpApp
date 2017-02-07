Ext.define('MetApp.controller.Security.SecurityController',{	
	extend: 'Ext.app.Controller',
	id: 'securityController',
	
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
				var viewport = Ext.create('MetApp.view.Principal.MyViewport');
			}			
		});		
	}
});


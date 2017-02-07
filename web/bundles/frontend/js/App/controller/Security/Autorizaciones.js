Ext.define('MetApp.controller.Security.Autorizaciones',{
	extend: 'Ext.app.Controller',
	
	roles: function(roles){
		this.roles = roles;
		
		this.isRoleActive = function(role){
			return this.roles[role] != undefined ? this.roles[role] : false;
		};
		
		this.existRole = function(role){
			return this.roles[role] != undefined ? true : false;
		};
		
		this.getRoles = function() {
			return this.roles;
		};
	},
	
	name: function(name){
		this.name = name;
	},
        
    authorization: function(roles){
        this.roles = roles;
        
        this.getAuthorizedElements = function (fullElements) {
	        var authElements = [];
	        var index = 0;
	        for(var i = 0; i < fullElements.length; i++) {
	            if(authorized(fullElements[i], this.roles.getRoles())) {
	                authElements[index] = fullElements[i];
	                index++;
	            } else if (fullElements[i+1] == '-') {
	                i++;
	            }
	        }
	        return authElements.length == 0 ? null : authElements;
	    };
        
        this.isRoleActive = function (role) {
            return this.roles.isRoleActive(role);
        };
        
        function authorized (element, roles) {
	        for(requiredProp in element.require) {
	            if(roles[requiredProp] == undefined) {
	                throw 'RoleNotFound: role "' + requiredProp + '" not found in klicap.commons.auth.Roles object';
	            }
	            if(element.require[requiredProp] != roles[requiredProp]){
	                return false;
	            }
	        }
	        return true;
	    };
    }
});

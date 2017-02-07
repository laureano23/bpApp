Ext.define("MetApp.controller.Security.Roles", {
     
  config: {    
    role: '',
    user: ''
  },
   
  constructor: function(config) {
    this.initConfig(config);
    this.callParent(arguments);
  }
  
});
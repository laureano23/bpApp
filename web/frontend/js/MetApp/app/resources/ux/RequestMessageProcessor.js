Ext.define('MetApp.resources.ux.RequestMessageProcessor', {
	
		initComponent: function(){
		    this.callParent();
	  	},
		Message: function(proxy, response){
			if (response && proxy) {
			try {
				if(proxy.reader){
					var responseData = proxy.reader.getResponseData(response);	
				}else{
					var responseData = Ext.decode(proxy.responseText);	
				}					
				
				if (responseData.message) {
					var messageDescription = 'Informacion'; // title of the alert box
					var messageIcon = Ext.MessageBox.INFO;
					
					if (!responseData.success)
					{
						var messageDescription = 'Error';
						var messageIcon = Ext.MessageBox.ERROR;
					}
					
					Ext.MessageBox.show({
						title: messageDescription,
						msg: responseData.message,
						buttons: Ext.MessageBox.OK,
						icon: messageIcon
					});
				}
			}
			catch(err) {
				// Malformed response most likely
				console.log(err);
			}
		}	
	}
});

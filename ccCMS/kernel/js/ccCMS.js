var ccCMS = {

	activeModule: '',

	queryBackend: function(queryPath, options, callback)
	{
		smdQS().ajax(
			'/ccCMS/REST/backend/' + queryPath,
			callback,
			options,
			'POST'
		);
	},

	openModule: function(moduleName, options)
	{
		var postOptions = {
			'action': 'form'
		};

		if (typeof options !== "undefined") {
			postOptions.data = options;

			if (typeof postOptions.data === "object") {
				postOptions.data = JSON.stringify(postOptions.data);
			}
		}

		ccCMS.queryBackend(
			'module/' + moduleName,
			postOptions,
			function(returnData) {
				returnObject = eval("(" + returnData + ")");
				ccCMS.activeModule = moduleName;
				ccCMS_form.createModuleWindow(returnObject);
				smdQS('#toggleMain').checked = false;
				console.log(returnObject);
			}
		);
	},

	sendDataToActiveModule: function(dataForActiveModule)
	{
		if (ccCMS.activeModule !== '') {
			if (typeof dataForActiveModule === "object") {
				dataForActiveModule = JSON.stringify(dataForActiveModule);
			}
			var postOptions = {
				'action': 'data',
				'data': dataForActiveModule
			};
			ccCMS.queryBackend(
				'module/' + ccCMS.activeModule,
				postOptions,
				function(returnData) {
					returnObject = eval("(" + returnData + ")");
					if (typeof returnObject.javascript !== "undefined") {
						var func = new Function(returnObject.javascript);
						func();
					}
					console.log(returnObject);
				}
			);
		}
	}
}

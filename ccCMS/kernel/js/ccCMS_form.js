var ccCMS_form =
{
	_getButtonElement: function(buttonData)
	{
		var newElement = document.createElement("BUTTON");
		newElement.setAttribute("name", buttonData.name);

		var buttonClass = "pure-button";
		if (typeof buttonData.buttonType !== "undefined") {
			switch (buttonData.buttonType) {
				case 'primary':
					buttonClass = buttonClass + " pure-button-primary";
					break;
				case 'disabled':
					buttonClass = buttonClass + " pure-button-disabled";
					break;
				case 'success':
					buttonClass = buttonClass + " ccCMS_button-success";
					break;
				case 'error':
					buttonClass = buttonClass + " ccCMS_button-error";
					break;
				case 'warning':
					buttonClass = buttonClass + " ccCMS_button-warning";
					break;
			}
		}
		newElement.setAttribute("class", buttonClass + " ccCMS_button");

		newElement.innerHTML = buttonData.value;
		newElement.setAttribute("onclick", buttonData.onClick + ";return false;");

		return newElement;
	},

	createModuleWindow: function(windowData)
	{
		var mainSection = smdQS("body > div > main");

		//todo: check for changes
		mainSection.innerHTML = "";

		var newHeader = document.createElement("HEADER");
		newHeader.innerHTML = "<h2>" + windowData.title + "</h2>";
		mainSection.appendChild(newHeader);

		var form = document.createElement("FORM");
		form.setAttribute('class', 'pure-form pure-form-aligned');
		form.setAttribute('id', 'form_' + ccCMS.activeModule);
		var fieldset = document.createElement("FIELDSET");

		var newElement = null;
		var newElementLabel = null;
		var elementContainer = null;
		var groupClass = '';
		for (var i in windowData.content) {

			groupClass = 'pure-control-group';

			switch (windowData.content[i].type) {
				case 'textarea':
					newElement = document.createElement("TEXTAREA");
					newElement.setAttribute("id", windowData.content[i].name);
					newElement.setAttribute("name", windowData.content[i].name);
					newElement.setAttribute("class", 'pure-input-2-3');
					if (typeof windowData.content[i].rows !== "undefined") {
						newElement.setAttribute("rows", windowData.content[i].rows);
					}
					newElement.innerHTML = windowData.content[i].value;
					break;
				case 'input':
					newElement = document.createElement("INPUT");
					newElement.setAttribute("id", windowData.content[i].name);
					newElement.setAttribute("name", windowData.content[i].name);
					if (typeof windowData.content[i].inputtype === "undefined") {
						windowData.content[i].inputtype = "text";
					}
					newElement.setAttribute("type", windowData.content[i].inputtype);
					newElement.setAttribute("value", windowData.content[i].value);
					newElement.setAttribute("checked", windowData.content[i].checked);
					if (windowData.content[i].inputtype === 'checkbox' || windowData.content[i].inputtype === 'radio') {
						groupClass = 'pure-controls';
					} else {
						newElement.setAttribute("class", 'pure-input-2-3');
					}
					break;
				case 'text':
					newElement = document.createElement("P");
					newElement.innerHTML = windowData.content[i].value;
					break;
				case 'hidden':
					newElement = document.createElement("INPUT");
					newElement.setAttribute("id", windowData.content[i].name);
					newElement.setAttribute("name", windowData.content[i].name);
					newElement.setAttribute("type", "hidden");
					newElement.setAttribute("value", windowData.content[i].value);
					break;
				case 'select':
					newElement = document.createElement("SELECT");
					newElement.setAttribute("class", 'pure-input-2-3');
					newElement.setAttribute("id", windowData.content[i].name);
					newElement.setAttribute("name", windowData.content[i].name);
					var option = null;
					for (var oi in windowData.content[i].options) {
						option = document.createElement("OPTION");
						option.setAttribute("value", windowData.content[i].options[oi].value);
						option.innerHTML = windowData.content[i].options[oi].caption;
						newElement.appendChild(option);
					}
					break;
				case 'label':
					newElement = document.createElement("LABEL");
					if (typeof windowData.content[i].for !== undefined) {
						newElement.setAttribute("for", windowData.content[i].for);
					}
					newElement.innerHTML = windowData.content[i].value;
					break;
				case 'button':
					newElement = ccCMS_form._getButtonElement(windowData.content[i]);
					break;
				case 'buttonGroup':
					newElement = document.createElement("DIV");
					newElement.setAttribute("class", "pure-button-group");
					newElement.setAttribute("role", "group");
					for (var bi in windowData.content[i].buttons) {
						newElement.appendChild(ccCMS_form._getButtonElement(windowData.content[i].buttons[bi]));
					}
					break;
				default:
					console.log("ERROR: COULD NOT INTERPRET NODE CONFIG:")
					console.log(windowData.content[i]);
			}

			if (typeof windowData.content[i].label !== "undefined") {

				newElementLabel = document.createElement('LABEL');
				newElementLabel.setAttribute("for", windowData.content[i].name);

				if (windowData.content[i].inputtype === 'checkbox' || windowData.content[i].inputtype === 'radio') {
					newElementLabel.appendChild(newElement);
					newElementLabel.setAttribute('class','pure-checkbox');
					newElement = null;
				}

				newElementLabel.appendChild(document.createTextNode(windowData.content[i].label));
			}

			if (typeof windowData.content[i].align !== "undefined") {
				switch (windowData.content[i].align) {
					case 'right':
						groupClass += " ccCMS_group-align-right";
						break;
					case 'center':
						groupClass += " ccCMS_group-align-center";
						break;
				}
			}

			if (newElement !== null || newElementLabel !== null) {

				elementContainer = document.createElement("DIV");
				elementContainer.setAttribute('class', groupClass);

				if (newElementLabel !== null) {
					elementContainer.appendChild(newElementLabel);
				}
				if (newElement !== null) {
					elementContainer.appendChild(newElement);
				}

				fieldset.appendChild(elementContainer);
				newElement = elementContainer = newElementLabel = null;
			}
		}
		form.appendChild(fieldset);
		mainSection.appendChild(form);
	},

	queryOption: function(queryName, queryString)
	{
		//!TODO: Styled version.

		var queryReturn = prompt(queryString, "");

		if (queryReturn != null && queryReturn != "") {
			var returnObject = {};
			returnObject[queryName] = queryReturn;
			ccCMS.sendDataToActiveModule(returnObject);
		} else {
			alert("empty?");
		}
		return false;
	},
	
	popupMessage: function(message)
	{
		//!TODO: Styled version.

		alert(message);
	},

	sendForm: function()
	{
		var kvpairs = {};
		var form = smdQS("#form_" + ccCMS.activeModule);
		for ( var i = 0; i < form.elements.length; i++ ) {
		  var e = form.elements[i];
			if(typeof e.name !== "undefined" && typeof e.value !== "undefined") {
				kvpairs[e.name] = e.value;
			}
		}
		ccCMS.sendDataToActiveModule(kvpairs);
	}
}

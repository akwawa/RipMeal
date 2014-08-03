function details(outerHTML) {
	// var param = {"path":"client", "fonctions":"detailsClient", "session":"all", "id":id};
	
	var nouveauDiv = null;
	nouveauDiv = document.createElement("div");
	nouveauDiv.id = "fond_detail";
	nouveauDiv.onclick = function() { fermer_details(); }
	var taille_page = getDocumentSize();
	var hauteurActuelle = getScrollPosition();
	nouveauDiv.style.width = taille_page[0]+'px';
	nouveauDiv.style.height = taille_page[1]+'px';
	document.body.appendChild(nouveauDiv);

	var nouveauDiv = null;
	nouveauDiv = document.createElement("article");
	nouveauDiv.id = "apercu_details";
	// background:#FFFFFF;border:20px solid #DDDDDD;border-radius:10px;box-shadow:0px 0px 20px #000000;
	nouveauDiv.innerHTML = outerHTML;
	nouveauDiv.style.top = hauteurActuelle[1]+'px';
	document.body.appendChild(nouveauDiv);

	// ecrire(ajax(param));
	
	return false;
}

function fermer_details() {
	var d = document.body; 
	d.removeChild(document.getElementById("fond_detail"));
	d.removeChild(document.getElementById("apercu_details"));
	
	return false;
}

function getDocumentSize() {
	return new Array((document.documentElement && document.documentElement.scrollWidth) ? document.documentElement.scrollWidth : (document.body.scrollWidth > document.body.offsetWidth) ? document.body.scrollWidth : document.body.offsetWidth,(document.documentElement && document.documentElement.scrollHeight) ? document.documentElement.scrollHeight : (document.body.scrollHeight > document.body.offsetHeight) ? document.body.scrollHeight : document.body.offsetHeight);
}

function getScrollPosition() {
	return Array((document.documentElement && document.documentElement.scrollLeft) || window.pageXOffset || self.pageXOffset || document.body.scrollLeft,(document.documentElement && document.documentElement.scrollTop) || window.pageYOffset || self.pageYOffset || document.body.scrollTop);
}

Object.size = function(obj) {
	var size = 0, key;
	for (key in obj) {
		if (obj.hasOwnProperty(key)) size++;
	}
	return size;
};

function trim(myString) {
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}

/******** Noty *********/

function noty_modifSuccess() {
	var layout=jQuery.cookie('positionMessage');
	var n = noty({
				dismissQueue: true,
				force: true,
				layout: layout,
				theme: 'defaultTheme',
				text: 'Les modifications ont été enregistrées',
				type: 'success'
			});
}

function noty_modifError(message) {
	if (!message) {message='Les modifications n\'ont pas été enregistrées.'}
	var layout=jQuery.cookie('positionMessage');
	var n = noty({
				dismissQueue: true,
				force: true,
				layout: layout,
				theme: 'defaultTheme',
				text: message,
				type: 'error'
			});
}

function noty_confirm(success, error) {
	var layout=jQuery.cookie('positionMessage');
	var n = noty({
				text        : 'Êtes-vous sur de vouloir enregistrer les modifications?',
				type        : 'alert',
				dismissQueue: false,
				layout      : layout,
				theme       : 'defaultTheme',
				buttons     : [
					{addClass: 'btn btn-primary', text: 'Oui', onClick: function ($noty) {
						$noty.close();
						eval(success);
						}
					},
					{addClass: 'btn btn-danger', text: 'Non', onClick: function ($noty) {
						$noty.close();
						eval(error);
						}
					}
				]
	});
}

function noty_action(success, error, cancel) {
	var layout=jQuery.cookie('positionMessage');
	var n = noty({
	text        : 'Que voulez-vous faire?',
	type        : 'alert',
	dismissQueue: true,
	layout      : layout,
	theme       : 'defaultTheme',
	buttons     : [
		{addClass: 'btn btn-primary', text: 'Modifier', onClick: function ($noty) {
			$noty.close();
			eval(success);
			}
		},
		{addClass: 'btn btn-danger', text: 'Supprimer', onClick: function ($noty) {
			$noty.close();
			eval(error);
			}
		},
		{addClass: 'btn btn-information', text: 'Annuler', onClick: function ($noty) {
			$noty.close();
			eval(cancel);
			}
		}
	]
	});
}
/***********************/

/******** AJAX version 2 ******/
function ajax(param, attente) {
	// param={'chemin':'api-compte-modifier','idPublic':id}
	var retour = false;
	var premier = true;
	var data = '';
	if (!attente) { attente = false; }
	for (var name in param) {
		if (name == 'chemin') {
			var chemin = param[name];
		} else if (typeof(param[name]) == 'string' || typeof(param[name]) == 'number') {
			if (premier) {
				data += name+'='+param[name];
				premier=false;
			} else {
				data += '&'+name+'='+param[name];
			}
		} else if (typeof(param[name]) == 'object') {
			var temp = param[name];
			for (var cle in temp) {
				if (typeof(temp[cle]) == 'string' && temp[cle] != "") {
					alert(cle+" "+temp[cle]);
				}
			}
		} else {
			alert(name+" "+typeof(param[name]));
		}
	}
	var req = createInstance();
	req.onreadystatechange = function() {
		if(req.readyState == 4) {
			if(req.status == 200) {
				retour = req.responseText;
			} else {
				alert("Error: returned status code " + req.status + " " + req.statusText + " : " + module + " " + page+" "+chemin);
			}
		}
	};
	if (!chemin) {
		var chemin = encodeURIComponent(api)+"-"+encodeURIComponent(module)+"-"+encodeURIComponent(page);
	}
	req.open("POST", chemin, attente);
	
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send(data);
	// alert(data);
	return retour;
}

function ajax_sans_ecrire(param) {return ajax(param, false);}

function createInstance() {
	var req = null;
	if(window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		try {
			req = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert("XHR non créé");
			}
		}
	}
	return req;
}
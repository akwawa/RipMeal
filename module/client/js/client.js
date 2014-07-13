var compte = new Object();

function modifier_compte(element) {
	var id=element.getAttribute("data-id");

	outerHTML = '<div id="chargement">Chargement en cours</div>';
	details(outerHTML);
	var form = document.getElementById("compte-modifier").innerHTML;

	if (!compte[id]) {
		var json=ajax({'chemin':'api-compte-modifier','idPublic':id});
		var result=JSON.parse(json);
		compte[id]=result['result'][0];
	}
	// alert(JSON.stringify(result));
	
	outerHTML='<form method="post" id="form-compte-modifier" action="#" onclick="return modifier_compte_valider(this);" data-id="' + id + '">' + form + "</form>";
	document.getElementById("chargement").outerHTML=outerHTML;
	$('#form-compte-modifier').populate({'login':compte[id]["u.login"], 'rank':compte[id]["r.id"]});
	
	var max = 0;
	$("label").each(function(){
		$(this).css('text-align','right');
		if ($(this).width() > max) max = $(this).width();    
	});
	$("label").width(max);
	
	return false;
}

function modifier_compte_valider(element){
	var id=element.getAttribute("data-id");
	var login=element.getAttribute("data-id");
	var id=element.getAttribute("data-id");
	
	alert(id);
	return false;
}
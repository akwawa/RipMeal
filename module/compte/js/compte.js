var compte = new Object();

function supprimer_compte(id) {
	// var id=element.getAttribute("data-id");
	
	// var reponse=confirm('Êtes-vous sûr de vouloir supprimer ce compte?');
	var reponse=true;

	if (reponse===true) {
		var json=ajax({'chemin':'api-compte-supprimer','idPublic':id});
		var result=JSON.parse(json);
		if(result['result'][0]==true){
			$('#ligne_'+id).remove();
		}else{
			alert('Une erreur est survenue');
		}
	}

	return false;
}

function action(element) {
	var id=$(element).attr("data-id");
	noty_action("modifier_compte("+id+");", "supprimer_compte("+id+");", "");

	return false;
}

function modifier_compte(id) {
	// var id=element.getAttribute("data-id");

	outerHTML = '<div id="chargement">Chargement en cours</div>';
	details(outerHTML);
	var form = document.getElementById("compte-modifier").innerHTML;

	if (!compte[id]) {
		var json=ajax({'chemin':'api-compte-modifier','idPublic':id});
		var result=JSON.parse(json);
		var temp=result['result'];
		compte[id]=temp[0];
	}
	// alert(JSON.stringify(result));
	
	outerHTML='<form method="post" id="form-compte-modifier" action="#" onsubmit="return modifier_compte_valider(this);" data-id="' + id + '">' + form + "</form>";
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

function modifier_compte_valider(form){
	var id=form.getAttribute("data-id");
	var login=form.elements["login"].value;
	var pass=form.elements["pass"].value;
	var idRank=form.elements["rank"].value;
	var rank=form.elements["rank"].options[parseInt(idRank)-1].text;
	var niveau=form.elements["niveau"].value;
	
	if (pass) {
		var json=ajax({'chemin':'api-compte-modifier','idPublic':id, 'login':login, 'idRank':idRank,'niveau':niveau});
	} else {
		var json=ajax({'chemin':'api-compte-modifier','idPublic':id, 'login':login, 'idRank':idRank,'niveau':niveau,'pass':pass});
	}
	var result=JSON.parse(json);
	if(result['result'][0]==true){
		$('#ligne_'+id).remove();
		$('#table_compte > tbody:first').append('<tr><td>'+id+'</td><td>'+login+'</td><td>'+rank+'</td><td><a class="popup" href="#" onclick="action(this);" data-id="'+id+'">Action</a></td></tr>');
		fermer_details();
		noty_modifSuccess();
	}else{
		noty_modifError();
	}
	
	return false;
}
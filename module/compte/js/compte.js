var compte = new Object();

function supprimer_compte(id) {
	if (typeof(confirm)==='undefined') {
		noty_confirm("supprimer_tournee("+id+", true)", '');
	} else {
		var json=ajax({'chemin':'api-compte-supprimer','idPublic':id});
		var result=JSON.parse(json);
		if(result['result'][0]===true){
			$('#tr_'+id).remove();
			noty_modifSuccess();
		}else{
			noty_modifError();
		}
	}
}

function modifier_compte(id) {
	$(document).ready( function () {
		$("#compte-modifier").removeClass("desktop-hidden");
		$('html,body').animate({scrollTop: $("#compte-modifier").offset().top}, 'slow');
		$('#mod-id').val($('#tr_' + id).data("id"));
		$('#mod-login').val($('#tr_' + id).data("login"));
		$('#mod-pass').val($('#tr_' + id).data("pass"));
		$('#mod-idRank').val($('#tr_' + id).data("idrank"));
	});
}

function valide_modif(json) {
	json = JSON.parse(json);
	if(json['result']) {
		noty_modifSuccess();
		var id = json['id'];
		var login = json['login'];
		var idRank = json['idRank'];
		var nameRank = json['nameRank'];
		$('#tr_' + id).data("name", name);
		$('#tr_' + id).data("login", login);
		$('#tr_' + id).data("idRank", idRank);

		$('#td_' + id + '_login').text(login);
		$('#td_' + id + '_idRank').text(nameRank);
		$('#td_' + id + '_idRank').data("idRank", idRank);

		$("#compte-modifier").addClass("desktop-hidden");
	} else {
		noty_modifError();
	}
}

function ajouter_compte() {
	$(document).ready( function () {
		$("#compte-ajouter").removeClass("desktop-hidden");
		$('html,body').animate({scrollTop: $("#compte-ajouter").offset().top}, 'slow');
	});
}

function valide_ajout(json) {
	json = JSON.parse(json);
	if(json['result']===true){
		noty_modifSuccess();
		var id = json['id'];
		var login = json['login'];
		var idRank = json['idRank'];
		var nameRank = json['nameRank'];
		$("#table_compte tr:last").after('<tr id="tr_' + id + '" data-id="' + id + '" data-login="' + login + '" data-idRank="' + idRank + '"><td id="td_' + id + '_id">' + id + '</td><td id="td_' + id + '_login">' + login + '</td><td id="td_' + id + '_idRank">' + nameRank + '</td><td><a class="popup" href="#" onclick="action(this);" data-id="' + id + '">Action</a></td></tr>');

		$("#compte-ajouter").addClass("desktop-hidden");
	} else {
		noty_modifError();
	}
}

function action(element) {
	var id=$(element).attr("data-id");
	noty_action("modifier_compte("+id+");", "supprimer_compte("+id+");", "");

	return false;
}
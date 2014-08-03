function ajouter_regime() {
	$(document).ready( function () {
		$("#regime-ajouter").removeClass("desktop-hidden");
		$('html,body').animate({scrollTop: $("#regime-ajouter").offset().top}, 'slow');
	});
}

function valide_ajout(json) {
	json = JSON.parse(json);
	if(json['result']) {
		noty_modifSuccess();
		var id = json['id'];
		var name = json['name'];
		var fullname = json['fullname'];
		var idRemp = json['idRemp'];
		if (idRemp===false) idRemp="";
		$("#table_regime tr:last").after('<tr id="tr_' + id + '" data-id="' + id + '" data-name="' + name + '" data-fullname="' + fullname + '" data-idRemp="' + idRemp + '"><td id="td_' + id + '_id">' + id + '</td><td id="td_' + id + '_name">' + name + '</td><td id="td_' + id + '_fullname">' + fullname + '</td><td id="td_' + id + '_idRemp">' + idRemp + '</td><td><a class="popup" href="#" onclick="action(this);" data-id="' + id + '">Action</a></td></tr>');

		$("#regime-ajouter").addClass("desktop-hidden");
	} else {
		noty_modifError();
	}
}

function supprimer_regime(id, confirm){
	if (typeof(confirm)==='undefined') {
		noty_confirm("supprimer_regime("+id+", true)", '');
	} else {
		var json=ajax({'chemin':'api-regime-supprimer','id':id});
		var result=JSON.parse(json);
		if(result['result'][0]==true){
			$('#tr_'+id).remove();
			noty_modifSuccess();
		}else{
			noty_modifError();
		}
	}
}

function modifier_regime(id) {
	$(document).ready( function () {
		$("#regime-modifier").removeClass("desktop-hidden");
		$('html,body').animate({scrollTop: $("#regime-modifier").offset().top}, 'slow');
		$('#mod-id').val($('#tr_' + id).data("id"));
		$('#mod-name').val($('#tr_' + id).data("name"));
		$('#mod-fullname').val($('#tr_' + id).data("fullname"));
		$('#mod-idRemp').val($('#tr_' + id).data("idRemp"));
	});
}

function valide_modif(json) {
	json = JSON.parse(json);
	if(json['result']) {
		noty_modifSuccess();
		var id = json['id'];
		var name = json['name'];
		var fullname = json['fullname'];
		var idRemp = json['idRemp'];

		$('#tr_' + id).data("name", name);
		$('#tr_' + id).data("fullname", fullname);
		$('#tr_' + id).data("idRemp", idRemp);

		$('#td_' + id + '_name').text(name);
		$('#td_' + id + '_fullname').text(fullname);
		$('#td_' + id + '_idRemp').text(idRemp);

		$("#regime-modifier").addClass("desktop-hidden");
	} else {
		noty_modifError();
	}
}

function action(element) {
	var id=$(element).attr("data-id");
	noty_action("modifier_regime("+id+");", "supprimer_regime("+id+");", "");

	return false;
}
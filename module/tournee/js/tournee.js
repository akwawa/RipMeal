function modif_numero(idClient, numeroTourneeOld, numeroTourneeNew, idTournee){
	var update=new Object();
	update["idClient"]=idClient;
	update["numeroTourneeOld"]=numeroTourneeOld;
	update["numeroTourneeNew"]=numeroTourneeNew;
	update["idTournee"]=idTournee;
	var request = jQuery.ajax({"url":"api-tournee-modifOrdre", "type":"POST", "data":update});
	request.done(function(json) {
		var result=JSON.parse(json);
		if(result['result']==true){
			noty_modifSuccess();
			voir_client(idTournee);
		}else{
			noty_modifError();
		}
	});
}

function forcer_ordre(idTournee) {
	var update=new Object();
	update["idTournee"]=idTournee;
	var request = jQuery.ajax({"url":"api-tournee-forcerOrdre", "type":"POST", "data":update});
	request.done(function(json) {
		var result=JSON.parse(json);
		if(result['result']==true){
			noty_modifSuccess();
			voir_client(idTournee);
		}else{
			noty_modifError();
		}
	});
}

function voir_client(id) {
	$(document).ready( function () {
		var update=new Object();
		update["id"]=id;

		if ($('#tournee-clients').length){$('#tournee-clients').remove();}

		var request = jQuery.ajax({"url":"api-tournee-clients", "type":"POST", "data":update});
		request.done(function(json) {
			json = JSON.parse(json);
			if(json['result']) {
				$("#contenu").after('<p><a class="button" href="#" onclick="forcer_ordre('+id+');">Forcer l\'ordre de tournée</a></p><table id="tournee-clients"><tr><thead><th>Nom</th><th>Numéro</th><th>Action</th></thead></tr><tr><tfoot><th>Nom</th><th>Numéro</th><th colspan="2">Action</th></tfoot></tr>');
				$.each(json['result'], function(key, val) {
					$('#tournee-clients').append('<tr><td>'+val['c.name']+' '+val['c.firstname']+'</td><td>'+val['c.numeroTournee']+'</td><td><img class="fleche" onclick="modif_numero('+val['c.id']+', '+val['c.numeroTournee']+', '+(parseInt(val['c.numeroTournee'])+1)+', '+id+');" src="img-tournee-fleche_bas.jpeg" /></td><td><img class="fleche" onclick="modif_numero('+val['c.id']+', '+val['c.numeroTournee']+', '+(parseInt(val['c.numeroTournee'])-1)+', '+id+');" src="img-tournee-fleche_haut.jpeg" /></td></tr>');
				});
			} else {
				noty_modifError();
			}
		});
		// $('html,body').animate({scrollTop: $("#tournee-ajouter").offset().top}, 'slow');
	});
}

function ajouter_tournee() {
	$(document).ready( function () {
		$("#tournee-ajouter").removeClass("desktop-hidden");
		$('html,body').animate({scrollTop: $("#tournee-ajouter").offset().top}, 'slow');
	});
}

function valide_ajout(json) {
	json = JSON.parse(json);
	if(json['result']) {
		noty_modifSuccess();
		var id = json['id'];
		var name = json['name'];
		var fullname = json['fullname'];
		$("#table_tournee tr:last").after('<tr id="tr_' + id + '" data-id="' + id + '" data-name="' + name + '" data-fullname="' + fullname + '"><td id="td_' + id + '_id">' + id + '</td><td id="td_' + id + '_name">' + name + '</td><td id="td_' + id + '_fullname">' + fullname + '</td><td><a class="popup" href="#" onclick="action(this);" data-id="' + id + '">Action</a></td></tr>');

		$('#tr_' + id).data("name", name);
		$('#tr_' + id).data("fullname", fullname);

		$('#td_' + id + '_name').text(name);
		$('#td_' + id + '_fullname').text(fullname);

		$("#tournee-ajouter").addClass("desktop-hidden");
	} else {
		noty_modifError();
	}
}

function modifier_tournee(id) {
	if (id===1) {
		noty_modifError('Vous ne pouvez pas modifier cette tournée');
	} else {
		$(document).ready( function () {
			$("#tournee-modifier").removeClass("desktop-hidden");
			$('html,body').animate({scrollTop: $("#tournee-modifier").offset().top}, 'slow');
			$('#mod-id').val($('#tr_' + id).data("id"));
			$('#mod-name').val($('#tr_' + id).data("name"));
			$('#mod-fullname').val($('#tr_' + id).data("fullname"));
		});
	}
}

function valide_modif(json) {
	json = JSON.parse(json);
	if(json['result']) {
		noty_modifSuccess();
		var id = json['id'];
		var name = json['name'];
		var fullname = json['fullname'];
		$('#tr_' + id).data("name", name);
		$('#tr_' + id).data("fullname", fullname);

		$('#td_' + id + '_name').text(name);
		$('#td_' + id + '_fullname').text(fullname);

		$("#tournee-modifier").addClass("desktop-hidden");
	} else {
		noty_modifError();
	}
}

function supprimer_tournee(id, confirm){
	if (id===1) {
		noty_modifError('Vous ne pouvez pas supprimer cette tournée');
	} else {
		if (typeof(confirm)==='undefined') {
			noty_confirm("supprimer_tournee("+id+", true)", '');
		} else {
			var json=ajax({'chemin':'api-tournee-supprimer','id':id});
			var result=JSON.parse(json);
			if(result['result'][0]==true){
				$('#tr_'+id).remove();
				noty_modifSuccess();
			}else{
				noty_modifError();
			}
		}
	}
}

function action(element) {
	var id=$(element).attr("data-id");
	noty_action("modifier_tournee("+id+");", "supprimer_tournee("+id+");", "");

	return false;
}

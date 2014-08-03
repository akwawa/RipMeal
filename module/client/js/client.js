var compte = new Object();

function modifier_compte(id) {
	$(document).ready( function () {
		$('html,body').animate({scrollTop: $("#client-modifier").offset().top}, 'slow');
		$('#id').val($('#tr_' + id).data("id"));
		$('#name').val($('#tr_' + id).data("name"));
		$('#firstname').val($('#tr_' + id).data("firstname"));
		$('#sexe').val($('#tr_' + id).data("sexe"));
		$('#address').val($('#tr_' + id).data("address"));
		$('#fulladdress').val($('#tr_' + id).data("fulladdress"));
		$('#zip').val($('#tr_' + id).data("zip"));
		$('#city').val($('#tr_' + id).data("city"));
		$('#phone').val($('#tr_' + id).data("phone"));
		$('#secondPhone').val($('#tr_' + id).data("secondphone"));
		$('#idTournee').val($('#tr_' + id).data("idtournee"));

		$('#numeroTournee').append('<option value="0">En premier</option>');
		var json = jQuery.parseJSON($('#tab_numeroTournee').text());
		jQuery.each(json[$('#tr_' + id).data("idtournee")], function(i, value) {
            $('#numeroTournee').append($('<option>').text(value["c.name"]+" "+value["c.firstname"]).attr('value', value["c.numeroTournee"]));
        });
        $('#numeroTournee').val($('#tr_' + id).data("numerotournee"));


		$('#pain').val($('#tr_' + id).data("pain"));
		$('#potage').val($('#tr_' + id).data("potage"));
		$('#actif').val($('#tr_' + id).data("actif"));
		$('#info').val($('#tr_' + id).data("info"));
		$('#AlimentInterdit').val($('#tr_' + id).data("alimentinterdit"));
		$('#sacPorte').val($('#tr_' + id).data("sacporte"));
		$('#corbeille').val($('#tr_' + id).data("corbeille"));
		$('#ressourceName').val($('#tr_' + id).data("ressourcename"));
		$('#ressourceNumber').val($('#tr_' + id).data("ressourcenumber"));
		$('#ressourceSecondNumber').val($('#tr_' + id).data("ressourcesecondnumber"));
		$('#ressourceAddress').val($('#tr_' + id).data("ressourceaddress"));
	});
}

// function modifier_compte(id) {
// 	outerHTML = '<div id="chargement">Chargement en cours</div>';
// 	details(outerHTML);
// 	var form = document.getElementById("client-modifier").innerHTML;

// 	if (!compte[id]) {
// 		var json=ajax({'chemin':'api-compte-modifier','idPublic':id});
// 		var result=JSON.parse(json);
// 		compte[id]=result['result'][0];
// 	}
	
// 	outerHTML='<form method="post" id="form-compte-modifier" action="#" onsubmit="return modifier_compte_valider(this);" data-id="' + id + '">' + form + "</form>";
// 	document.getElementById("chargement").outerHTML=outerHTML;
// 	$('#form-compte-modifier').populate({'login':compte[id]["u.login"], 'rank':compte[id]["r.id"]});
	
// 	var max = 0;
// 	$("label").each(function(){
// 		$(this).css('text-align','right');
// 		if ($(this).width() > max) max = $(this).width();    
// 	});
// 	$("label").width(max);
	
// 	return false;
// }

function modifier_compte_valider(element){
	var id=element.getAttribute("data-id");
	var login=element.getAttribute("data-id");
	var id=element.getAttribute("data-id");
	
	alert(id);
	return false;
}

function action(element) {
	var id=$(element).attr("data-id");
	noty_action("modifier_compte("+id+");", "supprimer_compte("+id+");", "");

	return false;
}

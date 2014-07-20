var compte = new Object();

function modifier_compte(id) {
	$(document).ready( function () {
		$('html,body').animate({scrollTop: $("#client-modifier").offset().top}, 'slow');
		$('#id').val($('#td_' + id + '_id').text());
		$('#name').val($('#td_' + id + '_name').text());
		$('#firstname').val($('#td_' + id + '_firstname').text());
		$('#sexe').val($('#td_' + id + '_sexe').text());
		$('#address').val($('#td_' + id + '_address').text());
		$('#fulladdress').val($('#td_' + id + '_fulladdress').text());
		$('#zip').val($('#td_' + id + '_zip').text());
		$('#city').val($('#td_' + id + '_city').text());
		$('#phone').val($('#td_' + id + '_phone').text());
		$('#secondPhone').val($('#td_' + id + '_secondPhone').text());
		$('#idTournee').val($('#td_' + id + '_idTournee').text());
		$('#numeroTournee').val($('#td_' + id + '_numeroTournee').text());
		$('#pain').val($('#td_' + id + '_pain').text());
		$('#potage').val($('#td_' + id + '_potage').text());
		$('#actif').val($('#td_' + id + '_actif').text());
		$('#info').val($('#td_' + id + '_info').text());
		$('#AlimentInterdit').val($('#td_' + id + '_AlimentInterdit').text());
		$('#sacPorte').val($('#td_' + id + '_sacPorte').text());
		$('#corbeille').val($('#td_' + id + '_corbeille').text());
		$('#ressourceName').val($('#td_' + id + '_ressourceName').text());
		$('#ressourceNumber').val($('#td_' + id + '_ressourceNumber').text());
		$('#ressourceSecondNumber').val($('#td_' + id + '_ressourceSecondNumber').text());
		$('#ressourceAddress').val($('#td_' + id + '_ressourceAddress').text());
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

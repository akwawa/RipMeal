function ajouter_sauvegarde() {
	$(document).ready( function () {
		$("#sauvegarde-creer").removeClass("desktop-hidden");
		$('html,body').animate({scrollTop: $("#sauvegarde-creer").offset().top}, 'slow');
	});
}

function valide_save(json) {
	json = JSON.parse(json);
	if(json['result']) {
		location.reload();
	} else {
		noty_modifError();
	}
}

function supprimer_sauvegarde(element) {
	var fichier = $(element).attr("data-fichier");
	var update=new Object();
	update["fichier"]=fichier;
	var request = jQuery.ajax({"url":"api-sauvegarde-supprimer", "type":"POST", "data":update});
	request.done(function(json) {
		var result=JSON.parse(json);
		if(result['result']==true){
			noty_modifSuccess();
			if (fichier==="all") {
				$.each($(element).parent().parent().parent(), function(key, val) {
					$(val).remove();
				});
			} else {
				$(element).parent().parent().remove();
			}
		}else{
			noty_modifError();
		}
	});
}
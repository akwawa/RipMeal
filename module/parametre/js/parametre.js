var update = new Object();

function modifier_parametre() {
	$("label").each(function(){
		id=$(this).attr('for');
		value=$('#'+id).val();
		update[id]=value;
	});

	noty_confirm("modif_param()", '');
}

function modif_param(){
	var request = jQuery.ajax({"url":"api-parametre-modifier", "type":"POST", "data":update});
	request.done(function(msg) {
		if (msg==true){
			jQuery.each(update, function(element){
				jQuery.cookie(element, update[element]);
			});
			noty_modifSuccess();
		} else {

		}
	});
}
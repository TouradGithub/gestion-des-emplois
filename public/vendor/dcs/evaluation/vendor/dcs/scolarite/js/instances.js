function resetInitModule(){

}

function filterInstances(){
    var id_etablissement = $("#etablissement").val();
    var id_specialite = $("#specialite").val();
    var id_etat = $("#etat").val();
    var id_evaluation = $("#progevaluation").val();
    $('#datatableshow').DataTable().ajax.url(racine+"instances/getDT/none/all/"+id_etablissement+"/"+id_specialite+"/"+id_etat+"/"+id_evaluation).load();
}

function valider_note_instance(element){
	saveform(element,function(data){
     if(data.success == 'true'){
     	var html = '<div class="alert alert-success" role="alert">'+data.msg+'</div>';
     	$('#msg_valide').html(html);
        $('#msg_valide').show();
        $('#button_validation').hide();
        $("#observation").attr("disabled", "disabled");
        setTimeout(function(){ $('#msg_valide').hide();} , 5000);
        $('#datatableshow').DataTable().ajax.url(racine+"instances/getDT").load();
     }
	});
}

function importerInstances(element){
    var id_etablissement = $("#etablissement").val();
    var id_specialite = $("#specialite").val();
    var id_etat = $("#etat").val();
    var id_valuation = $("#progevaluation").val();
    $(element).attr('href',racine+"instances/importerInstences/"+id_etablissement+"/"+id_specialite+"/"+id_etat+"/"+id_valuation);
}

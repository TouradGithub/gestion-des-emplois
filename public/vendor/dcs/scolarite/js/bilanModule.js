function resetInitModule(){

}

function filterBilanModule()
{
	var id_specialite = $("#id_specialite").val();
	var id_module = $("#id_module").val();
    $('#datatableshow').DataTable().ajax.url(racine+"bilanmodules/getDT/none/"+id_specialite).load();
}

function exporterBilan(element){
    var id_specialite = $("#id_specialite").val();
    $(element).attr('href',racine+"bilanmodules/exporter/"+id_specialite);
}

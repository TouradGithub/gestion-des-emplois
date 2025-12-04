function resetInitModule()
{

}

function filterModule()
{
	var id_module = $("#id_module").val();
	var id_specialite = $("#id_specialite").val();
    $('#datatableshow').DataTable().ajax.url(racine+"competences/getDT/"+id_specialite+"/"+id_module).load();
}

function filterbalance(){
	var id_module = $("#id_module").val();
	var id_specialite = $("#id_specialite").val();
	var id_etab = $("#id_etabliss").val();
	var id_eval = $("#id_eval").val();
    $('#datatableshow').DataTable().ajax.url(racine+"bilan/getDT/"+id_specialite+"/"+id_module+"/"+id_etab+"/"+id_eval).load();
}
function filterbesoin(){
	var id_module = $("#id_module").val();
	var id_specialite = $("#id_specialite").val();
	var id_etab = $("#id_etabliss").val();
	 $('#datatableshow').DataTable().ajax.url(racine+"besoin/getDT/"+id_specialite+"/"+id_module+"/"+id_etab).load();	
}

function filterSpecialites()
{
	var id_specialite = $("#id_specialite").val();
	if(id_specialite != '' && id_specialite != 'all')
	{
		$.ajax({
			type:'get',
			url: racine + 'competences/getListeModuleSpecialite/'+ id_specialite,
			success:function(data)
			{
			   // dd(data);
				$("#id_module").html(data);
				$("#id_module").selectpicker('refresh');
				filterModule();
				//resetInit();
			},
		});
	}
	else if(id_specialite == 'all')
	{
		$.ajax({
			type:'get',
			url: racine + 'competences/getAllModules',
			success:function(data)
			{
				if (data !='')
				{
					$("#id_module").html(data);
					$("#id_module").selectpicker('refresh');
					filterModule();
				}
			},
		});

	}
}


function exporterBilan(element){
    var id_module = $("#id_module").val();
    var id_specialite = $("#id_specialite").val();
    var id_etablissement = $("#id_etabliss").val();
    var id_programme = $("#id_eval").val();
    $(element).attr('href',racine+"bilan/exporter/"+id_module+"/"+id_specialite+"/"+id_etablissement+"/"+id_programme);
}

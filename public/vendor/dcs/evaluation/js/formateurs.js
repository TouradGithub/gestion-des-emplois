//filtrer des formateur a partir de specialite
function filterFormateur() {
    var specialite_id = $("#specialite").val();
    var etablissement_id = $("#etablissement").val();
    $('#datatableshow').DataTable().ajax.url(racine + "formateurs/getDT/none/"+ specialite_id+ "/"+etablissement_id).load();
}

function resetInitModule(){
  
}

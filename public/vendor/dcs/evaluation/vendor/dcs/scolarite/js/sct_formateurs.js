//filtrer des formateur a partir de specialite
function filterFormateur() {
    var specialite_id = $("#specialite").val();
    var etablissement_id = $("#etablissement").val();
    var genre = $("#genre").val();
    var type_contrat = $("#type_contrat").val();
    var link = racine + "formateurs/getDT/"+ specialite_id+ "/"+etablissement_id+ "/"+genre+ "/"+type_contrat;
    $('#datatableshow').DataTable().ajax.url(link).load();
    $('#datatableshow').attr('link', link);
}

function resetInitModule(){

}

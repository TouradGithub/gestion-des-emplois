//filtrer des formateur a partir de specialite
function filterFormateur() {
    var specialite_id = $("#specialite").val();
    var etablissement_id = $("#etablissement").val();
    var type_formateur = $("#type_formateur").val();
    var genre = $("#genre").val();
    var type_contrat = $("#type_contrat").val();
    var link = racine + "formateurs/getDT/";
    // var link = racine + "formateurs/getDT/"+ specialite_id+ "/"+etablissement_id+ "/"+genre+ "/"+type_contrat+ "/"+type_formateur;
    $('#datatableshow').DataTable().ajax.url(link).load();
    $('#datatableshow').attr('link', link);
}

// function filterTypeFormateur() {
//     var type_formateur = $("#type_formateur").val();
//     $("#type_contrat_container").show();
//     if(type_formateur != 1){
//         $("#type_contrat").val(null);
//         $("#type_contrat_container").hide();
//     }
//     refreshDatatable()
// }

function resetInitModule(){

}

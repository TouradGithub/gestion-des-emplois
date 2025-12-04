function filtre_candidats()
{
    var annee_scolaire = $("#filter_annee_scolaire_id").val();
    var type_enseignement = $("#filter_type_enseignement_id").val();
    var type_etablissement = $("#filter_type_etablissement_id").val();
    var diplome_id = $("#filter_diplome_id").val();
    var niveau_formation = $("#filter_niveau_formation_id").val();
    var specialite_id = $("#filter_specialite_id").val();
    var selectionne = $("#filter_sectionnees").val();
    $("#imprimerListeCandidatsPDF").attr('href',racine + 'concours/candidats/liste_pdf/'+ annee_scolaire + '/' + type_enseignement + '/' + type_etablissement+'/'+diplome_id+'/'+niveau_formation+'/'+specialite_id+'/'+selectionne)
    $("#imprimerListeCandidatsExcel").attr('href',racine + 'concours/candidats/liste_excel/'+ annee_scolaire + '/'  + type_enseignement + '/' + type_etablissement+'/'+diplome_id+'/'+niveau_formation+'/'+specialite_id+'/'+selectionne)
    $("#datatableshow").attr('link',racine + 'concours/candidats/getDT/'+ annee_scolaire + '/'  + type_enseignement+ '/'+ type_etablissement+'/'+diplome_id+'/'+niveau_formation+'/'+specialite_id + '/' +selectionne);
    $('#datatableshow').DataTable().ajax.url(racine + 'concours/candidats/getDT/'+ annee_scolaire + '/'+ type_enseignement +'/' +type_etablissement+'/'+diplome_id+'/'+niveau_formation+'/'+specialite_id+'/'+selectionne).load();
}

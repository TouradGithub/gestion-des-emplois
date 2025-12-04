function getCompetencesByGroupeCompetence(groupe_competence,competence_id){
    $.ajax({
        type: 'get',
        url: racine + 'persons/getCompetencesByGroupeCompetence/' + $(groupe_competence).val(),
        success: function (data) {
            console.log(data);
            $(competence_id).html(data)
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function  filtrePersons(){
    var situation_professionnelle_filtre = $("#situation_professionnelle_filtre").val();
    var situation_familiale_filtre = $("#situation_familiale_filtre").val();
    var nationalite_filtre = $("#nationalite_filtre").val();
    // $("#imprimerTournee").attr('href', racine + 'tournees/pdf/' + etat_tournee + '/' + zone + '/' + equipe + '/' + type + '/' + programme + '/' + commune + '/' + dateDebut + '/' + dateFin)
    // $("#imprimerTourneeExcel").attr('href', racine + 'tournees/excel/' + etat_tournee + '/' + zone + '/' + equipe + '/' + type + '/' + programme + '/' + commune + '/' + dateDebut + '/' + dateFin)
    url = racine + 'persons/getDT/' + situation_professionnelle_filtre + '/' + situation_familiale_filtre + '/' + nationalite_filtre;
    $('#datatableshow').attr('link', url);
    $('#datatableshow').DataTable().ajax.url(url).load();
}

function exportProfil(){
    alert('exportProfil');
}

function getEtablissement(pays,etablissements){
    $.ajax({
        type: 'get',
        url: racine + 'persons/getEtablissementsByPays/' + $(pays).val(),
        success: function (data) {
            $(etablissements).html(data)
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}



function getFormsInput(element){
    var id_type = $(element).val();
    getTheContent('persons/getFormsInput/'+id_type, '.container_persons_forms');
}

function  getInputLieu(ele,container,lieu_id){
    var id = $(ele).val();
    getTheContent('persons/getInputLieu/'+id+'/'+lieu_id, container);
}



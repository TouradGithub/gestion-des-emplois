function filter_examConcours() {
    var type_correction = $('#correction_filtre').val();
    var exam_id = $('#exam_id').val();
    var matiere_id = $('#matieres').val();
    var niv_formation = $('#niv_formation').val();

    var url = racine + 'examConcours/getDT/' + exam_id + "/" + type_correction + '/' + matiere_id+"/"+niv_formation;
    $('#datatableshow').attr('link', url);
    $('#datatableshow').DataTable().ajax.url(url).load();
}

function ExportPDF(lien) {
    document.filterExportForm.action = racine + lien;
    document.filterExportForm.target = "_blank";    // Open in a new window
    document.filterExportForm.submit();             // Submit the page
    return true;
}

function validerexamConcours(exam_id) {
    var msg = "Etes vous sûr de vouloir calculer les moyennes et valider les resultats?";
    // var confirme = confirm(msg);

    var specialite = $('#specialites').val();
    var niv_formation = $('#niv_formation').val();
    // if(confirme) {
    //     var specialite = $('#specialites').val();
    //     var niv_formation = $('#niv_formation').val();
    //     $.ajax({
    //         type: 'get',
    //         url: racine + 'examConcours/validerNotes/' + exam_id + "/"+ niv_formation + "/" + specialite,
    //         success: function (data) {
    //             getData();
    //
    //         },
    //         error: function () {
    //             $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
    //         }
    //     });
    // }
    var link = racine + 'examConcours/validerNotes/' + exam_id + "/"+ niv_formation + "/" + specialite;
    confirmAction(link,msg,function (data) {
        getData();
    })
}

function changeEtatPresence(element, detail_exam) {
    var ele = $(element).val();

    var etat = $('#etat_'+detail_exam).val();
    if(ele == 2) { //absent
        $('#note_matiere_1_' + detail_exam).attr('disabled', 'disabled');
        $('#note_matiere_2_' + detail_exam).attr('disabled', 'disabled');
        $('#note_matiere_3_' + detail_exam).attr('disabled', 'disabled');
    }
    else{
        $('#note_matiere_1_' + detail_exam).removeAttr('disabled');
        $('#note_matiere_2_' + detail_exam).removeAttr('disabled');
        $('#note_matiere_3_' + detail_exam).removeAttr('disabled');

    }

}

function get_specialite_and_matieres() {
    var exam_id = $("#exam_id").val();
    var niveau_formation = $("#niv_formation").val();
    $.ajax({
        type: 'get',
        url: racine + 'examConcours/get_specialite_and_matieres/' + exam_id + "/" + niveau_formation,
        success: function (data) {
            $("#matieres").html(data.matieres);
            $("#specialites").html(data.specialites);
            if(niveau_formation == 'all') {
                $( '#specialites' ).attr('disabled','disabled');
            }
            else
                $( '#specialites' ).removeAttr('disabled');
            $( '#matieres' ).attr('disabled','disabled');
            $("#contenu_notes").html("");

            $('.selectpicker').selectpicker('refresh');

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function get_matieres() {
    var exam_id = $("#exam_id").val();
    var specialites = $("#specialites").val();
    var niv_formation = $("#niv_formation").val();
    $.ajax({
        type: 'get',
        url: racine + 'examens/filtres/get_matieres/' + exam_id + "/" + specialites+"/"+niv_formation,
        success: function (data) {
            $("#matieres").html(data);
            if(specialites == 'all') {
                $( '#matieres' ).attr('disabled','disabled');
            }
            else
                $( '#matieres' ).removeAttr('disabled');
            $("#contenu_notes").html("");

            $('.selectpicker').selectpicker('refresh');

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function getData(){
    var type_correction = $('#correction_filtre').val();
    var exam_id = $('#exam_id').val();
    var matiere_id = $('#matieres').val();
    var specialites = $('#specialites').val();
    var niv_formation = $('#niv_formation').val();
    // togglebutton show hide

    if(matiere_id != 'all') {
        $('#divBtn').toggle();
        // add class to button
        $('#divBtn').addClass('d-flex');
        getTheContent("examens/filtres/getDonnesCalculeNotes/" + exam_id + "/" + type_correction + "/" + matiere_id + "/" + niv_formation+"/"+specialites, "#contenu_notes");
    }

}


function get_specialite() {
    var exam_id = $("#exam_id").val();
    var niveau_formation = $("#niveau_formation_id").val();
    var specialite_id = $("#specialite_id").val();
    $.ajax({
        type: 'get',
        url: racine + 'examConcours/get_specialite/' + exam_id + "/" + niveau_formation,
        success: function (data) {
            console.log(specialite_id);
            // if(specialite_id == 'all') {
            $( '.divBtn' ).hide();
            /*}
            else
                $( '.divBtn' ).show();*/

            $("#specialite_id").html(data);

            $('.selectpicker').selectpicker('refresh');

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function ExportGlobalExcel(form_id){
    var form = $('#'+form_id);
    form.attr('action',racine +'examConcours/exportExcel');
    form.attr('target','_blank');
    form.submit();
}


function ExportGlobalByForm(link,form_id){
    var form = $('#'+form_id);
    form.attr('action',racine+link);
    form.attr('target','_blank');
    form.submit();
}

function saveCommentaire(ele){
    var container = $(ele).attr('eleve_id');

    $.ajax({
        type: 'POST',
        url: racine + 'examens/filtres/saveCommentaire',
        data: {
            'commentaire': $('#commentaire_'+container).val(),
            'eleve_id': container,
            'exam_id': $('#exam_id').val(),
        },
        success: function (data) {
            // get p tag value
        },
        error: function (data) {
            console.log(data);
        }
    })
}

function setAbsentEleve(ele){
    var container = $(ele).attr('eleve_id');
    let loading_content_note = '<div class="d-flex justify-content-center"><span class="spinner-border spinner-border-sm" role="status"></span></div>';
    let route = $(ele).is(':checked') ? 'setAbsentEleve' : 'setPresentEleve';
    let message = $(ele).is(':checked') ? 'Voulez-vous vraiment mettre cet élève absent ?' : 'Voulez-vous vraiment mettre cet élève présent ?';
    $.confirm({
        title: 'Confirmation!',
        content: message,
        buttons: {
            confirm: function () {
                $('#eleve_moy_'+container).html(loading_content_note);
                $.ajax({
                    type: 'POST',
                    url: racine + 'examens/filtres/'+route,
                    data: {
                        'eleve_id': container,
                        'exam_id': $('#exam_id').val(),
                    },
                    success: function (data) {
                        // get p tag value
                        $('#note_matiere_2_'+container).val(0)
                        $('#note_matiere_1_'+container).val(0)
                        $('#eleve_moy_'+container).text(data.note);
                        $('#commentaire_'+container).val(data.commentaire);
                        toastr.success('Note enregistrée avec succès');
                        if (data.absent == 1){
                        $('#note_matiere_2_'+container).prop('disabled', true);
                        $('#note_matiere_1_'+container).prop('disabled', true);
                        }else{
                            $('#note_matiere_2_'+container).prop('disabled', false);
                            $('#note_matiere_1_'+container).prop('disabled', false);
                        }

                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            },
            cancel: function () {
                //close
                $(ele).prop('checked', false);
                $('#note_matiere_2_'+container).prop('disabled', false);
                $('#note_matiere_1_'+container).prop('disabled', false);
            }
        }
    })
}


function  getExamSpecialiteDataView(){
    var exam_id = $('#exam_id').val();
    var niveau_formation_id = $('#niveau_formation_id').val();
    var specialite_id = $('#specialite_id').val();
    var etablissement = $('#an_etablissements').val();
    var url = 'examens/jury/getExamSpecialiteDataView/'+exam_id+'/'+niveau_formation_id+'/'+specialite_id+'/'+etablissement;
    if(niveau_formation_id != 'all' && specialite_id != 'all'){
        getTheContent(url,'#exam_specialite_content');
    }
}


function openSaisieNotes(){
    // set attr disabled to false for a fieldset
    $.confirm({
        title: 'Confirmation!',
        content: 'Voulez-vous vraiment ouvrir la saisie des notes ?',
        buttons: {
            'Ouvrir': function () {
                $('#saisie_notes_field_set').removeAttr('disabled');
                toastr.success('La saisie des notes est ouverte avec succès');
            },
            'fermer': function () {

            }
        }
    })
}

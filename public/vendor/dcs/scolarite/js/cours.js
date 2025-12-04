function resetInitModule() {
}

function get_list_etudiant_non_inscrit() {
    var classe = $('#id_classe').val();
    // alert(courant);
    $("#courant").change(function () {
        if (this.checked) {
            $('.datatable_liste_non_afecte').DataTable().ajax.url(racine + "classes/listeEtudiantNonAffectesDT/" + classe + "/1").load();
        } else {
            $('.datatable_liste_non_afecte').DataTable().ajax.url(racine + "classes/listeEtudiantNonAffectesDT/" + classe + "/0").load();
        }

    });
}

function filter_chapitre_by_matiere() {
    $('#last_chp').val(0);
    $('#groups_actions').html('');
    var classe = $("#classe").val();
    var matiere = $("#matiere").val();
    // jstree_chapitre(classe, matiere);
    $('#jstree_chapitres').jstree("refresh");
}

function filter_chapitre_by_classe() {
    $('#last_chp').val(0);
    $('#groups_actions').html('');
    var classe = $("#classe").val();
    if (classe != '' && classe != 'all') {
        $.ajax({
            type: 'get',
            url: racine + 'chapitres/getMatiereParClasse/' + classe,
            success: function (data) {
                $("#matiere").html(data);
                $("#matiere").selectpicker('refresh');
                filter_chapitre_by_matiere();
                //resetInit();
            },
            error: function (data) {
                alert(racine + 'chapitres/getMatiereParClasse/' + id_model);
            }
        });
    } else if (classe == 'all') {
        $.ajax({
            type: 'get',
            url: racine + 'chapitres/getMatiereParClasse',
            success: function (data) {
                $("#matiere").html(data);
                $("#matiere").selectpicker('refresh');
                filter_chapitre_by_matiere();
                //resetInit();
            },
            error: function (data) {
            }
        });
    }
}

function add_chapitre(element) {
    saveform(element, function (id) {
        $('#jstree_chapitres').jstree("refresh");
        $('#groups_actions').html('');
        $('#add-modal').modal('toggle');
        openObjectModal(id, 'chapitres', '', 'main', '34', 'lg');
    })
}

$(function () {
    jstree_chapitre();
});

//  jstre chapitre
function jstree_chapitre() {
    var CurrentNode;
    var formateur = $('#formateur').val();
    $('#jstree_chapitres')
        .on('changed.jstree', function (e, data) {
            var i, j, r = [], s = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                CurrentNode = data.selected[0];
                console.log(data.selected[0]);
            }
        })
        .jstree({
            'core': {
                "themes": {
                    "icons": false
                },
                "check_callback": true,
                'data': {
                    'url': function (node) {
                        var classe = $("#classe").val();
                        var matiere = $("#matiere").val();
                        var derniere_chapitre_modifies = $('#last_chp').val();
                        if (derniere_chapitre_modifies == 1) {
                            return (formateur) ? racine + 'chapitres/get_chapitres/all/all/1/' + formateur : racine + 'chapitres/get_chapitres/all/all/1/all';
                        } else
                            return (formateur) ? racine + 'chapitres/get_chapitres/' + classe + '/' + matiere + '/0/' + formateur : racine + 'chapitres/get_chapitres/' + classe + '/' + matiere + '/0';
                    },
                    'data': function (node) {
                        return {'id': node.id};
                    },
                    dataType: "json",
                }
            }
        })
        .on("select_node.jstree", function (e, data) {
            var id = data.node.id;
            getTheContent('chapitres/get_action_chapitre/' + id, '#groups_actions');
            getTheContent('chapitres/get_libelle_chapitre/' + id, '#chap');
        });
}

function filtre_classes_by_etablissement() {
    var etablisement_id = $("#etablissement").val();
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: racine + 'classes/get_niveaux_pedagogiques_by_etablissement/' + etablisement_id,
        success: function (data) {
            $("#niveaux_pedagogique").html(data.Niveaux_pedagogiqueshtml);
            $("#niveaux_pedagogique").selectpicker('refresh');
            $("#specialite").html(data.Specialitehtml);
            // $("#specialite").selectpicker('refresh');
            $("#niveaux_formation").html(data.Niveaux_formationhtml);
            // $("#niveaux_formation").selectpicker('refresh');
            filtre_classe();
        },
        error: function (error) {
            alert('erreur');
        }
    });
}

// function filtre_classe() {
//     var niveaux_pedegogique = $("#niveaux_pedagogique").val();
//     var annee_scolaire = $("#annne_scolaire").val();
//     var etablissement = $("#etablissement").val();
//     var niveaux_formation = $("#niveaux_formation").val();
//     var specialite = $("#specialite").val();
//     var annee_etude = $("#annee_etude").val();
//     var link = "classes/get_classe_filtre/" + annee_scolaire + "/" + specialite + "/" + niveaux_pedegogique + "/" + etablissement + "/" + niveaux_formation + "/" + annee_etude;
//     getTheContent(link, "#row_now");
// }


function get_matiere() {
    var matiere_id = $('#classe').val();
    getTheContent('cours/get_options/' + matiere_id, '#matiere_block', function () {
        $("#matier").selectpicker('refresh');
    })
}

function addClasseAndPrintNewclasse(element) {
    saveform(element, function (tab) {
        $('#add-modal').modal('toggle');
        openObjectModal(tab[0], "classes", "", "main", 15);
        getfirstClasseOfEtablissement(tab[1], tab[0]);
    })
}

function getfirstClasseOfEtablissement(etablisement_id, classe_id) {
    $.ajax({
        type: "GET",
        url: racine + 'classes/get_classe_by_etablissement/' + etablisement_id,
        success: function (data) {
            printNewClasse(classe_id, data);
        },
        error: function (error) {
            alert('erreur');
        }
    });
}

function printNewClasse(classe_id, first_classe_id) {
    $.ajax({
        type: "GET",
        url: racine + 'classes/refrech_classe/' + classe_id + '/1',
        success: function (data) {
            if (data === "row")
                $("#row_now").html(data);
            else
                $(data).insertBefore($(first_classe_id));
        },
        error: function (error) {
            alert('erreur');
        }
    });
}

function add_classe_and_refrech_card(element, classe_id) {
    saveform(element, function () {
        setTimeout(function () {
            getTheContent('classes/refrech_classe/' + classe_id, '#classe-' + classe_id);
        }, 1000);
    })
}

function add_classe_and_refrech(element) {
    saveform(element, function (id) {
        $('#add-modal').modal('toggle');
        getTheContent('etablissements/getEtabTab/' + id + "/5", '#tab5');
    })
}

function add_cours_and_refrech(element) {
    saveform(element, function (id) {
        $('#add-modal').modal('toggle');
        getTheContent('classes/getTab/' + id + "/16", '#tab16');
    })
}

function affecterEtuduantToClasse() {
    $('#divAffecter #form-errors').hide();
    var element = $(this);

    $("#divAffecter .form-loading").show();
    var data = $('#divAffecter form').serialize();
    $.ajax({
        type: $('#divAffecter form').attr("method"),
        url: $('#divAffecter form').attr("action"),
        data: data,
        success: function (data) {
            $('#second-modal').modal('toggle');
            //setDataTable('#datatableshow');
            setMainTabs(17);
            $('#datatableshow4').DataTable().ajax.url(racine + 'classes/get_stagiaire_of_classeDT/' + data).load();
            //$('.selectpicker').selectpicker('refresh');
            //$('.message').html(data.msg);
            //$('.message').show();
            //$('.datatableshow2').selectpicker('refresh');
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors.errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#divAffecter #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#divAffecter .form-loading").hide();
            // $(element).removeAttr('disabled');
        }

    });

}

function openFormAddEtudiant(id) {
    $.ajax({
        type: 'get',
        url: racine + 'classes/getListeEtudiant/' + id,
        success: function (data) {
            container = "#" + 'second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable(".datatable_liste_non_afecte");
            // resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function closeContainer(container) {
    $(container).html('');
}

function add_matiere_to_niveaux_pedagogique(element) {
    saveform(element, function (id) {
        $('#datatableshow2').DataTable().ajax.url(racine + "niveauxpedagogiques/get_matiere_niveaux_pedagogique/" + id).load();
        closeContainer("#modeledaddmatiere");
    })
}

function add_formateur_and_refrech(element) {
    // var spiner = '<i class="fas fa-spinner fa-pulse"></i>';
    saveform(element, function (id) {
        $('#instance-modal').modal('hide');
        getTheContent('classes/getTab/' + id + '/16', '#tab2');
        // $('#check-'+id).html(spiner);
        // setTimeout(function () {
        //              $('#check-'+id).html('<i class="fas fa-check-circle"></i>');
        //           }, 3500);
    })
}

function toggle_ligne(classe_id, ligne) {
    var spiner = '<i class="fas fa-spinner fa-pulse"></i>';
    if (ligne)
        $('#check-' + classe_id).removeClass("btn-success");
    else
        $('#check-' + classe_id).removeClass("btn-secondary");
    var url = racine + "classes/toggle_en_ligne/" + classe_id;
    var cheked_toggle = ligne ? '<i class="fas fa-power-off"></i>' : '<i class="fas fa-toggle-off"></i>';
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            if (ligne)
                $('#check-' + classe_id).addClass("btn-secondary");
            else
                $('#check-' + classe_id).addClass("btn-success");
            $('#check-' + classe_id).html(spiner);
            setTimeout(function () {
                $('#check-' + classe_id).html(cheked_toggle);
            }, 3500);
        },
        error: function (error) {
            alert(error.responseJSON);
        }
    });
}

function openFormAddMatiere(id) {
    url = racine + "niveauxpedagogiques/form_add_matiere/" + id;
    $.ajax({
        type: "GET",
        url: url,
        success: function (data) {
            $("#modeledaddmatiere").html(data);
            resetInit();
        },
        error: function (error) {
            alert('erreur');
        }
    });
}

function showBtn(element) {
    $(element).css('visibility', 'visible');
    $(element).css('opacity', '1');
    $(element).css('height', 'auto');
    $(element).css('padding', '10px 16px');
    $(element).css('overflow', 'visible');
    $(element).addClass('margin5');
}

function hideBtn(element) {
    $(element).css('visibility', 'hidden');
    $(element).css('opacity', '0');
    $(element).css('height', '0');
    $(element).css('padding', '0');
    $(element).css('overflow', 'hidden');
    $(element).removeClass('margin5');
}

function openSousChapitreModal() {
    var idchapitre = $('.group-add-ch').val();
    //alert(idchapitre);
    openFormAddInModal('chapitres', idchapitre);
}

function openchapitreEditModal() {
    var idchapitre = $('.group-edit_ch').val();
    openObjectModal(idchapitre, 'chapitres', '', 'main', 34, 'lg');
}

function delete_chapitre(element, text) {
    var idchapitre = $('.group-delete_chapitre').val();
    var link = $(element).attr('defaultlink') + idchapitre;
    // alert(link);
    confirmAction(link, text, function () {
        $('#jstree_chapitres').jstree("refresh");
    });
}

function openFormAddChapitreInModal(chapitre_parent = "all") {
    var matiere = $('#matiere').val();
    var classe = $('#classe').val();
    var url = '';
    if (chapitre_parent == "all")
        var url = racine + 'chapitres/get_modal_add_chapitre/' + classe + '/' + matiere;
    else
        var url = racine + 'chapitres/get_modal_add_chapitre/' + classe + '/' + matiere + '/' + chapitre_parent;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#add-modal .modal-dialog").addClass("modal-lg");
            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function syncClasse(id) {
    let btn = "#sync_btn";
    $(btn).attr('disabled', 'true');
    $.ajax({
        url: "/synchronisations/syncClasse",
        type: "post",
        data: {id},
        success: function (data) {
            console.log(data)
        },
        complete: function () {
            $('#sync_btn').removeAttr('disabled');
        }
    });

}

function syncEtudiant(id) {
    let btn = "#sync_etu";
    $(btn).attr('disabled', 'true');
    //  $(btn+' #sync_icon').hide();
    //  $(btn+' .btn-add .spinner-border').show();
    $.ajax({
        url: "/synchronisations/syncEtudiant",
        type: "post",
        data: {id},
        success: function (data) {
            console.log(data)
            // $('.btn-add .spinner-border').hide();
            // $('.btn-add .main-icon').show();
        },
        complete: function () {
            $('#sync_etu').removeAttr('disabled');
        }
    });

}

function aftersaveEtabUser(element) {
    saveform(element, function (id) {
        getTheContent('etablissements/getEtabTab/' + id + '/6','#tab6')
    })
}

function onChangeEtablissementClasse(element) {
    getTheContent('classes/onChangeEtablissementClasse/' + $(element).val() ,'#specialite_id', function () {
        $("#specialite_id").select2();
        $('#niveaux_pedagogique_id').html('');
        $('#niveaux_pedagogique_id').selectpicker('refresh');
    });
}


function onChangeSpecialiteClasse(element) {
    getTheContent('classes/onChangeSpecialiteClasse/' + $(element).val() ,'#niveaux_pedagogique_id', function () {
        $('#niveaux_pedagogique_id').selectpicker('refresh');
    });
}

function filterNiveauxPedagogiques()
{
    specialite = $("#specialite").val();
    niveau_formation = $("#niveau_formation").val();
    etablissement = $("#etablissement").val();
    etat = $("#etat").val();
    link = racine + 'niveauxpedagogiques/getDT/' + specialite + '/' + niveau_formation + '/' + etablissement + '/' + etat;
    $('#datatableshow').DataTable().ajax.url(link).load();
    $('#datatableshow').attr('link', link);
}

function onMatiereTypeFormateurChange(classe_matiere_id) {
    $('#matiere_formateur_principale').html('');
    $('#type_formateur_principale_spinner').show();
    var type_formateur = $('#formateur_label input[name=type_formateur_principale]:checked').val();
    getTheContent('classes/onMatiereTypeFormateurChange/' + classe_matiere_id + '/' + type_formateur ,'#matiere_formateur_principale', function () {
        $('#matiere_formateur_principale').selectpicker('refresh');
        $('#type_formateur_principale_spinner').hide();
    });
}
function onMatiereTypeFormateurSecondeurChange(classe_matiere_id) {
    $('#matiere_formateur_secondeur').html('');
    $('#type_formateur_secondeur_spinner').show();
    var type_formateur = $('#formateur_label input[name=type_formateur_secondeur]:checked').val();
    getTheContent('classes/onMatiereTypeFormateurSecondeurChange/' + classe_matiere_id + '/' + type_formateur ,'#matiere_formateur_secondeur', function () {
        $('#matiere_formateur_secondeur').selectpicker('refresh');
        $('#type_formateur_secondeur_spinner').hide();
    });
}
function filterMatiereByEtablissement()
{
    var etablissement = $("#filtre-matieres-etablissement").val();
    var niveau_pedagogique = $("#filtre-matieres-niveau-pedagogique").val();
    var programme = $("#filtre-matieres-programme").val();
    var link = racine + 'matieres/getDT/' + etablissement + '/' + niveau_pedagogique+ '?programme=' + programme;
    $('#datatableshow').DataTable().ajax.url(link).load();
    $('#datatableshow').attr('link', link);
}

function transfererEleveVersUneAutreClasse(element) {
    saveform(element, function (id) {
        $('#third-modal').modal('toggle');
        $('.datatableshow3').DataTable().ajax.reload();
    })
}

function onNiveauPedagogiqueNiveauFormationChange() {
    var hideLevels = $('#niveaux_formation option:selected').attr('hideLevels');
    if(hideLevels == 1){
        $('.level-container').hide();
        $('.last-year-container').hide();
    } else {
        $('.level-container').show();
        $('.last-year-container').show();
    }
}

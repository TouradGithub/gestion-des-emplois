function filtre_class() {
    var classe = $("#classe_id").val();
    var examen = $("#examen_id").val();
    //alert('examens/get_matieres_of_class/'+classe+'/'+examen );
    getTheContent(
        "examens/planifications/get_matieres_of_class/" + classe + "/" + examen,
        "#contenu_class"
    );
}

function filtre_class_normale() {
    var classe = $("#classe_id").val();
    var examen = $("#examen_id").val();
    //alert('examens/get_matieres_of_class/'+classe+'/'+examen );
    getTheContent(
        "examens_normale/planifications/get_matieres_of_class/" + classe + "/" + examen,
        "#contenu_class"
    );
}

function voir_candidats_examen(examen_id) {
    openObjectModal(examen_id, "examens/planifications/get_examen_normale_candidats", '#datatableshow', 'main', 1, 'xl');
}

function voir_candidats_examen_normale(examen_id) {
    openObjectModal(examen_id, "examens_normale/planifications/get_examen_normale_candidats", '#datatableshow', 'main', 1, 'xl');
}

function valider_par_ligne_examain(element, cm_id, array, examen_detaille_id) {
    let message = '';
    var badge_danger =
        `<span class="badge badge-danger"> <i class="la la-close"></i>
        </span>
           `;
    var badge_light =
        '<span class="badge badge-light"> <i class="fas fa-spinner"></i></span>';
    var badge_success =
        '<span class="badge badge-success"> <i class="fa fa-check"></i></span><input type="hidden" name="success-' + cm_id + '" />';
    // var sall = $("#salle-" + cm_id).val();

    var date_palnifi = $("#date_palnifi-" + cm_id).val();
    var horaire = $("#horaire-" + cm_id).val();
    var examen = $("#examin_id").val();
    var salle_id = ($("#salle-" + cm_id).val()) ? $("#salle-" + cm_id).val() : 'all';
    const array1 = [null, "", undefined];
    var specialite = $("#specialite_id").val();
    var niveau = $("#niv_formation").val();
    if (
        array1.includes(date_palnifi) ||
        date_palnifi == "" ||
        horaire == "" ||
        array1.includes(horaire)
    ) {
        $(".badge-" + cm_id).html(badge_light);
        setTimeout(() => {
            $(".badge-" + cm_id).html(badge_danger);
        }, 500);
    } else {
        $.ajax({
            type: "GET",
            url:
                racine +
                "examens/planifications/valider_programme_examen/" +
                examen +
                "/" +
                date_palnifi +
                "/" +
                horaire +
                "/" +
                examen_detaille_id +
                "/" +
                salle_id +
                "/" +
                specialite +
                "/" +
                niveau + '/' + cm_id
            ,
            success: function (data) {
                if (data.status == true) {
                    afficher_erreur_apres_validation_formualire(
                        element,
                        array,
                        date_palnifi,
                        horaire,
                        cm_id,
                        salle_id
                    );
                } else {
                    let messageWithDanger = badge_danger +
                        `<p class="text-danger bold"> ${data.message}</p>`;

                    $(".badge-" + cm_id).html(badge_light);
                    setTimeout(() => {
                        $(".badge-" + cm_id).html(messageWithDanger);
                    }, 500);
                }
            },
            error: function () {
            },
        });
    }
}

function valider_par_ligne_examain_normale(element, cm_id, array, examen_detaille_id) {
    var badge_danger =
        '<span class="badge badge-danger"> <i class="la la-close"></i> </span>';
    var badge_light =
        '<span class="badge badge-light"> <i class="fas fa-spinner"></i></span>';
    var badge_success =
        '<span class="badge badge-success"> <i class="fa fa-check"></i></span><input type="hidden" name="success-' + cm_id + '" />';
    // var sall = $("#salle-" + cm_id).val();

    var date_palnifi = $("#date_palnifi-" + cm_id).val();
    var horaire = $("#horaire-" + cm_id).val();
    var examen = $("#examin_id").val();
    var salle_id = ($("#salle-" + cm_id).val()) ? $("#salle-" + cm_id).val() : 'all';
    const array1 = [null, "", undefined];
    var specialite = $("#specialite_id").val();
    var niveau = $("#niv_formation").val();
    if (
        array1.includes(date_palnifi) ||
        date_palnifi == "" ||
        horaire == "" ||
        array1.includes(horaire)
    ) {
        $(".badge-" + cm_id).html(badge_light);
        setTimeout(() => {
            $(".badge-" + cm_id).html(badge_danger);
        }, 500);
    } else {
        $.ajax({
            type: "GET",
            url:
                racine +
                "examens_normale/planifications/valider_programme_examen/" +
                examen +
                "/" +
                date_palnifi +
                "/" +
                horaire +
                "/" +
                examen_detaille_id +
                "/" +
                salle_id,
            success: function (data) {
                if (data == true) {
                    afficher_erreur_apres_validation_formualire(
                        element,
                        array,
                        date_palnifi,
                        horaire,
                        cm_id,
                        salle_id
                    );
                } else {
                    $(".badge-" + cm_id).html(badge_light);
                    setTimeout(() => {
                        $(".badge-" + cm_id).html(badge_danger);
                    }, 500);
                }
            },
            error: function () {
            },
        });
    }
}

function afficher_erreur_apres_validation_formualire(
    element,
    array,
    date_palnifi,
    horaire,
    cm_id,
    salle_id
) {
    var badge_danger =
        '<span class="badge badge-danger"> <i class="la la-close"></i> </span>';
    var badge_light =
        '<span class="badge badge-light"> <i class="fas fa-spinner"></i></span>';
    var badge_success =
        '<span class="badge badge-success"> <i class="fa fa-check"></i></span>';
    var val_formulaire = valider_formulaire(
        element,
        array,
        date_palnifi,
        horaire,
        cm_id,
        salle_id
    );
    if (val_formulaire == true) {
        $(".badge-" + cm_id).html(badge_light);
        setTimeout(() => {
            $(".badge-" + cm_id).html(badge_success);
        }, 500);
    } else {
        $(".badge-" + cm_id).html(badge_light);
        setTimeout(() => {
            $(".badge-" + cm_id).html(badge_danger);
        }, 500);
    }
}

function range(start, end) {
    var list = [];
    for (var i = start; i <= end; i++) {
        list.push(i);
    }
    return list;
    // return Array(end - start + 1).fill().map((_, idx) => start + idx)
}

function valider_formulaire(element, array, date_palnifi, horaire, cm_id, salle_id) {
    // date_palnifi1 = $("#date_palnifi-" + array[j]).val();
    let horaire_serch = String($("#horaire-" + cm_id).val());
    let date = String($("#date_palnifi-" + cm_id).val());
    const array_serch = horaire_serch.split(",");
    for (var j = 0; j < array.length; j++) {
        // faire quelque chose avec `value` (ou `this` qui est `value` )

        if (array[j] != cm_id) {

            var heures = String($("#horaire-" + array[j]).val());
            var salle_serch = $("#salle-" + array[j]).val();
            var date_serch = String($("#date_palnifi-" + array[j]).val());

            if (salle_id == 'all') {

                if (heures != "" && date_serch == date) {
                    const heures_serch = heures.split(",");
                    for (let index = 0; index < array_serch.length; index++) {
                        if (heures_serch.includes(array_serch[index])) return false;
                    }
                }
            } else {
                if (heures != "" && date_serch == date && salle_id == salle_serch) {

                    const heures_serch = heures.split(",");
                    for (let index = 0; index < array_serch.length; index++) {
                        if (heures_serch.includes(array_serch[index])) return false;
                    }
                }
            }

        }
    }

    return true;
}

function get_niv_pedagogiaue() {
    var type = $("#type_examen").val();
    if (type === 2)
        getTheContent(
            "examens/planifications/get_niv_pedagogiaue",
            "#niveau_pedag"
        );
    else $("#niveau_pedag").html("");
}

function valider_examen(id, text, aftersave = null) {
    confirmAction(
        racine + "examens/planifications/change_etat_examen/" + id,
        text,
        function (data) {
            $("#datatableshow").DataTable().ajax.reload();
            if (data.etat == 3) {
                setMainTabs(2)
            }
            if (aftersave != null) aftersave(data);
        }
    )
}

function valider_examen_espace_etab(id, text, aftersave = null) {
    confirmAction(
        racine + "examens/etablissements/change_etat_examen/" + id,
        text,
        function (data) {
            $("#datatableshow").DataTable().ajax.reload();
            if (data.etat == 3) {
                open_planification(data.id)
            }
            if (aftersave != null) aftersave(data);
        }
    )
}

function valider_examen_normale(id, text) {
    confirmAction(
        "/examens_normale/planifications/change_etat_examen/" + id,
        text,
        function (data) {
            $("#datatableshow").DataTable().ajax.reload();
            if (data.etat == 3) {
                open_planification(data.id)
            }
        }
    )
}

function filtre_par_niveau_pedagogique() {
    var niveau_pedagogique = $("#niveau_pedagogique").val();
    if (niveau_pedagogique != "" && niveau_pedagogique != "all") {
        $.ajax({
            type: "get",
            url:
                racine +
                "examens/planifications/get_class_of_niveau_pedagogique/" +
                niveau_pedagogique,
            success: function (data) {
                $("#classe_id").html(data);
                $("#classe_id").select2();
                if (data !== "") filtre_class();
                else $("#contenu_class").html("");
                //resetInit();
            },
            error: function (data) {
            },
        });
    } else if (niveau_pedagogique == "all") {
        $.ajax({
            type: "get",
            url:
                racine +
                "examens/planifications/get_class_of_niveau_pedagogique",
            success: function (data) {
                $("#classe_id").html(data);
                $("#classe_id").select2();
                if (data !== "") filtre_class();
                else $("#contenu_class").html("");
                //resetInit();
            },
            error: function (data) {
            },
        });
    }
}

function filtre_par_niveau_pedagogique_normale() {
    var niveau_pedagogique = $("#niveau_pedagogique").val();
    if (niveau_pedagogique != "" && niveau_pedagogique != "all") {
        $.ajax({
            type: "get",
            url:
                racine +
                "examens_normale/planifications/get_class_of_niveau_pedagogique/" +
                niveau_pedagogique,
            success: function (data) {
                $("#classe_id").html(data);
                $("#classe_id").select2();
                if (data !== "") filtre_class_normale();
                else $("#contenu_class").html("");
                //resetInit();
            },
            error: function (data) {
            },
        });
    } else if (niveau_pedagogique == "all") {
        $.ajax({
            type: "get",
            url:
                racine +
                "examens_normale/planifications/get_class_of_niveau_pedagogique",
            success: function (data) {
                $("#classe_id").html(data);
                $("#classe_id").select2();
                if (data !== "") filtre_class_normale();
                else $("#contenu_class").html("");
                //resetInit();
            },
            error: function (data) {
            },
        });
    }
}

function save_planification(element) {
    saveform(element, function () {
        $(".datatableshow").DataTable().ajax.reload();
        $("#datatableshow").DataTable().ajax.reload();
    });
}

function filtre_exemain() {
    var annee = $("#anne-filter").val();
    var type = $("#type_exemein-filter").val();
    var etat = $("#etat_examen_filtre").val();
    var niveau = $("#niveaux_formation_filtre").val();
    var date_debut = $("#date_debut_filtre").val();
    var date_fin = $("#date_fin_filtre").val();
    $("#datatableshow")
        .DataTable()
        .ajax.url(racine + "examens/planifications/getDT/"
        + annee + "/"
        + etat + "/"
        + date_debut + "/"
        + date_fin + "/"
        + niveau)
        .load();
}

function filtre_exemain_normale() {
    var annee = $("#anne-filter").val();
    var type = $("#type_exemein-filter").val();
    $("#datatableshow")
        .DataTable()
        .ajax.url(racine + "examens_normale/planifications/getDT/" + annee + "/" + type)
        .load();
}


function filtre_exemain_planification_normal(examen) {
    var classe = $("#classe_planification_id_normal").val();
    var matiere = $("#matiere_planification_id_normal").val();
    var salle = $("#salle_planification_id_normal").val();

    $(".datatableshow1")
        .DataTable()
        .ajax.url(
        racine +
        "examens/planifications/getExamenPlanificationNormalDT/" +
        examen +
        "/" +
        classe +
        "/" +
        matiere +
        "/" +
        salle
    )
        .load();

}

function filtre_exemain_planification() {
    var classe = $("#classe_planification_id").val();
    var matiere = $("#matiere_planification_id").val();
    var examen = $("#examen_id").val();
    var salle = $("#salle_planification_id").val();
    var heure = $("#heure_planification").val();
    var date = $("#date_planification").val();
    // alert(heure)
    $(".datatableshow")
        .DataTable()
        .ajax.url(
        racine +
        "examens/planifications/getExamenPlanificationDT/" +
        examen +
        "/" +
        classe +
        "/" +
        matiere +
        "/" +
        salle +
        "/" +
        heure +
        "/" +
        date
    )
        .load();
}

function filtre_exemain_planification_normale() {
    var classe = $("#classe_planification_id").val();
    var matiere = $("#matiere_planification_id").val();
    var examen = $("#examen_id").val();
    var salle = $("#salle_planification_id").val();
    var heure = $("#heure_planification").val();
    var date = $("#date_planification").val();
    // alert(heure)
    $(".datatableshow")
        .DataTable()
        .ajax.url(
        racine +
        "examens_normale/planifications/getExamenPlanificationDT/" +
        examen +
        "/" +
        classe +
        "/" +
        matiere +
        "/" +
        salle +
        "/" +
        heure +
        "/" +
        date
    )
        .load();
}

function filtre_examen_of_concours() {
    var niveaux_formation = $("#niv_form_planification_id").val();
    var specialite = $("#specialite_planification_id").val();
    var examen = $("#examen_id").val();
    $(".datatableshow")
        .DataTable()
        .ajax.url(
        racine +
        "examens/planifications/getExamenPlanificationConcoursDT/" +
        examen +
        "/" +
        niveaux_formation +
        "/" +
        specialite
    )
        .load();
    if ((niveaux_formation != null && niveaux_formation != "all") && (specialite != null && specialite != "all")) {
        $("#valider_planification_matiere_specialiteBtn").removeClass("d-none");
    }
    else {
        $("#valider_planification_matiere_specialiteBtn").addClass("d-none");
    }
}

function palnifier_examen(examen_id) {
    $("#main-modal").modal("hide");
    openObjectModal(
        examen_id,
        "examens/planifications/programmer",
        "#datatableshow",
        "second",
        1,
        "xl"
    );
}

function getNiveauPedagogiqueMatiereListes() {
    var niveau_formation = $("#niv_formation_plan_matiere").val();
    var specialite = $("#specialite_plan_matiere").val();
    var examen = $("#examen_id").val();

    if (niveau_formation !== "all" && specialite !== "all")
        getTheContent(
            "examens/planifications/getNiveauPedagogiqueMatiereListes/" +
            niveau_formation +
            "/" +
            specialite +
            "/" +
            examen,
            "#contenu_matiere"
        );
    else $("#contenu_matiere").html("");
}


function get_matiere_concours() {
    var niveau_formation = $("#niv_formation").val();
    var specialite = $("#specialite_id").val();
    var examen = $("#examen_id").val();

    if (niveau_formation !== "all" && specialite !== "all") {
        getTheContent(
            "examens/planifications/get_matieres_of_specialite/" +
            niveau_formation +
            "/" +
            specialite +
            "/" +
            examen,
            "#contenu_class"
        );
        $("#valider_planification_matiere_specialiteBtn").removeClass("d-none");
    } else {
        $("#contenu_class").html("");
        $("#valider_planification_matiere_specialiteBtn").addClass("d-none");
    }
}

function getSpecialiteByNiveau() {
    var niveau_formation = $("#niv_formation").val();
    if (niveau_formation !== "all") {
        $.ajax({
            url:
                racine +
                "examens/filtres/get_specialites_by_niveau/" +
                niveau_formation,
            success: function (data) {
                $("#specialite_id").html(data);
                $("#specialite_id").select2();
                get_matiere_concours();
            },
        });
    }
}

function exporter_planification_examen_normal(examen_id) {
    var classe = $("#classe_planification_id_normal").val();
    var matiere = $("#matiere_planification_id_normal").val();
    var salle = $("#salle_planification_id_normal").val();
    window.open(
        racine +
        "examens/planifications/exporter_examen_normal/" +
        examen_id +
        '/' + classe +
        '/' + matiere +
        '/' + salle,
        "_blank"
    );
}

function candidats_salles_page(examen_id) {
    window.open(
        racine +
        "examens/etablissements/listeCandidatsAffectesSall/" +
        examen_id,
        "_blank"
    );
}

function updateTextArea() {
    var allVals = [];
    $("#table_cocher :checked").each(function () {
        allVals.push($(this).val());
    });
}

function charger_salle_par_etablissement() {
    var etablissement = $("#etablissement_id").val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/planifications/charger_salle_par_etablissement/" +
            etablissement,
        success: function (data) {
            $("#salle_id").html(data);
            $("#salle_id").select2();
        },
        error: function () {
            $.alert(
                "Une erreur est survenue veuillez réessayer ou actualiser la page!"
            );
        },
    });
}

function open_form_add_cadidat_to_sall(id, lemodule) {
    let stagiare_id = [];
    $("input.candidat_check")
        .filter(":checked")
        .each(function () {
            stagiare_id.push($(this).val());
        });

    if (stagiare_id.length === 0) {
        $.alert({
            title: 'Erreur',
            content: 'Veuillez selectionner au moins un candidat !',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close: function () {
                }
            }
        });
        return;
    }


    $("#button_affecte").hide();
    $("#affecter_candidats_container").show();
}

$(document).on("hidden.bs.modal", function (e) {
    $("#button_affecte").hide();
});

function add_candidat_to_salle(text = "Voulez-vous vraiment affecter ces candidats à cette salle ?") {
    var salle = $("#salle_id").val();

    var stagiare_id = [];
    $("#table_cocher :checked").each(function () {
        stagiare_id.push($(this).val());
    });
    stagiare_id.shift();
    // check if stagiare_id is empty
    if (stagiare_id.length === 0) {
        $.alert({
            title: 'Erreur',
            content: 'Veuillez selectionner au moins un candidat !',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close: function () {
                }
            }
        });
        $('#btn_add_candidat_to_salle').html(" <span class=\"icon text-white\">\n" +
            "            <i class=\"main-icon fa fa-save\"></i>\n" +
            "            <span class=\"spinner-border spinner-border-sm\" style=\"display:none\" role=\"status\"\n" +
            "                  aria-hidden=\"true\"></span>\n" +
            "                <i class=\"answers-well-saved text-white fa fa-check\" style=\"display:none\"\n" +
            "                   aria-hidden=\"true\"></i>\n" +
            "        </span>\n" +
            "        <span class=\"text\">Affecter</span>");
        return;
    }
    // if stagiare_id is empty
    if (salle != 'all') {
        var link = racine +
            "examens/etablissements/add_candidat_to_salle/" +
            stagiare_id +
            "/" +
            salle;
        confirmActionAffectation(link,
            text,
            function () {
                $('#btn_add_candidat_to_salle').html('<i class="fa fa-spinner fa-spin"></i>')
            },
            function (data) {
                $("#button_affecte").hide();
                $("#datatableshow").DataTable().ajax.reload();
                $('#btn_add_candidat_to_salle').html(" <span class=\"icon text-white\">\n" +
                    "            <i class=\"main-icon fa fa-save\"></i>\n" +
                    "            <span class=\"spinner-border spinner-border-sm\" style=\"display:none\" role=\"status\"\n" +
                    "                  aria-hidden=\"true\"></span>\n" +
                    "                <i class=\"answers-well-saved text-white fa fa-check\" style=\"display:none\"\n" +
                    "                   aria-hidden=\"true\"></i>\n" +
                    "        </span>\n" +
                    "        <span class=\"text\">Affecter</span>");
                $('#checkall').prop('checked', false);
            }, function () {
                $('#btn_add_candidat_to_salle').html(" <span class=\"icon text-white\">\n" +
                    "            <i class=\"main-icon fa fa-save\"></i>\n" +
                    "            <span class=\"spinner-border spinner-border-sm\" style=\"display:none\" role=\"status\"\n" +
                    "                  aria-hidden=\"true\"></span>\n" +
                    "                <i class=\"answers-well-saved text-white fa fa-check\" style=\"display:none\"\n" +
                    "                   aria-hidden=\"true\"></i>\n" +
                    "        </span>\n" +
                    "        <span class=\"text\">Affecter</span>");
                $('#checkall').prop('checked', false);
            })
    } else {
        $.alert({
            title: 'Erreur',
            content: 'Veuillez selectionner une salle !',
            type: 'red',
            typeAnimated: true,
            buttons: {
                close: function () {
                }
            }
        });
    }
    ;


}

function confirmActionAffectation(link, text, load, aftersave, aftersave_error = null) {
    $.confirm({
        title: 'Confirmation',
        content: text,
        buttons: {
            confirm: function () {
                if (save_action) {
                    toastr.error(under_dev, error, {"progressBar": true});
                    return false;
                }
                load()
                $.ajax({
                    type: 'GET',
                    url: link,
                    success: function (data) {
                        if (data.success === "true") {
                            $.dialog(data.msg, 'Confirmation');
                            if (aftersave) {
                                aftersave(data);
                            }
                        } else
                            $.dialog(data.msg, 'Erreur');
                        setTimeout(function () {
                            if (aftersave_error) {
                                aftersave_error();
                            }
                            $("#main-modal").modal("hide");
                        }, 1000);
                    },
                    error: function () {
                        $.dialog(msg_erreur);
                        setTimeout(function () {
                            $("#main-modal").modal("hide");
                        }, 1000);

                    }
                });
            },
            close: function () {
            }
        }
    });
}

function filtre_candidat_sall(after_func = null) {
    var matiere = $("#mat_candidat").val();
    var specialite = $("#speci_cadidat").val();
    var niveau_formation = $("#niv_form_candidat").val();
    var examen = $("#candidat_examen").val();
    var etablissement = $("#etablissement").val();
    var salle_candidat = $("#salle_candidat").val();


    var palnifier = 1;
    // check if planifier is checked

    if ($("#planifier").is(":checked")) {
        palnifier = 2;
        $("#checkall").prop("checked", false);
    }
    $("#checkall").prop("checked", false);


    $("#datatableshow")
        .DataTable()
        .ajax.url(
        racine +
        "examens/etablissements/listeCandidatsAffectesSallDT/" +
        examen +
        "/" +
        etablissement +
        "/" +
        niveau_formation +
        "/" +
        specialite +
        "/" +
        matiere +
        "/" +
        palnifier
        + "/" +
        salle_candidat
    )
        .load();

    if (after_func) {
        after_func()
    }
}


function toggleInputs(etab, niv, spec) {
    var specialite = $(spec).val();
    var niveau_formation = $(niv).val();
    var etablissement = $(etab).val();

    if (etablissement == 'all') {
        $(niv).attr('disabled', 'disabled');
    } else {
        $(niv).removeAttr('disabled');
    }

    if (niveau_formation == 'all') {
        $(spec).attr('disabled', 'disabled');
    } else {
        $(spec).removeAttr('disabled');
    }


}

function getMatireBySpecialite() {
    var specialite = $("#speci_cadidat").val();
    var niveau_formation = $("#niv_form_candidat").val();
    var examen = $("#candidat_examen").val();
    var planifier = $('#planifie_matiere_if').val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/filtres/getMatireBySpecialite/"
            +
            niveau_formation
            + '/' +
            examen
            + '/'
            + specialite + '/' + planifier
        ,
        success: function (data) {
            $("#mat_candidat").html(data)
        },
        error: function () {
        },
    });
}

function getSpecialiteByNiveauFormations() {
    var niveau_formation = $("#niv_form_candidat").val();
    var examen = $("#candidat_examen").val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/planifications/getSpecialiteByNiveauFormations/"
            +
            niveau_formation
            + '/' +
            examen
        ,
        success: function (data) {
            $("#speci_cadidat").html(data)
        },
        error: function () {
        },
    });
}

function getSpecialiteByNiveauFormationsFiltre(specialite, niveau_formation, examen) {
    var niveau_formation = $(niveau_formation).val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/filtres/getSpecialiteByNiveauFormationsFiltre/"
            +
            niveau_formation
            + '/' +
            examen
        ,
        success: function (data) {
            $(specialite).html(data)
        },
        error: function () {
        },
    });
}

function getSpecialiteByNiveauFormationsWithoutExam(specialite, niveau_formation, etablissement) {
    var niveau_formation = $(niveau_formation).val();
    var etablissement = $(etablissement).val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/filtres/getSpecialiteByNiveauFormationsWithoutExam/"
            +
            niveau_formation
            + '/' +
            etablissement
        ,
        success: function (data) {
            $(specialite).html(data)
        },
        error: function () {
        },
    });
}

function getSpecialiteByNiveauFormationsAndEtablissement(examen, etablissement, niveau_formation, specialite) {
    var niveau_formation = $(niveau_formation).val();
    var etablissement = $(etablissement).val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/filtres/getSpecialiteByNiveauFormationsAndEtablissement/"
            +
            etablissement + '/'
            +
            niveau_formation
            + '/' +
            examen
        ,
        success: function (data) {
            $(specialite).html(data)
        },
        error: function () {
        },
    });
}

function getNiveauFormationsByEtablissement(examen = null, etablissement, niveau_formation) {
    var etablissement_val = $(etablissement).val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/planifications/getNiveauFormationsByEtablissement/"
            +
            etablissement_val + '/'
            +
            examen
        ,
        success: function (data) {
            $(niveau_formation).html(data)
        },
        error: function () {
        },
    });
}


function getNiveauFormationsByEtablissementWithout(etablissement, niveau_formation) {
    var etablissement_val = $(etablissement).val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/filtres/getNiveauFormationsByEtablissementWithout/"
            +
            etablissement_val
        ,
        success: function (data) {
            $(niveau_formation).html(data)
        },
        error: function () {
        },
    });
}

/*
function getSpecialiteByNiveauFormations(){
    var niveau_formation = $("#niv_form_candidat").val();
    var examen = $("#candidat_examen").val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/planifications/getSpecialiteByNiveauFormations/"
            +
            niveau_formation
            +'/' +
            examen
            ,
        success: function (data) {
          $("#speci_cadidat").html(data)
        },
        error: function () {
        },
    });
}
*/

function export_salle_candidats(salle_id) {
    // alert ($("#date_debut_").val())
    var exam_id = $("#exam_id").val();
    var niv_formation = $("#niv_formation").val();
    var specilaite = $("#speci_cadidat").val();
    var horaire = ($("#horaire").val() != '') ? $("#horaire").val() : 'all';
    var date = ($("#date_debut_").val() != '') ? $("#date_debut_").val() : 'all';
    var etablissement = $("#etablissement").val();
    var matiere = $("#mat_candidat").val();
    window.open(
        racine + "examens/etablissements/export_salle_candidats/" + exam_id + '/' + salle_id + '/' + niv_formation + '/' + specilaite + '/' + horaire + '/' + date + '/' + matiere,
        "_blank"
    );
}

function get_page_of_sall(examen) {
    window.open(
        racine + "examens/etablissements/salle_page/" + examen,
        "_blank"
    );
}

function get_info_detaille_salle(exam_examen_detail_id) {
    var etablissemet = $("#etablissement").val();
    var date_debut = $("#date_debut").val() ? $("#date_debut").val() : "all";
    var heure_deb = $("#heure_deb").val() ? $("#heure_deb").val() : "all";
    var heure_fin = $("#heure_fin").val() ? $("#heure_fin").val() : "all";
    var exam_id = $("#exam_id").val();
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/etablissements/getSalleInfo/" +
            exam_examen_detail_id,
        success: function (data) {
            container = "#main-modal";
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            resetInit()
        },
        error: function () {
        },
    });
}

function if_all_candidats_in_salls(examen_id, text1, text2) {
    $.ajax({
        type: "get",
        url:
            racine +
            "examens/etablissements/if_all_candidats_in_salls/" +
            examen_id,
        success: function (data) {
            if (data == 0) {
                confirmAction(
                    racine + "examens/etablissements/valider_planification_salle/" + examen_id,
                    text1,
                    function (data) {
                        setTimeout(function () {
                            getContentOfNavsItemsEtablissement(this, 'organisation_candidats', examen_id, '#contenu_data', 2)
                        }, 500);
                    });
            } else {
                // show msg in confirm alert
                $.confirm({
                    title: "Attention",
                    content: text2,
                    buttons: {
                        cancel: {
                            text: "Annuler",
                        }
                    }
                })
            }
        },
        error: function () {
            alert("herreur");
        },
    });
}


function exporter_planification_matieres(examen_id, type_examen) {
    if (type_examen == 2) {
        var niveau_formation = $("#niv_form_planification_id").val();
        var specialite = $("#specialite_planification_id").val();
        window.open(
            racine +
            "examens/planifications/exporter_planification_matieres/" +
            examen_id +
            "/" +
            niveau_formation +
            "/" +
            specialite,
            "_blank"
        );
    } else {
        var classe = $("#classe_planification_id").val();
        var matiere = $("#matiere_planification_id").val();
        var salle = $("#salle_planification_id").val();
        var heure = $("#heure_planification").val();
        var date = $("#date_planification").val();
        window.open(
            racine +
            "examens/planifications/exporter_planification_matieres_normal/" +
            examen_id +
            "/" +
            classe +
            "/" +
            matiere +
            "/" +
            salle +
            "/"
            + heure +
            "/"
            + date,
            "_blank"
        );
    }
}

function exporter_planification_matieres_normale(examen_id, type_examen) {
    var classe = $("#classe_planification_id").val();
    var matiere = $("#matiere_planification_id").val();
    var salle = $("#salle_planification_id").val();
    var heure = $("#heure_planification").val();
    var date = $("#date_planification").val();
    window.open(
        racine +
        "examens_normale/planifications/exporter_planification_matieres_normal/" +
        examen_id +
        "/" +
        classe +
        "/" +
        matiere +
        "/" +
        salle +
        "/"
        + heure +
        "/"
        + date,
        "_blank"
    );

}

function checkAll(element) {
    if ($(element).is(":checked")) {
        $("#button_affecte").show();
        $("input.candidat_check").prop("checked", true);
    } else {
        $("#button_affecte").hide();
        $("input.candidat_check").prop("checked", false);
    }
}

function checkAllPlanificationEtablissment(element) {
    if ($(element).is(":checked")) {
        // if #affecter_candidats_container is hidden then show button affecter
        if ($("#affecter_candidats_container").is(":hidden")) {
            $("#button_affecte").show();
        } else {
            $("#button_affecte").hide();
        }
        $("input.candidat_check").prop("checked", true);
    } else {
        if ($("#affecter_candidats_container").is(":hidden")) {
            $("#button_affecte").show();
        } else {
            $("#button_affecte").hide();
        }
        $("input.candidat_check").prop("checked", false);
    }
}

function checkAllCandidats(element) {
    if ($(element).is(":checked")) {
        if ($("#affecter_candidats_container").is(":hidden")) {
            $("#button_affecte").show();
        } else {
            $("#button_affecte").hide();
        }
    } else {
        var str = 0;

        $(":checkbox").each(function () {
            str += this.checked ? 1 : 0;
        });
        if (str == 0) {
            if ($("#affecter_candidats_container").is(":hidden")) {
                $("#button_affecte").show();
            } else {
                $("#button_affecte").hide();
            }
        } else {
            $("#button_affecte").show();
        }
        ;
    }
}

function filtre_sall(callback) {
    var etablissement = $("#etablissement").val();
    var niv_formation = $("#niv_formation").val();
    var specilaite = $("#specilaite").val();
    var examen = $("#candidat_examen").val();
    var date_debut = ($("#date_debut_").val() != '') ? $("#date_debut_").val() : 'all';
    var horaire = ($("#horaire").val() != '') ? $("#horaire").val() : 'all';

    // $("#datatableshow")
    //     .DataTable()
    //     .ajax.url(
    //     racine +
    //     "examens/etablissements/listesSallesDT/" +
    //     examen +
    //     "/" +
    //     etablissement +
    //     "/" +
    //     niv_formation +
    //     "/" +
    //     specilaite +
    //     "/" +
    //     horaire +
    //     "/" +
    //     date_debut
    // )
    //     .load();

    if (callback) {
        callback();
    }
}

function changer_date_par_defaut(niv_mati_ids_array) {
    date_defaut = $('#date_defaut').val();
    for (var i = 0; i < niv_mati_ids_array.length; i++) {
        $('#date_palnifi-' + niv_mati_ids_array[i]).val(date_defaut);
    }
}

function export_listes_candidats(exam_detail_id) {
    window.open(
        racine + "examens/planifications/exporter_planification_candidats/" + exam_detail_id,
        "_blank"
    );
}

function filtre_par_niveau_formation_normale(ele, target) {
    var niv_formation = $(ele).val()
    $.ajax({
        type: "get",
        url: racine + "examens_normale/planifications/get_niveau_pedagogique_by_niveau_formation/" + niv_formation,
        success: function (data) {
            $(target).html(data);
            filtre_par_niveau_pedagogique_normale();
            setTimeout(function () {
                filtre_class_normale();
            }, 1000);
        }
    });
}


function open_planification(id) {
    $("#second-modal").hide();
    openObjectModal(id, "examens/planifications/programmer", "#datatableshow", "second", 1, 'xl')
}

function open_planification_normale(id) {
    $("#second-modal").hide();
    openObjectModal(id, "examens_normale/planifications/programmer", "#datatableshow", "second", 1, 'xl')
}

function ValidateInputNum(ele) {
    var max = parseInt($(ele).attr('max'));
    var min = parseInt($(ele).attr('min'));
    if ($(ele).val() >= max) {
        $(ele).val(max);
    } else if ($(ele).val() <= min) {
        $(ele).val(min);
    }
}

function sendFormData(link, containerId) {
    var form = document.getElementById(containerId);
    var inputs = form.querySelectorAll('input');

    var formData = new FormData();
    for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];
        if (input.type !== 'submit' && input.type !== 'button') {
            formData.append(input.name, input.value);
        }
    }

    var urlencoded = new URLSearchParams(formData).toString();
    var fullLink = link + '?' + urlencoded;

    window.open(fullLink, '_blank');
}


function getContentOfNavsItems(ele, viewName, exam, container = '#content_examen_detail', get_items = 1) {
    getTheContent('examens/planifications/getView/' + viewName + '/' + exam + '/' + get_items, container)


}

function getMatierePlanifie(exam, container = '#content_examen_detail') {
    // ajax call
    $.ajax({
        type: "get",
        url: racine + "examens/planifications/getMatierePlanifie/" + exam,
        success: function (data) {
            $(container).html(data);
        }
    })
}

function getSpecialiteByNiveauMatiere() {
    var niveau_formation = $("#niv_formation_plan_matiere").val();
    var examen = $("#examen_id").val();
    if (niveau_formation !== "all") {
        $.ajax({
            url:
                racine +
                "examens/filtres/get_specialites_by_niveau/" +
                niveau_formation +
                "/" + examen
            ,
            success: function (data) {
                $("#specialite_plan_matiere").html(data);
                $("#specialite_plan_matiere").select2();
            },
        });
    }
}

function getSpecialiteExamByNiveauFormation() {
    var niveau_formation = $("#niv_formation_plan_matiere").val();
    var examen = $("#exam").val();
    if (niveau_formation !== "all") {
        $.ajax({
            url:
                racine +
                "examens/planifications/getSpecialiteExamByNiveauFormation/" +
                niveau_formation
                + "/" + examen
            ,
            success: function (data) {
                $("#specialite_plan_matiere").html(data);
                $("#specialite_plan_matiere").select2();
            },
        });
    }
}


function submitValueExamSpecialite(ele, exam_specialite_id) {
    var moy_min_deliberation = $('#moy_min_deliberation' + exam_specialite_id).val();
    var deliberation_plafond_max_matiere = $('#deliberation_plafond_max_matiere' + exam_specialite_id).val();
    var deliberation_nb_notes_modifiable = $('#deliberation_nb_notes_modifiable' + exam_specialite_id).val();
    var plafond_matiere = $('#plafond_matiere' + exam_specialite_id).val();
    $.ajax({
        type: "post",
        url: racine + "examens/planifications/updateExamSpecialite",
        data: {
            exam_specialite_id: exam_specialite_id,
            moy_min_deliberation: moy_min_deliberation,
            deliberation_plafond_max_matiere: deliberation_plafond_max_matiere,
            deliberation_nb_notes_modifiable: deliberation_nb_notes_modifiable,
            plafond_matiere: plafond_matiere,
        },
        success: function (data) {
            if (data == 1) {
                toastr.success('Modification effectuée avec succès');
            }
        }
    });
}

function updateSurveillants(examDetailExamenId) {
    let surveillants = $('#surveillants_id_' + examDetailExamenId).val().join();
    let salle = $('#salle_id_' + examDetailExamenId).val();
    $.ajax({
        url: racine + 'examens/etablissements/updateSurveillants',
        type: 'POST',
        data: {
            examDetailExamenId: examDetailExamenId,
            surveillants: surveillants,
            salle: salle,
        },
        success: function (data) {
            if (data.status == 'true') {
                toastr.success(data.msg);
            } else {
                toastr.error(data.msg);
            }
        },
        error: function (err) {
            // alert error with msg in data

        }
    });
}

function updateSalleMutiple(examDetailExamenId, salles, func) {
    $.ajax({
        url: racine + 'examens/etablissements/updateSalleMutiple',
        type: 'POST',
        data: {
            examDetailExamenId: examDetailExamenId,
            salle: salles,
        },
        success: function (data) {
            if (data.status == 'true') {
                toastr.success(data.msg);
            } else {
                toastr.error(data.msg);
            }
            if (func) {
                func();
            }
        },
        error: function (err) {
            // alert error with msg in data

        }
    });
}

function getContentOfNavsItemsEtablissement(ele, viewName, exam, container = '#content_examen_detail', get_items = 1) {
    getTheContent('examens/etablissements/getView/' + viewName + '/' + exam + '/' + get_items, container)

}

function export_salle() {
    var salle = $('#salle').val();
    // check if salle is empty
    if (salle == 'all') {
        alert('Veuillez choisir une salle');
        return;
    }
    export_salle_candidats(salle)
}

function getSalleByEtablissement(exam, etab, salle) {
    var etablissement = $(etab).val();
    $.ajax({
        url: racine + "examens/planifications/getSalleByEtablissement/" + exam + "/" + etablissement,
        success: function (data) {
            $(salle).html(data);
        }
    })
}

function addNewCand() {
    var nni = $("#nni_verif").val();
    $('#candLibreGroup').hide();
    $('#nni_verify_form').addClass('d-none');
    $('#candLibreCnt').removeClass('d-none');
    $('#nni').val(nni);
    $('#nni').attr('readonly', true);
}

function generateCandidatesGlobal(element) {
    $.confirm({
        title: "@lang('examen::examEtab.confirm') ",
        content: "<h5>@lang('examen::examEtab.confirm_msg'): <strong>{{$exam->libelle}}</strong><h5>",
        buttons: {
            confirm: function () {
                var el = $(element);
                el.attr('disabled', 'disabled');
                el.find('.main-icon').hide();
                el.find('.spinner-border').show();
                let url = "{{url('examens/etablissements/generateCandidates',$exam->id)}}";
                $.ajax({
                    url,
                    success: function (data) {
                        console.log(data);
                        getTheContent('examens/etablissements/getCandidatesList/{{$exam->id}}', "#listCnts");
                        $('#countTextt').removeClass('d-none');
                        $('#countTextt').text(data + " @lang('examen::examEtab.candidate_generated')");
                    },
                    error: function () {
                        alert('@lang("message_erreur.chargement")');
                    },
                    complete: function () {
                        el.removeAttr('disabled');
                        el.find('.main-icon').show();
                        el.find('.spinner-border').hide();
                    }
                });
            },
            cancel: {
                text: "@lang('examen::examEtab.cancel') ",
                action: function () {
                }
            },
        }
    });
    setTimeout(function () {
        $('#countTextt').addClass('d-none');
    }, 8000);

}

function control() {
    console.log('the information is about to finish')
}

function deleteObject1(link, text, datatableshow = "#datatableshow") {

    confirmAction(link, text, function (data) {
        alert(data);
        getTheContent("quartiers/divises/getTab/" + data + "/2", "#tab2");
    });
}

function ajouter_quartier_au_tel() {
    var data = $('#formst').serialize();

    $.ajax({
        type: $('#formst').attr("method"),
        url: $('#formst').attr("action"),
        data: data,
        dataType: 'json',
        success: function (data) {
            getTheContent("quartiers/divises/getTab/" + data + "/2", "#tab2");

        },
        error: function (data) {

        }
    });
}

function get_quartiers_of_localite() {
    var localite = $('#local').val();
    var devise = $('#id_devise').val();
    $.ajax({
        url: racine + "quartiers/divises/get_quartiers_of_localite/" + localite + "/" + devise,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $("#quart").html(response);
            $("#quart").selectpicker('refresh')
        }
    });
}

function saveformAndRefreshDT(element, datatable) {
    saveform(element, function (data) {
        $(datatable).DataTable().ajax.reload();
    });
}

function get_fich_logement(is_excel = false) {
    localite = $('#localite-filter').val();
    quartier = $('#quartier-filter').val();
    var date_debut = $("#date_debut").val();
    var date_fin = $("#date_fin").val();
    var type_logement = $("#type_logement").val();
    var eau = $("#eau_filter").val();
    var electricite = $("#electricite_filter").val();
    if (date_debut == ''||date_fin == '') {
        date_debut = 'all';
        date_fin = 'all';
    }
    var url_prefix = is_excel ? 'exportListeLogementsExcel/' : 'logementsPdfWithoutDetails/';
    var link = racine + 'quartiers/logements/'+ url_prefix + localite + '/' + quartier + '/' + date_debut + '/' + date_fin + '/' + type_logement + '/' + eau + '/' + electricite;

    window.open(link, '_blank');
}

function get_fich_famille() {
    localite = $('#localite-filter').val();
    quartier = $('#quartier-filter').val();
    logement = $('#logement-filter').val();
    window.open(racine + "quartiers/familles/exportFamillesPdfWithDetails/" + localite + '/' + quartier + '/' + logement, '_blank');

}

function get_fich_indivudus() {
    localite = $('#localite-filter').val();
    quartier = $('#quartier-filter').val();
    logement = $('#logement-filter').val();
    famille = $('#famille-filter').val();
    let situation_sante = $("#situation_sante-filter").val();
    let situation_familiale = $("#situation_familiale-filter").val();
    window.open(racine + "quartiers/individus/indivudusPdfWithDetails/" + localite + '/' + quartier + '/' + logement + '/' + famille + '/' + situation_sante + '/' + situation_familiale, '_blank');
}

function openFormIndividuInModal(lemodule) {
    let url = racine + lemodule + '/addIndividu/';
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#add-modal .modal-dialog").addClass("modal-lg");

            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function changeChef(element, link, text) {
    if (!confirmAction(link, text)) {
        element.checked = false;
    }
}

function changeResponsableFamille(element, link, text) {
    console.log(link)
    if (!confirmAction(link, text)) {
        element.checked = false;
    }
}

function getFilterLogements(datatable, link) {
    let localite = $('#localite-filter').val();
    let quartier = $('#quartier-filter').val();
    let date_deb = $('#date_debut').val();
    let date_fin = $('#date_fin').val();
    let type_logement = $('#type_logement').val();
    let eau_filter = $('#eau_filter').val();
    let electricite_filter = $('#electricite_filter').val();
    $("#imprimerListeLogementsExcel").attr('href', racine + 'quartiers/exportListeQuanrtiersExcel' + '/' + localite + '/' + quartier)
    //$("#exportLogementBtn").attr('href', racine +  'quartiers/logements/logementsPdfWithoutDetails' + '/' + localite + '/' + quartier );
    $(datatable).DataTable().ajax.url(racine + link + '/' + localite + '/' + quartier + '/' + date_deb + '/' + date_fin + '/' + type_logement + '/' + eau_filter + '/' + electricite_filter + "/all/2").load();
    //$(datatable).DataTable().ajax.url(`${racine}${link}/${localite}/${quartier}/${date_deb}/${date_fin}/${type_logement}/all/2`).load();
}

function getFilterFamilles(datatable, link) {
    let localite = $('#localite-filter').val();
    let quartier = $('#quartier-filter').val();
    let logement = $('#logement-filter').val();

    console.log(`${racine}${link}/${localite}/${quartier}/${logement}`)
    $("#exportLogementBtn").attr('href', racine + 'quartiers/familles/famillesPdfWithoutDetails' + '/' + localite + '/' + quartier + '/' + logement)
    $(datatable).DataTable().ajax.url(`${racine}${link}/${localite}/${quartier}/${logement}/all/2`).load();
}

function getFilterIndividus(datatable, link) {
    let localite = $('#localite-filter').val();
    let quartier = $('#quartier-filter').val();
    let logement = $('#logement-filter').val();
    let famille = $('#famille-filter').val();
    let situation_sante = $('#situation_sante-filter').val();
    let situation_familiale = $('#ituation_familiale-filter').val();
    console.log(`${racine}${link}/${localite}/${quartier}/${logement}/${famille}/${situation_sante}/${situation_familiale}`)
    $(datatable).DataTable().ajax.url(`${racine}${link}/${localite}/${quartier}/${logement}/${famille}/${situation_sante}/${situation_familiale}/all/2`).load();
}


function singleFilter(datatable, link, filterElement) {
    let filter = $(filterElement).val();
    $(datatable).DataTable().ajax.url(racine + link + '/' + filter).load();
}

function getLogementFilterByLocalite(datatable, link, filterElement, link2) {

    let localite = $('#localite-filter').val()
    $.ajax({
        url: `${link2}/${localite}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh')
            getFilterLogements(datatable, link)
        }
    });
}

function getLogementFilterQuartier(datatable, link, filterElement, link2) {

    let localite = $('#localite-filter').val()
    let quartier = $('#quartier-filter').val()
    $("#exportLogementBtn").attr('href', racine + 'quartiers/logements/pdfWithoutDetails' + '/' + localite + '/' + quartier)
    $.ajax({
        url: `${link2}/${localite}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterLogements(datatable, link);
        }
    });
}

function getFamillesByLocalite(datatable, link, filterElement, link2) {
    let ref_localite_id = $('#localite-filter').val()
    $.ajax({
        url: `${link2}/${ref_localite_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterLogements(datatable, link);
        }
    });
}

function getFamillesByQuartier(datatable, link, filterElement, link2) {

    let quartier_id = $('#quartier-filter').val()
    $.ajax({
        url: `${link2}/${quartier_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterFamilles(datatable, link);
        }
    });
}

function getFamillesByLogement(datatable, link, filterElement, link2) {
    let logement_id = $('#logement-filter').val()
    $.ajax({
        url: `${link2}/${logement_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterFamilles(datatable, link);
        }
    });
}

function getIndividusByLocalite(datatable, link, filterElement, link2) {
    let ref_localite_id = $('#localite-filter').val()
    $.ajax({
        url: `${link2}/${ref_localite_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterFamilles(datatable, link)
        }
    });
}

function getIndividusByQuartier(datatable, link, filterElement, link2) {

    let quartier_id = $('#quartier-filter').val()
    $.ajax({
        url: `${link2}/${quartier_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterIndividus(datatable, link);
        }
    });
}

function getIndividusByLogement(datatable, link, filterElement, link2) {

    let logement_id = $('#logement-filter').val()
    $.ajax({
        url: `${link2}/${logement_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filterElement).html(response);
            $(filterElement).selectpicker('refresh');
            getFilterIndividus(datatable, link);
        }
    });
}

function getFilterLocalite(filter, link, selectOption) {
    let ref_localite_id = $('#localite').val();

    $.ajax({
        url: `${link}/${ref_localite_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            $(selectOption).html(response);
        }
    });
}

function getFilterQuartier(filter, link, selectOption) {
    let quartier_id = $('#quartier').val()
    $.ajax({
        url: `${link}/${quartier_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            // console.log(response)
            $(filter).show()
            $(selectOption).html(response);
        }
    });
}

function getFilterLogement(filter, link, selectOption) {
    let logement_id = $('#logement').val()
    $.ajax({
        url: `${link}/${logement_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            console.log(response)
            $(filter).show()
            $(selectOption).html(response);
        }
    });
}

function getFilterFamille(filter, link, selectOption) {
    let famille_id = $('#famille').val()
    $.ajax({
        url: `${link}/${famille_id}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            console.log(response)
            $(filter).show()
            $(selectOption).html(response);
        }
    });
}

function typeFamille(typeFamille) {
    if ($(typeFamille).val() === "1") {
        $('#nom_famille').show()
    } else {
        $('#nom_famille').hide()
    }
}

function onchangeSexe(link, selectOption) {

    //alert(1);
    let order = $('#sexe').val();
    if (order) {
        $(selectOption).prop('disabled', false);
    }
    $.ajax({
        url: `${link}/${order}`,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            console.log(response)
            if (response.length > 0) {
                $(selectOption).html(response);
                $(selectOption).selectpicker('refresh');
            } else {
                $(selectOption).html(`<option>No logement selected</option>`);
            }
        }
    });
}

function openIndividuFormAddInModal(lemodule, id = false) {
    if (id != false)
        url = racine + lemodule + '/addIndividu/' + id;
    else
        url = racine + lemodule + '/addIndividu/';

    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            //modified by Medyahya
            $("#add-modal .modal-dialog").addClass("modal-lg");

            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function previewImage(element, imagePlaceholder) {
    const file = element.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = function (event) {
            $(imagePlaceholder)
                .attr("src", event.target.result);
        };
        reader.readAsDataURL(file);
    }
    $(imagePlaceholder).show()
    $('.upload-btn-wrapper').hide()
}

function filterQuartierByLocalite(datatable, link, filterElement, exportBtn) {
    singleFilter(datatable, link, filterElement)
    // console.log(exportBtn)
    $("#imprimerListeQuartiersExcel").attr('href', racine + 'quartiers/exportListeQuanrtiersExcel' + '/' + $(filterElement).val())
    $(exportBtn).attr('href', racine + 'quartiers/exportListeQuanrtierspdf' + '/' + $(filterElement).val())
}


function showDetails(element, detailShow) {
    if (element.checked) {
        $(detailShow).removeClass('d-none')
    } else {
        $(detailShow).find('input').each(function (i, input) {
            input.checked = false
        })
        $(detailShow).addClass('d-none')
    }

}

function openDetailModal(id) {
    $('#exportModal').modal('show')
    $('#exportModal').on('shown.bs.modal', function (event) {
        // console.log($('#exportQuartierBtn'))
        $('#exportQuartierBtn').attr('href', 'test')
    })

}


function resetInitModule() {
    if ($('#datatableshow_quartier').length) setDataTable('#datatableshow_quartier');
    if ($('.datatableshow_quartier').length) setDataTable('.datatableshow_quartier');

    // Datatables des onglets
    for (let i = 1; i < 10; i++) {
        if ($('#datatableshow_quartier' + i).length) setDataTable('#datatableshow_quartier' + i);
        if ($('.datatableshow_quartier' + i).length) setDataTable('.datatableshow_quartier' + i);
    }
}

// function fillter created by mamouny
function filtrerDataTablesFamilles() {
    var localite = $("#localite-filter").val();
    var quartier = $("#quartier-filter").val();
    var logement = $("#logement-filter").val();

    var link = racine + 'quartiers/familles/getDT/' + localite + '/' + quartier + '/' + logement + '/all/2';
    $("#imprimerListeFamillesExcel").attr('href', racine + 'quartiers/familles/exportListeFamillesExcel/' + localite + '/' + quartier + '/' + logement);
    $('.datatableshow5').attr('link', link);
    $('.datatableshow5').DataTable().ajax.url(link).load();
}

function filtrerDataTablesIndividus() {
    var localite = $("#localite-filter").val();
    var quartier = $("#quartier-filter").val();
    var logement = $("#logement-filter").val();
    var famille = $("#famille-filter").val();
    var situation_sante = $("#situation_sante-filter").val();
    var situation_familiale = $("#situation_familiale-filter").val();

    var link = racine + 'quartiers/individus/getDT/' + localite + '/' + quartier + '/' + logement + '/' + famille + '/' + situation_sante + '/' + situation_familiale + '/all/2';
    $("#imprimerListeIndividusExcel").attr('href', racine + 'quartiers/individus/exportListeIndividusExcel/' + localite + '/' + quartier + '/' + logement + '/' + famille + '/' + situation_sante + '/' + situation_familiale);
    $('#datatableshow5').attr('link', link);
    $('#datatableshow5').DataTable().ajax.url(link).load();
}

function filtrerDataTablesQuartiers() {
    var localite = $("#filter-by-localite").val();
    var link = racine + 'quartiers/getDT/' + localite;
    $("#imprimerListeQuartiersExcel").attr('href', racine + 'quartiers/exportListeQuanrtiersExcel/' + localite);
    $("#exportQuartierBtn").attr('href', racine + 'quartiers/exportListeQuanrtierspdf/' + localite);
    $('#datatableshow').attr('link', link);
    $('#datatableshow').DataTable().ajax.url(link).load();
}

function filtrerDataTablesLogements() {
    var localite = $("#localite-filter").val();
    var quartier = $("#quartier-filter").val();
    var date_debut = $("#date_debut").val();
    var date_fin = $("#date_fin").val();
    var type_logement = $("#type_logement").val();
    var eau = $("#eau_filter").val();
    var electricite = $("#electricite_filter").val();
    if (date_debut == '') {
        date_debut = 'all';
    }
    if (date_fin == '') {
        date_fin = 'all';
    }
    var link = racine + 'quartiers/logements/getDT/' + localite + '/' + quartier + '/' + date_debut + '/' + date_fin + '/' + type_logement + '/' + eau + '/' + electricite + '/all/2';
    $("#imprimerListeLogementsExcel").attr('href', racine + 'quartiers/logements/exportListeLogementsExcel/' + localite + '/' + quartier + '/' + date_debut + '/' + date_fin + '/' + type_logement + '/' + eau + '/' + electricite);
    $('.datatableshow3').attr('link', link);
    $('.datatableshow3').DataTable().ajax.url(link).load();
}

function genererListe(ele, link,content,matrices_ids) {
    const container = $(ele).attr('container');
    $(ele).attr('disabled', 'disabled');
    spinner_loadder = '<span class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></span>'
    $(ele).html( spinner_loadder + ' Chargement...');
    console.log(matrices_ids);
    $.ajax({
        type: 'post',
        url: link,
        data: {
            matrices_ids: matrices_ids
        },
        success: function (data) {
            well_saved_icon = '<i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>';
            $(ele).removeAttr('disabled');
            setTimeout(function () {
                $(ele).html(well_saved_icon + content)
            }, 3500);
            $(ele).html(content)
        },
        error: function (data) {
            $.alert(msg_erreur);
        }
    });
}


function genererListeOfOne(ele, link,matrice_id) {
    const container = $(ele).attr('container');
    var content = '<i class="fa fa-sync"></i>';
    $(ele).attr('disabled', 'disabled');
    var spinner_loadder = '<span class="spinner-border spinner-border-sm" role="status"></span>'
    $(ele).html( spinner_loadder);
    $.ajax({
        type: 'get',
        url: racine + link + matrice_id,
        success: function (data) {
            setMainTabs(1);
            well_saved_icon = '<i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>';
            $(ele).removeAttr('disabled');
            setTimeout(function () {
                $(ele).html(well_saved_icon + content)
            }, 3500);
            $(ele).html(content)
        },
        error: function (data) {
            $.alert(msg_erreur);
        }
    });
}

function filtre_planification() {
    var etablissement_id = $('#filter_etablissement_id').val();
    var anneescolaire_id = $('#filter_annee_scolaire_id').val();
    var link = racine + 'concours/planification/getDT/' + etablissement_id + '/' + anneescolaire_id;
    $('#datatableshow').DataTable().ajax.url(link).load();
}


function generateSelectionForEveryEtablissement(ele,text){
    // select the spinner and make it visible & disable the button & load the spinner
    var spinner = $(ele);
    spinner.attr('disabled', 'disabled');
    spinner.html('<span class="spinner-border white spinner-border-sm" role="status"></span>');
    // get the selected annee_scolaire_id
    // make aja request with the route concours/planification/generateSelectionForEveryEtablissement
    $.ajax({
        type: 'get',
        url: racine + 'concours/planification/generateSelectionForEveryEtablissement',
        success: function (data) {
            // remove the spinner and make the button available
            spinner.removeAttr('disabled');
            spinner.html('<i class="fa fa-check"></i>');
            setTimeout(function () {
                spinner.html(text);
            }, 3500);
            // reload the datatable
            $('#datatableshow').DataTable().ajax.reload();
        }
    })
}

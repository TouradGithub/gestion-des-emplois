function noteExportPDF() {
    document.noteExportForm.action = racine + 'examen/notes/export';
    document.noteExportForm.target = "_blank";    // Open in a new window
    document.noteExportForm.submit();             // Submit the page
    return true;
}
function planificationExportPDF() {
    document.planificationExportForm.action = racine + 'examen/planifications/export';
    document.planificationExportForm.target = "_blank";    // Open in a new window
    document.planificationExportForm.submit();             // Submit the page
    return true;
}

function filtresSpecialiteByNiveauFormationParam(niveau_form,specialite_form,examen=null,etab_id=null){
    var niveau_formation_id = $(niveau_form).val()
    var examen_id = $(examen).val()
    var etab = $(etab_id).val()
    var link = racine + "examens/filtres/filtresSpecialiteByNiveauFormationParam/" + niveau_formation_id + "/" + examen_id + "/" + etab;
    $.ajax({
        url: link,
        type: 'GET',
        success: function (data) {
            $(specialite_form).html(data);

            $('#matieres').html('<option value="">Tous</option>');
            $('#matieres').attr('disabled', 'disabled');
            $("#contenu_notes").html("");
        }
    });
}

function filtresSpecialiteByNiveauFormationParamAll(niveau_form,specialite_form,etab_id=null){
    var niveau_formation_id = $(niveau_form).val()
    var etab = $(etab_id).val()
    var link = racine + "examens/filtres/filtresSpecialiteByNiveauFormationParamAll/" + niveau_formation_id + "/" + etab;
    $.ajax({
        url: link,
        type: 'GET',
        success: function (data) {
            $(specialite_form).html(data);
        }
    });
}

function filtresSpecialiteByNiveauFormationParamFiltres(niveau_form,specialite_form){
    var niveau_formation_id = $(niveau_form).val()
    var link = racine + "examens/filtres/filtresSpecialiteByNiveauFormationParamFiltres/" + niveau_formation_id;
    $.ajax({
        url: link,
        type: 'GET',
        success: function (data) {
            $(specialite_form).html(data);
        }
    });
}

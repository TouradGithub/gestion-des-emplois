function filter_eleves() {
    var classe_id = $('#classe_filtre').val();
    var exam_id = $('#exam_id').val();
    var matiere_id = $('#matieresSelect').val();
    var url = racine + 'examens/getDT/' + exam_id + "/" + classe_id + '/' + matiere_id;
    $('#datatableshow').attr('link', url);
    $('#datatableshow').DataTable().ajax.url(url).load();
}

function getMatiereByClass() {
    var classe_id = $('#classe_filtre').val();
    $.ajax({
        url: racine + 'examens/getClasseMatiere/' + classe_id,
        success: function (data) {
            $("#matieresSelect").empty();
            $("#matieresSelect").html(data);
            filter_eleves();
        }
    });
}

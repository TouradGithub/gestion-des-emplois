
function getNiveauFormations(ele,content){
    $.ajax({
        type: 'get',
        url: racine + 'concours/getNiveauxFormations/' + $(ele).val(),
        success: function (data) {
            $(content).html(data)
        },
        error: function (data) {
            $.alert(msg_erreur);
        }
    });
}

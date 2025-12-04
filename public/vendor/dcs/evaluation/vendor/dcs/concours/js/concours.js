function getDiplomes(ele,loading,container){
    $(loading).show()
    $.ajax({
        type: 'get',
        url: racine + 'concours/getDiplome/' + $(ele).val(),
        success: function (data) {
            $(loading).hide()
            $(container).show()
            $(".niveau_etude_select").show()
            $("#diplome_select").html(data.diplomes)
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}


function getTypesDiplomeBasedOnType(ele,loading,container,select_type_bac,select_specialite){
    $(loading).show()
    $.ajax({
        type: 'get',
        url: racine + 'concours/getTypesDiplomeBasedOnType/' + $(ele).val(),
        success: function (data) {
            $(loading).hide()
            $(container).show()
            if (data.type === 0) {
                $(select_type_bac).show()
                $(select_specialite).hide()
                $('#types_bac_select_id').html(data.html)
                $('#types_bac_select_id').prop('disabled', false)

                $("#niveau_etude_select").html(data.niveaux)
                $('#specialite_id').prop('disabled', true)
                $("#specialite_etude_select").html(data.specialites)
                // $('#etatblissement_select_1').html(data.etablissements)
                // $('#etatblissement_select_2').html(data.etablissements)
                // $('#etatblissement_select_3').html(data.etablissements)
            }else {
                $(select_specialite).show()
                $(select_type_bac).hide()
                $('#specialite_id').html(data.html)
                $('#specialite_id').prop('disabled', false)
                $('#types_bac_select_id').prop('disabled', true)

                $("#specialite_etude_select").html(data.specialites)
                $("#niveau_etude_select").html(data.niveaux)
            }

        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}


function showPromotion(ele){
    $('.annee_promotion_select').show()
    $.ajax({
        type: 'get',
        url: racine + 'concours/getEtablissements/' + $(ele).val() + '/' + $('#diplome_select').val(),
        success: function (data) {
            // disable the select specialite

            if (data.niveau_id != 0) {
                $('#specialite_cible').prop('disabled', true)
                $('#specialite_cible').html(data.specialite_html)
            }else{
                $('#specialite_cible').prop('disabled', false)
                $('#specialite_cible').html(data.specialite_html)
            }
            $("#etatblissement_select_1").html(data.etablissement_html)
            $("#etatblissement_select_2").html(data.etablissement_html)
            $("#etatblissement_select_3").html(data.etablissement_html)
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}


function getSpecialites(ele){
    $('.annee_promotion_select').show()

    $.ajax({
        type: 'get',
        url: racine + 'concours/getSpecialites/' + $(ele).val() + '/' + $('#diplome_select').val(),
        success: function (data) {
            $("#specialite_cible").html(data.specialite_html)
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function getEtablissements(ele){
    $.ajax({
        type: 'get',
        url: racine + 'concours/getEtablissements/' + $(ele).val() + '/' + $('#diplome_select').val(),
        success: function (data) {
            // disable the select specialite
            $("#etatblissement_select_1").html(data.etablissement_html)
            $("#etatblissement_select_2").html(data.etablissement_html)
            $("#etatblissement_select_3").html(data.etablissement_html)
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

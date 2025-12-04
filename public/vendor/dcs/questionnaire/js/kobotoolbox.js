function selectObjects(object_id,etat,mission_id) {
    console.log(object_id);
    $.ajax({
        url: racine + "qst/getActiviteByNiveau/" + val,
        success: function (data) {

        },
    });
}
$('[data-toggle="tooltip"]').tooltip({
    trigger : 'hover'
})

// function afterDeploy() {
//     setMainTabs(3)
// }

function deployForm(element = null, url) {
    var container = $(element).attr('container');
    let mission_id = $(element).attr('mission_id');
    $('#' + container + ' #form-errors').hide();
      // disable all buttons
    $(element).prop('disabled', true);
    $('#deploy_icon').hide();
    $('#deploy_spinner').show();
    // return;
    $.ajax({
        type: "post",
        url: url,
        data: new FormData($('#' + container + ' form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {

                 if (!data.success){
                     alert("Erreur lors de la deploiement du formulaire");
                 }

            console.log(data);
            $('#deploy_icon').show();
            $('#deploy_spinner').hide();
            $(element).prop('disabled', false);
            setTimeout(function () {
                $('#' + container + ' .kb .answers-well-saved').hide();
                $('#' + container + ' .kb .main-icon').show();
            }, 3500);
            getTheContent("qst/missions/getTab/"+mission_id+"/30","#missions_tab30");

        },

        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            }
                // if (data.credential){
                //     alert('')
            // }
            else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#deploy_icon').show();
            $('#deploy_spinner').hide();
            $(element).prop('disabled', false);
        }
    });
}



function updateForm(element,url) {
    let mission_id = $(element).attr('mission_id');
    let qst_id = $(element).attr('qst_id');
    var container = $(element).attr('container');
    $('#' + container + ' #form-errors').hide();
    $(element).attr('disabled', 'true');
    $('#update_icon').hide();
    $('#update_spinner').show();
    $.ajax({
        url: url,
        type: "post",
        data: {mission_id,qst_id},
        success: function (data) {
            console.log(data);
              if (!data.success){
                  alert("Erreur lors de la mise à jour du formulaire");
              }
            $('#update_icon').show();
            $('#update_spinner').hide();
            $(element).removeAttr('disabled');
            getTheContent("qst/missions/getTab/"+mission_id+"/30","#tab30");
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            }
                // if (data.credential){
                //     alert('')
            // }
            else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#update_icon').show();
            $('#update_spinner').hide();
            $(element).removeAttr('disabled');
        }
    });

}

function filtreByGeo(val,type=1){
    $.ajax({
        url: "/qst/missions/filtreByGeo/" + val+'/'+type,
        success: function (data) {
            if (type===1){
                $("#moughataas").html(data.mg);
                $("#communes").html(data.cm);
            }
            else
                $("#communes").html(data);
            $('.selectpicker').selectpicker('refresh');
        }
    });
}
function onAddFilter(mission_id) {
    let formValues = $("#filter_form").serialize();
    let url = racine+"qst/missions/addObjectDT/"+mission_id+"/"+formValues
    $("#addObjectMissionDt").DataTable().ajax.url(url).load();
}
function onObjectFilter(mission_id) {
    let formValues = $("#filter_form_dt").serialize();
    let url = racine+"qst/missions/objectGetDT/"+mission_id+"/"+formValues
    $(".datatableshow5").DataTable().ajax.url(url).load();
}


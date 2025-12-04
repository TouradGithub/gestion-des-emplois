function getETabBySpecialite(id) {
    niveau_cible = $("#niveau_cible").val();
    concour_id = $("#concour_id").val();
    $("#titleChoix").toggleClass('d-none');
    getTheContent('concours/getETabBySpecialite/' + id + "/" + niveau_cible+ "/" + concour_id, '#choix_cnt');
}

function validateStep(step) {
    var res = false;
    $.ajax({
        url: racine+"concours/register_candidat/"+step,
        type: 'POST',
        data: $(`#stepsForm`).serialize(),
        async: false,
        success: function (data) {
            if (data.success) {
                $("#step_2_cnt").html(data.data);
                res = true;
            }
            else if(data.info)
            {
                $("#stepsFormCnt").html(data.data);
            }

        },
        error: function (data) {
            if (data.status === 422) {
                const errors = data.responseJSON;
                var errorsHtml = '<ul class="list-group">';
                const erreurs = (errors.errors) ? errors.errors : errors;
                var container="stepsFormCnt"

                $.each($('#' + container + ' form input'), function (key, item) {
                    let input = $(item);
                    if (!(input.attr('name') in erreurs)) {
                        input.next('.invalid-feedback').remove();
                        input.removeClass('is-invalid');
                    }
                });


                $.each(erreurs, function (key, value) {
                    const input = $(`#${container} .form-control[name=${key}]`);
                    if (input.attr('name') === key) {
                        if (input.hasClass('select2')) {
                            const span = $(`#${container} .form-control[name=${key}]`);
                            // span.addClass('dcs-is-invalid');
                            span.next().next('.dcs-invalid-feedback').remove();
                            $(`<p class='dcs-invalid-feedback'>${value}</p>`).insertAfter(span.next());
                        } else {
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove();
                            $(`<p class='invalid-feedback'>${value}</p>`).insertAfter(input);
                        }
                    }
                });

                $('#' + container + ' .form-control').keyup(function () {
                    $(this).next('.invalid-feedback').remove();
                    $(this).removeClass('is-invalid');
                });

                $('#' + container + ' .select2').change(function () {
                    $(this).next().next('.dcs-invalid-feedback').remove();
                    $(this).removeClass('dcs-is-invalid');
                });

                if (errors.errors) {
                    let form = $('#' + container + ' form');
                    $('#' + container).find('.alert-danger').remove();
                }
                // $.each(erreurs, function (key, value) {
                //     errorsHtml += '<li class="list-group-item list-group-item-danger">' + value + '</li>';
                // });
                // errorsHtml += '</ul>';
                // $('#' + container + ' #form-errors').show().html(errorsHtml);
            }
            else {
                console.log(data,data?.responseJSON.is_btn_confirm);
                $.alert({
                    title: data.responseJSON.msg_header?data.responseJSON.msg_header:"Erreur",
                    content: data.responseJSON.msg?`${data.responseJSON.msg}`:erreur_req,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        confirm: {
                            text: 'Continuer',
                            btnClass: 'btn-blue',
                            isHidden: !data?.responseJSON.is_btn_confirm,
                            action: function () {
                                if (data?.responseJSON.is_et) {
                                    window.location.replace(racine + "concours/inscriptionET/" + data.responseJSON.nni+"/"+ data.responseJSON.concour_id);
                                }else {
                                    url =racine + "concours/inscription/" + data.responseJSON.nni+"/"+ data.responseJSON.concour_id;
                                    window.location.replace(url);
                                }
                            }
                        },
                        cancel: {
                            text: 'Fermer',
                            btnClass: 'btn-red',
                            action: function () {
                            }
                        }
                    }
                });
            }
        }
    });
    return res;
}
function saveSteps(step,title = "Confirmation Candidature",content = "Voulez-vous vraiment confirmer votre candidature ?",confirmBtn = "Confirmer",cancelBtn = "Fermer") {
    var res = false;

    $.alert({
        title: title,
        content: content,
        type: 'success',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: confirmBtn,
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: racine+"concours/register_candidat/"+step,
                        type: 'POST',
                        data: $(`#stepsForm`).serialize(),
                        async: false, // Prevent jQuery from processing the data
                        success: function (data) {
                            if (data.success) {
                                // window.location.replace(racine+"concours/register_candidat/recu/"+data.id);
                                getTheContent('concours/register_candidat/getRecu/'+data.id, '#stepsFormCnt');
                                // sendFormData(data.id);
                            }
                        },
                        error: function (data) {
                            if (data.status === 422) {
                                const errors = data.responseJSON;
                                var errorsHtml = '<ul class="list-group">';
                                const erreurs = (errors.errors) ? errors.errors : errors;
                                var container="stepsFormCnt"

                                $.each($('#' + container + ' form input'), function (key, item) {
                                    let input = $(item);
                                    if (!(input.attr('name') in erreurs)) {
                                        input.next('.invalid-feedback').remove();
                                        input.removeClass('is-invalid');
                                    }
                                });


                                $.each(erreurs, function (key, value) {
                                    const input = $(`#${container} .form-control[name=${key}]`);
                                    if (input.attr('name') === key) {
                                        if (input.hasClass('select2')) {
                                            const span = $(`#${container} .form-control[name=${key}]`);
                                            // span.addClass('dcs-is-invalid');
                                            span.next().next('.dcs-invalid-feedback').remove();
                                            $(`<p class='dcs-invalid-feedback'>${value}</p>`).insertAfter(span.next());
                                        } else {
                                            input.addClass('is-invalid');
                                            input.next('.invalid-feedback').remove();
                                            $(`<p class='invalid-feedback'>${value}</p>`).insertAfter(input);
                                        }
                                    }
                                });

                                $('#' + container + ' .form-control').keyup(function () {
                                    $(this).next('.invalid-feedback').remove();
                                    $(this).removeClass('is-invalid');
                                });

                                $('#' + container + ' .select2').change(function () {
                                    $(this).next().next('.dcs-invalid-feedback').remove();
                                    $(this).removeClass('dcs-is-invalid');
                                });

                                if (errors.errors) {
                                    let form = $('#' + container + ' form');
                                    $('#' + container).find('.alert-danger').remove();
                                }
                                // $.each(erreurs, function (key, value) {
                                //     errorsHtml += '<li class="list-group-item list-group-item-danger">' + value + '</li>';
                                // });
                                // errorsHtml += '</ul>';
                                // $('#' + container + ' #form-errors').show().html(errorsHtml);
                            }
                            else {
                                $.alert({
                                    title: data.responseJSON.msg_header?data.responseJSON.msg_header:"Erreur",
                                    content: data.responseJSON.msg?`${data.responseJSON.msg}`:erreur_req,
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        close: function () {
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            },
            cancel: {
                text: cancelBtn,
                btnClass: 'btn-red',
                action: function () {
                }
            }
        }
    });


}

function getSpecialiteEtab(etab) {
    getTheContent('concours/reclamation/getSpecialiteByEtab/' + etab, '#specialitesEtab');
    $("#choixFormation").html("");
}

function getChoixFormationByNiveau(val) {
    var previous_niveau = $('#previous_niveau').val();
    var concour_id = $('#concour_id').val();
    if (previous_niveau != null && previous_niveau != "" && previous_niveau != undefined) {
        getTheContent('concours/reclamation/getChoixFormationByNiveau/' + previous_niveau + "/" + val+"/"+concour_id, '#choixFormation');
    }

}
function OnNiveau(val){
    $("#choixFormation").html("");
    $("#specialitesEtab").removeClass('d-none');
    $("#choix_cnt").html("");
}


function sendFormData(cn_candidate_id) {
    // Create a FormData object to serialize the form data
    var formData = new FormData($('#imageFormAjax')[0]); // Serialize the first matching form with id 'imageFormAjax'

    // Add a candidat_id parameter to the form data
    formData.append('candidat_id', cn_candidate_id); // Replace 'your_candidat_id_value' with the actual candidat_id value
    formData.append('imageFile', document.getElementById('imageFile').files[0]); // Replace 'your_image_file_input' with the actual file input id



    $.ajax({
        url: racine + 'concours/candidats/saveImage',
        type: 'POST',
        data: formData, // Use the FormData object
        contentType: false, // Don't set contentType
        success: function (data) {
            // Handle the success response from the server
            console.log(data);
        },
        error: function (xhr, status, error) {
            // Handle errors
            console.error(xhr, status, error);
        }
    });
}

function validateFile(input) {
    const file = input.files[0];
    const errorText = document.getElementById('errorText');

    if (file) {
        if (file.type !== 'application/pdf') {
            // Display an error message
            errorText.textContent = 'Veuillez choisir un fichier PDF';
            input.value = ''; // Clear the file input
        } else {
            // Reset the error message
            errorText.textContent = '';
        }
    }
}

function selectProfilUser(element) {
    var id = $(element).val();
    $.ajax({
        type: 'get',
        url: racine + 'admin/users/selectProfilForUser/' + id,
        success: function (show_objects) {
            if (show_objects == 1) {
                $('.objects-container').removeClass('d-none');
                $('.is_admin_etab').val("1");
            } else {
                $('.objects-container').addClass('d-none');
                $('.is_admin_etab').val("0");
            }
            // $('.selectpicker').selectpicker({
            //     size: 4
            // });
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez rÃ©essayer ou actualiser la page!");
        }
    });
}

// Ouvrire le modal d'ajout de profile a un user
function addProfileToUser(id) {
    $.ajax({
        type: 'get',
        url: racine + 'admin/users/addProfileToUser/' + id,
        success: function (data) {
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();

            $("#select-wilaya select").change(function () {
                var link = racine + 'territoires/getMoughataasAndCommunesOfWilaya/' + $(this).val();
                $.ajax({
                    type: 'GET',
                    url: link,
                    success: function (data) {
                        $("#select-moughataa select").html(data.moughataas);
                        $("#select-commune select").html(data.communes);
                        $("#select-moughataa select, #select-commune select").selectpicker('refresh');
                        $("#select-moughataa select").focus();
                    },
                    error: function () {
                        console.log('La requête n\'a pas abouti');
                    }
                });
            });
            // Afficher communes quand on selectionne une moughataa
            $("#select-moughataa select").change(function () {
                var link = racine + 'territoires/getCommunesOfMoughataaAndWilaya/' + $("#select-wilaya select").val() + '/' + $(this).val();
                $.ajax({
                    type: 'GET',
                    url: link,
                    success: function (data) {
                        $("#select-commune select").html(data);
                        // $("#select-commune").show();
                        $("#select-commune select").selectpicker('refresh');
                        $("#select-commune select").focus();
                        // $(".addfiche").attr('disabled', 'disabled');
                    },
                    error: function () {
                        $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                });
            });
            $("#profile-niveau").change(function () {
                if ($(this).val() == 4) {
                    $("#wilayas-section").hide();
                    $("#moughataas-section").hide();
                    $("#communes-section").hide();
                } else if ($(this).val() == 3) {
                    $("#wilayas-section").show();
                    $("#moughataas-section").hide();
                    $("#communes-section").hide();
                } else if ($(this).val() == 2) {
                    $("#wilayas-section").show();
                    $("#moughataas-section").show();
                    $("#communes-section").hide();
                } else {
                    $("#wilayas-section").show();
                    $("#moughataas-section").show();
                    $("#communes-section").show();
                }
            });

            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez rÃ©essayer ou actualiser la page!");
        }
    });
}

function saveProfileUser(id, element) {
    saveform(element, function (id) {
        //$(element).attr('disabled','disabled');
        setTimeout(function () {
            $('#second-modal').modal('toggle');
            getTheContent('admin/users/getTab/' + id + '/2', '#tab2');
        }, 1000);
    });
}

function deleteProfileFromUser(link, text) {
    confirmAction(link, text, function (id) {
        getTheContent($('#link2').attr("link"), '#tab2');
    });
}

function loguser() {

    $('#loginModal #form-errors').hide();
    $("#loginModal .form-loading").show();
    var data = $('#loginModal form').serialize();
    $.ajax({
        type: $('#loginModal form').attr("method"),
        url: $('#loginModal form').attr("action"),
        data: data,
        dataType: 'json',
        success: function (data) {
            window.location.href = data;
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
                $('#loginModal #form-errors').show().html(errorsHtml);
            } else {
                $.alert('La requête n\'a pas abouti');
            }
            $("#loginModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
    });
}

function showMessageSuccess(data) {

    $("#resultat_resetpsw").html(data).show();
    setTimeout(function () {
        $("#resultat_resetpsw").html('').hide();
    }, 5000);
}


// new added from kachel.old by cheikh ahmed
function filterEmployes() {
    let genre = $("#ref_genre_id").val();
    let type_contrat = $("#type_contrat").val();
    let refSituationFamilliale = $("#ref_situation_familliale_id").val();
    let objects = $('#object_id').val();
    console.log(objects);
    $('#datatableshow').DataTable().ajax.url(racine + 'rh/employes/getDT/' + genre + '/' + type_contrat + '/' + refSituationFamilliale+'/'+objects).load();
}

function autre_champs(element) {
    $(element).find('i').toggleClass('fa-plus fa-minus');
    $("#autre_champ").toggle();
}

function info_card(data) {
    $("#full_name").html(data.full_name);
    $("#surname").html(data.surname);
    $("#titre_c").html(data.titre);
    $("#profil_info").html(data.info_right);

}

function encours() {
    if ($('#en_cours').is(':checked')) {
        $('.fin').hide();
    } else {
        $('.fin').show();
    }
}

function addImageModal(id) {
    url = racine + "rh/employes" + '/openModalImage/' + id;
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

function get_fiche_pdf() {
    document.formstpdf.action = racine + 'rh/employes/fiche_pdf';
    document.formstpdf.target = "_blank";    // Open in a new window
    document.formstpdf.submit();             // Submit the page
    return true;
}

function editImageStagiare() {
    //alert(1)
    $('#add-modal #form-errors').hide();
    // var element = $(this);
    $("#add-modal .form-loading").show();

    // var data = $('#image-modal form').serialize();
    $.ajax({
        type: $('#add-modal form').attr("method"),
        url: $('#add-modal form').attr("action"),
        data: new FormData($('#image-modal form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#add-modal').modal('toggle');
            $('#add-modal .form-loading').hide();
            $('#add-modal .answers-well-saved').show();
            $('#avatar').attr('src', racine + data);
            previewFile();
            //openStagiaireModal(data);
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
                $('#add-modal #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#image-modal .form-loading").hide();
        }
    });
}

function previewFile() {
    // const preview = document.querySelector('img');
    const preview = document.querySelector("#img_pic");
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        // convert image file to base64 string
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}
function getEmployesPDF(){
    document.formst.action = racine+'rh/employes/exportEmployesPDF';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;

}

function getEmployesExcel(){

    document.formst.action = racine+'rh/employes/exportEmployesExcel';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;

}

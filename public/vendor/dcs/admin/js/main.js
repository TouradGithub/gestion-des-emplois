function selectProfilUser(element) {
    var id = $(element).val();
    if (id) {
        $.ajax({
            type: 'get',
            url: racine + 'admin/users/selectProfilForUser/' + id,
            success: function (show_objects) {
                if (show_objects != 1) {
                    $('.objects-container').removeClass('d-none');
                    $('#for_object').val("1");
                } else {
                    $('.objects-container').addClass('d-none');
                    $('#for_object').val("0");
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
            getTheContent('admin/users/getTab/' + id + '/2', '#users_tab2');
        }, 1000);
    });
}

function deleteProfileFromUser(user_profil, text) {

    var link = racine + "admin/users/deleteProfileFromUser/" + user_profil;
    confirmAction(link, text, function (id) {
        $("#" + user_profil).hide();
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


function getNiveauGeo(element) {
    var niveau_geo = $(element).val();
    switch (niveau_geo) {
        case '1':
            $("#commune").show();
            $("#wil").show();
            $("#mg").show();
            $("#com").show();
            $.ajax({
                type: 'get',
                url: racine + 'multi/list_wilayas',
                cache: false,
                success: function (data) {
                    $("#wilayas").empty();
                    $('#wilayas').html(data.wilayas);
                    $("#moughataas").empty();
                    $('#moughataas').html(data.moughataas);
                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
            // charger les communes
            $.ajax({
                type: 'get',
                url: racine + 'multi/multSelect_communes',
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });

            break;
        case '2':
            $("#commune").show();
            $("#wil").show();
            $("#mg").hide();
            $("#com").show();
            // $("#mg").hide();
            // charger les wilaya
            $.ajax({
                type: 'get',
                url: racine + 'multi/list_wilayas',
                cache: false,
                success: function (data) {
                    $("#wilayas").empty();
                    $('#wilayas').html(data.wilayas);

                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
            $.ajax({
                type: 'get',
                url: racine + 'multi/multSelect_moughataas',
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });

            break;
        case '3':
            $("#commune").show();
            $("#wil").hide();
            $("#mg").hide();
            $("#com").show();
            $.ajax({
                type: 'get',
                url: racine + 'multi/multSelect_wilayas',
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });

            break;

        case '4':
            $("#commune").hide();
            $("#wil").hide();
            $("#mg").hide();
            $("#com").hide();
            break;
        default:

            break;
    }

}

function getFiltreWilaya(element) {
    var id = $(element).val();
    var niveau_geo = $("#profile-niveau").val();

    //if(id != 0)
    //{
    switch (niveau_geo) {
        case '1':
            ulr_select = racine + 'multi/multSelect_communes_byWilaya/';
            $("#mg").show('slow');
            //charger la liste des moughataas
            $.ajax({
                type: 'get',
                url: racine + 'multi/liste_moughataas/' + id,
                cache: false,
                success: function (data) {

                    $('#moughataas').html(data.moughataas);
                },
                error: function () {
                    //loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
            break;
        case '2':
            ulr_select = racine + 'multi/multSelect_moughataas_byWilaya/';
            $("#mg").hide('slow');
            urr = ulr_select + id;
            //alert(urr)
            $.ajax({
                type: 'get',
                url: ulr_select + id,
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {

                    //loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                }
            });
            break;
        default:

            break;
    }
    //charger la liste des moughataas de la wilaya

    // charger la liste des communes de la wilayas
    //}
    /*else {
        $("#mg").hide('slow');
        niveau_geo=$("#niveau_geo").val();
        switch (niveau_geo) {
            case '1':
                ulr_select=racine+'multi/multSelect_communes';
                //charger la liste des moughataas
                break;
            case '2':
                ulr_select=racine+'multi/multSelect_moughataas';
                break;
            default:

                break;
        }
        $.ajax({
            type: 'get',
            url: ulr_select,
            cache: false,
            success: function(data)
            {
                $('#com').html(data);
            },
            error: function () {

                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    }*/
}

function getFiltreMoughataa(element) {
    var id = $(element).val();
    var id_w = $("#wilayas").val();
    if (id != 0) {
        $.ajax({
            type: 'get',
            url: racine + 'multi/multSelect_communes_byMoughataas/' + id,
            cache: false,
            success: function (data) {
                $('#com').html(data);
            },
            error: function () {
                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    } else {
        $.ajax({
            type: 'get',
            url: racine + 'multi/multSelect_communes_byWilaya/' + id_w,
            cache: false,
            success: function (data) {

                $('#com').html(data);

            },
            error: function () {

                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    }
}


$(function () {
    let CurrentNode;
    $('#structures_tree')
        .on('changed.jstree', function (e, data) {
            let i, j, r = [], s = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                CurrentNode = data.selected[0];
            }
        })
        .jstree({
            'core': {
                "themes": {
                    "icons": false
                },
                "check_callback": true,
                'data': {
                    'url': function (node) {
                        return racine + 'admin/structures/getStructuresTree';
                    },
                    'data': function (node) {
                        return {'id': node.id};
                    },
                    dataType: "json",
                }
            }
        })
        .on("select_node.jstree", function (e, data) {
            let id = data.node.id;
            getTheContent('admin/structures/getActionsStructures/' + id, '#actions');
        });
});

function deleteSysStructure(id) {
    confirmAction(racine + 'admin/structures/delete/' + id, 'Etes vous sur de vouloir supprimer ?', function () {
        $('#structures_tree').jstree(true).refresh();
    })
}
function changeLinkedToObject()
{
    resetInit();
    var lik = $("#linked_to_object").val();
    if (lik == 1)
        $("#grp_object").show();
    else
        $("#grp_object").hide();
}

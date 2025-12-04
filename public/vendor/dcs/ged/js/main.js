// Ouvrir le modal ajout document
function AddNewDocument(id_objet, type_obj, no_objet) {
    $.ajax({
        type: 'get',
        url: racine + 'ged/add/' + id_objet + "/" + type_obj + "/" + no_objet,
        success: function (data) {
            $("#add-modal .modal-dialog").addClass("modal-lg");
            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function addDocumentBr() {
    $('#document_div #form-errors').hide();
    $('#document_div  .main-icon').hide();
    $("#document_div .spinner-border").show();

    $.ajax({
        type: $('#document_div form').attr("method"),
        url: $('#document_div form').attr("action"),
        data: new FormData($('#document_div form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#document_div .spinner-border').hide();
            $('#document_div .answers-well-saved').show();
            setTimeout(function () {
                $('#document_div .answers-well-saved').hide();
                $('#document_div .main-icon').show();
            }, 3500);
            var form = document.getElementById("addpiece");
            form.reset();
            $('.datatableshow4').DataTable().ajax.reload();
            $('#datatableshow_ged').DataTable().ajax.url(racine + "ged/getdt/" + data.objet_id + '/' + data.type_objet + '/' + data.id).load();
            $("#document_div").hide();
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
                $('#document_div #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#document_div .spinner-border").hide();
            $('#document_div .main-icon').show();
        }
    });
}
function addDocument() {
    $('#document_div #form-errors').hide();
    $('#document_div  .main-icon').hide();
    $("#document_div .spinner-border").show();

    $.ajax({
        type: $('#document_div form').attr("method"),
        url: $('#document_div form').attr("action"),
        data: new FormData($('#document_div form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#document_div .spinner-border').hide();
            $('#document_div .answers-well-saved').show();
            setTimeout(function () {
                $('#document_div .answers-well-saved').hide();
                $('#document_div .main-icon').show();
            }, 3500);
            var form = document.getElementById("addpiece");
            form.reset();
            $('.datatableshow4').DataTable().ajax.reload();
            $('#datatableshow_ged').DataTable().ajax.url(racine + "ged/getdt/" + data.objet_id + '/' + data.type_objet + '/' + data.id).load();
            $("#document_div").hide();
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
                $('#document_div #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#document_div .spinner-border").hide();
            $('#document_div .main-icon').show();
        }
    });
}

function deleteDocument(element) {
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer ce document ? ");
    if (confirme) {
        $.ajax({
            type: 'get',
            url: racine + 'ged/delete/' + element,
            success: function (data) {
                $('.datatableshow4').DataTable().ajax.reload();
                $('#datatableshow_ged').DataTable().ajax.reload();
            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $('#fichlaboModal #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            }
        });
    }
}

function filtrerGeds() {
    var url = racine + "ged/getAllGedsDT/" + $("#nature").val() + "/" + $("#type_document").val() + "/" + $("#type_objet").val() + "/" + $("#object_options").val()
    $("#datatableshow").attr('link', url);
    $("#datatableshow").DataTable().ajax.url(url).load();
}

function GetGedsObject(val) {
    $('#loading_cnt').show();
    $('#loading_cnt').html(loading_content);
    $.ajax({
        url: racine + 'ged/getTypeObjects/' + val,
        success: function (data) {
            $("#objects_cnt").show();
            $("#object_options").html(data)
            $('#loading_cnt').html('')
        },
        complete: function () {
            $('#loading_cnt').hide()
        },
        error: function (msg_erreur) {
            $.alert(msg_erreur);
        }
    });
}

// function qui permet de changer le contenu des types de documents en fonction du type de fichier
function filterTypeFichier() {
    let url = racine + 'ged/getTypeDocuments/' + $('#type_fichier').val();

    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            $('#type_document').html(data);
        },
        error: function () {
            console.log('La requête n\'a pas aboutie');
        }
    });
}

function displayInputs() {
    $('#inputsPathAndModelTypeGed').toggle('d-none');
}

function gedsFilter() {
    let val = $("#type_fichier").val();
    if (val !== '4') {
        let params = $("#type_document").val() + "/" + $("#type_objet").val() + "/" + $("#object_options").val()
        getTheContent('ged/getView/1/' + params, '#view_container');
    } else {
        let params = $("#type_document").val() + "/" + $("#type_objet").val() + "/" + $("#object_options").val()
        getTheContent('ged/getView/2/' + params, '#view_container');
    }
}

$(function () {
    let CurrentNode;

    $('#mots_cles_tree')
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
                        return racine + 'ged/mots_cles/getMotsClesTree';
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
            getTheContent('ged/mots_cles/getActionsMotcles/' + id, '#actions');
        });
});

function deleteMotCle(id) {
    confirmAction(racine + 'ged/mots_cles/delete/' + id, 'Etes vous sur de vouloir supprimer ?', function () {
        $('#mots_cles_tree').jstree(true).refresh();
    })
}

function indexerGed(motCle_id,ged_id)
{
    $.ajax({
        url : racine + 'ged/createIndexGed/' + motCle_id + '/' + ged_id,
        success : function(){
            $('#main-modal').modal('hide');
        },
        error : function(err){
            console.log("La requete n'a pas aboutie !");
        }
    });
}

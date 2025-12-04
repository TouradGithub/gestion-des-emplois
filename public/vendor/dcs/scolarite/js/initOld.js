// $(document).ready(function () {
//     // racine = '/onispa/public/';
//
//     // Ajout du CSRF Token pour les requettes ajax
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });
//
//     // Customzing dataTable ajax errors
//     $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
//         console.log(message);
//         $.alert("Une erreur est survenue lors du chargement du contenu veuillez réessayer ou actualiser la page!");
//     };
//
//     resetInit();
// });

// Ouvrir dans le Main Modal
// function openInModal(link, aftersave = null) {
//     $.ajax({
//         type: 'get',
//         url: link,
//         success: function (data) {
//             $("#main-modal .modal-header-body").html(data);
//             $("#main-modal").modal();
//             resetInit();
//             if (aftersave)
//                 aftersave();
//         },
//         error: function () {
//             $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//         }
//     });
// }

// Get the content from Ajax and show it in a div
// function getTheContent(link, container, element = null) {
//     // traitement special ftp questionnaire
//     // if($('#tab2').hasClass('active')) $('#tab2').html('');
//     //***
//     var loading_content = '<div style="height:' + $(container).outerHeight() + 'px!important;position:relative;text-align:center"><i style="position: absolute;top:calc(50% - 15px);" class="fa fa-refresh fa-spin fa-2x"></i></div>';
//     if (element) {
//         $('.tr-list').css('background-color', '#fff');
//         $(element).css('background-color', '#eee');
//     }
//     $(container).html(loading_content);
//     $.ajax({
//         type: 'get',
//         url: racine + link,
//         success: function (data) {
//             $(container).html(data);
//             resetInit();
//         },
//         error: function () {
//             $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//         }
//     });
// }

// Init of DataTables
// function setDataTable(element) {
//     // Data tables to load
//     if (!$.fn.dataTable.isDataTable(element) && $(element).length) {
//         var colonnes = [];
//         var index = [];
//         var target;
//         if (typeof $(element).attr("index") !== 'undefined') {
//             var lists = $(element).attr("index").split(',');
//             for (var i = 0; i < lists.length; i++) {
//                 index.push(parseInt(lists[i]));
//             }
//         } else {
//             index.push(-1);
//         }
//         var nbr = $(element).attr("nbr");
//         if (typeof $(element).attr("nbr") !== 'undefined') {
//             nbr = $(element).attr("nbr");
//         } else {
//             nbr = 10;
//         }
//         var lists = $(element).attr("colonnes").split(',');
//         for (var i = 0; i < lists.length; i++) {
//             colonnes.push({
//                 'data': lists[i],
//                 'name': lists[i]
//             });
//         }
//         target = 'targets:' + index;
//         oTable = $(element).DataTable({
//             oLanguage: {
//                 sUrl: racine + "vendor/datatables/js/datatable-fr.json",
//             },
//             "processing": true,
//             "serverSide": true,
//             "responsive": true,
//             "orderCellsTop": true,
//             "bDestroy": true,
//             "cache": false,
//             "pageLength": nbr,
//             "iDisplayLength": nbr,
//             //"ordering": false,
//             "order": [
//                 [0, "asc"]
//             ],
//             "columnDefs": [{
//                     orderable: false,
//                     targets: index
//                 },
//                 {
//                     searchable: false,
//                     targets: index
//                 }
//             ],
//             "ajax": $(element).attr("link"),
//             "columns": colonnes,
//             "drawCallback": function () {
//                 // init tooltips
//                 if ($(".status-check").length) {
//                     $('.status-check').bootstrapToggle({
//                         on: 'Présent',
//                         off: 'Absent'
//                     });
//                 };
//                 // init tooltips
//                 $('[data-toggle="tooltip"]').tooltip();
//                 $('.delete').confirm({
//                     title: 'Confirmation',
//                     content: 'Êtes-vous sûr de vouloir supprimer cet élément?',
//                     buttons: {
//                         ok: {
//                             text: 'Oui',
//                             btnClass: 'btn-default',
//                             action: function () {
//                                 $.ajax({
//                                     type: 'GET',
//                                     url: this.$target.attr('href'),
//                                     success: function (data) {
//                                         if (data.success == "true") {
//                                             $('#datatableshow').DataTable().ajax.reload()
//                                             $.alert(data.msg, 'Elément supprimé');
//                                         } else $.alert(data.msg, 'Erreur');
//                                     },
//                                     error: function () {
//                                         $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                                     }
//                                 });
//                             }
//                         },
//                         cancel: {
//                             text: 'Non',
//                             btnClass: 'btn-default'
//                         }
//                     }
//                 });
//                 //reset Inits
//                 resetInit();
//             }
//         })
//     }
//
// }

// updating a group des elements
// function updateGroupeElements(element = null) {
//     var questions = $(".group-elements").sortable('toArray');
//     var childscount = $(".group-elements li").length;
//     var idgroup = $(".group-elements").attr('idgroup');
//     var lien = $(".group-elements").attr('lien');
//     if (element) {
//         if ($(element).hasClass("close")) {
//             questions = jQuery.grep(questions, function (value) {
//                 return value != $(element).parent().attr('id');
//             });
//             $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
//         } else {
//             $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
//             questions.push($(element).attr('idelt'));
//         }
//     }
//     if (questions.length)
//         var qsts = questions.join();
//     else
//         var qsts = 0;
//     var link = racine + lien + "/" + qsts + '/' + idgroup;
//     $.ajax({
//         type: 'GET',
//         url: link,
//         success: function (data) {
//             if (element) {
//                 if ($(element).hasClass("close"))
//                     $(element).parent().remove();
//                 else {
//                     var idelt = $(element).attr('idelt');
//                     var libelle = $(element).attr('libelle');
//                     $(element).parents('tr').remove();
//                     $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
//                 }
//                 if ($('.btn-drftval').length) {
//                     if (qsts.length > 0)
//                         $('.btn-drftval').show();
//                     else
//                         $('.btn-drftval').hide();
//                 }
//             }
//             $('.datatableshow1').DataTable().ajax.reload();
//         },
//         error: function () {
//             if (element) {
//                 if ($(element).hasClass("close"))
//                     $(element).html('&times;');
//                 else {
//                     $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
//                 }
//             }
//             $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//         }
//     });
// }

// function saveform(element, aftersave=null) {
//     var container = $(element).attr('container');
//     $('#' + container + ' #form-errors').hide();
//     var element = $(this);
//     // $(element).attr('disabled','disabled');
//     $('#' + container + ' .form-loading').show();
//     var data = $('#' + container + ' form').serialize();
//     $.ajax({
//         type: $('#' + container + ' form').attr("method"),
//         url: $('#' + container + ' form').attr("action"),
//         data: data,
//         dataType: 'json',
//         success: function (data) {
//             console.log(data);
//             $('.datatableshow').DataTable().ajax.reload();
//             $('#' + container + ' .form-loading').hide();
//             $('#' + container + ' .answers-well-saved').show();
//             setTimeout(function () {
//                 $('#' + container + ' .answers-well-saved').hide();
//             }, 3500);
//             if (aftersave) {
//                 aftersave(data);
//             }
//         },
//         error: function (data) {
//             if (data.status === 422) {
//                 var errors = data.responseJSON;
//                 console.log(errors);
//                 errorsHtml = '<div class="alert alert-danger"><ul>';
//                 var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
//                     errorsHtml += '<li>' + value[0] + '</li>';
//                 });
//                 errorsHtml += '</ul></div>';
//                 $('#' + container + ' #form-errors').show().html(errorsHtml);
//             } else {
//                 alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//             }
//             $('#' + container + ' .form-loading').hide();
//             // $(element).removeAttr('disabled');
//         }
//     });
// }

function resetInitModule() {
    // Sortable
    $(".sortable").sortable({
        axis: 'y',
        update: function (event, ui) {}
    });
    // $(".group-elements").sortable({
    //     axis: 'y',
    //     update: function (event, ui) {
    //         updateGroupeElements();
    //     }
    // });
    $( "#questionnaire-elements" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            updateQuestionnaireElements();
        }
    });
    $( "#infochild-elements" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            updateChildElements();
        }
    });

    // // init du select picker
    // $('.selectpicker').selectpicker({
    //     size: 10
    // });

    // TreeView
    if ($('#childrentree').length){
        $('#childrentree').jstree({
            'core' : {
                "themes":{
                    "icons":false
                },
                "dblclick_toggle": false,
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return racine+'questionnaires/getChildren/'+$('#childrentree').attr('idqst');
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };
                    },
                    dataType: "json",
                }
            },
        });
        $('#childrentree').delegate("a","dblclick", function(e) {
            var idn = $(this).parent().attr("id");
            getInfoChild(idn,0);
        });
    }
    //
    // // Datatables to load General
    // if ($('#datatableshow').length) setDataTable('#datatableshow');
    // if ($('.datatableshow').length) setDataTable('.datatableshow');
    // // Datatables des onglets
    // for (let i = 1; i < 10; i++) {
    //     if ($('.datatableshow'+i).length) setDataTable('.datatableshow'+i);
    // }

    // init tooltips
    // $('[data-toggle="tooltip"]').tooltip();

    // Confirmation Alert lors de suppression du groupe
    // Cette declaration est dejas utiliser dans MDP differament
    $('.confirmate').confirm({
        title: 'Confirmation',
        content: function () {
                return this.$target.attr('msg')
            },
        buttons: {
            ok: {
                text: 'Oui',
                btnClass: 'btn-default',
                action: function () {
                    var element = this.$target;
                    $.ajax({
                        type: 'GET',
                        url: this.$target.attr('href'),
                        success: function (data) {
                            if (data.success == "true") {
                                element.closest('.dlt-element').remove();
                                if ($('#datatableshow').length) $('#datatableshow').DataTable().ajax.reload();
                                if ($('#datatableshow1').length) $('#datatableshow1').DataTable().ajax.reload();
                                if ($('.datatableshow').length) $('.datatableshow').DataTable().ajax.reload();
                                $.alert(data.msg, 'Confirmation');
                            } else $.alert(data.msg, 'Erreur');
                        },
                        error: function () {
                            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                        }
                    });
                }
            },
            cancel: {
                text: 'Non',
                btnClass: 'btn-default'
            }
        }
    });


    // Save answers from fiche communale
    $(".save-answers").on('click', function () {
        var idgroup = $(this).attr("idgroup");
        $('#tab' + idgroup + ' .form-loading').show();
        $('#tab' + idgroup + ' .answers-well-saved').hide();
        var data = $('#tab' + idgroup + ' form').serialize();
        $.ajax({
            type: $('#tab' + idgroup + ' form').attr("method"),
            url: $('#tab' + idgroup + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function (data) {
                $('#tab' + idgroup + ' .form-loading').hide();
                $('#tab' + idgroup + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#tab' + idgroup + ' .answers-well-saved').hide();
                }, 3500);
            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#tab' + idgroup + ' .answers-alert').html(errorsHtml);
                } else
                    $('#tab' + idgroup + ' .answers-alert').text('La requête n\'a pas abouti');
                $('#tab' + idgroup + ' .answers-alert').show();
                setTimeout(function () {
                    $('#tab' + idgroup + ' .answers-alert').hide();
                }, 10000);
                $('#tab' + idgroup + ' .form-loading').hide();
            }
        });
    });
    // Filter pour afficher les questions (non) répondues seulement
    $(".questions-filter").change(function () {
        var numtab = $(this).attr("numtab");
        var idfiche = $(this).attr("idfiche");
        var etat = $(this).val();
        $('#datatableshow' + numtab).DataTable().ajax.url(racine + "questions/getFicheGroupeQuestionsDT/" + idfiche + "/" + numtab + "/" + etat).load();
    });
    //**********************/
}

function ClonerQuestionnaire(id,lemodule)
{
    //alert(lemodule)
    $.ajax({
        type: 'get',
        url: racine + lemodule + '/' + id,
        success: function (data) {

            $("#add-modal .modal-dialog").addClass("modal-lg");
            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();

              },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function afterClone(data)
{
    $('#add-modal').modal('toggle');
    openQuestionnaireModal(data)
}
// $('#main-modal').on('hidden.bs.modal', function () {
//     if ($('.datatableshow').length) {
//         $('.datatableshow').DataTable().ajax.reload();
//     }else if ($('#datatableshow').length){
//         $('#datatableshow').DataTable().ajax.reload();
//     }
// });

// function resetInitModule() {
//
// }

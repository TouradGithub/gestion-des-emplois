$(document).ready(function () {
    // Ajout du CSRF Token pour les requettes ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Customzing dataTable ajax errors
    $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
        console.log(message);
        $.alert(msg_erreur);
    };

    loading_content = '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

    resetInit();

    $('#main-modal').on('hidden.bs.modal', function () {
        if ($('.datatableshow').length) {
            $('.datatableshow').DataTable().ajax.reload();
        } else if ($('#datatableshow').length) {
            $('#datatableshow').DataTable().ajax.reload();
        }
    });
});


// Get the content from Ajax and show it in a div
function getTheContent(link, container, aftersave = null) {

    $(container).html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + link,
        success: function (data) {
            $(container).html(data);
            if(aftersave) aftersave();
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}


// Init of DataTables
function setDataTable(element) {
    // Data tables to load
    if (!$.fn.dataTable.isDataTable(element) && $(element).length) {
        var colonnes = [];
        var index = [];
        var target;
        var search;
        var visibility = [];
        var showbtn = [];


        if (typeof $(element).attr("export") !== 'undefined') {

            showbtn = ['colvis', 'csv', {extend: 'excel', exportOptions: { columns: ':visible' } }, 'pdf', 'print'];
        }

        if (typeof $(element).attr("index") !== 'undefined') {
            var lists = $(element).attr("index").split(',');
            for (var i = 0; i < lists.length; i++) {
                index.push(parseInt(lists[i]));
            }
        } else {
            index.push(-1);
        }
        if (typeof $(element).attr("hiddens") !== 'undefined') {
            var lists = $(element).attr("hiddens").split(',');
            for (var i = 0; i < lists.length; i++) {
                visibility.push(parseInt(lists[i]));
            }
        }

        var nbr = $(element).attr("nbr");
        if (typeof $(element).attr("nbr") !== 'undefined') {
            nbr = $(element).attr("nbr");
        } else {
            nbr = 10;
        }
        if (typeof $(element).attr("ordre") !== 'undefined') {
            ordre = $(element).attr("ordre");
        }
        else
            ordre = 'asc';
        if (typeof $(element).attr("disableOrder") !== 'undefined') {
            ordering = false;
        }
        else
            ordering = true;
        if (typeof $(element).attr("search") !== 'undefined') {
            search = false;
        } else {
            search = true;
        }
        var lists = $(element).attr("colonnes").split(',');
        for (var i = 0; i < lists.length; i++) {
            colonnes.push({
                'data': lists[i],
                'name': lists[i]
            });
        }
        target = 'targets:' + index;

        oTable = $(element).DataTable({
            oLanguage: {
                sUrl: racine + "vendor/datatables/datatable-fr.json",
            },
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "orderCellsTop": true,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tous"]],
            "bDestroy": true,
            "cache": false,
            "searching": search,
            "pageLength": nbr,
            "iDisplayLength": nbr,
            "ordering": ordering,
            "order": [[0, ordre]],
            "columnDefs": [{
                orderable: false,
                targets: index,

            },
            {
                searchable: false,
                targets: index,

            }
            ],
            "aoColumnDefs": [{ "bVisible": false, "aTargets": visibility }],
            "ajax": $(element).attr("link"),
            "columns": colonnes,
            "drawCallback": function (row, data) {
                // init tooltipsopenFormAddInModal
                if ($(".status-check").length) {
                    $('.status-check').bootstrapToggle({
                        on: 'Présent',
                        off: 'Absent'
                    });
                }
                // init tooltips
                $('[data-toggle="tooltip"]').tooltip();
                $('.delete').confirm({
                    title: 'Confirmation',
                    content: 'Êtes-vous sûr de vouloir supprimer cet élément',
                    buttons: {
                        ok: {
                            text: 'Oui',
                            btnClass: 'btn-default',
                            action: function () {
                                $.ajax({
                                    type: 'GET',
                                    url: this.$target.attr('href'),
                                    success: function (data) {
                                        if (data.success == "true") {
                                            //alert($(element).attr("datatable_name"));
                                            $('#datatableshow').DataTable().ajax.reload()
                                            $.alert(data.msg, 'Elément supprimé');
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
                resetInit();
            },
            dom: 'Blfrtip',
            buttons:
                showbtn

            /*,
            "fnDrawCallback": function() {
                //bind the click handler script to the newly created elements held in the table
                $('.flagsmileysad').bind('click',flagsmileysadclick(1));
            }*/
        })
            .on('click', 'tr', function (event) {
                var rowData = oTable.row(this).data();
                //$(this).css('background', 'red');
                ///alert(rowData.id);
                //alert( 'You clicked on '+id+'\'s row' );
                //flagsmileysadclick( $(this).children('.test').attr('test'));
                //alert(event.target);
            }).on('dblclick', 'tr', function (event) {
                //var rowData = oTable.row( this ).data();
                alert(rowData.id);
                $(this).css('background', 'red');
            });
    }
}

function openObjectModal(id, lemodule, datatableshow = "#datatableshow", modal = "main", tab = 1, largeModal = 'lg') {

    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {
            container = "#" + modal + '-modal';
            $(container + " .modal-dialog").addClass("modal-" + largeModal);
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setMainTabs(tab, container);
            $(datatableshow).DataTable().ajax.url($(datatableshow).attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function openObject(id, lemodule, datatableshow = "#datatableshow", container = "result", tab = 1) {

    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {
            $(container).html(data);
            setMainTabs(tab, container, false);
            $(datatableshow).DataTable().ajax.url($(datatableshow).attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function openFormAddInModal(lemodule, id = false, static = true) {
    if (id != false)
        url = racine + lemodule + '/add/' + id;
    else
        url = racine + lemodule + '/add/';
    $('.btn-add').attr('disabled', 'disabled');
    $('.btn-add .main-icon').hide();
    $('.btn-add .spinner-border').show();
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#add-modal .modal-dialog").addClass("modal-lg");
            $("#add-modal .modal-header-body").html(data);
            if (static)
                $("#add-modal").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            else
                $("#add-modal").modal();
            $('.btn-add').removeAttr('disabled');
            $('.btn-add .spinner-border').hide();
            $('.btn-add .main-icon').show();
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function setMainTabs(tab = 1, container = '') {
    element = container + " .main-tabs a[data-toggle='tab']";
    $(element).on('click', function () {
        link = $($(this)).attr("link");
        href = $($(this)).attr("href");
        id = $($(this)).attr("id");
        getTheContent(link, href)
        // if (typeof $($(this)).attr("aftersave") !== 'undefined') {
        //     getTheContent(link, href, function () {
        //         $($(this)).attr("aftersave");
        //     });
        // }
        // else {
        //     $($(this)).attr("aftersave");
        // }
    });
    $('#link' + tab).trigger('click');
}
function addObject(element, lemodule, datatable = "#datatableshow", modal = "main", tab = 1, largeModal = 'lg') {
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            $('#add-modal').modal('toggle');
            openObjectModal(id, lemodule, datatable, modal, tab, largeModal);
        }, 1500);
    });

}

function saveform(element, aftersave = null,containers=null) {
    if(containers != null)
        var container =containers;
    else
        var  container = $(element).attr('container');
    $('#' + container + ' #form-errors').hide();
    $(element).attr('disabled', 'disabled');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
    var data = $('#' + container + ' form').serialize();
    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);
            //$('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
            if (aftersave) {
                aftersave(data);
            }
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
            } else {
                alert(msg_erreur);
            }
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .main-icon').show();
            $(element).removeAttr('disabled');
        }
    });
}


function saveform_image(element, aftersave = null , cont=null , contente_img=null) {
    if(cont != null)
        var container =cont;
    else
    var  container = $(element).attr('container');

    $('#' + container + ' #form-errors').hide();
    $(element).attr('disabled', 'disabled');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
    if(contente_img == null){
    alert(container);
     var data = $('#' + container + ' form').serialize();
    }
    else
        var data =new FormData($('#' + container + ' form')[0]);
    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (data) {

            console.log(data);
            //$('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
            if (aftersave) {
                aftersave(data);
            }
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
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .main-icon').show();
            $(element).removeAttr('disabled');
        }
    });
}


function confirmAction(link, text, aftersave = null) {
    $.confirm({
        title: 'Confirmation',
        content: text,
        buttons: {
            confirm: function () {
                $.ajax({
                    type: 'GET',
                    url: link,
                    success: function (data) {
                        if (data.success == "true") {
                            $.dialog(data.msg, 'Confirmation');
                            if (aftersave) {
                                aftersave(data);
                            }
                        }
                        else
                            $.dialog(data.msg, 'Erreur');
                    },
                    error: function () {
                        $.dialog(msg_erreur);
                    }
                });
            },
            close: function () {
            }
        }
 });
}

function deleteObject(link, text, datatableshow = "#datatableshow") {
    // alert(datatableshow);
    confirmAction(link, text, function () {
        $(datatableshow).DataTable().ajax.reload();
    });
}

// updating a group des elements
function updateGroupeElements(btn = null) {
    //$('[data-toggle="tooltip"]').tooltip('dispose');
    var elements = $(".group-elements").sortable('toArray');
    //$('datatableshow').DataTable().ajax.reload();
    //var childscount = $(".group-elements li").length;
    var idgroup = $(".group-elements").attr('idgroup');
    var datatble = $(".group-elements").attr('datatable-source');
    var lien = $(".group-elements").attr('lien');
    if (btn) {
        if ($(btn).hasClass("close")) {
            elements = jQuery.grep(elements, function (value) {
                return value != $(btn).parent().attr('id');
            });
            $(btn).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(btn).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            elements.push($(btn).attr('idelt'));
        }
    }
    if (elements.length)
        var qsts = elements.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + qsts + '/' + idgroup;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (btn) {
                if ($(btn).hasClass("close"))
                    $(btn).parent().remove();
                else {
                    var idelt = $(btn).attr('idelt');
                    var libelle = $(btn).attr('libelle');
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
                }
            }
            $(datatble).DataTable().ajax.reload();
        },
        error: function () {
            if (btn) {
                if ($(btn).hasClass("close"))
                    $(btn).html('&times;');
                else {
                    $(btn).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert(msg_erreur);
        }
    });
}

// update grouping quetionnaires
function updateGroupeElementsNew(element = null, container = ".group-elements") {
    var questions = $(container).sortable('toArray');
    var childscount = $(container + "li").length;
    var idgroup = $(container).attr('idgroup');
    var datatble = $(container).attr('datatable-source');
    var lien = $(container).attr('lien');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + qsts + '/' + idgroup;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(container).append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElementsNew(this)">&times;</button></li>');
                    $(container).append()
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            $(datatble).DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function resetInit() {
    // Call resetInit of the module
    if ($.isFunction(window.resetInitModule)){
      resetInitModule();
    }
    // init du select picker
    $('.selectpicker').selectpicker({
        size: 10
    });

    // Basic Select2 select
    $(".select2").select2({
        // the following code is used to disable x-scrollbar when click in select input and
        // take 100% width in responsive also
        // dropdownAutoWidth: true,
        placeholder: "Select a state",
        width: '100%'
    });

    //Grouping
    $(".group-elements").sortable({
        axis: 'y',
        update: function (event, ui) {
            updateGroupeElements();
        }
    });
    // Image uploader
    $(".fileinput").fileinput();

    // Datatables to load General
    if ($('#datatableshow').length) setDataTable('#datatableshow');
    if ($('#datatableshow_ind').length) setDataTable('#datatableshow_ind');
    if ($('#datatableshow_ged').length) setDataTable('#datatableshow_ged');

    if ($('.datatableshow').length) setDataTable('.datatableshow');
    // Datatables des onglets
    for (let i = 1; i < 10; i++) {
        if ($('.datatableshow' + i).length) setDataTable('.datatableshow' + i);
        if ($('#datatableshow' + i).length) setDataTable('#datatableshow' + i);
        if ($('.datatableshow_ind' + i).length) setDataTable('.datatableshow_ind' + i);
        if ($('#datatableshow_ind' + i).length) setDataTable('#datatableshow_ind' + i);
    }

    // init tooltips
    $('[data-toggle="tooltip"]').tooltip();

}

(function ($, window) {
    'use strict';
    var MultiModal = function (element) {
        this.$element = $(element);
        this.modalCount = 0;
    };
    MultiModal.BASE_ZINDEX = 1040;
    MultiModal.prototype.show = function (target) {
        var that = this;
        var $target = $(target);
        var modalIndex = that.modalCount++;
        $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);
        window.setTimeout(function () {
            if (modalIndex > 0)
                $('.modal-backdrop').not(':first').addClass('hidden');

            that.adjustBackdrop();
        });
    };
    MultiModal.prototype.hidden = function (target) {
        this.modalCount--;

        if (this.modalCount) {
            this.adjustBackdrop();

            // bootstrap removes the modal-open class when a modal is closed; add it back
            $('body').addClass('modal-open');
        }
    };

    MultiModal.prototype.adjustBackdrop = function () {
        var modalIndex = this.modalCount - 1;
        $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
    };

    function Plugin(method, target) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('multi-modal-plugin');

            if (!data)
                $this.data('multi-modal-plugin', (data = new MultiModal(this)));

            if (method)
                data[method](target);
        });
    }

    $.fn.multiModal = Plugin;
    $.fn.multiModal.Constructor = MultiModal;

    $(document).on('show.bs.modal', function (e) {
        $(document).multiModal('show', e.target);
    });

    $(document).on('hidden.bs.modal', function (e) {
        $(document).multiModal('hidden', e.target);
    });
}(jQuery, window));

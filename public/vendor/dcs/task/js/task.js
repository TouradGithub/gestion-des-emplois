function getLists(id) {
    getTheContent('tasks/getLists/' + id, '#lists');
    $("#membersCntAdd").html('');
}

function getListOnAdminPartie(id) {
    getTheContent('tasks/getListOnAdminPartie/' + id, '#lists');
    $("#membersCntAdd").html('');
}

function getListOnAdminPartieFunctionaite(id) {
    getTheContent('tasks/getListOnAdminPartieFunctionaite/' + id, '#lists');
    $("#membersCntAdd").html('');
}

function listMemebers(id) {
    getTheContent('tasks/lists/members/' + id, '#membersCntAdd');
}
function listMemebersPartieAdmin(id) {
    getTheContent('tasks/admin/lists/members/' + id, '#membersCntAdd');
}

function listMemebersPartieAdminModalFunctionality(id) {
    getTheContent('tasks/tab-f/lists/members/' + id, '#membersCntAdd');
}

function afterSave() {
    $('#main-modal').modal('hide');
    $("#refreshBtn").click();
}

function afterEdit() {
    $('#main-modal').modal('hide');
    $("#refreshBtn").click();
}


function addMembers() {
    openInModal('tasks/effectMembers', null, 'second')
}

function getLibelle(el) {
    var text = $("#type_add option[value='" + el + "']").text();
    text = text.replace(/^\s+/g, '');
    // if ($("#libelle_fr").val() == '') {
        $("#libelle_fr").val($.trim(text));
    // }
    var is_heure = $("#type_add option[value='" + el + "']").data('heure');
    if (is_heure === 1) {
        $('.form_date_time').show();
        $('.form_date_ni_heure').hide();
    } else {
        $('.form_date_time').hide();
        $('.form_date_ni_heure').show();
    }

}

$('[data-toggle-second="tooltip"]').tooltip();
$('#newTaskModal').on('shown.bs.modal', function (e) {
    console.log('modal shown');
//     $('.daterange').datepicker({
//         format: 'mm/dd/yyyy',
//         autoclose: true,
//         todayHighlight: true,
//         container: 'modal-body'
//     })
});

function changeRef(task_id, objet_id, type) {
    $.ajax({
        url: racine + "tasks/changeRef",
        type: "POST",
        data: {
            task_id: task_id,
            objet_id: objet_id,
            type,
        },
        success: function () {
            $('#datatableshow').DataTable().ajax.reload();
        },
    });
}

$(document).ready(function() {
    $('.daterangepicker').daterangepicker();
});


function genererOrdre()
{
    $('.generer-ordre .form-loading').show();
    $('.generer-ordre .answers-well-saved').hide();
    var data = $('.generer-ordre form').serialize();
    $.ajax({
        type: $('.generer-ordre form').attr("method"),
        url: $('.generer-ordre form').attr("action"),
        data: data,
        dataType: 'json',
        success: function(data){
            // console.log(data);
            // $('#questionnaire-elements').html(data);
            $('#childrentree').jstree("refresh");
            $('.generer-ordre .form-loading').hide();
            $('.generer-ordre .answers-well-saved').show();
            setTimeout(function(){
                $('.generer-ordre .answers-well-saved').hide();
            }, 3500);
        },
        error: function(data){
            if( data.status === 422 ) {
                var errors = data.responseJSON;
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each( erreurs, function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('.generer-ordre #form-errors').html(errorsHtml);
            } else
                $('.generer-ordre #form-errors').text('La requête n\'a pas abouti');
            $('.generer-ordre #form-errors').show();
            setTimeout(function(){
                $('.generer-ordre #form-errors').hide();
            }, 10000);
            $('.generer-ordre .form-loading').hide();
        }
    });
}

function setTreeView(element = '#childrentree') {
    if ($(element).length){
        $(element).jstree({
            'core' : {
                "themes":{
                    "icons":false
                },
                "dblclick_toggle": false,
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return racine+'qst/questionnaires/getChildren/'+$(element).attr('idqst');
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };
                    },
                    dataType: "json",
                }
            },
        });
        $(element).delegate("a","dblclick", function(e) {
            var idn = $(this).parent().attr("id");
            getInfoChild(idn,0);
        });
    }
}

function selectDeclancheur(element) {
    var ismultiple = $('option:selected', element).attr('ismultiple');
    if (ismultiple==1)
        $('#label-reponse-renvoi').html('Si le nombre de choix séléctionnés est supérieur ou égal à: <span class="required_field">*</span>');
    else
        $('#label-reponse-renvoi').html('Si la réponse de cette question est égale à: <span class="required_field">*</span>');
    $.ajax({
        type: 'GET',
        url: racine+"qst/questionnaires/getRenvoiTargets/"+$(element).val(),
        success: function (data) {
            $('#renvoi-target').html(data.targets);
            $('#renvoi-input').html(data.input);
            $('.selectpicker').selectpicker('refresh');
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function getInfoChild(id,niveau=0) {
    if($('#have_renvoi').val()==0){
        var link = (!niveau) ? racine+"qst/questionnaires/getInfoChild/"+id : racine+"qst/questionnaires/getInfoChild/"+id+"/"+1;
        $.ajax({
            type: 'GET',
            url: link,
            success: function (data) {
                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function updateChildElements(element = null) {
    if($("#infochild-elements").length){
        var elements = $("#infochild-elements").sortable('toArray');
        var idqst = $("#infochild-elements").attr('idqst');
        var isroot = $("#infochild-elements").attr('isroot');
        var link = racine+"qst/questionnaires/updateChildren";
        $('#second-modal .form-loading').show();
        $('#second-modal .answers-well-saved').hide();
        $.ajax({
            data: {list:elements,id:idqst,isroot:isroot},
            type: 'POST',
            url: link,
            success: function (data) {
                $('#infochild-elements').html(data);
                $('#second-modal .form-loading').hide();
                $('#second-modal .answers-well-saved').show();
                $('#childrentree').jstree("refresh");
                setTimeout(function(){
                    $('#second-modal .answers-well-saved').hide();
                }, 3500);
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function addrenvoi(element) {
    saveform(element, function(id){
        getTheContent('qst/questionnaires/getTab/' + id + '/3', '#tab3');
    });
}

function deleteRenvoi(id) {
    var link = racine + "qst/questionnaires/renvois/delete/"+id;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            getTheContent('qst/questionnaires/getTab/' + data + '/3', '#tab3');
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez rÃ©essayer ou actualiser la page!");
        }
    });
}

function resetInitModule() {
    setTreeView();
    if ($("#infochild-elements").length) {
        $("#infochild-elements").sortable({
            axis: 'y',
            update: function (event, ui) {
                updateChildElements();
            }
        });
    }
    updateChildElements();
}



$(function () {
    var CurrentNode;
    type_projet = $('#type_projet').val();
    if($('#questionnaire_id').val() != "")
        questionnaire_id = $('#questionnaire_id').val();
    else
        questionnaire_id = "null";
    $('#groupesTree')
        .on('changed.jstree', function (e, data) {
            var i, j, r = [], s = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                // r.push(data.instance.get_node(data.selected[i]).text);
                CurrentNode = data.selected[0];
                console.log(data.selected[0]);

            }
            //node = data.node;
            //alert(node.id);
            /* var i, j, r = [];
             for(i = 0, j = data.selected.length; i < j; i++) {
                 r.push(data.instance.get_node(data.selected[i]).data);
             }
             $('#event_result').html('Selected: ' + r.join(', '));*/
            //$(this).jstree(true).refresh();
        })
        .jstree({
            'core' : {
                "themes":{
                    "icons":false
                },
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return racine+'qst/groupes/get_groupes/'+type_projet+"/"+questionnaire_id;
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };

                    },
                    dataType: "json",
                }
            }
        })
        .on("select_node.jstree", function (e, data) {
            var id = data.node.id;
            getTheContent('qst/groupes/getActionGroupe/'+id+"/"+questionnaire_id, '#actions');
        });
});






// updating a group des elements
// function updateGroupeElements(btn = null) {
//     // $('[data-toggle="tooltip"]').tooltip('dispose');
//     var elements = $(".group-elements").sortable('toArray');
//     //$('datatableshow').DataTable().ajax.reload();
//     //var childscount = $(".group-elements li").length;
//     var idgroup = $(".group-elements").attr('idgroup');
//     var datatble = $(".group-elements").attr('datatable-source');
//     var lien = $(".group-elements").attr('lien');
//     if (btn) {
//         if ($(btn).hasClass("close")) {
//             elements = jQuery.grep(elements, function (value) {
//                 return value != $(btn).parent().attr('id');
//             });
//             $(btn).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
//         } else {
//             $(btn).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
//             elements.push($(btn).attr('idelt'));
//         }
//     }
//     // alert($(btn).attr('idelt'));
//
//     if (elements.length)
//         var qsts = elements.join();
//     else
//         var qsts = 0;
//     var link = racine + lien + "/" + qsts + '/' + idgroup;
//     $.ajax({
//         type: 'GET',
//         url: link,
//         success: function (data) {
//             if (btn) {
//                 if ($(btn).hasClass("close"))
//                     $(btn).parent().remove();
//                 else {
//                     var idelt = $(btn).attr('idelt');
//                     var libelle = $(btn).attr('libelle');
//                     $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
//                 }
//             }
//             $(datatble).DataTable().ajax.reload();
//         },
//         error: function () {
//             if (btn) {
//                 if ($(btn).hasClass("close"))
//                     $(btn).html('&times;');
//                 else {
//                     $(btn).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
//                 }
//             }
//             $.alert(msg_erreur);
//         }
//     });
// }

function addGroupe(element) {
    saveform(element, function (id) {
        $('#main-modal').modal('toggle');
        $('#groupesTree').jstree("refresh");

    });
}

function editGroupe(element)
{
    saveform(element, function (id) {
        $(element).attr('disabled', 'disabled');
        $('#groupesTree').jstree(true).refresh();
    });
}

function deleteGroupe(link, text) {
    confirmAction(link, text, function () {
        $('#groupesTree').jstree("refresh");
    });
}

function openContentInModal(link, modal = 'main', modalsize = 'lg', aftersave = null) {
    $.ajax({
        type: 'get',
        url: link,
        success: function (data) {
            $("#" + modal + "-modal .modal-header-body").html(data);
            $("#" + modal + "-modal .modal-dialog").addClass("modal-" + modalsize);
            $("#" + modal + "-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            resetInit();
            if (aftersave)
                aftersave();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function AddAndReload(element,lien)
{
    saveform(element, function (id) {
        window.location.href = racine + lien;

    });
}

function changeProgrammeActive(id_prog){
    // alert(id_prog);
    type_projet = $('#type_projet').val();

    msg = "Etes vous sûr de vouloir ouvrir ce programme ?";
    var confirme = confirm(msg);
    if(confirme){
        url = racine + 'qst/groupes/changeProgrammeActive/'+ id_prog+"/"+type_projet;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                window.location.href = racine+'qst/groupes/'+type_projet;

                resetInit();
            },
            error: function () {
                $.alert(msg_erreur);
            }
        });
    }
}

function changeEtatProgramme(id_questionnaire,etat,msg){
    // alert(id_prog);
    type_projet = $('#type_projet').val();

    // msg = "Etes vous sûr de vouloir ouvrir ce programme ?";
    var confirme = confirm(msg);
    if(confirme){
        url = racine + 'qst/groupes/changeEtatProgramme/'+ id_questionnaire+"/"+etat;
        $.ajax({
            type: 'get',
            url: url,
            success: function (data) {
                window.location.href = racine+'qst/groupes/'+type_projet;

                resetInit();
            },
            error: function () {
                $.alert(msg_erreur);
            }
        });
    }
}

function addNewLigneGroupeCollection(groupe_id,qst_evaluation_id)
{
    $.ajax({
        type: 'GET',
        url: racine + 'qst/questions/addNewLigneGroupeCollection/'+groupe_id+'/'+qst_evaluation_id,
        success: function(data) {
            $('#btn_add_new_ligne_collection_'+groupe_id).hide();
            $('#groupe_collection_'+groupe_id).append(data);
            resetInit();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function deleteReponseGroupeCollection(qst_evaluation_id,groupe_id,sequence_collection,msg)
{
    if(confirm(msg)){
        $.ajax({
            type: 'GET',
            url: racine + 'qst/questions/deleteReponseGroupeCollection/'+qst_evaluation_id+'/'+groupe_id+'/'+sequence_collection,
            success: function(data) {
                $('#row_'+groupe_id+'_'+sequence_collection).remove();
                if ($.isFunction(window.afterQstAnswerSaving)) {
                    afterQstAnswerSaving(qst_evaluation_id);
                }
                getTheContent('qst/questions/listeDataGroupeCollection/'+qst_evaluation_id+'/'+groupe_id,'#groupe_collection_'+groupe_id);
            },
            error: function() {
                console.log('La requête n\'a pas abouti');
            }
        });
    }
}

function saveNewGroupeCollectionReponse(element)
{
    saveform(element, function (data) {
        let link = 'qst/questions/listeDataGroupeCollection/'+data.qst_evaluation_id+'/'+data.groupe_id;
        // alert(link)
        getTheContent(link,'#groupe_collection_'+data.groupe_id);
        $('#btn_add_new_ligne_collection_'+data.groupe_id).show();
        if ($.isFunction(window.afterQstAnswerSaving) && data.qst_evaluation_id) {
            afterQstAnswerSaving(data.qst_evaluation_id);
        }
    });
}

function saveReponseGlobal(qst_champ_id,msg_validation,qst_evaluation_id, aftersave = null)
{
    $('#champs_'+qst_champ_id).removeClass('is-invalid');
    if($('#champs_'+qst_champ_id+'-helper').length) $('#champs_'+qst_champ_id+'-helper').remove();
    if($('#champs_'+qst_champ_id).prop('required') && $('#champs_'+qst_champ_id).val() == "")
        alert(msg_validation);
    else {
        if ($('#champs_' + qst_champ_id).is(':radio'))
            reponse = $("input:radio[name=champs_" + qst_champ_id + "]:checked").val();
        else if($('#champs_' + qst_champ_id).is(':checkbox')){
            reponse = $("input:checkbox[name=champs_" + qst_champ_id + "]:checked").val();
            // alert(reponse)
        }
        else
            reponse = $('#champs_' + qst_champ_id).val();
        if (reponse)
            reponse = reponse;
        else
            reponse = 'none';
        //verifier la reponse du choix "Autre a preciser"
        is_other = 0;
        // console.log($('#champs_'+qst_champ_id).prop('type'))
        if($('#champs_'+qst_champ_id).prop("tagName").toLowerCase() != 'textarea'){
            if (reponse instanceof Array){
                for (let i = 0; i < reponse.length; i++) {
                    var is_other = $('#option_'+reponse[i]).attr('is_other'); //has error with appostroph
                }
            }
            else{
                var is_other = $('#option_'+reponse.replace(/'/g, "\\'")).attr('is_other'); //has error with appostroph
            }
        }
        other_reponse = 'none';
        if(is_other == 1){
            // alert('#option_'+reponse+"//"+id_qst);
            if(is_other && $('#is_other_'+qst_champ_id).is(':visible') && $('#is_other_'+qst_champ_id).val() == ""){
                alert("Veiller saisir la réponse du choix Autres (à préciser) !!");
                return false;
            }else if(is_other && $('#is_other_'+qst_champ_id).val() == "" && $('#is_other_'+qst_champ_id).is(':hidden')){
                $('#is_other_'+qst_champ_id).show();
                $('#is_other_'+qst_champ_id).focus();
                return false;
            }
            else{
                other_reponse = $('#is_other_'+qst_champ_id).val().replace(/\//g, "-");
            }
        }else{
            $('#is_other_'+qst_champ_id).hide();
        }

        var container = 'btnSave_champ_'+qst_champ_id;
        $('#' + container + ' .main-icon'+ qst_champ_id).hide();
        $('#' + container + ' .spinner-border'+ qst_champ_id).show();
        $.ajax({
            type: 'get',
            url: racine + 'qst/questions/saveReponseGlobal/'+qst_evaluation_id+"/"+qst_champ_id+"/"+reponse+"/"+other_reponse,
            success: function (data) {
                // updateMehnetiProgressBar(data.progress);
                // $('#' + container + ' .spinner-border'+ id_qst).hide();
                $('#' + container + ' .spinner-border'+ qst_champ_id).hide();
                $('#' + container + ' .answers-well-saved'+ qst_champ_id).show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved'+ qst_champ_id).hide();
                    $('#' + container + ' .main-icon'+ qst_champ_id).show();
                },1500);
                $('.btnSave'+qst_champ_id).css("color","#2ece1b;");
                $('.td_'+qst_champ_id).css("background-color","#b8fbb8");
                $('.btnSave'+qst_champ_id).removeClass("btn-secondary");
                $('.btnSave'+qst_champ_id).addClass("btn-success");
                // $('.btnSave'+data.id_ques).show();

                if (data.questions_to_show.length > 0){
                    var lists = data.questions_to_show;
                    for (var i = 0; i < lists.length; i++) {
                        $('#qst_row_'+lists[i]).show();
                        $('.btnSave'+lists[i]).removeClass("btn-success");
                        $('.btnSave'+lists[i]).addClass("btn-secondary");
                        var tagName = $('#champs_'+lists[i])[0].tagName;
                        $('#champs_'+lists[i]).val('');
                        if(tagName == "SELECT"){
                            $('.selectpicker').selectpicker('refresh');
                        }
                        else if (tagName == "INPUT" && $('#champs_' + lists[i]).prop('type') == 'radio') {
                            $('.form_radio_champs_' + lists[i]).removeAttr('checked');
                        }
                        else if (tagName == "INPUT" && $('#champs_' + lists[i]).prop('type') == 'checkbox') {
                            $('.input_checkbox_champs_' + lists[i]).removeAttr('checked');
                        }
                    }
                };
                if (data.questions_to_hide.length > 0){
                    var lists = data.questions_to_hide;
                    for (var i = 0; i < lists.length; i++) {
                        $('#qst_row_'+lists[i]).hide();
                    }
                };
                if (data.groupes_to_show.length > 0){
                    var lists = data.groupes_to_show;
                    for (var i = 0; i < lists.length; i++) {
                        $('#groupe_row_'+lists[i]).show();
                        $('#btn_save_all_reponses_groupe_'+lists[i]).show();
                    }
                };
                if (data.groupes_to_hide.length > 0){
                    var lists = data.groupes_to_hide;
                    for (var i = 0; i < lists.length; i++) {
                        $('#groupe_row_'+lists[i]).hide();
                        $('#btn_save_all_reponses_groupe_'+lists[i]).hide();
                    }
                };
                if (data.champs_to_update.length > 0){
                    var lists = data.champs_to_update;
                    for (var i = 0; i < lists.length; i++) {
                        getReponseQuestionAndUpdateInput(data.qst_evaluation_id,lists[i])
                    }
                };
                if(aftersave){
                    aftersave(qst_evaluation_id);
                }
                // data.progress_questionnaire
                // data.progress_groupe_direct
                // data.progress_first_groupe_level
                // data.groupe_direct_id
                // data.groupe_first_level_id
                // data.qst_questionnaire_id
                if($('.icon_check_groupe_' + data.groupe_direct_id).length && data.progress_groupe_direct == 100) $('.icon_check_groupe_' + data.groupe_direct_id).show();
                if($('.icon_check_groupe_' + data.groupe_first_level_id).length && data.progress_first_groupe_level == 100) $('.icon_check_groupe_' + data.groupe_first_level_id).show();
                if ($.isFunction(window.afterQstAnswerSaving) && data.qst_evaluation_id) {
                    afterQstAnswerSaving(data.qst_evaluation_id);
                }
                // if (data.saute == 1)
                //     $('.td_'+qst_champ_id).css("background-color","#f5ede6;");
                // if (data.non_repondu == 1)
                //     $('.td_'+qst_champ_id).css("background-color","#f3f3ff;");
                // if(data.progress_questionnaire){
                //     $('.ms-progress').html(data.progress_questionnaire);
                //     getTheContent('programmeMpma/progress_view_pme/'+data.qst_evaluation_id,'#progress_view');
                // }
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors.errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors;
                    $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                        $('#'+key).addClass('is-invalid');
                        $('#'+key).after('<small id="'+key+'-helper" class="form-text text-danger">' + value + '</small>');
                    });
                    errorsHtml += '</ul></div>';
                    $( '#divReponses #form-errors'+ qst_champ_id ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#divReponses .form-loading"+ qst_champ_id).hide();
                $('#' + container + ' .main-icon'+ qst_champ_id).show();
                $('#' + container + ' .spinner-border'+ qst_champ_id).hide();
            }
        });
    }
}

function onReponseChange(qst_champ_id){
    // trigger click on save button
    $('#btnSave_champ_'+qst_champ_id).trigger('click');
}

function selectNatureChamp(element) {
    var link = 'qst/questions/onSelectNatureChamp/' + $(element).val();
    getTheContent(link, '#changeOnSelectNatureChamp');
}

function filtrerQuestions(type_project) {
    var nature_champ = $("#nature_champ_filter").val();
    var type = $("#type_filter").val();
    var structure = $("#structure").val();
    var niveau_geo= $("#niveau_geo").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'qst/questions/getDT/'+type_project + "/" + nature_champ + "/" + type+"?niveau="+niveau_geo+"&structure="+structure).load();

}

function addChampValeur(element, lemodule, datatable = "#datatableshow", modal = "add-main", tab = 1, largeModal = 'lg') {
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        $(element).attr('disabled', 'disabled');
        var modals = modal.split('-');
        var add = modals[0];
        var main = modals[1];
        setTimeout(function () {
            $('#'+add+'-modal').modal('toggle');
            // openObjectModal(id, lemodule, datatable, main, tab, largeModal);
        }, 1000);
    });
}

function getQuestions(element,question_id) {
    let el = $(element);
    getTheContent(  'qst/questions/getQuestion/' + el.val()+"/"+question_id, '#questionsCnt');

}
function getQuestionConditions(element) {
    let el = $(element);
    getTheContent(  'qst/questions/getQuestionConditions/' + el.val(), '#conditionsCnt');
    $('#valuesCnt').html('');
}
function getQuestionOptions(val) {
    let qst_question_val = $("#qst_question_val").val();
    if (val>=3) {
        getTheContent(  'qst/questions/questionOptions/'+qst_question_val, '#valuesCnt');
    }else {
        $('#valuesCnt').html('');
    }
}
function afterConditionSave(element) {
    saveform(element, function (id) {
        $('.datatableshow3').DataTable().ajax.reload();
        $('#cndModalBtn').trigger('click');
    });
}

function saveValCible(element,datatable=".datatableshow8") {
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        $('#add-modal').modal('toggle');
        // setMainTabs(4, '#main-modal');

    });
}

function saveSituationRef(element,datatable=".datatableshow8") {
    var link = id;
    tab = link.split("link");
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        $('#add-modal').modal('toggle');
        setMainTabs(tab[1], '#main-modal');

    });
}

function checkAllQtions(element){
    if ($(element).is(':checked')) {
        $('.btnsRepondreHistoriques').show();
    }
    else
    {
        var str = 0;
        $(':checkbox').each(function() {
            str += this.checked ? 1 : 0;
        });
        if (str == 0) {
            $('.btnsRepondreHistoriques').hide();
        }
        else
            $('.btnsRepondreHistoriques').show();
    }
}


function getFormEvaluation(){
    var questions_id = [];
    $("input:checked").each(function () {
        var id = $(this).val();
        questions_id.push(id);
    });
    openInModal(racine + 'qst/questions/getFormEvaluation/' + questions_id,null,'add','xl');
    /*$.ajax({
        type: 'get',
        url: racine + 'qst/questions/getFormEvaluation/' + questions_id,
        success: function (data) {
            $("#add-modal .modal-header-body").html(data);
            $("#add-modal .modal-dialog").addClass("modal-xl");
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });*/

}

function filtrerEvaluation(type_project) {
    niveau = $("#niveau").val();
    $('.btnsRepondreHistoriques').hide();
    var link=racine + 'qst/questions/getDTEvaluations/'+type_project + "/" + niveau+"?structures="+$("#structures").val()+"&aggregation="+$("#aggregation").val();
    $('#datatableshow').attr('link',link);
    $('#datatableshow').DataTable().ajax.url(link).load();
}

function  getReponsesWilayas(){
    var niveau = $('#niveau').val();
    var territoires = $('#wilayaa').val();
    var ids_questions = $('#ids_questions').val();
    var niveau_geo = $('#niveau_geo').val();
    var type_objet = $('#type_objet').val();
    if (niveau == 3) { //wilaya
        if (territoires.length <= 3)
            var type = 1;
        else
            var type = 0;
        if (territoires == "")
            var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
        else
            var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
        $.ajax({
            type: 'get',
            url: racine + link,
            success: function (data) {
                $(".div_repondre").empty();
                $(".div_repondre").html(data);
                $("#btnsaveanswer").show();
                $(".datedefault").show();
                $("#territoires_id").val(territoires);
                $('.selectpicker').selectpicker('refresh');

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else{
        /*if (niveau_geo != 0) {
            $.ajax({
                type: 'get',
                url: racine + 'getObjets/' + territoires + '/' + type_objet,
                success: function (data) {
                    $("#objet").html(data);
                    // $("#comm").html(data.commune);
                    $(".div_repondre").empty();
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });

        }
        else{*/
        // alert("mukattas");
        $.ajax({
            type: 'get',
            url: racine + 'qst/questions/wilayas/getmoughataas/' + territoires + '/' + niveau,
            success: function (data) {
                $("#moughataas").html(data.mukattas);
                $("#comm").html(data.commune);
                $(".div_repondre").empty();
                if (niveau==6){
                    $("#objets_geo").html(data.objets_geo);
                }
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        // }
    }
    resetInit();
}
function getObjetsGeoByWilaya(questions_id) {
    var wilaya = $('#wilayaa').val();
    $.ajax({
        type: 'get',
        url: racine + 'qst/questions/getObjetsGeoByWilaya/' + wilaya +'/' + questions_id,
        success: function (data) {
            $("#objets_geo").html(data);
            $('.selectpicker').selectpicker('refresh');
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function  getReponsesMoughataas(){
    var niveau = $('#niveau').val();
    var territoires = $('#moughataas').val();
    var wilaya = $('#wilayaa').val();
    var ids_questions = $('#ids_questions').val();
    var niveau_geo = $('#niveau_geo').val();
    var type_objet = $('#type_objet').val();
    if (niveau == 2) {
        if (territoires.length <= 3)
            var type = 1;
        else
            var type = 0;
        if (territoires == "")
            var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
        else
            var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
        $.ajax({
            type: 'get',
            url: racine + link,
            success: function (data) {
                $(".div_repondre").empty();
                $(".div_repondre").html(data);
                $("#btnsaveanswer").show();
                $(".datedefault").show();
                $("#territoires_id").val(territoires);
                $('.selectpicker').selectpicker('refresh');

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else{
        /*if (niveau_geo != 0) {
            $.ajax({
                type: 'get',
                url: racine + 'getObjets/' + territoires + '/' + type_objet,
                success: function (data) {
                    $("#objet").html(data);
                    // $("#comm").html(data.commune);
                    $(".div_repondre").empty();
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
        else{*/
        $.ajax({
            type: 'get',
            url: racine + 'qst/questions/mukataas/getcommunes/' + territoires +'/'+ wilaya ,
            success: function (data) {
                $("#comm").html(data);
                $(".div_repondre").empty();
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        // }
    }
    resetInit();
}

function  getReponsesCommunes(){
    var niveau = $('#niveau').val();
    var territoires = $('#comm').val();
    var ids_questions = $('#ids_questions').val();
    var niveau_geo = $('#niveau_geo').val();
    var type_objet = $('#type_objet').val();
    if (niveau == 1) {
        if (territoires.length <= 3)
            var type = 1;
        else
            var type = 0;
        // alert(territoires);
        if (territoires == "")
            var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
        else
            var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
        // $('.form-loading').show();
        $.ajax({
            type: 'get',
            url: racine + link  ,
            success: function (data) {
                // alert(data);
                $('.form-loading').hide();
                $(".div_repondre").empty();
                $(".div_repondre").html(data);
                $("#btnsaveanswer").show();
                $(".datedefault").show();
                $("#territoires_id").val(territoires);
                $('.selectpicker').selectpicker('refresh');

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    /*else{
        if (niveau_geo != 0) {
            $.ajax({
                type: 'get',
                url: racine + 'getObjets/' + territoires + '/' + type_objet,
                success: function (data) {
                    $("#objet").html(data);
                    // $("#comm").html(data.commune);
                    $(".div_repondre").empty();
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });

        }
    }*/
    resetInit();
    // alert(ids_questions);
}
function  getReponsesObjetsGeo(){
    var niveau = $('#niveau').val();
    var territoires = $('#objets_geo').val();
    var ids_questions = $('#ids_questions').val();
    var niveau_geo = $('#niveau_geo').val();
    var type_objet = $('#type_objet').val();
    if (niveau == 6) {
        if (territoires.length <= 3)
            var type = 1;
        else
            var type = 0;
        // alert(territoires);
        if (territoires == "")
            var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
        else
            var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
        // $('.form-loading').show();
        $.ajax({
            type: 'get',
            url: racine + link  ,
            success: function (data) {
                // alert(data);
                $('.form-loading').hide();
                $(".div_repondre").empty();
                $(".div_repondre").html(data);
                $("#btnsaveanswer").show();
                $(".datedefault").show();
                $("#territoires_id").val(territoires);
                $('.selectpicker').selectpicker('refresh');

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    /*else{
        if (niveau_geo != 0) {
            $.ajax({
                type: 'get',
                url: racine + 'getObjets/' + territoires + '/' + type_objet,
                success: function (data) {
                    $("#objet").html(data);
                    // $("#comm").html(data.commune);
                    $(".div_repondre").empty();
                    $('.selectpicker').selectpicker('refresh');
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });

        }
    }*/
    resetInit();
    // alert(ids_questions);
}

function openHistotyReponses(id_qst) {
    var niveau = $("#niveau").val();
    if(niveau != 5)
        var territoires_id = $("#territoires_id").val();
    else
        var territoires_id = 'all';
    $.ajax({
        type: 'get',
        url: racine + 'qst/questions/getHistoriqueReponses/' + territoires_id + '/' + niveau + '/' + id_qst,
        success: function (data) {
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal .modal-dialog").addClass("modal-lg");

            $("#second-modal").modal();
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
    // alert(id_qst+"op"+territoires+"op"+niveau);
}

function checkSuiviQuestion()
{
    if(($('#situation_reference').is(':checked')== true) || ($('#valeurs_cibles').is(':checked')== true) || ($('#evaluation').is(':checked')== true))
        $("#divDetails").show();
    else
        $("#divDetails").hide();

}


function getQstPDFExcel(lien)
{
    document.formFilter.action = racine + lien;
    document.formFilter.target = "_blank";    // Open in a new window
    document.formFilter.submit();             // Submit the page
    return true;
}

function selectIsPeriodicite(){
    is_periodicite = $('#is_periodicite').val();
    if(is_periodicite == 1) {
        $('.divFormule').show();
    }
    else{
        $('.divFormule').hide();
    }

}
function selectAgregation(){
    agregation = $('#agregation').val();
    if(agregation == 1) {
        $('.divAgregation').show();
    }
    else{
        $('.divAgregation').hide();
    }

}

function openEvaluationModal(id, lemodule, datatableshow = "#datatableshow", modal = "main", tab = 1, largeModal = 'lg') {
    $('.btnsRepondreHistoriques').hide();
    openObjectModal(id, lemodule, datatableshow = "#datatableshow", modal = "main", tab = 1, largeModal = 'lg');

}

// function changedate(element,terrt=0) {
//     if (terrt) {
//         $("#date_"+element+"_"+terrt).attr('date_changed',1);
//     };
//     $("#date_"+element).attr('date_changed',1);
// }

//changer la date d'enregistrement

function changedateEnre() {
    var date_enreg = $('#date_enreg').val();
    $('.daterep').each(function(){
        // var date_changed = $(this).attr('date_changed');
        // if (date_changed == 0 ) {
        $(this).val(date_enreg);
        // };
    });
}

function getReponseQuestionAndUpdateInput(evaluation_id,champ_id){
    $.ajax({
        type: 'get',
        url: racine + 'qst/questions/getReponseQuestion/' + evaluation_id + '/' + champ_id,
        success: function (data) {
            $('#champs_'+champ_id).val(data);
        },
        // error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
}

function saveGroupeAllAnswersQuestions(element){
    // cont permet de pour voir envoyer le container par parametre
    var  container = $(element).attr('container');
    $('#' + container + ' #form-errors').hide();
    $('#' + container + ' .is-invalid').removeClass('is-invalid');
    $('#' + container + ' small.text-danger').remove();
    $(element).attr('disabled', 'disabled');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
    // var data = $('#' + container + ' form').serialize();
    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: new FormData($('#' + container + ' form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        // dataType: 'json',
        success: function (data) {
            //$('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();

            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
            // if(data.progress_questionnaire){
            //     $('.ms-progress').html(data.progress_questionnaire);
            //     getTheContent('programmeMpma/progress_view_pme/'+data.qst_evaluation_id,'#progress_view');
            // }
            if (data.questions_to_show.length > 0){
                var lists = data.questions_to_show;
                for (var i = 0; i < lists.length; i++) {
                    $('#qst_row_'+lists[i]).show();
                }
                if(tagName == "SELECT"){
                    $('#champs_'+lists[i]).val('');
                    $('.selectpicker').selectpicker('refresh');
                }
                else if (tagName == "INPUT" && $('#champs_' + lists[i]).prop('type') == 'radio') {
                    $('.form_radio_champs_' + lists[i]).removeAttr('checked');
                }
                else if (tagName == "INPUT" && $('#champs_' + lists[i]).prop('type') == 'checkbox') {
                    $('.input_checkbox_champs_' + lists[i]).removeAttr('checked');
                }
            };
            if (data.questions_to_hide.length > 0){
                var lists = data.questions_to_hide;
                for (var i = 0; i < lists.length; i++) {
                    $('#qst_row_'+lists[i]).hide();
                }
            };
            if (data.groupes_to_show.length > 0){
                var lists = data.groupes_to_show;
                for (var i = 0; i < lists.length; i++) {
                    $('#groupe_row_'+lists[i]).show();
                    $('#btn_save_all_reponses_groupe_'+lists[i]).show();
                }
            };
            if (data.groupes_to_hide.length > 0){
                var lists = data.groupes_to_hide;
                for (var i = 0; i < lists.length; i++) {
                    $('#groupe_row_'+lists[i]).hide();
                    $('#btn_save_all_reponses_groupe_'+lists[i]).hide();
                }
            };
            if (data.champs_to_update.length > 0){
                var lists = data.champs_to_update;
                for (var i = 0; i < lists.length; i++) {
                    getReponseQuestionAndUpdateInput(data.qst_evaluation_id,lists[i])
                }
            };
            if ($.isFunction(window.afterQstAnswerSaving) && data.qst_evaluation_id) {
                afterQstAnswerSaving(data.qst_evaluation_id);
            }
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    $('#'+key).addClass('is-invalid');
                    $('#'+key).after('<small id="'+key+'-helper" class="form-text text-danger">' + value + '</small>');
                });
                errorsHtml += '</ul>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez rÃƒÂ©essayer ou actualiser la page!");
            }
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .main-icon').show();
            $(element).removeAttr('disabled');
        }
    });
}


function hideAndShowReleatedQuestions(qst_champ_id,qst_evaluation_id){
    if ($('#champs_' + qst_champ_id).is(':radio'))
        reponse = $("input:radio[name=champs_" + qst_champ_id + "]:checked").val();
    else
        reponse = $('#champs_' + qst_champ_id).val();
    if (reponse)
        reponse = reponse;
    else
        reponse = 'none';

    $.ajax({
        type: 'GET',
        url: racine + 'qst/questions/hideAndShowRelatedQuestions/'+qst_champ_id+'/'+qst_evaluation_id+'/'+reponse,
        success: function(data) {
            if (data.questions_to_show.length > 0){
                var lists = data.questions_to_show;
                for (var i = 0; i < lists.length; i++) {
                    $('#qst_row_'+lists[i]).show();
                    var tagName = $('#champs_'+lists[i])[0].tagName;
                    if(tagName == "SELECT"){
                        // set value selected
                        $('#champs_'+lists[i]).val('');
                        $('.selectpicker').selectpicker('refresh');
                    }
                    else if (tagName == "INPUT" && $('#champs_' + lists[i]).prop('type') == 'radio') {
                        $('.form_radio_champs_' + lists[i]).removeAttr('checked');
                    }
                    else if (tagName == "INPUT" && $('#champs_' + lists[i]).prop('type') == 'checkbox') {
                        $('.input_checkbox_champs_' + lists[i]).removeAttr('checked');
                    }
                }
            };
            if (data.questions_to_hide.length > 0){
                var lists = data.questions_to_hide;
                for (var i = 0; i < lists.length; i++) {
                    $('#qst_row_'+lists[i]).hide();
                }
            };
            if (data.groupes_to_show.length > 0){
                var lists = data.groupes_to_show;
                for (var i = 0; i < lists.length; i++) {
                    $('#groupe_row_'+lists[i]).show();
                    $('#btn_save_all_reponses_groupe_'+lists[i]).show();
                }
            };
            if (data.groupes_to_hide.length > 0){
                var lists = data.groupes_to_hide;
                for (var i = 0; i < lists.length; i++) {
                    $('#groupe_row_'+lists[i]).hide();
                    $('#btn_save_all_reponses_groupe_'+lists[i]).hide();
                }
            };
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}



function saveReponseQuestionTypeFile(element){
    // cont permet de pour voir envoyer le container par parametre
    var  container = $(element).attr('container');
    $('#' + container + ' #form-errors').hide();
    $('#' + container + ' .is-invalid').removeClass('is-invalid');
    $('#' + container + ' small.text-danger').remove();
    $(element).attr('disabled', 'disabled');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
    // var data = $('#' + container + ' form').serialize();
    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: new FormData($('#' + container + ' form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        // dataType: 'json',
        success: function (data) {
            //$('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();
            // set link to download file
            $('#link_file_qst_'+data.qst_champ_id).text(data.file_name);
            $('#icon_edit_reponse_file_'+data.qst_champ_id).show();
            $('#link_file_qst_href_' + data.qst_champ_id).attr('onclick', "window.open('" + racine + data.file_link + "','_blank')");
            $('#champs_'+data.qst_champ_id).hide();
            $('#form_btnSave_champ_'+data.qst_champ_id).hide();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    $('#'+key).addClass('is-invalid');
                    $('#'+key).after('<small id="'+key+'-helper" class="form-text text-danger">' + value + '</small>');
                });
                errorsHtml += '</ul>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez rÃƒÂ©essayer ou actualiser la page!");
            }
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .main-icon').show();
            $(element).removeAttr('disabled');
        }
    });
}




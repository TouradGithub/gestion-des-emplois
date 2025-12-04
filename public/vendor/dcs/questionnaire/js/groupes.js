//
// $(function () {
//     var CurrentNode;
//     type_projet = $('#type_projet').val();
//     if($('#questionnaire_id').val() != "")
//         questionnaire_id = $('#questionnaire_id').val();
//     else
//         questionnaire_id = "null";
//     $('#groupesTree')
//         .on('changed.jstree', function (e, data) {
//             var i, j, r = [], s = [];
//             for (i = 0, j = data.selected.length; i < j; i++) {
//                 // r.push(data.instance.get_node(data.selected[i]).text);
//                 CurrentNode = data.selected[0];
//                 console.log(data.selected[0]);
//
//             }
//             //node = data.node;
//             //alert(node.id);
//            /* var i, j, r = [];
//             for(i = 0, j = data.selected.length; i < j; i++) {
//                 r.push(data.instance.get_node(data.selected[i]).data);
//             }
//             $('#event_result').html('Selected: ' + r.join(', '));*/
//             //$(this).jstree(true).refresh();
//         })
//         .jstree({
//             'core' : {
//                 "themes":{
//                     "icons":false
//                 },
//                 "check_callback" : true,
//                 'data' : {
//                     'url' : function (node) {
//                         return racine+'qst/groupes/get_groupes/'+type_projet+"/"+questionnaire_id;
//                     },
//                     'data' : function (node) {
//                         return { 'id' : node.id };
//
//                     },
//                     dataType: "json",
//                 }
//             }
//         })
//         .on("select_node.jstree", function (e, data) {
//             var id = data.node.id;
//             getTheContent('qst/groupes/getActionGroupe/'+id+"/"+questionnaire_id, '#actions');
//         });
// });
//
//
//
//
//
//
//     // updating a group des elements
// // function updateGroupeElements(btn = null) {
// //     // $('[data-toggle="tooltip"]').tooltip('dispose');
// //     var elements = $(".group-elements").sortable('toArray');
// //     //$('datatableshow').DataTable().ajax.reload();
// //     //var childscount = $(".group-elements li").length;
// //     var idgroup = $(".group-elements").attr('idgroup');
// //     var datatble = $(".group-elements").attr('datatable-source');
// //     var lien = $(".group-elements").attr('lien');
// //     if (btn) {
// //         if ($(btn).hasClass("close")) {
// //             elements = jQuery.grep(elements, function (value) {
// //                 return value != $(btn).parent().attr('id');
// //             });
// //             $(btn).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
// //         } else {
// //             $(btn).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
// //             elements.push($(btn).attr('idelt'));
// //         }
// //     }
// //     // alert($(btn).attr('idelt'));
// //
// //     if (elements.length)
// //         var qsts = elements.join();
// //     else
// //         var qsts = 0;
// //     var link = racine + lien + "/" + qsts + '/' + idgroup;
// //     $.ajax({
// //         type: 'GET',
// //         url: link,
// //         success: function (data) {
// //             if (btn) {
// //                 if ($(btn).hasClass("close"))
// //                     $(btn).parent().remove();
// //                 else {
// //                     var idelt = $(btn).attr('idelt');
// //                     var libelle = $(btn).attr('libelle');
// //                     $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
// //                 }
// //             }
// //             $(datatble).DataTable().ajax.reload();
// //         },
// //         error: function () {
// //             if (btn) {
// //                 if ($(btn).hasClass("close"))
// //                     $(btn).html('&times;');
// //                 else {
// //                     $(btn).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
// //                 }
// //             }
// //             $.alert(msg_erreur);
// //         }
// //     });
// // }
//
// function addGroupe(element) {
//     saveform(element, function (id) {
//             $('#main-modal').modal('toggle');
//         $('#groupesTree').jstree("refresh");
//
//     });
// }
//
// function editGroupe(element)
// {
//     saveform(element, function (id) {
//         $(element).attr('disabled', 'disabled');
//         $('#groupesTree').jstree(true).refresh();
//     });
// }
//
// function deleteGroupe(link, text) {
//     confirmAction(link, text, function () {
//         $('#groupesTree').jstree("refresh");
//     });
// }
//
// function openContentInModal(link, modal = 'main', modalsize = 'lg', aftersave = null) {
//     $.ajax({
//         type: 'get',
//         url: link,
//         success: function (data) {
//             $("#" + modal + "-modal .modal-header-body").html(data);
//             $("#" + modal + "-modal .modal-dialog").addClass("modal-" + modalsize);
//             $("#" + modal + "-modal").modal({
//                 backdrop: 'static',
//                 keyboard: false
//             });
//             resetInit();
//             if (aftersave)
//                 aftersave();
//         },
//         error: function () {
//             $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//         }
//     });
// }
//
//
//
// function AddAndReload(element,lien)
// {
//     saveform(element, function (id) {
//         window.location.href = racine + lien;
//
//     });
// }
//
// function changeProgrammeActive(id_prog){
//     // alert(id_prog);
//     type_projet = $('#type_projet').val();
//
//     msg = "Etes vous sûr de vouloir ouvrir ce programme ?";
//     var confirme = confirm(msg);
//     if(confirme){
//         url = racine + 'qst/groupes/changeProgrammeActive/'+ id_prog+"/"+type_projet;
//         $.ajax({
//             type: 'get',
//             url: url,
//             success: function (data) {
//                 window.location.href = racine+'qst/groupes/'+type_projet;
//
//                 resetInit();
//             },
//             error: function () {
//                 $.alert(msg_erreur);
//             }
//         });
//     }
// }
//
// function changeEtatProgramme(id_questionnaire,etat,msg){
//     // alert(id_prog);
//     type_projet = $('#type_projet').val();
//
//     // msg = "Etes vous sûr de vouloir ouvrir ce programme ?";
//     var confirme = confirm(msg);
//     if(confirme){
//         url = racine + 'qst/groupes/changeEtatProgramme/'+ id_questionnaire+"/"+etat;
//         $.ajax({
//             type: 'get',
//             url: url,
//             success: function (data) {
//                 window.location.href = racine+'qst/groupes/'+type_projet;
//
//                 resetInit();
//             },
//             error: function () {
//                 $.alert(msg_erreur);
//             }
//         });
//     }
// }
//
// function addNewLigneGroupeCollection(groupe_id,qst_evaluation_id)
// {
//     $.ajax({
//         type: 'GET',
//         url: racine + 'qst/questions/addNewLigneGroupeCollection/'+groupe_id+'/'+qst_evaluation_id,
//         success: function(data) {
//             $('#btn_add_new_ligne_collection_'+groupe_id).hide();
//             $('#groupe_collection_'+groupe_id).append(data);
//             resetInit();
//         },
//         error: function() {
//             console.log('La requête n\'a pas abouti');
//         }
//     });
// }
//
// function deleteReponseGroupeCollection(qst_evaluation_id,groupe_id,sequence_collection,msg)
// {
//     if(confirm(msg)){
//         $.ajax({
//             type: 'GET',
//             url: racine + 'qst/questions/deleteReponseGroupeCollection/'+qst_evaluation_id+'/'+groupe_id+'/'+sequence_collection,
//             success: function(data) {
//                 $('#row_'+groupe_id+'_'+sequence_collection).remove();
//                 getTheContent('qst/questions/listeDataGroupeCollection/'+qst_evaluation_id+'/'+groupe_id,'#groupe_collection_'+groupe_id);
//             },
//             error: function() {
//                 console.log('La requête n\'a pas abouti');
//             }
//         });
//     }
// }
//
// function saveNewGroupeCollectionReponse(element)
// {
//     saveform(element, function (data) {
//         let link = 'qst/questions/listeDataGroupeCollection/'+data.qst_evaluation_id+'/'+data.groupe_id;
//         // alert(link)
//         getTheContent(link,'#groupe_collection_'+data.groupe_id);
//         $('#btn_add_new_ligne_collection_'+data.groupe_id).show();
//     });
// }
//
// function saveReponseGlobal(qst_champ_id,msg_validation,qst_evaluation_id, aftersave = null)
// {
//     if($('#champs_'+qst_champ_id).prop('required') && $('#champs_'+qst_champ_id).val() == "")
//         alert(msg_validation);
//     else {
//         if ($('#champs_' + qst_champ_id).is(':radio'))
//             reponse = $("input:radio[name=champs_" + qst_champ_id + "]:checked").val();
//         else
//             reponse = $('#champs_' + qst_champ_id).val();
//         if (reponse)
//             reponse = reponse;
//         else
//             reponse = 'none';
//         //verifier la reponse du choix "Autre a preciser"
//         is_other = 0;
//         // console.log($('#champs_'+qst_champ_id).prop('type'))
//         if($('#champs_'+qst_champ_id).prop("tagName").toLowerCase() != 'textarea'){
//             if (reponse instanceof Array){
//                 for (let i = 0; i < reponse.length; i++) {
//                     var is_other = $('#option_'+reponse[i]).attr('is_other'); //has error with appostroph
//                 }
//             }
//             else{
//                 var is_other = $('#option_'+reponse).attr('is_other'); //has error with appostroph
//             }
//         }
//         other_reponse = 'none';
//         if(is_other == 1){
//             // alert('#option_'+reponse+"//"+id_qst);
//             if(is_other && $('#is_other_'+qst_champ_id).val() == ""){
//                 $('#is_other_'+qst_champ_id).show();
//                 alert("Veiller saisir la réponse du choix Autres (à préciser) !!");
//                 exit();
//             }
//             else{
//                 other_reponse = $('#is_other_'+qst_champ_id).val();
//             }
//         }
//         var container = 'divReponses';
//         $('#' + container + ' .spinner-border'+ qst_champ_id).show();
//         $.ajax({
//             type: 'get',
//             url: racine + 'qst/questions/saveReponseGlobal/'+qst_evaluation_id+"/"+qst_champ_id+"/"+reponse+"/"+other_reponse,
//             success: function (data) {
//                 // updateMehnetiProgressBar(data.progress);
//                 // $('#' + container + ' .spinner-border'+ id_qst).hide();
//                 $('#' + container + ' .answers-well-saved'+ qst_champ_id).show();
//                 setTimeout(function () {
//                     $('#' + container + ' .answers-well-saved'+ qst_champ_id).hide();
//                     $('#' + container + ' .main-icon'+ qst_champ_id).show();
//                 },1500);
//                 $('.btnSave'+qst_champ_id).css("color","#2ece1b;");
//                 $('.td_'+qst_champ_id).css("background-color","#b8fbb8");
//                 $('.btnSave'+qst_champ_id).removeClass("btn-secondary");
//                 $('.btnSave'+qst_champ_id).addClass("btn-success");
//                 // $('.btnSave'+data.id_ques).show();
//
//                 if (data.questions_to_show.length > 0){
//                     var lists = data.questions_to_show;
//                     for (var i = 0; i < lists.length; i++) {
//                         $('#qst_row_'+lists[i]).show();
//                     }
//                 };
//                 if (data.questions_to_hide.length > 0){
//                     var lists = data.questions_to_hide;
//                     for (var i = 0; i < lists.length; i++) {
//                         $('#qst_row_'+lists[i]).hide();
//                     }
//                 };
//                 if (data.groupes_to_show.length > 0){
//                     var lists = data.groupes_to_show;
//                     for (var i = 0; i < lists.length; i++) {
//                         $('#groupe_row_'+lists[i]).show();
//                     }
//                 };
//                 if (data.groupes_to_hide.length > 0){
//                     var lists = data.groupes_to_hide;
//                     for (var i = 0; i < lists.length; i++) {
//                         $('#groupe_row_'+lists[i]).hide();
//                     }
//                 };
//                 if(aftersave){
//                     aftersave(qst_evaluation_id);
//                 }
//                 // if (data.saute == 1)
//                 //     $('.td_'+qst_champ_id).css("background-color","#f5ede6;");
//                 // if (data.non_repondu == 1)
//                 //     $('.td_'+qst_champ_id).css("background-color","#f3f3ff;");
//             },
//             error: function(data){
//                 if( data.status === 422 ) {
//                     var errors = data.responseJSON;
//                     console.log(errors.errors);
//                     errorsHtml = '<div class="alert alert-danger"><ul>';
//                     var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
//                         errorsHtml += '<li>' + value[0] + '</li>';
//                     });
//                     errorsHtml += '</ul></div>';
//                     $( '#divReponses #form-errors'+ qst_champ_id ).show().html( errorsHtml );
//                 } else {
//                     alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                 }
//                 $("#divReponses .form-loading"+ qst_champ_id).hide();
//             }
//         });
//     }
// }
//
// function onReponseChange(qst_champ_id){
//     // trigger click on save button
//     $('#btnSave_champ_'+qst_champ_id).trigger('click');
// }
//

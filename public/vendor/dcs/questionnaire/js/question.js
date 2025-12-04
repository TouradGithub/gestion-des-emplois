// function selectNatureChamp(element) {
//     var link = 'qst/questions/onSelectNatureChamp/' + $(element).val();
//     getTheContent(link, '#changeOnSelectNatureChamp');
// }
//
// function filtrerQuestions(type_project) {
//     nature_champ = $("#nature_champ_filter").val();
//     type = $("#type_filter").val();
//     var niveau_geo= $("#niveau_geo").val();
//     $('#datatableshow').DataTable().ajax.url(racine + 'qst/questions/getDT/'+type_project + "/" + nature_champ + "/" + type+"?niveau="+niveau_geo).load();
//
// }
//
// function addChampValeur(element, lemodule, datatable = "#datatableshow", modal = "add-main", tab = 1, largeModal = 'lg') {
//     saveform(element, function (id) {
//         $(datatable).DataTable().ajax.reload();
//         $(element).attr('disabled', 'disabled');
//         var modals = modal.split('-');
//         var add = modals[0];
//         var main = modals[1];
//         setTimeout(function () {
//             $('#'+add+'-modal').modal('toggle');
//             // openObjectModal(id, lemodule, datatable, main, tab, largeModal);
//         }, 1000);
//     });
// }
//
// function getQuestions(element,question_id) {
//     let el = $(element);
//     getTheContent(  'qst/questions/getQuestion/' + el.val()+"/"+question_id, '#questionsCnt');
//
// }
// function getQuestionConditions(element) {
//     let el = $(element);
//     getTheContent(  'qst/questions/getQuestionConditions/' + el.val(), '#conditionsCnt');
//     $('#valuesCnt').html('');
// }
// function getQuestionOptions(val) {
//     let qst_question_val = $("#qst_question_val").val();
//     if (val>=3) {
//         getTheContent(  'qst/questions/questionOptions/'+qst_question_val, '#valuesCnt');
//     }else {
//         $('#valuesCnt').html('');
//     }
// }
// function afterConditionSave(element) {
//     saveform(element, function (id) {
//         $('.datatableshow3').DataTable().ajax.reload();
//         $('#cndModalBtn').trigger('click');
//     });
// }
//
// function saveValCible(element,datatable=".datatableshow8") {
//     saveform(element, function (id) {
//         $(datatable).DataTable().ajax.reload();
//         $('#add-modal').modal('toggle');
//         // setMainTabs(4, '#main-modal');
//
//     });
// }
//
// function saveSituationRef(element,datatable=".datatableshow8") {
//     var link = id;
//     tab = link.split("link");
//     saveform(element, function (id) {
//         $(datatable).DataTable().ajax.reload();
//         $('#add-modal').modal('toggle');
//         setMainTabs(tab[1], '#main-modal');
//
//     });
// }
//
// function checkAllQtions(element){
//     if ($(element).is(':checked')) {
//         $('.btnsRepondreHistoriques').show();
//     }
//     else
//     {
//         var str = 0;
//         $(':checkbox').each(function() {
//             str += this.checked ? 1 : 0;
//         });
//         if (str == 0) {
//             $('.btnsRepondreHistoriques').hide();
//         }
//         else
//             $('.btnsRepondreHistoriques').show();
//     }
// }
//
//
// function getFormEvaluation(){
//         var questions_id = [];
//         $("input:checked").each(function () {
//             var id = $(this).val();
//             questions_id.push(id);
//         });
//         openInModal(racine + 'qst/questions/getFormEvaluation/' + questions_id,null,'add','xl');
//         /*$.ajax({
//             type: 'get',
//             url: racine + 'qst/questions/getFormEvaluation/' + questions_id,
//             success: function (data) {
//                 $("#add-modal .modal-header-body").html(data);
//                 $("#add-modal .modal-dialog").addClass("modal-xl");
//                 $("#add-modal").modal();
//                 resetInit();
//             },
//             error: function () {
//                 $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//             }
//         });*/
//
//     }
//
// function filtrerEvaluation(type_project) {
//     niveau = $("#niveau").val();
//     $('.btnsRepondreHistoriques').hide();
//     var link=racine + 'qst/questions/getDTEvaluations/'+type_project + "/" + niveau;
//     $('#datatableshow').attr('link',link);
//     $('#datatableshow').DataTable().ajax.url(link).load();
// }
//
//     function  getReponsesWilayas(){
//         var niveau = $('#niveau').val();
//         var territoires = $('#wilayaa').val();
//         var ids_questions = $('#ids_questions').val();
//         var niveau_geo = $('#niveau_geo').val();
//         var type_objet = $('#type_objet').val();
//         if (niveau == 3) { //wilaya
//             if (territoires.length <= 3)
//                 var type = 1;
//             else
//                 var type = 0;
//             if (territoires == "")
//                 var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
//             else
//                 var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
//             $.ajax({
//                 type: 'get',
//                 url: racine + link,
//                 success: function (data) {
//                     $(".div_repondre").empty();
//                     $(".div_repondre").html(data);
//                     $("#btnsaveanswer").show();
//                     $(".datedefault").show();
//                     $("#territoires_id").val(territoires);
//                         $('.selectpicker').selectpicker('refresh');
//
//                 },
//                 error: function () {
//                     $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                 }
//             });
//         }
//         else{
//             /*if (niveau_geo != 0) {
//                 $.ajax({
//                     type: 'get',
//                     url: racine + 'getObjets/' + territoires + '/' + type_objet,
//                     success: function (data) {
//                         $("#objet").html(data);
//                         // $("#comm").html(data.commune);
//                         $(".div_repondre").empty();
//                         $('.selectpicker').selectpicker('refresh');
//                     },
//                     error: function () {
//                         $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                     }
//                 });
//
//             }
//             else{*/
//                 // alert("mukattas");
//                 $.ajax({
//                     type: 'get',
//                     url: racine + 'qst/questions/wilayas/getmoughataas/' + territoires + '/' + niveau,
//                     success: function (data) {
//                         $("#moughataas").html(data.mukattas);
//                         $("#comm").html(data.commune);
//                         $(".div_repondre").empty();
//                         $('.selectpicker').selectpicker('refresh');
//                     },
//                     error: function () {
//                         $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                     }
//                 });
//             // }
//         }
//         resetInit();
//     }
//
//     function  getReponsesMoughataas(){
//         var niveau = $('#niveau').val();
//         var territoires = $('#moughataas').val();
//         var wilaya = $('#wilayaa').val();
//         var ids_questions = $('#ids_questions').val();
//         var niveau_geo = $('#niveau_geo').val();
//         var type_objet = $('#type_objet').val();
//         if (niveau == 2) {
//             if (territoires.length <= 3)
//                 var type = 1;
//             else
//                 var type = 0;
//             if (territoires == "")
//                 var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
//             else
//                 var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
//             $.ajax({
//                 type: 'get',
//                 url: racine + link,
//                 success: function (data) {
//                     $(".div_repondre").empty();
//                     $(".div_repondre").html(data);
//                     $("#btnsaveanswer").show();
//                     $(".datedefault").show();
//                     $("#territoires_id").val(territoires);
//                         $('.selectpicker').selectpicker('refresh');
//
//                 },
//                 error: function () {
//                     $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                 }
//             });
//         }
//         else{
//             /*if (niveau_geo != 0) {
//                 $.ajax({
//                     type: 'get',
//                     url: racine + 'getObjets/' + territoires + '/' + type_objet,
//                     success: function (data) {
//                         $("#objet").html(data);
//                         // $("#comm").html(data.commune);
//                         $(".div_repondre").empty();
//                         $('.selectpicker').selectpicker('refresh');
//                     },
//                     error: function () {
//                         $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                     }
//                 });
//             }
//             else{*/
//                 $.ajax({
//                     type: 'get',
//                     url: racine + 'qst/questions/mukataas/getcommunes/' + territoires +'/'+ wilaya ,
//                     success: function (data) {
//                         $("#comm").html(data);
//                         $(".div_repondre").empty();
//                         $('.selectpicker').selectpicker('refresh');
//                     },
//                     error: function () {
//                         $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                     }
//                 });
//             // }
//         }
//         resetInit();
//     }
//
//     function  getReponsesCommunes(){
//         var niveau = $('#niveau').val();
//         var territoires = $('#comm').val();
//         var ids_questions = $('#ids_questions').val();
//         var niveau_geo = $('#niveau_geo').val();
//         var type_objet = $('#type_objet').val();
//         if (niveau == 1) {
//             if (territoires.length <= 3)
//                 var type = 1;
//             else
//                 var type = 0;
//             // alert(territoires);
//             if (territoires == "")
//                 var link =  'qst/questions/getQuestionsWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
//             else
//                 var link =  'qst/questions/getQuestionsWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
//            // $('.form-loading').show();
//             $.ajax({
//                 type: 'get',
//                 url: racine + link  ,
//                 success: function (data) {
//                     // alert(data);
//                    $('.form-loading').hide();
//                     $(".div_repondre").empty();
//                     $(".div_repondre").html(data);
//                     $("#btnsaveanswer").show();
//                     $(".datedefault").show();
//                     $("#territoires_id").val(territoires);
//                     $('.selectpicker').selectpicker('refresh');
//
//                 },
//                 error: function () {
//                     $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                 }
//             });
//         }
//         /*else{
//             if (niveau_geo != 0) {
//                 $.ajax({
//                     type: 'get',
//                     url: racine + 'getObjets/' + territoires + '/' + type_objet,
//                     success: function (data) {
//                         $("#objet").html(data);
//                         // $("#comm").html(data.commune);
//                         $(".div_repondre").empty();
//                         $('.selectpicker').selectpicker('refresh');
//                     },
//                     error: function () {
//                         $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//                     }
//                 });
//
//             }
//         }*/
//         resetInit();
//         // alert(ids_questions);
//     }
//
//     function openHistotyReponses(id_qst) {
//         var niveau = $("#niveau").val();
//         if(niveau != 5)
//             var territoires_id = $("#territoires_id").val();
//         else
//             var territoires_id = 'all';
//         $.ajax({
//             type: 'get',
//             url: racine + 'qst/questions/getHistoriqueReponses/' + territoires_id + '/' + niveau + '/' + id_qst,
//             success: function (data) {
//                 $("#second-modal .modal-header-body").html(data);
//                 $("#second-modal .modal-dialog").addClass("modal-lg");
//
//                 $("#second-modal").modal();
//             },
//             error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
//         });
//         // alert(id_qst+"op"+territoires+"op"+niveau);
//     }
//
//       function checkSuiviQuestion()
//     {
//         if(($('#situation_reference').is(':checked')== true) || ($('#valeurs_cibles').is(':checked')== true) || ($('#evaluation').is(':checked')== true))
//             $("#divDetails").show();
//         else
//             $("#divDetails").hide();
//
//     }
//
//
// function getQstPDFExcel(lien)
//     {
//         document.formFilter.action = racine + lien;
//         document.formFilter.target = "_blank";    // Open in a new window
//         document.formFilter.submit();             // Submit the page
//         return true;
//     }
//
// function selectIsPeriodicite(){
//     is_periodicite = $('#is_periodicite').val();
//     if(is_periodicite == 1) {
//         $('.divFormule').show();
//     }
//     else{
//         $('.divFormule').hide();
//     }
//
// }
// function selectAgregation(){
//     agregation = $('#agregation').val();
//     if(agregation == 1) {
//         $('.divAgregation').show();
//     }
//     else{
//         $('.divAgregation').hide();
//     }
//
// }
//
//     function openEvaluationModal(id, lemodule, datatableshow = "#datatableshow", modal = "main", tab = 1, largeModal = 'lg') {
//         $('.btnsRepondreHistoriques').hide();
//         openObjectModal(id, lemodule, datatableshow = "#datatableshow", modal = "main", tab = 1, largeModal = 'lg');
//
//     }
//
// // function changedate(element,terrt=0) {
// //     if (terrt) {
// //         $("#date_"+element+"_"+terrt).attr('date_changed',1);
// //     };
// //     $("#date_"+element).attr('date_changed',1);
// // }
//
// //changer la date d'enregistrement
//
// function changedateEnre() {
//     var date_enreg = $('#date_enreg').val();
//     $('.daterep').each(function(){
//         // var date_changed = $(this).attr('date_changed');
//         // if (date_changed == 0 ) {
//             $(this).val(date_enreg);
//         // };
//     });
//
// }

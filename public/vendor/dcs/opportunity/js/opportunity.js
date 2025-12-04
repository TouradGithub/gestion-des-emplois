 function showArabefiles() {
$("#chempsArabe").toggle();

//$("#chempsArabe").hide();

//$('#chempsArabe').toggle('show/hide');

}
 function
 filter_offres()
 {
     etat_id= $('#etat_filtre').val();
     employeur = $('#employeur').val();
     secteur = $('#secteur').val();
     agence= $('#agence').val();

     let url = racine + 'offres/getDT/' + etat_id + '/' + employeur + '/' + secteur + '/' + agence;

     $('#datatableshow').attr('link', url);
     $('#datatableshow').DataTable().ajax.url(url).load();
 }
 function filter_formations()
 {
     etat_id= $('#etat_filtre').val();
     date_debut = $('#date_debut').val();
     date_fin = $('#date_fin').val();

     let url = racine + 'formations/getDT/' + etat_id + '/' + date_debut + '/' + date_fin;

     $('#datatableshow').attr('link', url);
     $('#datatableshow').DataTable().ajax.url(url).load();
 }

 function  resetInitModule(){
     $('#trumbowyg-demo').trumbowyg();
 }
 function  postuler(){
    alert("hello");

 }
 function qstSave(qst_champs_id) {
     var qst_val = $("#champs_" + qst_champs_id).val();
     let url = racine + "opportunities/saveQst/" + qst_champs_id;
     var container = 'divReponses';
     $('#' + container + ' .spinner-border' + qst_champs_id).show();

     $.ajax({
         url: url,
         type: 'POST',
         data: {qst_val, qst_champs_id},
         success: function (data) {
             // $('#' + container + ' .spinner-border'+ id_qst).hide();
             $('#' + container + ' .answers-well-saved' + qst_champs_id).show();
         }
     });
 }



 // all functions for the new version of the package opportunity by mamouny

 function postulerProgrammeOpportunity(opt_id,name, msg, personne_id) {
     var datatableshow = '#datatableshow1';
     var conf = confirm(msg + ': ' + name);
     if (conf){
         url = 'opportunities/postulerProgrammeOpportunity/' + opt_id + '/' + personne_id;
         type = 'get';
         res = 'resultat_msg';
         $.ajax({
             type: type,
             url: racine + url,
             success: function (data) {
                 //alert(555);
                 $("#" + res).html(data.msg).show("slow").delay(4000).hide("slow");
                 console.log(data);
                 $("#divBtnPostulerOptionOpportunity_"+opt_id).removeClass('text-white');
                 $("#divBtnPostulerOptionOpportunity_"+opt_id).html(loading_content);

                 window.location.href = racine+'opportunities/espaceDossierParticipant/'+data.opt_personne_id;
                 // if(data.is_de){
                 // }else{
                 //     $('#main-modal').modal('hide');
                 //     $('#second-modal').modal('hide');
                 //     openInModal('programmeMpma/getMpmaOptionsConseiller/'+data.mpma_option_id);
                 // }

                 // $('#de_tab-modal').on('hidden.bs.modal', function () {
                 //     location.reload();
                 // });
             }
         });
     }
     return false;
 }
 function showOptDescriptionOption(opt_id,groupe_opp_id='all',largeModal = 'lg'){
     url = 'opportunities/showDescriptionOption/' + opt_id+'/'+groupe_opp_id;
     type = 'get';
     $.ajax({
         type: type,
         url: racine + url,
         success: function (data) {
             $("#second-modal .modal-dialog").addClass("modal-"+largeModal);
             $("#second-modal .modal-header-body").html(data);
             $("#second-modal").modal();
         },
         error: function () {
             $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
         }
     });
 }

 function confirmEnvoieOpportunity(id,qst_evaluation_id) {
    $('.btnEnvoieOpportunity .fa-share-square').hide();
    $('.btnEnvoieOpportunity .fa-spinner').show();
     $.ajax({
         type: 'get',
         url: racine + 'opportunities/confirmEnvoieOpportunity/' + id+'/'+qst_evaluation_id,
         success: function (data) {
             $("#second-modal .modal-header-body").html(data);
             $("#second-modal").modal();
             resetInit();
            $('.btnEnvoieOpportunity .fa-share-square').show();
            $('.btnEnvoieOpportunity .fa-spinner').hide();
         },
         error: function () {
             $.alert("Une erreur est survenue veuillez rÃƒÂ©essayer ou actualiser la page!");
            $('.btnEnvoieOpportunity .fa-share-square').show();
            $('.btnEnvoieOpportunity .fa-spinner').hide();
         }
     });
 }

 function selectOpportunitieExperience(id_exp,opt_person_note_id) {
     let lien = "opportunities/experiences/selectOpportunitieExperience/"+opt_person_note_id+"/"+id_exp;
     $.ajax({
         type: 'get',
         url: racine + lien,
         success: function (qst_evaluation_id) {
             $(".datatableshow3").DataTable().ajax.reload();
             if ($.isFunction(window.afterQstAnswerSavingExperience)) {
                 afterQstAnswerSavingExperience(qst_evaluation_id);
             }
         },
         error: function () {
             $.alert("Une erreur est survenue veuillez rÃ©essayer ou actualiser la page!");
         }
     });
 }

 function afterQstAnswerSavingExperience(qst_evaluation_id) {
     updateOpportunityProgressBarExperience(qst_evaluation_id);
 }

 function updateOpportunityProgressBarExperience(qst_evaluation_id) {
     let link = 'opportunities/updateOpportunityProgressBarAndRefreshTheHeader/'+qst_evaluation_id;
     $.ajax({
         type: 'get',
         url: racine + link,
         success: function (data) {
             console.log(data.header);
             $('#opportunitie_header').html(data.header);
         },
         error: function () {
             // $.alert(msg_erreur);
         }
     });
     return false;
 }

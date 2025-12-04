function  actualiserValeurs(id) {
    getTheContent('pequestions/getvaleurs/'+id,'#valeursquestion');
}

function affichervaleur(id1){
    $('#valeursquestion .list-group-item').removeClass('list-group-item-info');
    $('.'+id1).addClass('list-group-item-info');
    getTheContent("pequestions/valeurquestion/formeditvaleur/"+id1,"#modeleditvaleur");
    resetInit();
}

function updateOrdresValeurs(container = '.group-elements'){
  var ordre = 1;
  $(container + " li").each(function(i, item){
  $(item).children('.valeur-ordre').html(ordre);
  ordre++;
  });
  if(container == ".group-elements-right"){
    var idQuestion = $(container).attr('idgroup');
    getTheContent('pequestions/getListe1/'+idQuestion,'#liste1');
  }
}

function changerNomEtat(id,container = '.buttonEtat'){
  if(id == 1 || id == 3){
    $(container).removeClass('btn-primary');
    $(container).removeClass('.'+id);
    $(container).addClass('btn-secondary');
    $(container+' .btn-secondary').addClass('2');
    $(container + " span").html('Desactiver');

  }
  else{
    $(container).removeClass('btn-secondary');
    $(container).removeClass('.'+id);
    $(container).addClass('btn-primary');
    $(container+' .btn-primary').addClass('3');
    $(container + " span").html('Activer');
  }
}

function closeContainer(container){
    $(container).html('');
}

function addObjectRespValeur(element,idquestion){
  saveform(element);
  $.ajax({
      success: function () {
        getTheContent('pequestions/getvaleurs/'+idquestion,'#valeursquestion');
      },

  });
}

function filterQuestionParModule(){
  var id_model = $("#id_model").val();
      if(id_model != '' && id_model != 'all'){
        $.ajax({
          type:'get',
          url: racine + 'pequestions/getCompetenceParModule/'+id_model,
          success:function(data)
          {
            $("#id_competence").html(data);
            $("#id_competence").selectpicker('refresh');
            filterQuestions();
            //resetInit();
          },
          error : function(data){
            alert(racine + 'pequestions/getCompetenceParModule/'+id_model);
          }
        });
      }else if(id_model == 'all'){
         $.ajax({
          type:'get',
          url: racine + 'pequestions/getCompetenceParModule',
          success:function(data){
            $("#id_competence").html(data);
            $("#id_competence").selectpicker('refresh');
            filterQuestions();
            //resetInit();
          },
          error : function(data){
          }
        });
      }
}

function filterQuestionParSpecialite(){
     var id_specialite = $("#id_specialite").val();
      if(id_specialite != '' && id_specialite != 'all'){
        $.ajax({
          type:'get',
          url: racine + 'pequestions/getModelParSpecialite/'+id_specialite,
          success:function(data)
          {
            $("#id_model").html(data);
            $("#id_model").selectpicker('refresh');
           filterQuestions();
            //resetInit();
          },
          error : function(data){
            alert(racine + 'pequestions/getModelParSpecialite/'+getModelParSpecialite);
          }
        });
      }else if(id_specialite == 'all'){
         $.ajax({
          type:'get',
          url: racine + 'pequestions/getModelParSpecialite',
          success:function(data){
            $("#id_model").html(data);
            $("#id_model").selectpicker('refresh');
            filterQuestions();
            //resetInit();
          },
          error : function(data){
          }
        });
      }
}

function filterQuestions() {
    var id_type_question = $("#id_type_question").val();
    var id_competence = $("#id_competence").val();
    var id_model = $("#id_model").val();
    var id_specialite = $("#id_specialite").val();
    $('#datatableshow').DataTable().ajax.url(racine+"pequestions/getDT/none/"+id_type_question+"/"+id_competence+"/"+id_model+"/"+id_specialite).load();
}

function filtrerQuestionParTypeQuestion(){
      var id_type_question = $("#id_type_question").val();
      var id_competence = $("#id_competence").val();
      var id_model = $("#id_model").val();
      var id_specialite = $("#id_specialite").val();
      $('#datatableshow').DataTable().ajax.url(racine+"pequestions/getDT/none/"+id_type_question+"/"+id_competence+"/"+id_model+"/"+id_specialite).load();
}

function filterGroupeParSpecialite(){
  var id_spe = $("#specialite").val();
  $('#datatableshow').DataTable().ajax.url(racine+"pegroupequestions/getDT/none/"+id_spe).load();
}

function filterQuestionnaireParSpecialite(){
  var id_spe = $("#specialite").val();
  $('#datatableshow').DataTable().ajax.url(racine+"pequestionnaires/getDT/none/"+id_spe).load();
}

function deleteQuestionOfGroupes(id_question,text){
  link = racine+"pequestions/deleteQuestionOfGroupes/"+id_question;
  confirmAction(link ,text , function(data){
    getTheContent("pequestions/getTab/"+data.id_question+"/15",'#tab15');
  });
}

function addobjectvaleur(element,lemodule,idquestion){
    addPequestion(element);
    $.ajax({
      success: function(){
         getTheContent('pequestions/getvaleurs/'+idquestion,'#valeursquestion');
      }
    });
}

function addvaleurajax(element,idquestion){
  saveform_image(element, function(){
  getTheContent('pequestions/getvaleurs/'+idquestion,'#valeursquestion');
  closeContainer('#addval');
  },null,'img');

}

function editvaleurajax(element,idquestion){
   var photo = $('#img_valeur').val();
   var valeur_id = $('#id_valeur').val();
   var valeur = $('#valeur').val(); 
   if(photo == undefined && valeur != '')
      supperimer_photo(valeur_id);
   saveform_image(element, function(){
   getTheContent('pequestions/getvaleurs/'+idquestion,'#valeursquestion');
   closeContainer('#editval');
  },null,'img');
}

function supperimer_photo(id_valeur){
   $.ajax({
      type: 'get',
      url: racine +"pequestions/delete_photo/"+id_valeur,
      success: function (data) {
        console.log(data);
      },
      error: function () {
          alert('bbb');
      }
  });
}


function getValeurQuestion(id1){
  getTheContent("pequestions/valeurquestion/formeditvaleur/"+id1,"#valeursquestion");
}

function getValeursQuestionsGroupes(id){
   $.ajax({
        type: 'get',
        url: racine +"pegroupequestions/getValeurs/"+id,
        success: function (data) {
            container = '#second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable('#datatablevaleur');
            setDataTable('#datatablevaleurliste2');
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function addObjectPequestion(element, lemodule, datatable = "#datatableshow", modal = "question", tab = 15, largeModal = 'lg'){
        saveform_image(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            $('#add-modal').modal('toggle');
            openObjectModal(id, lemodule, datatable,modal,tab,largeModal);
        }, 1500);
    },null,'img');
  }

function editObjectPequestion(element, lemodule, datatable = "#datatableshow", modal = "question", tab = 15, largeModal = 'lg'){
        var image = $('#question_photo').val();
        if(image == undefined) {
          var id_question = $('#id_question').val();
          deletePhotoQuestion(id_question);
         // alert(image);
        }
        saveform_image(element,null,null,'img');
}

  function deletePhotoQuestion(id_question){
     $.ajax({
        type: 'get',
        url: racine +"pequestions/deletePhotoQuestion/"+id_question,
        success: function (data) {
          console.log(data);
        },
        error: function () {
            alert('erreur');
        }
    });

  }

  function getGroupeQuestions(id) {
    $.ajax({
        type: 'get',
        url: racine+'pequestionnaires/getGroupeQuestions/'+id,
        success: function (data) {
            container = '#second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable('#datatableshow-groupe-questions');
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function getGroupeValeurs(id) {
    $.ajax({
        type: 'get',
        url: racine+'pequestionnaires/getGroupeValeurs/'+id,
        success: function (data) {
            container = '#second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable('#datatableshow-groupe-valeurs');
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}
//ajouter des groupes  nourdine
function get_groupes(id,etat){
      if(etat == 1 || etat == 3){
        url = racine +"pequestionnaires/getAllGroupes/"+id;
      }
      else{
        url = racine +"pequestionnaires/alert";
      }
      $.ajax({
        type: "GET",
        url: url ,
        success : function(data){
        $("#groupes1").html(data);
          $('#main-modal').animate({
            scrollTop: $("#groupes1").offset().top
            }, 500);
          resetInit();

          },
          error: function(error){
        alert('erreur');
        }
      });
}

 //ajouter des questions nourdine
function ajouterQuestions(id,ids='all'){
    var body = $("#second-modal");
    url = racine +"pegroupequestions/getQuestiontableDT/"+id+'/all/'+ids;
    $.ajax({
      type: "GET",
      url:url,
      success : function(data){
        var modal = $('#groupequestion-modal').val();
        if(modal != '')
          modal =  $('#main-modal');
        else
          modal = $('#groupequestion-modal');
         setDataTable('#datatablegroupe2');
         $("#groupes_tables").html(data);
          modal.animate({
            scrollTop:$("#groupes_tables").offset().top
            }, 500);
          resetInit();
      },
      error: function(error){
      alert('erreur');
      }
    });
}

function resetInitModule(){
   $(".group-elements").sortable({
        axis: 'y',
        update: function (event, ui) {
            updateGroupeElementsNew(null,".group-elements");
            updateOrdresValeurs(".group-elements");
        }
    });
   $(".group-elements-left").sortable({
        axis: 'y',
        update: function (event, ui) {
            updateGroupeElementsNew(null,".group-elements-left");
            updateOrdresValeurs(".group-elements-left");
        }
    });
  $(".group-elements-right").sortable({
        axis: 'y',
        update: function (event, ui) {
            updateGroupeElementsNew(null, ".group-elements-right");
            updateOrdresValeurs(".group-elements-right");
        }
    });
}

function changerEtatQuetionnaire(idquestionnnaire,text){
  link = racine +"pequestionnaires/changerEtatQuetionnair/"+idquestionnnaire;
  confirmAction(link,text, function(data){
    getTheContent("pequestionnaires/getTab/"+data.id_questionnaire+"/1",'#tab1');
    $('#datatableshow').DataTable().ajax.reload();
  });
}

function changerEtatQuetion(idquestion,text='confrmation le changement'){
  link = racine +"pequestions/changerEtatQuetion/"+idquestion;
  confirmAction(link,text, function(data){
    getTheContent("pequestions/getTab/"+data.id_question+"/15",'#tab15');
    $('#datatableshow').DataTable().ajax.reload();
  });
}

function openBlockchoix(blockea){
  var type = $('#ref_pe_types_question_id').val();
  if(type == 1 || type == 2)
    $(blockea).show();
  else
    $(blockea).hide();
    //alert(type);
}

function openFormAddvaleur(id){
    url = racine +"pequestions/ajax/formaddvaleur/"+id;
    $.ajax({
        type: "GET",
        url: url,
        success : function(data){
          $("#modeleditvaleur").html(data);
          resetInit();
        },
        error: function(error){
               alert('erreur');
        }
   });
}

function deleteValeur(id,text='confrmation') {
  link = racine + "pequestions/deletevaleur/"+id;
  confirmAction(link,text,function (data) {
    getTheContent('pequestions/getvaleurs/'+data.id_question,'#valeursquestion');
  });
}

function openImgContainer() {
  $('.img-container').toggle('slow');
}

function updateGroupeElementsNew(element = null, container = ".group-elements") {
    var elements = $(container).sortable('toArray');
    var childscount = $(container + "li").length;
    var idgroup = $(container).attr('idgroup');
    var datatble = $(container).attr('datatable-source');
    var lien = $(container).attr('lien');
    if (element) {
        if ($(element).hasClass("close")) {
            elements = jQuery.grep(elements, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            elements.push($(element).attr('idelt'));
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
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElementsNew(this)">&times;</button></li>');

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
            $.alert(msg_erreur);
        }
    });
}

function ajouterGroupeAuQuestionnaire(idgroup,idquestionnaire){
    $.ajax({
        type: "get",
        url:racine +'pequestionnaires/ajouterGroupe/'+idgroup+'/'+idquestionnaire,
        success: function (){
            $('#datatableshow3').DataTable().ajax.url(racine+"pequestionnaires/groupesDT/"+idquestionnaire).load();
            getTheContent('pequestionnaires/get_groupe_qetionn/'+idquestionnaire,'#liste-groupe');
        },
        error:function (){
          alert('erreur');
        }
    });
}

function deleteGroupeOfQuetionnaire(idg,idq){
  link = racine +'pequestionnaires/deleteGroupeOfQuetionnaire/'+idg+'/'+idq;
  confirmAction(link,'voulez vraiment suprimer le groupe du questionnaire',function(data){
    ordonnerListeGroupe(data.id_questionnaire);
    $('#datatableshow3').DataTable().ajax.url(racine+"pequestionnaires/groupesDT/"+data.id_questionnaire).load();
    getTheContent('pequestionnaires/get_groupe_qetionn/'+data.id_questionnaire,'#liste-groupe');

  });
}

function ordonnerListeGroupe(id_questnn){
   $.ajax({
        type:"GET",
        url:racine+"pequestionnaires/updateOrdreGroupe/"+id_questnn,
         success: function (data) {
        },
        error:function (){
          alert('erreur');
        }
   });
}

function ajouterQuestionAugroupe(idg,idq){
   $.ajax({
        type: "get",
        url:racine +'pegroupequestions/ajouterQuestionAugroupe/'+idg+'/'+idq,
        success: function (data) {
          if(data.question_double == 'error'){
            var msg = '<div class="alert alert-danger" role="alert">'+data.msg+'</div>';
            $('#main-modal').animate({
              scrollTop: $("#mssg").offset().top
              }, 500);
              $("#mssg").html(msg);
              $("#mssg").show();
              setInterval(function(){  $("#mssg").hide(); }, 30000);
          }
          else{
           $('#datatableshow2').DataTable().ajax.url(racine+'pegroupequestions/getQuestionDT/'+idg+'/all').load();
           getTheContent('pegroupequestions/getQuestionOfGroupes/'+idg ,'#pegroupequestions');
          }

        },
        error:function (){
          alert('erreur');
        }
    });
}

function deleteQuetionOfGroupe(idg,idq){
       link = racine +'pegroupequestions/deleteQuetionOfGroupe/'+idg+'/'+idq;
        confirmAction(link,'voulez vraiment suprimer la question  du groupe',function(data){
          $('#datatableshow2').DataTable().ajax.url(racine+'pegroupequestions/getQuestionDT/'+data.id_groupe+'/all').load();
          getTheContent('pegroupequestions/getQuestionOfGroupes/'+data.id_groupe ,'#pegroupequestions');
        });
}

function printQuestions(idg,idsp){
      $("#checkboxQuestions").change(function() {
        if(this.checked) {
           ajouterQuestions(idg,idsp);
        }else{
          ajouterQuestions(idg,'all');
        }

      });
}

function exportQuestionnaire(idquestionnaire){
  alert(idquestionnaire);
   $.ajax({
        type: "get",
        url:racine +'pequestionnaires/ViewExportPDF/'+idquestionnaire,
        success: function () {
          alert('Questionnaire Exporter');
        },
        error:function (){
          alert('erreur du export');
        }
  });
}


function afficherAssociations(id) {
    $('.associations-valeur').hide();
    $('.association' + id).show();
    $('.association' + id +' .select2-selection__rendered').trigger('click');
}

function cacherAssociations() {
    $('.associations-valeur').hide();
}

function saveCorespondence(valeur,question){
  var options = [];
        $.each($(".valeur_association" +valeur+ " option:selected"), function () {
            options.push($(this).val());
  });
  if(!options.length)
    addValeurCorresspondence(question,valeur,'option_vide');
  else
    addValeurCorresspondence(question,valeur,options);
}


function addValeurCorresspondence(idquestion,idvaleur1,optionListe2){
  url = racine + "pequestions/saveCorrespodence/"+idquestion+"/"+idvaleur1+"/"+optionListe2;
  $.ajax({
      type: "GET",
      url:url,
      success : function(data){
        console.log(data);
        getTheContent('pequestions/getCorrespondencesDeListe1/'+idvaleur1,'#correspndence-'+idvaleur1);

      },
      error: function(error){
             alert('erreur');
      }
  });
}

// Ouvrire le modal d'une question
function openQuestionModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'questions/get/' + id ,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            getTheContent('questions/getQuestionTab/' + id + '/1', '#tab1');
            $('#main-modal').on('hidden.bs.modal', function () {
                $('.datatableshow').DataTable().ajax.reload();
            });
            //get content on tab click
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'un filtre
function openFiltreModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'filtres/get/' + id,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            getTheContent('filtres/getFiltreTab/' + id + '/1', '#tab1');
            $('#main-modal').on('hidden.bs.modal', function () {
                $('#datatableshow').DataTable().ajax.reload();
            });
            //get content on tab click
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'un user
function openUserModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'users/get/' + id,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            getTheContent('users/getUserTab/' + id + '/1', '#tab1');
            $('#main-modal').on('hidden.bs.modal', function () {
                $('#datatableshow').DataTable().ajax.reload();
            });
            //get content on tab click
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'un user
function openPasswordModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'users/resetpassword/' + id,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'ajout de profile a un user
function addProfileToUser(id) {
    $.ajax({
        type: 'get',
        url: racine + 'users/addProfileToUser/' + id,
        success: function (data) {
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            // $('#second-modal').on('hidden.bs.modal', function () {
            //     getTheContent('users/getUserTab/' + id + '/2', '#tab2');
            // });
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
                if ($(this).val() == 4){
                    $("#wilayas-section").hide();
                    $("#moughataas-section").hide();
                    $("#communes-section").hide();
                }
                else if ($(this).val() == 3){
                    $("#wilayas-section").show();
                    $("#moughataas-section").hide();
                    $("#communes-section").hide();
                }
                else if ($(this).val() == 2){
                    $("#wilayas-section").show();
                    $("#moughataas-section").show();
                    $("#communes-section").hide();
                }
                else{
                    $("#wilayas-section").show();
                    $("#moughataas-section").show();
                    $("#communes-section").show();
                }
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
//enregister un profil pour un user ajax
function saveProfileUser(id) {
    var container = 'second-modal';
    $('#' + container + ' #form-errors').hide();
    var element = $(this);
    // $(element).attr('disabled','disabled');
    $('#' + container + ' .form-loading').show();
    var data = $('#' + container + ' form').serialize();
    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: data,
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .form-loading').hide();
            $('#' + container + ' .answers-well-saved').show();
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
            }, 3500);
            getTheContent('users/getUserTab/' + id + '/2/' + data, '#tab2');
            $('#' + container).modal('toggle');
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#' + container + ' .form-loading').hide();
            // $(element).removeAttr('disabled');
        }
    });
}

// function editDroitAccess()
// {
//   $( '#show-droit-modal #form-errors' ).hide();
//   var element = $(this);
//   $("#show-droit-modal .form-loading").show();
//   var data = $('#show-droit-modal form').serialize();
//   $.ajax({
//     type: $('#show-droit-modal form').attr("method"),
//     url: $('#show-droit-modal form').attr("action"),
//     data: data,
//     success: function(data){
//       $('#show-droit-modal').modal('toggle');
//        var container = 'show-droit-modal';
//        $('#' + container + ' .form-loading').hide();
//        $('#' + container + ' .answers-well-saved').show();
//        setTimeout(function () {
//          $('#' + container + ' .answers-well-saved').hide();
//        }, 3500);
//        window.location.href = data;
//     },
//     error: function(data){
//         if( data.status === 422 ) {
//           var errors = data.responseJSON;
//           console.log(errors);
//           errorsHtml = '<div class="alert alert-danger"><ul>';
//           var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
//             errorsHtml += '<li>' + value[0] + '</li>';
//           });
//           errorsHtml += '</ul></div>';
//           $( '#show-droit-modal #form-errors' ).show().html( errorsHtml );
//         }
//         else
//         {
//         alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//         }
//     $("#show-droit-modal .form-loading").hide();
//     }
//   });
// }

function openProfilsModal(id,libelle){
  $.ajax({
    type: 'get',
    url: racine+'profilsAcces/droitsAcces/'+id,
    success: function (data)
    {
      $("#profil").html(libelle);
      listeDroitsAcces(id);
      $("#droitsModal .modal-body").html(data);
      $("#droitsModal").modal();
      resetInit();
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}

function listeDroitsAcces(id)
{
  $.ajax({
    type: 'get',
    url: racine+'profilsAcces/droitsAcces/getDroitsAcces/'+id,
    success: function (data) {
      $("#droitsAcces").html(data);
      setDataTable('#datatableshow');
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}

function newDroitsAcces(id)
{
  $.ajax({
    type: 'get',
    url: racine+'profilsAcces/droitsAcces/'+id+'/add',
    success: function (data) {
      $("#newDroitsAcces").html(data);
      $('tr').css('background-color','');
      $('.selectpicker').selectpicker({
        size: 4
      });
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}
function addDroitsAcces()
{
  $( '#droitsModal #form-errors' ).hide();
  var element = $(this);
  $("#droitsModal .form-loading").show();
  var data = $('#droitsModal form').serialize();
  $.ajax({
    type: $('#droitsModal form').attr("method"),
    url: $('#droitsModal form').attr("action"),
    data: data,
    success: function(data){
      listeDroitsAcces(data.ref_profils_acces_id);
      newDroitsAcces(data.ref_profils_acces_id);
      var container = 'droitsModal';
      $('#' + container + ' .form-loading').hide();
      $('#' + container + ' .answers-well-saved').show();
      setTimeout(function () {
        $('#' + container + ' .answers-well-saved').hide();
      }, 3500);
    },
    error: function(data){
      if( data.status === 422 ) {
        var errors = data.responseJSON;
        console.log(errors);
        errorsHtml = '<div class="alert alert-danger"><ul>';
        var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
          errorsHtml += '<li>' + value[0] + '</li>';
        });
        errorsHtml += '</ul></div>';
        $( '#droitsModal #form-errors' ).show().html( errorsHtml );
      }
      else
      {
        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
      }
      $("#droitsModal .form-loading").hide();
    }
  });
}

function openDroitAccessModal(id){
  $.ajax({
    type: 'get',
    url: racine+'droitsAcces/getDroit/'+id,
    success: function (data) {
      //listeMethodesParame(id);
      //newMethodeParame(id);
      $("#show-droit-modal .modal-header-body").html(data);
      $("#show-droit-modal").modal();

      $('.selectpicker').selectpicker({
         size: 4
      });
      $('#datatableshow').DataTable().ajax.url(racine + "droitsAcces/getDroitsAccesDT/" + id).load();
      InitLabo();
      unitesLabo();
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}

function editDroitAccess()
{
  $( '#show-droit-modal #form-errors' ).hide();
  var element = $(this);
  $("#show-droit-modal .form-loading").show();
  var data = $('#show-droit-modal form').serialize();
  $.ajax({
    type: $('#show-droit-modal form').attr("method"),
    url: $('#show-droit-modal form').attr("action"),
    data: data,
    success: function(data){
      $('#show-droit-modal').modal('toggle');
       var container = 'show-droit-modal';
       $('#' + container + ' .form-loading').hide();
       $('#' + container + ' .answers-well-saved').show();
       setTimeout(function () {
         $('#' + container + ' .answers-well-saved').hide();
       }, 3500);
        $('#datatableshow').DataTable().ajax.reload();
    },
    error: function(data){
        if( data.status === 422 ) {
          var errors = data.responseJSON;
          console.log(errors);
          errorsHtml = '<div class="alert alert-danger"><ul>';
          var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
            errorsHtml += '<li>' + value[0] + '</li>';
          });
          errorsHtml += '</ul></div>';
          $( '#show-droit-modal #form-errors' ).show().html( errorsHtml );
        }
        else
        {
        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    $("#show-droit-modal .form-loading").hide();
    }
  });
}

function openProfilsModal(id,libelle){
  $.ajax({
    type: 'get',
    url: racine+'profilsAcces/droitsAcces/'+id,
    success: function (data)
    {
      $("#profil").html(libelle);
      listeDroitsAcces(id);
      $("#droitsModal .modal-body").html(data);
      $("#droitsModal").modal();
      resetInit();
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}

function listeDroitsAcces(id)
{
  $.ajax({
    type: 'get',
    url: racine+'profilsAcces/droitsAcces/getDroitsAcces/'+id,
    success: function (data) {
      $("#droitsAcces").html(data);
      setDataTable('#datatableshow');
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}

function newDroitsAcces(id)
{
  $.ajax({
    type: 'get',
    url: racine+'profilsAcces/droitsAcces/'+id+'/add',
    success: function (data) {
      $("#newDroitsAcces").html(data);
      $('tr').css('background-color','');
      $('.selectpicker').selectpicker({
        size: 4
      });
    },
    error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
  });
}
function addDroitsAcces()
{
  $( '#droitsModal #form-errors' ).hide();
  var element = $(this);
  $("#droitsModal .form-loading").show();
  var data = $('#droitsModal form').serialize();
  $.ajax({
    type: $('#droitsModal form').attr("method"),
    url: $('#droitsModal form').attr("action"),
    data: data,
    success: function(data){
      listeDroitsAcces(data.ref_profils_acces_id);
      newDroitsAcces(data.ref_profils_acces_id);
      var container = 'droitsModal';
      $('#' + container + ' .form-loading').hide();
      $('#' + container + ' .answers-well-saved').show();
      setTimeout(function () {
        $('#' + container + ' .answers-well-saved').hide();
      }, 3500);
    },
    error: function(data){
      if( data.status === 422 ) {
        var errors = data.responseJSON;
        console.log(errors);
        errorsHtml = '<div class="alert alert-danger"><ul>';
        var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
          errorsHtml += '<li>' + value[0] + '</li>';
        });
        errorsHtml += '</ul></div>';
        $( '#droitsModal #form-errors' ).show().html( errorsHtml );
      }
      else
      {
        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
      }
      $("#droitsModal .form-loading").hide();
    }
  });
}
// 17-09-2018 Ahmedou
function selectProfilUser(element) {
    var id = $(element).val();
    $.ajax({
        type: 'get',
        url: racine + 'profilsAcces/selectProfilUser/' + id ,
        success: function (admin_etab) {
            if(admin_etab==1){
                $('.etablissements-container').show();
                $('.is_admin_etab').val("1");
            }
            else{
                $('.etablissements-container').hide();
                $('.is_admin_etab').val("0");
            }
            // $('.selectpicker').selectpicker({
            //     size: 4
            // });
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
}

function filterQst()
{
    nature = $("#filter-nature").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'questionslibres/getQuestionsLibresDT/' + nature ).load();
}

// Ouvrire le modal d'un questionnaire
function openQuestionnaireModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'questionnaires/get/' + id ,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            // $('#datatableshow').DataTable().ajax.url(racine + "questionnaires/getQuestionnairesDT/"+id).load();
            getTheContent('questionnaires/getQuestionnaireTab/' + id + '/1', '#tab1');
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'un employeur
function openEmployeurModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'employeurs/getEmployeur/' + id ,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            // $('#datatableshow').DataTable().ajax.url(racine + "questionnaires/getQuestionnairesDT/"+id).load();
            getTheContent('employeurs/getEmployeurTab/' + id + '/1', '#tab1');
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'une enquete
function openEnqueteModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'enquetes/get/' + id ,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal .modal-dialog").addClass("modal-xl");

            $("#main-modal").modal();
            // $('#datatableshow').DataTable().ajax.url(racine + "enquetes/getEnquetesDT/1/"+id).load();
            getTheContent('enquetes/getEnqueteTab/' + id + '/1', '#tab1');
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
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
// updating questionnaire order
function updateQuestionnaireElements(element = null) {
    var elements = $("#questionnaire-elements").sortable('toArray');
    var idqst = $("#questionnaire-elements").attr('idqst');
    var link = racine+"questionnaires/update";
    $.ajax({
        data: {list:elements,id:idqst},
        type: 'POST',
        url: link,
        success: function (data) {
            $('#questionnaire-elements').html(data);
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function updateChildElements(element = null) {
    var elements = $("#infochild-elements").sortable('toArray');
    var idqst = $("#infochild-elements").attr('idqst');
    var isroot = $("#infochild-elements").attr('isroot');
    var link = racine+"questionnaires/updateChildren";
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
function selectDeclancheur(element) {
    var ismultiple = $('option:selected', element).attr('ismultiple');
    if (ismultiple==1)
        $('#label-reponse-renvoi').html('Si le nombre de choix séléctionnés est supérieur ou égal à: <span class="required_field">*</span>');
    else
        $('#label-reponse-renvoi').html('Si la réponse de cette question est égale à: <span class="required_field">*</span>');
    $.ajax({
        type: 'GET',
        url: racine+"questionnaires/getRenvoiTargets/"+$(element).val(),
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
        var link = (!niveau) ? racine+"questionnaires/getInfoChild/"+id : racine+"questionnaires/getInfoChild/"+id+"/"+1;
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
function checkEnqueteParam(element) {
    if ($(element).is(':checked'))
        $('.parametre'+$(element).val()).removeAttr('disabled');
    else
        $('.parametre'+$(element).val()).attr('disabled',true);
    $('.selectpicker').selectpicker('refresh');
    updateTailleEnqueteOptimal();
}
function addQuestionnaire(element) {
    saveform(element, function(id){
        $('#datatableshow').DataTable().ajax.reload();
        $('#addNewQuestionnaireModal').modal('toggle');
        openQuestionnaireModal(id);
        // alert(id);
    });
}
function addNewEmployeur(element) {
    saveform(element, function(id){
        $('#datatableshow').DataTable().ajax.reload();
        $('#addNewEmployeurModal').modal('toggle');
        openEmployeurModal(id);
        // alert(id);
    });
}
function addQuestion(element) {
    saveform(element, function(id){
        $('#datatableshow').DataTable().ajax.reload();
        $('#addNewModal').modal('toggle');
        openQuestionModal(id);
        // alert(id);
    });
}
function addEnquete(element) {
    // $('#enquete-generate-warning').show();
    saveform(element, function(id){
        $('#datatableshow').DataTable().ajax.reload();
        $('#addNewEnqueteModal').modal('toggle');
        openEnqueteModal(id);
        // $('#enquete-generate-warning').hide();
    });
}
function editEnquete(element) {
    // $('#enquete-generate-warning').show();
    saveformenquete(element, function(id){
        $('#datatableshow').DataTable().ajax.reload();
        // setTimeout(function(){
        //     $('#enquete-generate-warning').hide();
        // }, 1000);
    });
}
function addEnqueteStagaire(element) {
    saveform(element, function(id){
        $('#main-modal #datatableshow').DataTable().ajax.reload();
        setTimeout(function(){
            $('#second-modal').modal('toggle');
        }, 2000);
    });
}
function updateTailleEnqueteOptimal() {
    var data = $('.form_enquete form').serialize();
    $.ajax({
        type: 'POST',
        url: racine + 'enquetes/updateTailleEnqueteOptimal',
        data: data,
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $('.input_population_maire').val(data.population_maire);
            $('.nb_population_maire').text(data.population_maire);
            $('.nb_taille_optimal').text(data.taille_optimal);
            $('.taille').val(data.taille_optimal);
        },
        error: function (data) {
                console.log(data);
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        }
    });
}
function addEnqueteStagaireModal(id) {
    // alert(2366);
    var link = racine+"enquetes/showFormAddStagaire/"+id;
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
function addSG(element) {
  var  container = $(element).attr('container');
  if(!$('#sous_groupe').val())
    alert('aucun sous groupe sélectionné');
  else
    saveform(element, function(id){
        $('#main-modal').modal('toggle');
        $('#jstree').jstree("refresh");
        // alert(id);
    });
}
function addNewGroup(element) {
    saveform(element, function(id){
        $('#addNewModal').modal('toggle');
        $('#jstree').jstree("refresh");
        $('.group-edit').val(id);
        openGroupQstEditModal();
        setTimeout(function () {
            $('#jstree #'+id+'_anchor').trigger( "click" );
        }, 1000);
    });
}
function editGroup(element) {
    saveform(element, function(id){
        $('#jstree').jstree("refresh");
    });
}
function addrenvoi(element) {
    saveform(element, function(id){
        getTheContent('questionnaires/getQuestionnaireTab/' + id + '/3', '#tab3');
    });
}
function addSGO(element) {
    $('#addsg .form-loading').show();
    $('#addsg .answers-well-saved').hide();
    var data = $('#addsg form').serialize();
    $.ajax({
        type: $('#addsg form').attr("method"),
        url: $('#addsg form').attr("action"),
        data: data,
        dataType: 'json',
        success: function(data){
            console.log(data);
            $('#addsg .form-loading').hide();
            $('#addsg .answers-well-saved').show();
            setTimeout(function(){
                $('#addsg .answers-well-saved').hide();
            }, 3500);
        },
        error: function(data){
            if( data.status === 422 ) {
                var errors = data.responseJSON;
                errorsHtml = '<ul>';
                var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#addsg #form-errors').html(errorsHtml);
            } else
                $('#addsg #form-errors').text('La requête n\'a pas abouti');
            $('#addsg #form-errors').show();
            setTimeout(function(){
                $('#addsg #form-errors').hide();
            }, 10000);
            $('#addsg .form-loading').hide();
        }
    });
}
function deleteRenvoi(id) {
    var link = "questionnaires/renvois/delete/"+id;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            getTheContent('questionnaires/getQuestionnaireTab/' + data + '/3', '#tab3');
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
//filter une echantilln
function filterStagiaireEnquete()
{
    specialite = $("#filter_specialite").val();
    promotion = $("#filter_promotion").val();
    etablissement = $("#filter_etablissement").val();
    niveau = $("#filter_niveau").val();
    etat = $("#filter_etat").val();
    genre = $("#filter_genre").val();
    id = $("#id_enquete").val();
    $('.datatableshow').DataTable().ajax.url(racine + 'enquetes/getEchantillonDT/' + id + '/' + promotion + '/' + etablissement + '/' + specialite + '/' + niveau + '/' + genre + '/' + etat + '/none').load();
}

function filterEnquete()
{
    etat = $("#filter-etat").val();
    is_globale = $("#filter-is_globale").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'enquetes/getEnquetesDT/' + is_globale + '/' + etat ).load();
}

function openSignalerAbscence(id) {
    var link = racine+"enquetes/signalerAbscence/"+id;
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
function enleverAbscence(id) {
    var link = racine+"enquetes/signalerAbscence/"+id;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            $('#main-modal .datatableshow').DataTable().ajax.reload();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function signalerAbscence(element) {
    saveform(element, function(id){
        setTimeout(function(){
            $('#second-modal').modal('toggle');
        }, 700);
    });
}
function changeEnqueteEtat(id,etat) {
    $('#tab1 .form-loading').show();
    var link = racine+"enquetes/changestat/"+ id +"/"+etat;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            getTheContent('enquetes/getEnqueteTab/' + data + '/1', '#tab1');
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function saveformenquete(element, aftersave=null) {
    var container = $(element).attr('container');
    $('#' + container + ' #form-errors').hide();
    var element = $(this);
    // $(element).attr('disabled','disabled');
    $('#' + container + ' .form-loading').show();
    var data = $('#' + container + ' form').serialize();
    var enqueteid=0;
    $(".enquete-progressbar .progress-bar").css('width','0%');
    $(".enquete-progressbar .progress-bar").attr('aria-valuenow',0);
    $(".enquete-progressbar .progress-bar sr-only").html('0%');
    $(".enquete-progressbar").show();
    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: data,
        dataType: 'json',
        // this is the magic bit that gives you access to the readyStates
        beforeSend: function(jqXHR, settings) {
            var self = this;
            var xhr = settings.xhr;
            settings.xhr = function() {
                var output = xhr();
                output.onreadystatechange = function() {
                    if (typeof(self.readyStateChanged) == "function") {
                        self.readyStateChanged(this);
                    }
                };
                return output;
            };
        },
        // listen to the readyStates
        readyStateChanged: function(jqXHR) {
        // if it's '3', then it's an update,
            if (jqXHR.readyState == 3) {
                // update an element with the last number the script output. The script output is contained in jqXHR.responseText.
                processing = jqXHR.responseText.split(",")[jqXHR.responseText.split(",").length-1];
                enquete_id = processing.split(";")[processing.split(";").length-1];
                enquete_id = enquete_id.replace('"', '');
                progress = processing.split(";")[processing.split(";").length-2];
                $(".enquete-progressbar .progress-bar").css('width',progress+'%');
                $(".enquete-progressbar .progress-bar").attr('aria-valuenow',progress);
                $(".enquete-progressbar .progress-bar sr-only").html(progress+'%');
                console.log(processing);
                console.log(enquete_id);
                enqueteid = enquete_id;
            }
        },
        complete: function () {
            $(".enquete-progressbar .progress-bar").css('width','100%');
            $(".enquete-progressbar .progress-bar").attr('aria-valuenow',100);
            $(".enquete-progressbar .progress-bar sr-only").html('100%');
            setTimeout(function () {
                $(".enquete-progressbar").hide();
            }, 1500);
        },
        success: function (data) {
            console.log(data);
            $('.datatableshow').DataTable().ajax.reload();
            $('#' + container + ' .form-loading').hide();
            $('#' + container + ' .answers-well-saved').show();
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
            }, 3500);
            if (aftersave) {
                aftersave(enqueteid);
            }
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#' + container + ' #form-errors').show().html(errorsHtml);
            } else {
                if($(".enquete-progressbar .progress-bar").attr('aria-valuenow')){
                    $('.datatableshow').DataTable().ajax.reload();
                    $('#' + container + ' .form-loading').hide();
                    $('#' + container + ' .answers-well-saved').show();
                    setTimeout(function () {
                        $('#' + container + ' .answers-well-saved').hide();
                        $(".enquete-progressbar .progress-bar").css('width','0%');
                        $(".enquete-progressbar .progress-bar").attr('aria-valuenow',0);
                        $(".enquete-progressbar .progress-bar sr-only").html('0%');
                    }, 3500);
                    if (aftersave) {
                        aftersave(enqueteid);
                    }
                }
                else
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#' + container + ' .form-loading').hide();
        }
    });
}
function launchGenerating(id) {

    // var container = $(element).attr('container');
    // $('#' + container + ' #form-errors').hide();
    $('#enquete-generate-warning').show();
    var element = $(this);
    $(element).attr('disabled','disabled');
    $('#generation-loading').show();
    // var data = $('#' + container + ' form').serialize();
    // var enqueteid=0;
    $(".enquete-progressbar .progress-bar").css('width','0%');
    $(".enquete-progressbar .progress-bar").attr('aria-valuenow',0);
    $(".enquete-progressbar .progress-bar sr-only").html('0%');
    $(".enquete-progressbar").show();
    $.ajax({
        type: 'get',
        url: racine + 'enquetes/launchGenerating/'+id,
        dataType: 'json',
        // this is the magic bit that gives you access to the readyStates
        beforeSend: function(jqXHR, settings) {
            var self = this;
            var xhr = settings.xhr;
            settings.xhr = function() {
                var output = xhr();
                output.onreadystatechange = function() {
                    if (typeof(self.readyStateChanged) == "function") {
                        self.readyStateChanged(this);
                    }
                };
                return output;
            };
        },
        // listen to the readyStates
        readyStateChanged: function(jqXHR) {
        // if it's '3', then it's an update,
            if (jqXHR.readyState == 3) {
                // update an element with the last number the script output. The script output is contained in jqXHR.responseText.
                processing = jqXHR.responseText.split(",")[jqXHR.responseText.split(",").length-1];
                enquete_id = processing.split(";")[processing.split(";").length-1];
                enquete_id = enquete_id.replace('"', '');
                progress = processing.split(";")[processing.split(";").length-2];
                $(".enquete-progressbar .progress-bar").css('width',progress+'%');
                $(".enquete-progressbar .progress-bar").attr('aria-valuenow',progress);
                $(".enquete-progressbar .progress-bar sr-only").html(progress+'%');
                console.log(processing);
            }
        },
        complete: function () {
            $(".enquete-progressbar .progress-bar").css('width','100%');
            $(".enquete-progressbar .progress-bar").attr('aria-valuenow',100);
            $(".enquete-progressbar .progress-bar sr-only").html('100%');
            setTimeout(function () {
                $(".enquete-progressbar").hide();
                $('#enquete-generate-warning').hide();
            }, 1500);
        },
        success: function (data) {
            console.log(data);
            $('.datatableshow').DataTable().ajax.reload();
            $('#generation-loading').hide();
            $('#generation-well-saved').show();
            setTimeout(function () {
                $('#generation-well-saved').hide();
            }, 3500);
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors);
            } else {
                if($(".enquete-progressbar .progress-bar").attr('aria-valuenow')){
                    $('.datatableshow').DataTable().ajax.reload();
                    $('#generation-loading').hide();
                    $('#generation-well-saved').show();
                    setTimeout(function () {
                        $('#generation-well-saved').hide();
                        $(".enquete-progressbar .progress-bar").css('width','0%');
                        $(".enquete-progressbar .progress-bar").attr('aria-valuenow',0);
                        $(".enquete-progressbar .progress-bar sr-only").html('0%');
                    }, 3500);
                }
                else
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#generation-loading').hide();
        }
    });
}
// function deleteObject(link,aftersave=null) {
//     $.ajax({
//         type: 'GET',
//         url: link,
//         success: function (data) {
//             $.alert(data,'Elément bien supprimé');
//             if (aftersave) {
//                 aftersave();
//             }
//         },
//         error: function (data) {
//             if( data.status === 422 ) {
//                 var errors = data.responseJSON;
//                 errorsHtml = '<ul>';
//                 var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
//                     errorsHtml += '<li>' + value[0] + '</li>';
//                 });
//                 errorsHtml += '</ul>';
//                 $.alert(errorsHtml);
//             } else
//                 $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
//         }
//     });
// }
function deleteGroupe(element){
    deleteObject($(element).attr('href'), function(){
        hideBtn(".btn-groups");
        $('#jstree').jstree("refresh");
    });
}
function selectEmployeur(element){
  SelectEmployeurFromId($(element).val());
}
function SelectEmployeurFromId(id){
    $('#employeur-loading').show();
    $.ajax({
        type: 'get',
        url: racine + 'employeurs/selectEmployeur/' + $('#echantillon_id').val() + '/' + id ,
        success: function (data) {
            $('#employeur-well-saved').show();
            $("#detail-employeur").html(data);
            setTimeout(function () {
                $('#employeur-well-saved').hide();
            }, 3500);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        },
        complete: function () {
            $('#employeur-loading').hide();
        },
    });
}
function showFormNewEmployeur(){
    $('#employeur-loading').show();
    $("#employeurs").val([]);
    $('.selectpicker').selectpicker('refresh');
    $.ajax({
        type: 'get',
        url: racine + 'employeurs/formNewEmployeur',
        success: function (data) {
            $("#detail-employeur").html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        },
        complete: function () {
            $('#employeur-loading').hide();
        },
    });
}
function addEmployeurFromEnquete(element) {
    saveform(element, function(id){
        SelectEmployeurFromId(id);
        refreshListeEmployeurs(id);
    });
}
function editEmployeurFromEnquete(element) {
    saveform(element, function(id){
        // SelectEmployeurFromId(id);
        refreshListeEmployeurs(id);
    });
}
function refreshListeEmployeurs(id){
    $.ajax({
        type: 'get',
        url: racine + 'employeurs/getListe/' + id ,
        success: function (data) {
            $("#employeurs-container").html(data);
            resetInit();
        }
    });
}
function showSaveButton(id){
    $('#save-button').show();
    $('#editEmployeurFromEnquete fieldset').removeAttr('disabled');
}

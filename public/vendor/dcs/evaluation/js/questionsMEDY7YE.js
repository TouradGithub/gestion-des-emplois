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
        success: function (admin) {
            if(admin==1){
                $('.structure-container').hide();
                $('.is_admin').val("1");
            }
            else{
                $('.structure-container').show();
                $('.is_admin').val("0");
            }
            // $('.selectpicker').selectpicker({
            //     size: 4
            // });
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
}
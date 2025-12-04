function openDetailsModal(url) {
    $('.btn-add').attr('disabled', 'disabled');
    $('.btn-add .main-icon').hide();
    $('.btn-add .spinner-border').show();
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            $("#main-modal .modal-dialog").addClass("modal-lg");
            $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
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

function synchronisationGlobal(element,sync_etudiants=0) {
    $(element).attr('disabled', 'disabled');
    var  container = $(element).attr('container');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
          if (sync_etudiants){
              console.log('synchronisation des etudiants');
              syncEtudiantGlobal();
              return;
          }

    $.ajax({
        url:"/synchronisations/syncGlobal",
        type:"post",
        success:function (data){
            // reload page
              if (data.success) {
                  window.location.reload();
                  $('#' + container + ' .answers-well-saved').show();
              }
            //
        },
        error:function (data){
            $('#' + container + ' .answers-well-saved').hide();
            alert("Error")
        },
        complete:function (){
            $('#' + container + ' .spinner-border').hide();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
        }
    });
}

function syncEtudiantGlobal() {
    $.ajax({
        url:"/synchronisations/syncEtudiantsGlobal",
        type:"post",
        success:function (){
           window.location.reload();
        },
        error:function (){
          alert("Error")
        },
        complete:function (){
            $('#sync_etu').removeAttr('disabled');
        }
    });

}

function syncEtudiant(id) {
    let btn = "#sync_etu";
    $(btn).attr('disabled', 'true');
    //  $(btn+' #sync_icon').hide();
    //  $(btn+' .btn-add .spinner-border').show();
    $.ajax({
        url:"/synchronisations/syncEtudiant",
        type:"post",
        data:{id},
        success:function (data){
            console.log(data)
        },
        error:function (){
            alert("Error")
        },
        complete:function (){
            $('#sync_etu').removeAttr('disabled');
        }
    });

}


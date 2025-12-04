// Ouvrire le modal ajout document
function openDocumentModal(id_objet,type_obj) {

    // alert(id);
    $.ajax({
        type: 'get',
        url: racine + 'documents/get_document/'+id_objet +"/" + type_obj,
        success: function (data) {
            $("#document_div").html(data);
            $("#document_div").show();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function closeDivGED(){
    // alert("ghhhhhhhh");
    $('#divGED').remove();
}


function addDocument(){
    $( '#document_div #form-errors' ).hide();
    // var element = $(this);
    $('#document_div  .main-icon').hide();
    $("#document_div .spinner-border").show();
    // var data = $('#document_div form').serialize();
    $.ajax({
        type: $('#document_div form').attr("method"),
        url: $('#document_div form').attr("action"),
        data: new FormData($('#document_div form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function(data){
            // alert(racine + "documents/getDocuments/"+ data.objet_id + '/'  + data.type_objet);
            $('#document_div .spinner-border').hide();
            $('#document_div .answers-well-saved').show();
            setTimeout(function () {
                $('#document_div .answers-well-saved').hide();
                $('#document_div .main-icon').show();
            }, 3500);
            var form = document.getElementById("addpiece");
            form.reset();
            $('.datatableshow4').DataTable().ajax.reload();
            //$('#datatableshow_ged').DataTable().ajax.reload();
             $('#datatableshow_ged').DataTable().ajax.url(racine + "documents/getDocuments/"+ data.objet_id + '/'  + data.type_objet+'/'+ data.id).load();

            $("#document_div").hide();

        },
        error: function(data){
            if( data.status === 422 ) {
                var errors = data.responseJSON;
                console.log(errors.errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $( '#document_div #form-errors' ).show().html( errorsHtml );
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#document_div .spinner-border").hide();
            $('#document_div .main-icon').show();
        }
    });
}

function deleteDocument (element) {
    // alert(element);
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer ce document ? ");
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'documents/deleteDocument/'+element,
            success: function (data)
            {
                $('.datatableshow4').DataTable().ajax.reload();
                $('#datatableshow_ged').DataTable().ajax.reload();
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    $.each( errors, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#fichlaboModal #form-errors' ).show().html( errorsHtml );
                }
                else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            }
        });
    };
}

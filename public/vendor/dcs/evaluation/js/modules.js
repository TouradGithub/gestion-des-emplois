function resetInitModule()
{

}

function filterSpecialiteModule(id_module)
{
    var id_specialite = $("#filterIdSpecialite").val();
    $('.datatableshow2').DataTable().ajax.url(racine+"modules/getSpecialiteModuleDT/"+id_module+"/none/"+id_specialite).load();
}

function openAddSpecialiteModule(id)
{
	$.ajax({
        type:'get',
        url: racine + 'modules/getFormAddSpecialite/'+ id,
        success:function(data){
            container = "#" + 'second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable(".datatablespecialitesnon-affecte");
           // resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function affecterSpecialiteModule(id)
{
    $( '#divAffecter #form-errors' ).hide();
        var element = $(this);
        $("#divAffecter .form-loading").show();
        var data = $('#divAffecter form').serialize();
        $.ajax({
            type: $('#divAffecter form').attr("method"),
            url: $('#divAffecter form').attr("action"),
            data: data,
            success: function(data)
            {
                $('#divAffecter .form-loading').hide();
                $('#divAffecter .answers-well-saved').show();
                setTimeout(function ()
                {
                    $('#divAffecter .answers-well-saved').hide();
                }, 3500);
                $('.datatablespecialitesnon-affecte').DataTable().ajax.url(racine+"modules/listeSpecialiteNonAffectesDT/"+id+"/all").load();
                $('.datatablespecialitesnon-affecte').selectpicker('refresh');
                $('.message').html(data.msg);
                $('.message').show();
				$('.datatableshow2').DataTable().ajax.reload();
            },
            error: function(data)
            {
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors.errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#divAffecter #form-errors' ).show().html( errorsHtml );
                }
                else
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#divAffecter .form-loading").hide();
            }

        });

}



function checkAllSpecialites(element)
{
	if ($(element).is(':checked')) {
		$('.btnAffecterSpecialites').show();
	}
	else
	{
		var str = 0;

		$(':checkbox').each(function() {
			str += this.checked ? 1 : 0;
		});
		if (str == 0) {
			$('.btnAffecterSpecialites').hide();
		}
		else
			$('.btnAffecterSpecialites').show();

	}
}


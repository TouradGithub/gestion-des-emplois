function resetInitModule(){}

function filterFiliere()
{
	var id_filiere = $("#id_filiere").val();
    $('#datatableshow').DataTable().ajax.url(racine+"specialites/getDT/none/"+id_filiere).load();
}

function openAffecteModuleSpecialite(id){

	$.ajax({
        type:'get',
        url: racine + 'specialites/getFormAffecteModules/'+ id,
        success:function(data){
            container = "#" + 'second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable(".dataTableModule-Non-affecte");
           // resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function affecterModuleSpecialite(id)
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
                $('.dataTableModule-Non-affecte').DataTable().ajax.url(racine+"specialites/listeModulesNonAffectesDT/"+id+"/all").load();
                $('.dataTableModule-Non-affecte').selectpicker('refresh');
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



function checkAllModules(element)
{
	if ($(element).is(':checked')) {
		$('.btnAffecterModules').show();
	}
	else
	{
		var str = 0;

		$(':checkbox').each(function() {
			str += this.checked ? 1 : 0;
		});
		if (str == 0) {
			$('.btnAffecterModules').hide();
		}
		else
			$('.btnAffecterModules').show();

	}
}
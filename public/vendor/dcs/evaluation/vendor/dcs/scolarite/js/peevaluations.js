function filterSpecialite() {
    var id_specialite = $("#id_specialite").val();
    $('#datatableshow').DataTable().ajax.url(racine+"peevaluations/getDT/none/"+id_specialite).load();
}

function filterProgrammeFormateurs(id) {
    var id_evaluation = id;
  //  alert(id_evaluation);
    var id_etablissement = $("#filterIdEtablissement").val();
    var id_wilaya = $("#filterIdWilaya").val();
    var id_specialite = $("#filterIdSpecialite").val();

    $('.datatableshow2').DataTable().ajax.url(racine+"peevaluations/getFormateursDT/"+id_evaluation+"/none/"+id_etablissement+"/"+id_wilaya+"/"+id_specialite).load();
}

function filterProgrammeFormateursByWilaya(id_evaluation)
{
    var id_wilaya = $("#filterIdWilaya").val();
    $.ajax({
        type:'get',
        url: racine + 'peevaluations/getListeEtablissementWilaya/'+ id_evaluation +"/"+id_wilaya,
        success:function(data)
        {
           // dd(data);
            $("#filterIdEtablissement").html(data);
            $("#filterIdEtablissement").selectpicker('refresh');
            filterProgrammeFormateurs(id_evaluation);
            //resetInit();
        },
    });
}

function filterFormateursNonAffecte(id_evaluation){
	var id_etablissement = $("#filterIdEtablissementFormateursNonAffecte").val();
    var id_wilaya = $("#filterIdWilayaFormateursNonAffecte").val();
    var id_specialite = $("#idSpecialiteFormateursNonAffecte").val();
    $('.datatableformateursnon-affecte').DataTable().ajax.url(racine+"peevaluations/getListeFormateurs/"+id_evaluation+"/none/"+id_etablissement+"/"+id_wilaya+"/"+id_specialite).load();
}

function filterProgrammeFormateursNonAffecteByWilaya(id_evaluation)
{
	 var id_wilaya = $("#filterIdWilayaFormateursNonAffecte").val();
    $.ajax({
        type:'get',
        url: racine + 'peevaluations/getListeEtablissementWilaya/'+ id_evaluation +"/"+id_wilaya,
        success:function(data)
        {
           // dd(data);
            $("#filterIdEtablissementFormateursNonAffecte").html(data);
            $("#filterIdEtablissementFormateursNonAffecte").selectpicker('refresh');
            filterFormateursNonAffecte(id_evaluation);
            //resetInit();
        },
    });
}

function openListeFormateurs(id)
{
    $.ajax({
        type:'get',
        url: racine + 'peevaluations/getListeFormateurs/'+ id,
        success:function(data){
            container = "#" + 'second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable(".datatableformateursnon-affecte");
           // resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}



function gererResultatsFormateursAffecte(id)
{
	$('#tab1 .form-loading').show();
    var link = racine+"peevaluations/resultatFormateursAffecte/"+ id;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data)
		{

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function changeEtatProgrammeEvaluation(id,etat)
{
	gererResultatsFormateursAffecte(id);
    $('#tab1 .form-loading').show();
    var link = racine+"peevaluations/changeEtatProgramme/"+ id +"/"+etat;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data)
		{
            getTheContent('peevaluations/getTab/' + data + '/1', '#tab1');
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function DevaliderProgrammeEvaluation(id,etat)
{
	$('#tab1 .form-loading').show();
    var link = racine+"peevaluations/changeEtatProgramme/"+ id +"/"+etat;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data)
		{
            getTheContent('peevaluations/getTab/' + data + '/1', '#tab1');
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function resetInitModule()
{

}

function getNbreQuestions()
{
	var id = $("#pr_pe_questionnaire_id").val();
	if(id != 'all'){
	//alert(id);
		$.ajax({
			type:'get',
			url: racine + 'peevaluations/getNbreQuestions/'+ id,
			success:function(data)
			{
			   $("#nbreQuestions").removeAttr('disabled');
   			 $("#nbreQuestions").attr('max',data);
   			 $("#nbreQuestions").val(data);
			},
			error: function () {
				$.alert("Error");
			}
		});
		return false;
	}
	else
	{
		$("#divNbreqestions").html('');
	}

}



function affecterFormateursProgrammeEvaluation(id_evaluation)
{
    $( '#divAffecter #form-errors' ).hide();
        var element = $(this);
/*
		saveform(element, function (id) {
				$(datatable).DataTable().ajax.reload();
				$(element).attr('disabled', 'disabled');
				setTimeout(function () {
					$('#divAffecter .answers-well-saved').hide();
				}, 1500);
			}); */
        //saveform(element,updateDT);

        // $(element).attr('disabled','disabled');
        $("#divAffecter .form-loading").show();
        var data = $('#divAffecter form').serialize();
        $.ajax({
            type: $('#divAffecter form').attr("method"),
            url: $('#divAffecter form').attr("action"),
            data: data,
           // alert(data);
            // dataType: 'json',
            success: function(data){
                // alert(data);
                // window.location.href = data;
                // $('#datatableshow').DataTable().ajax.reload();
                $('#second-modal').modal('toggle');

                /*$('#divAffecter .form-loading').hide();
                $('#divAffecter .answers-well-saved').show();
                setTimeout(function ()
                {
                    $('#divAffecter .answers-well-saved').hide();
                }, 3500);*/
                // $('#datatableshow').DataTable().ajax.reload();
                $('.datatableshow2').DataTable().ajax.url(racine +  'peevaluations/getFormateursDT/'+ data).load();
                $('.selectpicker').selectpicker('refresh');
                $('.message').html(data.msg);
                $('.message').show();
				$('.datatableshow2').selectpicker('refresh');
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
                // $(element).removeAttr('disabled');
            }

        });


}



function checkAllFormateurs(element)
{
	if ($(element).is(':checked')) {
		$('.btnAffecterFormateurs').show();
	}
	else
	{
		var str = 0;

		$(':checkbox').each(function() {
			str += this.checked ? 1 : 0;
		});
		if (str == 0) {
			$('.btnAffecterFormateurs').hide();
		}
		else
			$('.btnAffecterFormateurs').show();

	}
}

function validerProgrammeEvaluation(id)
{
    // alert(id);
    $('#tab1 .form-loading').show();
    $('#btnValider').hide();
    $('#alertValidation').removeClass('d-none');
    var link = racine+"peevaluations/resultatFormateursAffecte/"+ id;

    $.ajax({
        type: 'GET',
        data: { notation_id: $('#notation_id').val() },
        url: link,
        success: function (data)
        {
            getTheContent('peevaluations/getTab/' + data + '/1', '#tab1');
            resetInit();
        },
        error: function () {
            $('#tab1 .form-loading').hide();
            $('#btnValider').show();
            $('#alertValidation').addClass('d-none');
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

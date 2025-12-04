


function  get_pe_static(){
	$('#basicModal_s').modal('show');
    $("#resutClick_s").html('<div id="loading1" class="loading1 text-center" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p> Chargement en cours </p></div>').fadeIn('fast');
	var data = $('#formst').serialize();
	$.ajax({
        type: $('#formst').attr("method"),
        url: $('#formst').attr("action"),
        data: data,
        success: function(data){
            $("#resutClick_s").html("");
            $("#form-errors").html("");
            $("#resutClick_s").html(data);
        },
        error: function(data){
			setTimeout(function () {
	            $('#basicModal_s').modal('hide');
            }, 500);
        	if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#addForm #form-errors').show().html(errorsHtml);
            }
            else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $('#addForm .spinner-border').hide();
            $('#addForm .main-icon').show();
        }
    });
}

function resetInitModule(){

}

function get_filtre_module(){
    // var id_competence = $("#").val();
    // var id_specialite = $("#").val();
    // var id_centre = $("#").val();
    $("#button_submit").show();
    var id_model = $("#static").val();
    if(id_model == 1){
        $("#competence").hide();
        $("#specialite").hide();
        $("#centre").show();
    }
    if(id_model == 2){
        $("#competence").show();
        $("#specialite").hide();
        $("#centre").hide();
    }
    if(id_model == 3){
        $("#competence").hide();
        $("#specialite").show();
        $("#centre").hide();
    }
}

function resetInitModule(){
}
function print_etablissement(etablissement_id){
	var modulee = "gestion.etablissements";
	 openEtabModal(etablissement_id);
}

function affectation_etudiant(link ,text, element){
    var etablissement_id_new = $('#etablissement_id_new').val();
    var etablissement_id_old = $('#etablissement_id_old').val();
    var stagiaire_id = $('#stagiaireid').val();
    link +="/"+etablissement_id_old+"/"+etablissement_id_new+"/"+stagiaire_id; 
    $("#affectation").attr('disabled', 'disabled');
    $('#affectation .main-icon').hide();
    $('#affectation .spinner-border').show();
    if(etablissement_id_new)
        confirmAction(link,text, function(){ 
            var msg = '<div class="alert alert-success">etudiant affecté</div>';
            $('#affectation_msg').html(msg);
            $('#affectation .spinner-border').hide();
            $('#affectation .answers-well-saved').show();
            $("#affectation").removeAttr('disabled');
            setTimeout(function () {
                $('#affectation .answers-well-saved').hide();
                $('#affectation .main-icon').show();
            }, 3500);


        });
    else
        alert("Selectionner un etablissement....");
}

    $(document).ready(function () {
        	$('.formations-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            var link = $(e.target).attr("link");
            var container = $(e.target).attr("href");
            // alert(link);
            // alert(container);
            $(container).empty();
            getTheContent(link, container);
        });
    });

    
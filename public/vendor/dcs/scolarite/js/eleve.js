function info_card(data) {
    $("#full_name").html(data.full_name);
    $("#surname").html(data.matricule);
    $("#titre_c").html(data.numero);
    $("#profil_info").html(data.info_right);
}

function changeLieu() {
    if ($('.checkLieu').is(':checked')) {
        $('.divPays').hide();
        $('.divRIM').show();
    } else {
        $('.divPays').show();
        $('.divRIM').hide();
    }
}

function info_formation(data)
{

    getTheContent('eleves/getTab/' + data.stagid + '/2',"#eleves_tab2");

}
function getSpecialiteEtab() {

    var etab = $('#b_etablissements_id').val();
    $.ajax({
        type: 'get',
        url: racine + 'eleves/formations/getSpecialites/' + etab,
        success: function (data) {
            // alert(data.speci);
            $("#b_specialites_id").html(data.speci);
            $("#ref_niveaux_formations_id").html(data.niveau);
            $('#b_specialites_id').selectpicker('refresh');
            $('#ref_niveaux_formations_id').selectpicker('refresh');
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
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

function hideContenteDiv(id)
{
    $("#"+id).hide('slow').show('slow');
    $("#"+id).html("");
}

function saveAndRefreshTabEleve(element, tab) {
    saveform(element, function (id) {
        getTheContent('eleves/getTab/' + id + '/' + tab,'#eleves_tab'+tab)
    })
}

function saveFormationEleve(element) {
    saveform(element,function () {
        $('#eleves_link2').trigger('click');
    });
}

function addSuiviStragiaire(element) {
    saveform(element,function () {
        refreshDatatable('.datatableshow2');
        $('#new_suivi').html('');
        $('#eleves_link2').trigger('click');
        $('#second-modal').modal('hide');
    } );
}

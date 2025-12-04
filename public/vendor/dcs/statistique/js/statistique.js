$(document).ready(function () {
    selection_type_st(1);
    var startDate = new Date();
    var fechaFin = new Date();
    var FromEndDate = new Date();
    var ToEndDate = new Date();
    $('#promotion').datepicker({
        autoclose: true,
        minViewMode: 1,
        format: 'mm/yyyy'
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        // $('.to').datepicker('setStartDate', startDate);
    });

});

function get_type_st_model(id=null)
{
    if(id == null)
        id = $("#model_st").val();
    $.ajax({
        type: 'get',
        url: racine+'statistiques/get_type_st_model/'+id,
        cache: false,
        success: function(data)
        {
            $('#type_st_model').html('');
            $('#type_st_model').html(data.data);
            if(parseInt(data.id)==6){
                $("#com").empty();
                $(".frm_filtre").hide();
            }
            else{
                selection_type_st(parseInt(data.id));
            }
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}
function formule()
{
    id = $("#agregation_moughataa").val();
    alert(id)

}

$('.checktypeexamen').click(function(){

    if($(this).is(':checked'))
    {
        $('.typeexamen').removeAttr("disabled");
    }
    else
    {
        $('.typeexamen').attr("disabled", "disabled");
    }
});

$('.checktranche_age').click(function(){

    if($(this).is(':checked'))
    {
        $('.tranche_age').removeAttr("disabled");
    }
    else
    {
        $('.tranche_age').attr("disabled", "disabled");
    }
});

function getSource(element)
{
    id = $(element).val();
    if(id == 0)
    {
        $("#source_tables").show();
        $("#source_champs_table").show();
        $(".form_agregation").hide();
        $(".formule_calcul_stat").hide();
    }
    else {
        $("#source_tables").hide();
        $("#source_champs_table").hide();
        $(".form_agregation").show();
        $(".formule_calcul_stat").show();
    }
}


/*$('.to').datepicker({
    autoclose: true,
    minViewMode: 1,
    format: 'mm/yyyy'
}).on('changeDate', function(selected){
    FromEndDate = new Date(selected.date.valueOf());
    FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
    $('.from').datepicker('setEndDate', FromEndDate);
});*/






function get_type_st() {


    s_id = $('#type_st').val();
    // alert(s_id);
    id = parseInt(s_id);
    switch (id) {
        case 1:
            selection_type_st(id);
            break;
        case 2:
            selection_type_st(id);
            break;
        case 3:
            selection_type_st(id);
            break;
        case 4:
            selection_type_st(id);
            break;
        case 5:
            selection_type_st(id);
            break;
        case 6:

            $("#com").empty();
            $(".frm_filtre").hide();
            break;
        case 7:
            id_enquete =$('#id_enquete').val();
            selection_type_st(id,id_enquete);
            break;
    }
    /* if(id !=6)
      selection_type_st(id);
     else {
         //alert(1)

     }*/

}

function selection_type_st(id,id_objet=null)
{
    $(".frm_filtre").show();
    for (i=1; i<=9; i++)
    {
        if(i != id)
        {
            string = get_id_select(i);
            //alert(string);
            array_tab =  string.split("+");
            id_delect=array_tab[0];
            id_div=array_tab[1];
            $('#id_delect option[value=all]').prop('selected', 'selected').change();
            $("#"+id_div).show();
        }
    }

    string = get_id_select(id);
    array_tab =  string.split("+");
    id_delect=array_tab[0];
    id_div=array_tab[1];
    $('#id_delect option[value=all]').prop('selected', 'selected').change();
    $("#"+id_div).hide();
    get_objets(id,id_objet);

}

function get_promos() {

    id_objet=$("#id_enquete").val();
    id=$("#type_st").val();

    if(id == 7)
    {
        $.ajax({
            type: 'get',
            // url: racine+'statistiques/get_objets/'+id+"/"+id_objet,
            cache: false,
            success: function(data)
            {

                $('#com').html('');
                $('#com').html(data);
            },
            error: function () {
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    }
}
function get_objets(id,id_objet)
{

    switch (id) {
        case 6 :
            $('#com').hide();
            break;
        case 7:
            $('#com').show();

            $.ajax({
                type: 'get',
                // url: racine+'statistiques/get_objets/'+id+"/"+id_objet,
                cache: false,
                success: function(data)
                {

                    $('#com').html('');
                    $('#com').html(data);
                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
            break;
        default:
            $('#com').show();
            $.ajax({
                type: 'get',
                // url: racine+'statistiques/get_objets/'+id+"/"+id_objet,
                cache: false,
                success: function(data)
                {

                    $('#com').html('');
                    $('#com').html(data);
                },
                error: function () {
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
            break;
    }
    /* if(id != '6')
     {
         $('#com').show();
         $.ajax({
             type: 'get',
             // url: racine+'statistiques/get_objets/'+id,
             cache: false,
             success: function(data)
             {

                 $('#com').html('');
                 $('#com').html(data);
             },
             error: function () {
                 $.alert("{{ trans('message_erreur.request_error') }}");
             }
         });
     }
     else
     {
         $('#com').hide();
     }*/

}
function diabled_st(id)
{

    for (i=1; i<=3; i++ )
    {

        n_f = get_id_select(i);
        if(id != i)
            $("#"+n_f).removeAttr('disabled');

    }
    $("#etablissement").hide();
    // $("#"+n_f).attr('disabled', 'disabled');
}
function get_id_select(id)
{
    //alert(id);
    rep='';
    switch (id) {
        case 1:
            rep='id_etab+etablissement';
            break;
        case 2:
            rep='filiere_id+filiere';
            break;
        case 3:
            rep='specialite_id+specialite';
            break;
        case 4:
            rep='niveau_form_id+niveau_formation';
            break;
        case 5:
            rep='genre_id+genre';
            break;
        /*case 6:
            rep='id_etab';
            break;
        case 7:
            rep='id_etab';
            break;*/

    }
    return rep;
}

function get_niveau_des(element)
{
    id = $(element).val();
    switch (id)
    {
        case '1' :
            get_niveau_zone_geo();
            $("#zones").hide();
            $("#groupes_esp").hide();

            break;
        case '2' :
            $("#zones").show();
            $("#groupes_esp").hide();
            get_niveau_pays();
            break
    }
}

function get_niveau_esp(element)
{
    id = $(element).val();
    switch (id)
    {
        case '1' :
            get_niveau_groupes();
            $("#groupes_esp").hide();
            $("#zones").hide();
            break;
        case '2' :
            $("#groupes_esp").show();
            $("#zones").hide();
            get_niveau_espece();
            break
    }
}
function get_espece(element)
{
    id = $(element).val();
    if(id==0)
    {
        get_niveau_espece();
    }else{
        get_niveau_espece(id);
    }
}
function get_pays(element)
{
    id = $(element).val();
    if(id==0)
    {
        get_niveau_pays();
    }else{
        get_niveau_pays(id);
    }
}

function get_st_par_type(element)
{
    id = $(element).val();
    switch (id)
    {
        case '1' :
            $("#niveau_des").show();
            $("#niveau_esp").hide();
            $("#zones").hide();
            $("#groupes_esp").hide();
            $("#niveau").prop("selectedIndex", 0);
            get_niveau_zone_geo();
            break;
        case '2' :
            $("#zones").hide();
            $("#groupes_esp").hide();
            $("#niveau_des").hide();
            $("#niveau_esp").show();
            $("#niveau_es").prop("selectedIndex", 0);
            get_niveau_groupes();
            break;
        case '3' :
            $("#zones").hide();
            $("#groupes_esp").hide();
            $("#niveau_des").hide();
            $("#niveau_esp").hide();
            get_exportateurs();
            break;
    }
}
function get_niveau_pays(zone="none")
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_pays/'+zone,
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}

function get_niveau_espece(groupe='none')
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_espece/'+groupe,
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}

function get_niveau_zone_geo(element)
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_zone',
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}
function get_niveau_groupes(element)
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_groupe_especes',
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}

function get_statistique_st()
{
    var data = $('#formst').serialize();
    $('#basicModal_s').modal('show');
    //loading_show();
    $("#resutClick_s").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p> Chargement en cours </p></div>').fadeIn('fast');
    $.ajax({
        type: $('#formst').attr("method"),
        url: $('#formst').attr("action"),
        data: data,
        success: function(data){
            $("#resutClick_s").html("");
            $("#form-errors").html("");
            // alert(data);
            $("#resutClick_s").html(data);

            /* if(data.error)
             {

             }*/
        },
        error: function(data){
            $("#resutClick_s").html('');

            var visible_modal = jQuery('.modal.in').attr('id'); // modalID or undefined
            if (visible_modal) { // modal is active
                jQuery('#' + visible_modal).modal('hide'); // close modal
            }

            if(data.status==422)
            {
                if(data.responseJSON.communes)
                    $('.multiselect').css('border-color','red');
                if(data.responseJSON.date_deb)
                    $('#date_deb').css('border-color','red');
                if(data.responseJSON.date_deb)
                    $('#date_fin').css('border-color','red');
                if(data.responseJSON.promotion)
                    $('#promotion').css('border-color','red');
                errorsHtml = '<div class="alert alert-danger"> 1- Verifier les champs obligatoires <br> 2- Choisissez les objets de type statistique    !';
                errorsHtml += '</div>';
                $( '#form-errors' ).show().html( errorsHtml );
            }
            else
            {
                $.alert("{{ trans('message_erreur.validate_error') }}");
            }
        }

    });
}
function hide_modal()
{

}
function get_specialite(element)
{
    id_fil = $(element).val();
    id_etab = $("#etab_id").val();

    $.ajax({
        type: 'get',
        url: racine+'statistiques/get_specialite/'+id_fil+'/'+id_etab,
        cache: false,
        success: function(data)
        {
            $('#specialite').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });

}
function get_exportateurs()
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_exportateurs',
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}
// analyses

function get_site_analyse()
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_site',
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}

function get_niveau(element)
{
    id = $(element).val();
    switch (id)
    {
        case '1' :

            $("#labos_site").hide();
            $("#unites_labos").hide();
            get_site_analyse();
            break;
        case '2' :

            $("#labos_site").show();
            $("#unites_labos").hide();
            $("#site_lab").prop("selectedIndex", 0);
            get_laboratoire();
            break;

        case '3' :
            $("#site_lab").prop("selectedIndex", 0);
            $("#lab_unites").prop("selectedIndex", 0);

            $("#labos_site").show();
            $("#unites_labos").show();
            get_division();
            get_unites();
            break;
    }
}
function get_laboratoire(all="none")
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_laboratoire/'+all,
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}

function get_laboratoires_site(element)
{
    id = $(element).val();
    niveau =$("#niveau").val();
    if(id==0)
    {
        if(niveau == 2)
        {
            get_laboratoire();
        }
        else{
            get_unites();
            get_division();
        }

    }else{
        if(niveau == 2)
        {
            get_laboratoire(id);
        }
        else{
            get_division(id);
            get_unites(id);

        }

    }
}

function get_unites(site="none",labos='none')
{
    $.ajax({
        type: 'get',
        url: racine+'statistiques/multSelect_unites/'+site+'/'+labos,
        cache: false,
        success: function(data)
        {
            $('#com').html(data);
        },
        error: function () {
            $.alert("{{ trans('message_erreur.request_error') }}");
        }
    });
}

function get_unites_labo(element)
{
    site_lab =$("#site_lab").val();

    id = $(element).val();
    if(id==0)
    {
        if(site_lab != 0)
            get_unites(site_lab,'none');
        else
            get_unites();

    }else {
        get_unites('none',id);
    }
}
function get_division(site='none')
{

    $.ajax({
        type: 'get',
        url: racine+'statistiques/get_laboratoires/'+site,
        cache: false,
        success: function(data)
        {

            $('#lab_unites').html(data.labos);
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            //$.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });
}

function get_champs_table(element) {
    id = $(element).val();
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $("#spinner-label_champs_table").show();
    $("#spinner-label_champs_table").html(loading_content);
    $.ajax({
        type: 'GET',
        url: racine + 'stat/modeles/getChampsTable/'+id,
        success: function(data) {
            $("#champs_table_source").html('');
            $("#champs_table_source").html(data);
            $('.selectpicker').selectpicker('refresh');
            $("#spinner-label_champs_table").hide();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function addRequete(element){
    if($("#optAutreChamp").is(":selected"))
    {
        $(".form_add_requete").show();
        $(".form_calcul_en_moyenn").show();
    }
    else {
        $(".form_add_requete").hide();
        $(".form_calcul_en_moyenn").hide();
    }
}

function filtre_categories_modele_st()
{
    var type_categorie = $("#type_categorie").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'stat/modeles/getGroupStatDT/' + type_categorie + '/all').load();
}

function getShowTypeGraphe(element)
{
    if($("#optPie").is(":selected"))
    {
        $(".autre_pie").show();
    }
    else {
        $(".autre_pie").hide();
    }
}

function getShowOptionsTypeGraphe(element)
{
    if($("#optGraphe").is(":selected"))
    {
        $(".form_type_visualisation").show();
    }
    else {
        $(".form_type_visualisation").hide();
    }
}

function getFormTypesGraphe(){
    if($("#optGraphe").is(":selected"))
    {
        $(".from_types_graph").show();
        $(".btnShowGraphe").show();
        $(".btnExportExcel").hide();
        $(".btnExportPdf").hide();
    }
    else if($("#optExcel").is(":selected")) {
        $(".from_types_graph").hide();
        $(".btnShowGraphe").hide();
        $(".btnExportExcel").show();
        $(".btnExportPdf").hide();
        $(".form_chiffre").hide();
    }else{
        $(".from_types_graph").hide();
        $(".btnShowGraphe").hide();
        $(".btnExportExcel").hide();
        $(".btnExportPdf").show();
        $(".form_chiffre").hide();
    }
}

function affichierFormChiffre(){
    if($("#optBarVertical").is(":selected") || $("#optBarHorizontal").is(":selected"))
    {
        $(".form_chiffre").show();
    }
    else {
        $(".form_chiffre").hide();
    }
}


function addQuestionsToModeleStatistique(question_id,model_id){
    $.ajax({
        type: 'GET',
        url: racine + 'statis/groupes/addQuestionsToModel/'+question_id+'/'+model_id,
        success: function(data) {
            setMainTabs(2)
            $(".datatableshow2").DataTable().ajax.reload();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function openListeQuestions(id)
{
    $.ajax({
        type:'get',
        url: racine + 'stat/modeles/getQuestionsNonAffecter/'+ id,
        success:function(data){
            container = "#" + 'second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable(".datatableQuestionsnon-affecte");
            // resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function checkListeQuestions(element){
    if ($(element).is(':checked')) {
        $('.btnAffecterQuestionsModele').show();
    }
    else
    {
        var str = 0;
        $(':checkbox').each(function() {
            str += this.checked ? 1 : 0;
        });
        if (str == 0) {
            $('.btnAffecterQuestionsModele').hide();
        }
        else
            $('.btnAffecterQuestionsModele').show();
    }
}

function affecterQuestionsforModele(modele_id)
{
    $( '#divAffecter #form-errors' ).hide();
    var element = $(this);
    $("#divAffecter .form-loading").show();
    var data = $('#divAffecter form').serialize();
    $.ajax({
        type: $('#divAffecter form').attr("method"),
        url: $('#divAffecter form').attr("action"),
        data: data,
        success: function(data){
            $('#second-modal').modal('toggle');
            $('#divAffecter .form-loading').hide();
            $('#divAffecter .answers-well-saved').show();
            setTimeout(function ()
            {
                $('#divAffecter .answers-well-saved').hide();
            }, 3500);
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
            // $(element).removeAttr('disabled');
        }

    });
}




function openListeGroupes(id)
{
    $.ajax({
        type:'get',
        url: racine + 'stat/modeles/getGroupesNonAffecter/'+ id,
        success:function(data){
            container = "#" + 'second-modal';
            $(container + " .modal-dialog").addClass("modal-lg");
            $(container + " .modal-header-body").html(data);
            $(container).modal();
            setDataTable(".datatableGroupesnon-affecte");
            // resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
    return false;
}

function checkListeGroupes(element){
    if ($(element).is(':checked')) {
        $('.btnAffecterGroupeModele').show();
    }
    else
    {
        var str = 0;
        $(':checkbox').each(function() {
            str += this.checked ? 1 : 0;
        });
        if (str == 0) {
            $('.btnAffecterGroupeModele').hide();
        }
        else
            $('.btnAffecterGroupeModele').show();
    }
}

function affecterGroupesforModele(modele_id)
{
    $( '#divAffecter #form-errors' ).hide();
    var element = $(this);
    $("#divAffecter .form-loading").show();
    var data = $('#divAffecter form').serialize();
    $.ajax({
        type: $('#divAffecter form').attr("method"),
        url: $('#divAffecter form').attr("action"),
        data: data,
        success: function(data){
            $('#second-modal').modal('toggle');
            $('#divAffecter .form-loading').hide();
            $('#divAffecter .answers-well-saved').show();
            setTimeout(function ()
            {
                $('#divAffecter .answers-well-saved').hide();
            }, 3500);
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
            // $(element).removeAttr('disabled');
        }
    });
}

function afterConfirmDeleteModeleSTAT(){
    $('#datatableshow').DataTable().ajax.reload();
}

function afterConfirmDeleteGroupeOrQuestionModele(){
    $('.datatableshow2').DataTable().ajax.reload();
}

function afterConfirmDeleteCategorieModele(){
    $('#datatableshow').DataTable().ajax.reload();
}
function afterConfirmDeleteTableModele(){
    $('#datatableshow').DataTable().ajax.reload();
}

function getNiveauAffichage(element){
    id = $(element).val();
    type_modele = $("#type_enfants").val();
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $("#spinner-label_niveaux_affichage").show();
    $("#spinner-label_niveaux_affichage").html(loading_content);
    $.ajax({
        type: 'GET',
        url: racine + 'stat/modeles/getOptionsNiveauAffichage/'+id + '/' +type_modele,
        success: function(data) {
            $("#niveaux").html('');
            $("#niveaux").html(data.optionsNiveauAffichage);
            $('.selectpicker').selectpicker('refresh');
            $("#spinner-label_niveaux_affichage").hide();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function getObjetsLibre(element){
    if($("#optObjetLibre").is(":selected")){
        $(".objet_libre").show();
    }else{
        $(".objet_libre").hide();
    }
}

function getTypeFormuleCalcul(element){
    if($("#otpChampInput").is(":selected")){
        $(".base_calcul").show();
    }else{
        $(".base_calcul").hide();
    }
    if($("#otpSelectQst").is(":selected")){
        $.ajax({
            type: 'GET',
            url: racine + 'stat/modeles/getOptionsQuestion',
            success: function(data) {
                $("#baseqst").html('');
                $("#baseqst").html(data);
                $('.selectpicker').selectpicker('refresh');
                $(".baseqst_calcul").show();
            },
            error: function() {
                $(".baseqst_calcul").hide();
                console.log('La requête n\'a pas abouti');
            }
        });
    }else{
        $(".baseqst_calcul").hide();
    }
}

function get_modele_statistique(element){
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $(".from_types_graph").hide();
    $(".form_chiffre").hide();
    $("#spinner-label_model").show();
    $("#spinner-label_model").html(loading_content);
    categorie_id = $(element).val();
    $.ajax({
        type: 'GET',
        url: racine + 'stat/getModeleStatistiqueOfCategorie/'+categorie_id,
        success: function(data) {
            $(".listeByObjet").hide();
            $("#type_model_st").html('');
            $("#model_st").html('');
            $("#model_st").html(data);
            $("#spinner-label_model").hide();
            $('.selectpicker').selectpicker('refresh');
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function getOptionsSelon(element){
    if ($(element).is(':checked')) {
        $('#tranche_age').removeAttr('disabled');
    }else{
        $('#tranche_age').attr('disabled', 'disabled');
    }
}

function getStatistique()
{
    var data = $('#formst').serialize();
    $('#basicModal_s').modal('show');
    $("#resutClick_s").html('<div id="loading1" class="loading1 text-center" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p><div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div></p></div>').fadeIn('fast');
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
            $("#resutClick_s").html('');
            $('#basicModal_s').modal('hide');
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        }
    });
}

function getStatistiqueExportFile()
{
    $(".spinner-border").show();
    var data = $('#formst').serialize();
    $.ajax({
        type: $('#formst').attr("method"),
        url: $('#formst').attr("action"),
        data: data,
        success: function(data){
            $(".spinner-border").hide();
            var form = document.getElementById("formst");
            form.action = racine+'stat/get_statistique';
            form.target = "_blank";    // Open in a new window
            form.submit();             // Submit the page
            return true;
        },
        error: function(data){
            $("#resutClick_s").html('');
            $('#basicModal_s').modal('hide');
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('#form-errors').show().html(errorsHtml);
            } else {
                $(".spinner-border").hide();
                //alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        }
    });
}


function getTypeStat_model(element)
{
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $(".from_types_graph").hide();
    $(".form_chiffre").hide();
    $(".btnShowGraphe").show();
    $(".btnExportExcel").hide();
    $(".btnExportPdf").hide();
    $("#spinner-label_type_model").show();
    $("#spinner-label_type_model").html(loading_content);
    $("#spinner-label_type_visualisation").show();
    $("#spinner-label_type_visualisation").html(loading_content);
    model_id = $(element).val();
    $.ajax({
        type: 'GET',
        url: racine + 'stat/getTypeStatByModel/'+model_id,
        success: function(data) {
            $(".listeByObjet").hide();
            $(".form_type_objet").hide();
            $(".form_objet_objet").hide();
            if(data.periode == 0){
                $(".inputDateDebut").hide();
                $(".inputDateFin").hide();
                $(".inputUnite").hide();
                // $(".selectSelon").removeClass('col-md-2');
                // $(".selectSelon").addClass('col-md-4');
            }else{
                $(".inputDateDebut").show();
                $(".inputDateFin").show();
                $(".inputUnite").show();
                // $(".selectSelon").removeClass('col-md-4');
                // $(".selectSelon").addClass('col-md-2');
            }
            if(data.typeObjetGeo != ''){
                $("#type_objet_libre").html('');
                $("#objet_libre").html('');
                $("#type_objet_libre").html(data.optionTypeObjet);
                var objet_libre = $("#objet_libre").empty();
                $.each(data.listeObjetGeo, function(key, element) {
                    objet_libre.append($("<option></option>")
                        .attr("value", element.id).text(element.libelle_fr));
                    objet_libre.selectpicker('refresh');
                });
            }
            $("#type_model_st").html('');
            $("#type_graph").html('');
            $("#type_visualisation").html('');
            $("#type_model_st").html(data.optionsTypeStat);
            $("#type_graph").html(data.optionsTypeGraphe);
            $("#type_visualisation").html(data.optionsTypeVisualisation);
            $('.selectpicker').selectpicker('refresh');
            $("#spinner-label_type_model").hide();
            $("#spinner-label_type_visualisation").hide();

            $(".from_types_graph").show();
            $(".form_chiffre").show();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function getFiltersByType(element){
    //$(".form_chiffre").hide();
    type = $(element).val();
    if(type == 4){
        $(".listeByObjet").html('');
        $('.listeByObjet').hide();
        $(".form_type_objet").show();
        $(".form_objet_objet").show();
    }else if(type == 5){
        $(".listeByObjet").html('');
        $('.listeByObjet').hide();
        $(".form_type_objet").hide();
        $(".form_objet_objet").hide();
    }
    else{
        $(".form_type_objet").hide();
        $(".form_objet_objet").hide();
        $.ajax({
            type: 'GET',
            url: racine + 'stat/getObjet/'+type,
            success: function(data) {
                // console.log(data);
                $(".listeByObjet").html('');
                $(".listeByObjet").html(data);
                $('.selectpicker').selectpicker('refresh');
                $('.listeByObjet').show();
            },
            error: function() {
                console.log('La requête n\'a pas abouti');
            }
        });
    }
}

function getFilterMoughataasByWilaya(){
    wilaya = $("#wilaya").val();
    if (wilaya.length == 0){
        wilaya = 'all';
    }
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $("#spinner-label_moughataa").show();
    $("#spinner-label_moughataa").html(loading_content);
    $("#spinner-label_commune").show();
    $("#spinner-label_commune").html(loading_content);

    $.ajax({
        type: 'GET',
        url: racine + 'stat/getFilterMoughataasByWilaya/'+wilaya,
        success: function(data) {
            var moughataa = $("#moughataa").empty();
            $.each(data.moughataas, function(key, element) {
                moughataa.append($("<option></option>")
                    .attr("value", element.id).text(element.libelle_fr));
                moughataa.selectpicker('refresh');
            });
            var commune = $("#commune").empty();
            console.log(data.communes);
            $.each(data.communes, function(key, element) {
                commune.append($("<option></option>")
                    .attr("value", element.id).text(element.libelle_fr));
                commune.selectpicker('refresh');
            });
            $('.selectpicker').selectpicker('refresh');
            $("#spinner-label_moughataa").hide();
            $("#spinner-label_commune").hide();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function getFilterCommunesByMoughataas(){
    moughataa = $("#moughataa").val();

    if (moughataa.length == 0){
        moughataa = 'all';
    }
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $("#spinner-label_commune").show();
    $("#spinner-label_commune").html(loading_content);
    $.ajax({
        type: 'GET',
        url: racine + 'stat/getFilterCommunesByMoughataas/'+moughataa,
        success: function(data) {
            var communes = $("#commune").empty();
            communes.html(data);
            $('.selectpicker').selectpicker('refresh');
            $("#spinner-label_commune").hide();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function validerModeleStat(id){
    var link = racine + "stat/validerModele/" + id;
    $.ajax({
        type: 'GET',
        url: link,
        success: function(data) {
            setMainTabs(1);
        },
        error: function() {
            $('#tab1 .form-loading').hide();
            $('#btnValider').show();
            $('#alertValidation').addClass('d-none');
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function devaliderModeleStat(id){
    var link = racine + "stat/devaliderModele/" + id;
    $.ajax({
        type: 'GET',
        url: link,
        success: function(data) {
            setMainTabs(1);
        },
        error: function() {
            $('#tab1 .form-loading').hide();
            $('#btnDeValider').show();
            $('#alertValidation').addClass('d-none');
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

// function ListeObjetStat(){
//     var dateD = $("#dateDebut").val();
//     var dateF = $("#dateFin").val();
//     var objet = $("#type_model_st").val();
// }

function getAllChampInTable(element) {
    nameTable = $(element).val();
    loading_content = '<div class="spinner-border w-0 p-0" role="status"><span class="sr-only">Loading...</span></div>';
    $("#spinner-label_champs_table").show();
    $("#spinner-label_champs_table").html(loading_content);
    $.ajax({
        type: 'GET',
        url: racine + 'stat/tables/getAllChampInTable/'+nameTable,
        success: function(data) {
            $("#allChamps_table").html('');
            $("#allChamps_table").html(data);
            $('.selectpicker').selectpicker('refresh');
            $("#spinner-label_champs_table").hide();
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function PersenceUnitePeriode(element){
    var present = $("#presence_unite_periode").val();
    if(present == 1)
        $("#form_unite_periode").show();
    else
        $("#form_unite_periode").hide();
}

function avecUnitePeriode(element){
    var present = $("#periode").val();
    if(present == 1){
        $(".form_unite_periode_date").hide();
        $(".form_avec_unitePeriode").show();
    }else{
        $(".form_unite_periode_date").hide();
        $(".form_avec_unitePeriode").hide();
    }
}

var index_periode = 0;
function getNewPeriode(){
    // alert("Cette option encous dev");
    index_periode++;
    $.ajax({
        type: 'GET',
        url: racine + 'stat/getNewPeriode/'+index_periode,
        success: function(data) {
            $("#nouvel_periode").append(data);
            //$(element).attr('href',container_ligne+`_${index}`);
            $("html, body").animate({
                scrollTop: $("#nouvel_periode").offset().top
            }, 1500);
        },
        error: function() {
            console.log('La requête n\'a pas abouti');
        }
    });
}

function deleteRow(el) {
    $(el).closest('.row').remove();
}

function useNiveauxAffichage(){
    var niveau = $("#use_niveau").val();
    if(niveau == 1){
        $(".form_stat_type_objet").hide();
        $(".form_niveaux_aff").show();
    }else{
        $(".form_niveaux_aff").hide();
        $(".form_stat_type_objet").show();
    }
}


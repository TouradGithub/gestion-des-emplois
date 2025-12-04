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

function get_source(element)
{
    id = $(element).val();

    if(id == 0)
    {
        $("#source").show();
    }
    else {
        $("#source").hide();
}
    //alert($("#type_enfants").val());
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
            url: racine+'statistiques/get_objets/'+id+"/"+id_objet,
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
                url: racine+'statistiques/get_objets/'+id+"/"+id_objet,
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
                url: racine+'statistiques/get_objets/'+id+"/"+id_objet,
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
            url: racine+'statistiques/get_objets/'+id,
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
function get_statistique()
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

//$('#divRatings').selectpicker().change(function(){toggleSelectAll($(this));}).trigger('change');
function plus_info_commune(element,arr,module,ref,niveau)
{
    if($(element).find('.fa-plus').length)
    {
        //$('#resultat_detait_info').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p><?php echo e(trans("message_erreur.chargement")); ?></p>').fadeIn('fast');
        $('#resultat_detait_info').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"><?php echo e(trans("message_erreur.chargement")); ?></i></div>')
        $.ajax({
            url: racine+'plus_info_commune',
            method: "GET",
            data: { data : arr,
                module:module,
                ref:ref,
                niveau:niveau
            },
            cache: false,
            success: function(data)
            {
                $("#resultat_detait_info").html(data);
            },
            error: function () {
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });

    }
    else{

    }
}

function export_tab_commune()
{

    document.formexport.action = "getExport_tab_commune"
    document.formexport.target = "_blank";    // Open in a new window

    document.formexport.submit();             // Submit the page

    return true;
}

function get_axes(niveau,module)
{
    /*if($(element).find('.fa-plus').length)
    {*/
        //$('#resultat_detait_info').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p><?php echo e(trans("message_erreur.chargement")); ?></p>').fadeIn('fast');
        $('#axe_stategique').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"><?php echo e(trans("message_erreur.chargement")); ?></i></div>')
        $.ajax({
            url: racine+'get_axes/'+niveau+'/'+module,
            method: "GET",
            /*data: { data : arr,
                module:module,
                ref:ref,
                niveau:niveau
            },*/
            cache: false,
            success: function(data)
            {
                $("#axe_stategique").html(data);
                $("#navigation").treeview();
                resetInit();

            },
            error: function () {
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });

}

function plus_info_activites()
{

    var data_json = $("#loc").val();
    var module = 2;
    var ref = '';
    var niveau = 5;

    if($(element).find('.fa-plus').length)
    {
        //$('#resultat_detait_info').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p><?php echo e(trans("message_erreur.chargement")); ?></p>').fadeIn('fast');
        $('#resultat_detait_info').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"><?php echo e(trans("message_erreur.chargement")); ?></i></div>')
        $.ajax({
            url: racine+'patrimoine/carte/plus_info_commune',
            method: "GET",
            data: { data : data_json,
                module:module,
                ref:ref,
                niveau:niveau
            },
            cache: false,
            success: function(data)
            {

                $("#resultat_detait_info").html(data);
                resetInit();
            },
            error: function () {
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });

    }
    else{

    }
}


function plus_info_equipements(element)
{

    var loc = $("#loc").val();
    var eq = $("#eq").val();

    if($(element).find('.fa-plus').length)
    {
        //$('#resultat_detait_info').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p><?php echo e(trans("message_erreur.chargement")); ?></p>').fadeIn('fast');
        $('#resultat_detait_info').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"><?php echo e(trans("message_erreur.chargement")); ?></i></div>')
        $.ajax({
            url: racine+'patrimoine/carte/plus_info_equipements',
            method: "GET",
            data: {
                loc:loc,
                eq:eq
            },
            cache: false,
            success: function(data)
            {

                $("#resultat_detait_info").html(data);
                resetInit();
            },
            error: function () {
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });

    }
    else{

    }
}

function export_activite()
{


    document.formexport.action = racine+'export_activite_excel'
    document.formexport.target = "_blank";    // Open in a new window

    document.formexport.submit();
    //$('#resultat_detait_info').html('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"><?php echo e(trans("message_erreur.chargement")); ?></i></div>')
   /* $.ajax({
        url: racine+'export_activite_excel',
        method: "GET",
        data: { data : data_json,
            module:module,
            ref:ref,
            niveau:niveau
        },
        cache: false,
        success: function(data)
        {

            $("#resultat_detait_info").html(data);
            resetInit();
        },
        error: function () {
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });*/
}
function add_filtre(element)
{
    // get liste indicateur d'un groupe
    var id = $(element).attr('idqst');
    var niveau = $(element).attr('niveau');
    var module = $(element).attr('module');

    $('#basicModal').modal("show");

    $.ajax({
        type: 'GET',
        url: racine+'indicateursGroupe/'+id+'/'+niveau+'/'+module,
        cache: false,
        success: function (data) {

            $("#resutClick").html(data);

        },
        error: function () {
            //alert('La requête n\'a pas abouti');
            console.log('La requête n\'a pas abouti');
        }
    });
    return false;

    //alert(id)
    //$(element).children('i').removeClass('fw fa-eye').addClass('fa-refresh fa-spin');
    //$(element).children('i').removeClass('fa-refresh fa-spin').addClass('fw fa-eye');
}

function addToFilter(id,module)
{
    // ajouter legend d'indicateur to legend

    var optionExists = ($("#legend option[value="+id+"]").length <= 0);
    if(optionExists)
    {
        $.ajax({
            type: 'GET',
            url: racine+'get_filtre_info/'+id,
            cache: false,
            success: function (data) {

                $('#legend').append($('<option>', {
                    value: id,
                    text: data.libelle
                }));
                $("#legend").val(id).change();
                $("#legend").selectpicker('refresh');
                valider_filtre_pers(module)
            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });

        // $('#legend option:eq(2)').prop('selected', true);

    }
    else{
        $("#legend").val(id).change();
        $("#legend").selectpicker('refresh');
    }


    var set_val =$("#id_fil").val() +','+id;

    $("#id_fil").val(set_val);

    $('#basicModal').modal('toggle');

}

function valider_filtre_pers(module)
{
    // ajouter un filtre au niveau d'affichage
    var arr = [];

    $("#legend option").each(function()
    {
        arr.push( $(this).val() );
    });

    $.ajax({
        type: 'get',
        url: racine+'patrimoine/carte/liste_filtre_pers/'+arr+'/'+module,
        cache: false,
        success: function(data)
        {

            $("#filtre").html(data);
            //ajout legend du filtre
        },
        error: function () {

            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            //$.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });

    //$("#filtre").html(arr);
    return false;
}


function coordonnee_gps_zone_humide()
{
    // cordonne du zone humide
    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine+'patrimoine/carte/coordonnee_gps_zone_humide',
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
           // alert(theResponse)
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });

    //alert(theResponse);
    return theResponse;
}


function coordonnee_gps_equipements(type,localite='all')
{
    // cordonne du zone humide
    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine+'patrimoine/carte/coordonnee_gps_equipements/'+type+'/'+localite,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
            // alert(theResponse)
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });

    //alert(theResponse);
    return theResponse;
}

function onMapClick(id)
{
    $('#basicModal').modal('show');
    $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>chargement en cours</p></div>').fadeIn('fast');

//alert(id);
    $.ajax({
        type: 'GET',
        url: racine + 'patrimoine/carte/detaitInfoZone_himudes/' +id,
        success: function (data) {
            $("#resutClick").html(data);

        },
        error: function () {
            $.alert(msg_erreur);
            console.log('La requête n\'a pas abouti');
        }
    });
}


function onPolyClick()
{
  //alert(1)
}


function openFormStatModal(id) {
    // formulaire statistique
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/recherche/' + id,
        success: function (data) {
            $("#resutClick_form").html(data);
            $('#basicModal_form').modal("show");
            resetInit();
        },
        error: function () {
            $.alert(msg_erreur);
        }
    });
}

function legend_niveau() {

    // legend du niveau de la module
    var niveau_geo = $("#niveau_geo").val();
    var module = '<?php echo $module ?>';
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/liste_legend_niveau/' + niveau_geo + '/' + module,
        cache: false,
        success: function (d) {
            $("#legend_pers").empty();
            $("#legend_pers").html(d);
            // apel getCount pour actualiser la carte apres led cangement de legande
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            //$.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });

}

function filtre_niveau() {
    // filtre du niveau de la module
    var niveau_geo = $("#niveau_geo").val();
    var module = '<?php echo $module ?>';
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/liste_filtre_niveau/' + niveau_geo + '/' + module,
        cache: false,
        success: function (k) {
            $("#filtre").empty();
            $("#filtre").html(k);
            //ajout legend du filtre
        },
        error: function () {

            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            //$.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });
}

function toggleIconeCard(e)
{
    var ele = $(e).attr('rel');
    $("#"+ele).collapse('toggle');
      /*  $(e.target)
            .prev('.card-header')
            .find(".more-less")
            .toggleClass('fa-plus fa-minus');*/

}

function toggleIconeCostum(e)
{
    var ele = $(e).attr('rel');
    $("#collapsecond"+ele).collapse('toggle');
    /*  $(e.target)
          .prev('.card-header')
          .find(".more-less")
          .toggleClass('fa-plus fa-minus');*/
}

function getlayout(niveau) {

    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/couche/' + niveau ,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
            // alert(theResponse)
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });

    return theResponse;

}

function getlocalite(commune) {

    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/coucheLocalite/'+commune,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
            // alert(theResponse)
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });

    return theResponse;

}
 function baseLayersNiveau(niveau)
{
    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/baseLayers/' + niveau ,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });
    return theResponse;
}

function typeEquipementsLayers(type,localite)
{
    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/groupedOverlays/'+type+'/'+localite,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });
    return theResponse;
}

function getCarteInModalEmplacement(){

    var largeModal='xl';
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/getCarteEmplacement',
        success: function (data) {

            $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function getCarteInModal()
{
    var type = $("#typeEq").val();
    var localite = $("#localites").val();

    var largeModal='xl';
    $.ajax({
        type: 'get',
        url: racine  + 'patrimoine/carte/getCarte/' + type+'/' + localite,
        success: function (data) {

            $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function getCoucheEmplacement(id,localite)
{
// cordonne du zone humide
    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine+'patrimoine/carte/coordonnee_gps_emplacement/'+id+'/'+localite,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
            // alert(theResponse)
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });

    //alert(theResponse);
    return theResponse;
}
function getCarteLocaliteInModal(id)
{

    var largeModal='xl';
    $.ajax({
        type: 'get',
        url: racine  + 'patrimoine/carte/getCarteLocalite/' + id,
        success: function (data) {

            $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function cordinateLocalite(localite_id)
{
    var theResponse = null;
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/carte/getCordonateLocalite/'+localite_id,
        dataType: "html",
        async: false,
        cache: false,
        success: function(data)
        {
            theResponse = data;
        },
        error: function () {
            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            $.alert(msg_erreur);
        }
    });
    return theResponse;
}
function updateCordonateLocalite()
{
    $('.datatableshow2').DataTable().ajax.reload();
}


function get_cordinate_marker(lat,log)
{
    $('#latitude').val(lat);
    $('#longitude').val(log);
    $('#latitudeNew').val(lat);
    $('#longitudeNew').val(log);
}
function  getMap()
{

}


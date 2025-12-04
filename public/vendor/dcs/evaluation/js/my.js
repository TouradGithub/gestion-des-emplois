$(document).ready(function () {
    // racine = '/onispa/public/';

    // Ajout du CSRF Token pour les requettes ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
    //begin referentiesl
    function addnew(){
        // $( '#addNewModal #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        // $("#addNewModal .form-loading").show();
        $('#addNewModal .main-icon').hide();
        $('#addNewModal .spinner-border').show();
        var data = $('#addNewModal form').serialize();
        $.ajax({
            type: $('#addNewModal form').attr("method"),
            url: $('#addNewModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                window.location.href = data;
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                    errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';

                    });
                    errorsHtml += '</ul>';
                    $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                // $("#addNewModal .form-loading").hide();
                $('#addNewModal .spinner-border').hide();
                $('#addNewModal .main-icon').show();
                // $(element).removeAttr('disabled');
            }

        });
    }
    function openEditRefModal(array)
    {
        var tableau=array.split(",");
        var ref=tableau[0];
        var id=tableau[1];
        // alert(ref);
        // alert(id);
       $.ajax({
           type: 'get',
           url: racine+'ref/edit/'+ref+'/'+id,
           // alert(url);
           success: function (data) {
            // alert(data);
            $("#main-modal .modal-header-body").html(data);
            var title_modif =$('#title_modif').val();
            $(".title_modif").html(title_modif);
            var libelleref =$('#libelleref').val();
            $(".libelleref").html(libelleref);
            $("#main-modal").modal();
            // initmain();


           },
           error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
       });
    }
    //end referentiel
    //changer le lieu d'employeur
    function changeLieu(){
        if ($('.checkLieu').is(':checked')){
            $( '.divPays' ).hide();
            $( '.divRIM' ).show();
            $("#checkBtn").val(1);

        }
        else {
            $( '.divPays' ).show();
            $( '.divRIM' ).hide();
            $("#checkBtn").val(0);

        }
    }

    function filterFormation()
    {
        type = $("#type").val();
        centre = $("#centre").val();
        domaine = $("#domaine").val();
        langue = $("#langue").val();
        $('#datatableshow').DataTable().ajax.url(racine + "formations/getDT/"+ type + '/'  + centre  + '/'  + domaine  + '/'  + langue  + '/all').load();
    }

    function filterEmployeur()
    {
        secteur = $("#secteur").val();
        $('#datatableshow').DataTable().ajax.url(racine + "employeurs/getDT/"+ secteur +  '/all').load();
    }

    // updating a group des elements
function updateGroupeElements(element = null) {
    $('[data-toggle="tooltip"]').tooltip('dispose');

    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    var idgroup = $(".group-elements").attr('idgroup');
    var lien = $(".group-elements").attr('lien');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + qsts + '/' + idgroup;
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            $('.datatableshow1').DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

//ouvrir le model de modification des donnees d'authentification des employeurs
function openFormAuthInModal(lien,object) {
    // alert(lien+"////"+object);
    $.ajax({
        type: 'get',
        url: racine +  lien + "/"+ object,
        success: function (data) {
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

    function addAuthEmployeur(element){
        // $( '#addNewModal #form-errors' ).hide();
        // var element = $(this);
        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        // alert($('#' + container + ' form').attr("action"));
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('#second-modal').modal('toggle');
                // getTheContent('employeurs/getTab/' + data, '/1');
                $('#main-modal').modal('toggle');

                openObjectModal(data,"employeurs");

                // getTheContent('employeurs/getTab/' + data + '/1/', '#tab1');


            },
            error: function(data){
                 if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#' + container + ' #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .main-icon').show();
                $(element).removeAttr('disabled');
            }

        });
    }




    

    // Ouvrire le modal d'un objet 
    function openModalObject(lien,id_object) {

        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + lien + "/"+ id_object,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-xl");

                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //recuperer les listes des DEs
    function changeTypeDE() {

        form_id = $("#form_id").val();
        type = $("#type_de").val();

        // alert(type);
        $.ajax({
            type: 'get',
            url: racine + 'formations/getDEs/'+ type + '/' + form_id,
            success: function (data) {
                // alert(data);
                $("#demandeurs_form").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function addDE_affectes(element,type){
        // alert(type);

        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
            url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                //$('.datatableshow').DataTable().ajax.reload();
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('.datatableshow2').DataTable().ajax.reload();
                $('#second-modal').modal('toggle');
                if(type == 1)
                    getTheContent('formations/getTab/' + data + '/2/', '#tab2');
                else
                    getTheContent('programmes/getTab/' + data + '/2/', '#tab2');


            },
            error: function (data) {
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#' + container + ' #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .main-icon').show();
                $(element).removeAttr('disabled');
            }
        });
    }

    function deleteCompte (id_user) {

        var confirme = confirm("Êtes-vous sûr de vouloir supprimer ce compte");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+'employeurs/deleteUser/'+id_user,
                success: function (data)
                {
                    $('#main-modal').modal('toggle');
                    openObjectModal(data,"employeurs");
                    // getTheContent('employeurs/getTab/' + id_user + '/1');

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


    // updating a group des elements
function updateElementsDEform(element = null) {
    $('[data-toggle="tooltip"]').tooltip('dispose');

    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    // var idgroup = $(".addDE").attr('idgroup');
    // var lien = $(".addDE").attr('lien');
    var idgroup = $(element).attr('idgroup');
    var lien = $(element).attr('lien');
    var elem = $(element).attr('idelt');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + elem + '/' + idgroup;
    // alert(link);
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close addDE" lien="formations/retirerDE"  idgroup="' + idgroup + '" aria-hidden="true" onclick="updateElementsDEform(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            $('.datatableshow2').DataTable().ajax.reload();
            $('.datatableshow5').DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


    function filterDEsLibres(type=1)
    {
        niveau = $("#niveau").val();
        domaine = $("#domaine").val();
        genre = $("#genre").val();
        if (type == 1) {
            form_id = $("#form_id").val();
            $('.datatableshow3').DataTable().ajax.url(racine + "formations/getDEsLibres/"+ niveau + '/'  + domaine   +'/'  + genre   + '/' + form_id).load();
        }
        else{
            programme_id = $("#programme_id").val();
            $('.datatableshow3').DataTable().ajax.url(racine + "programmes/getDEsLibres/"+ niveau + '/'  + domaine   +'/'  + genre   + '/' + programme_id).load();
    
        }
    }

    function filterOffres(element)
    {
        etat = $("#etat").val();
        lieu = $("#lieu").val();
        // form_id = $("#form_id").val();
        // alert(racine + "employeurs/getDTOffres/"+ etat + '/'  + lieu   + '/' + element);
        $('.datatableshow3').DataTable().ajax.url(racine + "employeurs/getDTOffres/"+ etat + '/'  + lieu   + '/' + element).load();
    }

    //check all input in DEs libres
    function checkAllDEs(element){
        etat = $('#etat').val();
        switch(etat) {
            case '1': //etat = postuler
                var msg = "Preselectionner un DE";
            break;
            case '2': //etat = preselcitonner
                var msg = "Selectionner ";
            break;
            case '3': //etat = postuler
                var msg = "Placer ";
            break;
            case '5', '14': //etat = demande in fomration
                var msg = "Valider la demande ";
            break;
            case '6', '15': //etat = demande in fomration
                var msg = "Selectionner DE(s) ";
            break;
            case 'all' : //etat = tous
                var msg = "Selectionner un DE libre";
            break;
            break;
              /*default:
                var msg = "Selectionner un DE libre";*/
            // break;
        }
        // alert(msg);

        $('.textBtn').html(msg)
        if ($(element).is(':checked')) {
            $('.btnAddDEslibres').show();
        }
        else
        {
            var str = 0;

            $(':checkbox').each(function() {
                str += this.checked ? 1 : 0;
            });
            if (str == 0) {
                $('.btnAddDEslibres').hide();
            }
            else
                $('.btnAddDEslibres').show();

        }
    }


    //check all input in DEs libres
    function checkAllDEsPostuler(element){
        etat = $('#etat_postuler').val();
        // alert(etat);
        switch(etat) {
            case '1': //etat = postuler
                var msg = "Postuler";
            break;
            case '2': //etat = preselcitonner
                var msg = "Selectionner un DE libre ";
            break;
            /*default:
                var msg = "Selectionner un DE libre";*/
            // break;
        }
        $('.textBtnPostuler').html(msg)
        if ($(element).is(':checked')) {
            $('.btnAddDEsPostuler').show();
        }
        else
        {
            var str = 0;

            $(':checkbox').each(function() {
                str += this.checked ? 1 : 0;
            });
            if (str == 0) {
                $('.btnAddDEsPostuler').hide();
            }
            else
                $('.btnAddDEsPostuler').show();

        }
    }

    function filterOffre()
    {

        /*if(Auth::user()->sys_types_user_id == 3){
            employeur = "all";
            agence = "all";
        }
        else{*/
            employeur = $("#employeur").val();
            agence = $("#agence").val();
            etat = $("#etat").val();
        // }
        
        // commune = $("#commune").val();
        secteur = $("#secteur").val();
        // alert(racine + "offres/getDT/"+ employeur + '/'  + agence  + '/'  + secteur+ '/'  + etat  + '/all');
        $('#datatableshow').DataTable().ajax.url(racine + "offres/getDT/"+ employeur + '/'  + agence  + '/'  + secteur+ '/'  + etat  + '/all').load();
    }

    function autre_champs(element)
    {
        $(element).find('i').toggleClass('fa-plus fa-minus');
        $("#autre_champ").toggle();
    }

    function show_criters(element)
    {
        $(element).find('i').toggleClass('fa-plus fa-minus');
        $("#criteres").toggle();
    }

//recuperer les listes des agents par agence
    function changeAgence() {

        agence = $("#agence_id").val();

        // alert(type);
        $.ajax({
            type: 'get',
            url: racine + 'offres/getAgents/'+ agence,
            success: function (data) {
                // alert(data);
                $("#agent_id").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function changeGrCompetance() {

        gr_competance = $("#gr_competance").val();

        // alert(type);
        $.ajax({
            type: 'get',
            url: racine + 'offres/getCompetance/'+ gr_competance,
            success: function (data) {
                // alert(data);
                $("#competence_id").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }




    function openDiv(element,id) {


        switch(element) {
            case 1: //competance
                var lien =  'offres/competances/get_form_compe/'+id;
                var nameDiv = "competance_div";
            break;
            case 2: //formation offre
                var lien =  'offres/formation/get_compeOffre/'+id;
                var nameDiv = "form_div";
            break;
            break;
              default:
                // code block
        }
        // alert(lien+"///"+nameDiv);

        $.ajax({
            type: 'get',
            url: racine + lien,
            success: function (data) {

                $("#"+nameDiv).html(data).slideDown("slow");
                // $("#"+nameDiv).html(data);
                // $("#"+nameDiv).show();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function deleteCompetance (element) {
        // alert(element);
        var confirme = confirm("Êtes-vous sûr de vouloir supprimer cette competance ? ");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+'offres/competances/deleteCompetance/'+element,
                success: function (data)
                {
                    $('.datatableshow4').DataTable().ajax.reload();
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

    function deleteObject (array) {
        // alert(array);
        var tableau=array.split(",");
        var id=tableau[0];
        var type=tableau[1];
        // alert(id+"///"+type);
        // var type =  2;
        switch(type) {
            case '1': //competance
                var lien =  'offres/competances/deleteCompetance/'+id;
                var datatable = "datatableshow2";
                var msg = "Êtes-vous sûr de vouloir supprimer cette competance ? ";
            break;
            case '2': //formation offre
                var lien =  'offres/formation/deleteOffre/'+id;
                var datatable = "datatableshow3";
                var msg = "Êtes-vous sûr de vouloir supprimer cette formation ? ";
            break;
            default:
                // code block
        }

        // alert(lien + "//"+ nameDiv);
        // alert(element);
        var confirme = confirm(msg);
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+lien,
                success: function (data)
                {
                    $('.'+datatable).DataTable().ajax.reload();
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
    function addNewObject(element){
        // alert(element);
        switch(element) {
            case 1: //competance
                var lien =  "offres/competances/getCompetancesDT/";
                var nameDiv = "competance_div";
                var datatable = "datatableshow2";
            break;
            case 2: //formation offre
                var lien =  "offres/formation/getFormDT/";
                var nameDiv = "form_div";
                var datatable = "datatableshow3";
            break;
            default:
            // code block
        }
        // alert('#' + nameDiv + ' #form-errors');

        $( '#' + nameDiv + ' #form-errors' ).hide();
        var element = $(this);
        $('#' + nameDiv + ' .main-icon').hide();
        $('#' + nameDiv + ' .spinner-border').show();
        // $('#' + nameDiv + ' .spinner-border').show();
        var data = $('#' + nameDiv + ' form').serialize();
        $.ajax({
            type: $('#' + nameDiv + ' form').attr("method"),
            url: $('#' + nameDiv + ' form').attr("action"),
            data: data,
            success: function(data){
                // $('#' + nameDiv'+ ').modal('toggle');
                // openStagiaireModal(data);
                $('#' + nameDiv + ' .spinner-border').hide();
                $('#' + nameDiv + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + nameDiv + ' .answers-well-saved').hide();
                    $('#' + nameDiv + ' .main-icon').show();

                }, 3500);

                // $('.'+datatable).DataTable().ajax.url(racine + lien + data).load();
                    $('.'+datatable).DataTable().ajax.reload();

                /*var form = document.getElementById("formSTAG");
                form.reset();*/
                $('#' + nameDiv).hide();
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
                   $( '#' + nameDiv + '  #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + nameDiv + '  .spinner-border').hide();
                $('#' + nameDiv + ' .main-icon').show();
            }
        });
    }



    // updating a group des elements
function updateElementsDEoffre(element = null) {
    var questions = $(".group-elements").sortable('toArray');
    var childscount = $(".group-elements li").length;
    // var idgroup = $(".addDE").attr('idgroup');
    // var lien = $(".addDE").attr('lien');
    var idgroup = $(element).attr('idgroup');
    var lien = $(element).attr('lien');
    var elem = $(element).attr('idelt');
    if (element) {
        if ($(element).hasClass("close")) {
            questions = jQuery.grep(questions, function (value) {
                return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        } else {
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idelt'));
        }
    }
    if (questions.length)
        var qsts = questions.join();
    else
        var qsts = 0;
    var link = racine + lien + "/" + elem + '/' + idgroup;
    // alert(link);
    $.ajax({
        type: 'GET',
        url: link,
        success: function (data) {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else {
                    var idelt = $(element).attr('idelt');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close addDE" lien="offres/retirerDESelectionner"  idgroup="' + idgroup + '" aria-hidden="true" onclick="updateElementsDEoffre(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            // $('.datatableshow2').DataTable().ajax.reload();
            $('.datatableshow5').DataTable().ajax.reload();
        },
        error: function () {
            if (element) {
                if ($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


//changer le lieu d'employeur
    function changeBtnCandt(element){
        if (element == 1){
            $( '.divSel' ).show();
            $( '.divPre' ).hide();
        }
        else {
            $( '.divSel' ).hide();
            $( '.divPre' ).show();
        }
        resetInit();

    }

    //filter des listes des candidatures
    function filterCandidatures(element)
    {
        etat = $("#etat_pres").val();
        /*niveau = $("#niveau").val();
        $('.btnAddDEslibres').hide();
*/
// alert(etat);
    // alert(racine + "offres/getDEsPreselect/"+ etat + '/all/' + element);
        $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselect/"+ etat + '/all/' + element).load();
    }

    //filter des listes des candidatures pour la formation et programme
    function filterCandidats(type,element)
    {
        etat = $("#etat").val();
        niveau = $("#niveau").val();
        $('.btnAddDEslibres').hide();
        if(type == 1) //formation 
            $('.datatableshow2').DataTable().ajax.url(racine + "formations/getDEs/"+ etat + '/' + niveau + '/'  + element).load();
        else
            $('.datatableshow2').DataTable().ajax.url(racine + "programmes/getDEs/"+ etat + '/' + niveau + '/'  + element).load();
    
    }


    //ajouter des DEs pour la formation et programme(element){
    function addDEsToObject(element){
        etat = $('#etat').val();
        type = $('#type_object').val();

        
        switch(etat) {
            case '5', '14': //selectionner
                var msg =  'Êtes-vous sûr de vouloir valider ces demandes ?';
            break;
            case '6', '15': //selectionner
                var msg =  'Êtes-vous sûr de vouloir selecitonner  ces demandeurs d`emplois ?';
            break;
            case 'all': //selectionner un DE libre 
                var msg =  'Êtes-vous sûr de vouloir selecitonner  ces demandeurs d`emplois ?';
            break;
            default:
                var msg =  'Êtes-vous sûr de vouloir valider ces demandes ?';
        }
        var confirme = confirm(msg);
        if(confirme){
            var container = $(element).attr('container');
            $('#' + container + ' #form-errors').hide();
            $(element).attr('disabled','disabled');
            $('#' + container + ' .main-icon').hide();
            $('#' + container + ' .spinner-border').show();
            var data = $('#' + container + ' form').serialize();
            $.ajax({
                type: $('#' + container + ' form').attr("method"),
                url: $('#' + container + ' form').attr("action"),
                data: data,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    //$('.datatableshow').DataTable().ajax.reload();
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .answers-well-saved').show();
                    $(element).removeAttr('disabled');
                    $('.divMsg').html("Les données sont enregistrées avec succéss");
                    $('.divMsg').show();

                    setTimeout(function () {
                        $('#' + container + ' .answers-well-saved').hide();
                        $('#' + container + ' .main-icon').show();
                        $('.divMsg').hide();

                    }, 3500);
                    // alert(racine + "formations/getDEs/"+ data.etat + '/all/'  + data.form_id);
                    // $('.datatableshow4').DataTable().ajax.reload();
                    if(type == 1) //formation
                        $('.datatableshow2').DataTable().ajax.url(racine + "formations/getDEs/"+ data.etat + '/all/'  + data.form_id).load();
                    else
                        $('.datatableshow2').DataTable().ajax.url(racine + "programmes/getDEs/"+ data.etat + '/all/'  + data.form_id).load();
                    $('.btnAddDEslibres').hide();

                    // $('#second-modal').modal('toggle');
                    // getTheContent('offres/getTab/' + data + '/4/', '#tab4');


                },
                error: function (data) {
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<ul class="list-group">';
                        var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                            errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#' + container + ' #form-errors').show().html(errorsHtml);
                    } else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .main-icon').show();
                    $(element).removeAttr('disabled');
                }
            });
        }
    }

    //retirer un DE d'une fomration ou d'un programme 

    function retirerDEsFromObject(id, object, etat, type) {
        // alert(id+"//"+object+"//"+etat+"//"+type);
        if(type == 1){ //formation
            var lien = 'formations/retirerDE/'+id +'/'+object+'/'+etat;
            var lien_datatables = "formations/getDEs/"+ etat + '/all/'  + object
        }
        else //programme
        {
            var lien = 'programmes/retirerDE/'+id +'/'+object+'/'+etat;
            var lien_datatables = "programmes/getDEs/"+ etat + '/all/'  + object

        }   
        var confirme = confirm("Êtes-vous sûr de vouloir retirer ce demandeur d'emploi ?");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine + lien ,
                success: function (data)
                {
                    $('.divMsg').html("Le demandeur d`emploi a été retiré !");
                    $('.divMsg').show();

                    setTimeout(function () {
                        $('.divMsg').hide();

                    }, 3500);
                    $('.datatableshow2').DataTable().ajax.url(racine + lien_datatables).load();

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

    //postuler un DE ou ajouter un DE libre a une offre  
    function postulerDE_offre(type,element){
        // alert(type);
        if(type == 1)
            var msg =  'Êtes-vous sûr de vouloir postuler à cette offre ?';
        else
            var msg =  'Êtes-vous sûr de vouloir selectionner ces demandeurs d`emplois ?';

        var confirme = confirm(msg);
        if(confirme){
            var container = $(element).attr('container');
            $('#' + container + ' #form-errors').hide();
            $(element).attr('disabled','disabled');
            $('#' + container + ' .main-icon').hide();
            $('#' + container + ' .spinner-border').show();
            var data = $('#' + container + ' form').serialize();
            $.ajax({
                type: $('#' + container + ' form').attr("method"),
                url: $('#' + container + ' form').attr("action"),
                data: data,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    //$('.datatableshow').DataTable().ajax.reload();
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .answers-well-saved').show();
                    $(element).removeAttr('disabled');
                    /*$('.divMsg').html("Les données sont enregistrées avec succéss");
                    $('.divMsg').show();*/
                $('#second-modal').modal('toggle');

                    setTimeout(function () {
                        $('#' + container + ' .answers-well-saved').hide();
                        $('#' + container + ' .main-icon').show();
                        // $('.divMsg').hide();

                    }, 3500);
                    // $('.datatableshow4').DataTable().ajax.reload();
                    $('.datatableshow4').DataTable().ajax.reload();
                    $('.datatableshow5').DataTable().ajax.reload();
                    $('.datatableshow6').DataTable().ajax.reload();
                    // if(type)
                    // $('.datatableshow5').DataTable().ajax.url(racine + "offres/getDEsSelectionnes/3/"+ data).load();
                    // $('.btnAddDEslibres').hide();

                    // $('#second-modal').modal('toggle');
                    // getTheContent('offres/getTab/' + data + '/4/', '#tab4');


                },
                error: function (data) {
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<ul class="list-group">';
                        var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                            errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#' + container + ' #form-errors').show().html(errorsHtml);
                    } else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .main-icon').show();
                    $(element).removeAttr('disabled');
                }
            });
        }
    }

//ajouter des DEs pour l'offresfunction addDE_affectes(element){
    function addDE_offre(element){
        etat = $('#etat').val();
        // alert(etat);
        switch(etat) {
            case '3': //selectionner
                var msg =  'Êtes-vous sûr de vouloir placer ces demandes ?';
            break;
            case '1': //selectionner
                var msg =  'Êtes-vous sûr de vouloir preselcitonner  ces demandeurs d`emplois ?';
            break;
            default:
                var msg =  'Êtes-vous sûr de vouloir selectionner ces demandeurs d`emplois ?';
        }
        var confirme = confirm(msg);
        if(confirme){
            var container = $(element).attr('container');
            $('#' + container + ' #form-errors').hide();
            $(element).attr('disabled','disabled');
            $('#' + container + ' .main-icon').hide();
            $('#' + container + ' .spinner-border').show();
            var data = $('#' + container + ' form').serialize();
            $.ajax({
                type: $('#' + container + ' form').attr("method"),
                url: $('#' + container + ' form').attr("action"),
                data: data,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    //$('.datatableshow').DataTable().ajax.reload();
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .answers-well-saved').show();
                    $(element).removeAttr('disabled');
                    $('.divMsg').html("Les données sont enregistrées avec succéss");
                    $('.divMsg').show();

                    setTimeout(function () {
                        $('#' + container + ' .answers-well-saved').hide();
                        $('#' + container + ' .main-icon').show();
                        $('.divMsg').hide();

                    }, 3500);
                    // $('.datatableshow4').DataTable().ajax.reload();
                    $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselections/"+ data.etat + '/all/'  + data.offre_id).load();
                    $('.btnAddDEslibres').hide();

                    // $('#second-modal').modal('toggle');
                    // getTheContent('offres/getTab/' + data + '/4/', '#tab4');


                },
                error: function (data) {
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<ul class="list-group">';
                        var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                            errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#' + container + ' #form-errors').show().html(errorsHtml);
                    } else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .main-icon').show();
                    $(element).removeAttr('disabled');
                }
            });
        }
    }


   


    /*function openFormPostuler(id) {
        $.ajax({
            type: 'get',
            url: racine  + 'offres/openFormPostuler/' + id,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-lg");

                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }*/

    function openFormPreselection(id) {
        $.ajax({
            type: 'get',
            url: racine  + 'offres/getFormPreselection/' + id,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-xl");

                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }



    function generePreselectionnes(element){
        // $( '#addNewModal #form-errors' ).hide();
        // var element = $(this);
        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        // alert($('#' + container + ' form').attr("action"));
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
            url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('#second-modal').modal('toggle');
                // $('#main-modal').modal('toggle');

                // openObjectModal(data,"offres");
                getTheContent('offres/getTab/' + data + '/4/', '#tab');

                $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselect/2/all/" + data).load();

            },
            error: function(data){
                 if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#' + container + ' #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .main-icon').show();
                $(element).removeAttr('disabled');
            }

        });
    }

    function openDetailPreselections(id) {
        $.ajax({
            type: 'get',
            url: racine  + 'offres/getDetailsPreselections/' + id,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-lg");

                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }    


    //ouvrir le modal de detail d'une offre si l'utilisateur est un DE 
    function openOffreModal(id, lemodule,largeModal = 'lg') {
        // alert(racine + lemodule + '/get/' + id);
        $.ajax({
            type: 'get',
            url: racine + lemodule + '/getDetailOffre/' + id,
            success: function (data) {

                $("#main-modal .modal-dialog").addClass("modal-"+largeModal);
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                // setMainTabs(tab);
                $("#datatableshow").attr('link');
                $('#datatableshow').DataTable().ajax.url($("#datatableshow").attr('link') + "/" + id).load();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        return false;
    }


    function addOrientation(element){
        // $( '#addNewModal #form-errors' ).hide();
        var container = 'second-modal';
        // var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        // alert($('#' + container + ' form').attr("action"));
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
            url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                /*$('.divMsg').html(data);
                $('.divMsg').show();*/
                if(data.msg != 1)
                    $('.divMsg').show().html(data.msg);

                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                    // $('.divMsg').hide();
                    $('.divMsg').hide();

                }, 3500);
                $('#second-modal').modal('toggle');

                $('.datatableshow2').DataTable().ajax.url(racine + "DE/orientations/getDemandesOrientsDT/"+ data.DE_id).load();
                
                // getTheContent('employeurs/getTab/' + data + '/1/', '#tab1');
            },
            error: function(data){
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#' + container + ' #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .main-icon').show();
                $(element).removeAttr('disabled');
            }

        });
    }



    function openFormOrientationModal(type,de) {
        // alert(type+"vvvvvv"+de);
        if(type == 1) //DE
            lien = 'DE/fiche/getFormOrientation/';
        else if(type == 2)//conseiller
            lien = 'DE/conseiller/getFormOrient/';
        else //detail de l'orientation
            lien = 'DE/orientations/getDetailOrientation/';
        // alert(type+"vvvvvv"+de);

        $.ajax({
            type: 'get',
            url: racine + lien  +  de,
            success: function (data) {
                $("#second-modal .modal-dialog").addClass("modal-lg");

                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                        // alert(getRandomColor());

                      // $(".test").css("background-color", getRandomColor());
                
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }


    function ShowObject(element){
        switch(element) {
                case 1: {
                    // alert("rrr");
                    if ($('.checkComp').is(':checked'))
                        $('#competance_id').removeAttr("disabled");
                    else 
                        $('#competance_id').attr('disabled','disabled');

                }
                
                break;
                case 2: {
                    if ($('.checkProgramme').is(':checked'))
                        $('#programmes_id').removeAttr("disabled");
                    else 
                        $('#programmes_id').attr('disabled','disabled');
                }
                break;
                case 3: {
                    if ($('.checkForm').is(':checked'))
                        $('#formations_id').removeAttr("disabled");
                    else 
                        $('#formations_id').attr('disabled','disabled');
                }
                break;
        }
            $('.selectpicker').selectpicker('refresh')
        

        resetInit();
    }


    function newOrientation(element){
        // $( '#addNewModal #form-errors' ).hide();
        // var element = $(this);
        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        // alert($('#' + container + ' form').attr("action"));
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
            url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('#second-modal').modal('toggle');
                // getTheContent('employeurs/getTab/' + data, '/1');
                
                getTheContent('DE/getTab/' + data + '/16/', '#tab16');
            },
            error: function(data){
                if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#' + container + ' #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .main-icon').show();
                $(element).removeAttr('disabled');
            }
        });
    }


    //ouvrir l'objet avec editor

    function openObjetModal(id, lemodule, tab = 1, largeModal = 'lg') {
    // alert(racine + lemodule + '/get/' + id);

    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {

            $("#main-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            setMainTabs(tab);
            $("#datatableshow").attr('link');
            $('#datatableshow').DataTable().ajax.url($("#datatableshow").attr('link') + "/" + id).load();
            setTimeout(function () {
                initEditor();
                    

                }, 3500);
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    return false;
}


function openFormOffreInModal(lemodule, id = false) {
    if (id != false)
        url = racine + lemodule + '/add/' + id;
    else
        url = racine + lemodule + '/add/';

    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            //modified by Medyahya
            $("#add-modal .modal-dialog").addClass("modal-lg");

            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
            setTimeout(function () {
                initEditor();
            }, 3500);
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}





//permet de preselection/selectionneer/placer les DEs
    function selectDE(id, offre, etat) {
        // alert(id+"//"+offre+"//"+etat);
        // alert(etat);
        var eta = $("#etat_pres").val();
        // alert(racine + 'offres/selectDE/'+id +'/'+offre+'/'+etat);

        switch(etat) {
            /*case '1': //selectionner
                var msg =  'Êtes-vous sûr de vouloir preselectionner  ces demandeurs d`emplois ?';
            break;*/
            case '1','2': //presselectionner
                var msg =  'Êtes-vous sûr de vouloir selectionner  ce DE ?';
            break;
            case '3': //selectionner
                var msg =  'Êtes-vous sûr de vouloir placer ces demandes ?';
            break;
            
            default:
                var msg =  'Êtes-vous sûr de vouloir selectionner ces demandeurs d`emplois ?';
        }
        var confirme = confirm(msg);
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine + 'offres/selectDE/'+id +'/'+offre+'/'+etat,
                success: function (data)
                {
                    if(etat == 1 || etat == 2){
                        $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselect/" + eta +'/all/'  + offre).load();
                    }
                    else
                        $('.datatableshow6').DataTable().ajax.url(racine + "offres/getDEsSelectionnes/4/"   + offre).load();
                    $('.datatableshow5').DataTable().ajax.url(racine + "offres/getDEsSelectionnes/3/"   + offre).load();
                    
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

     //retirer un DE offre
    function retirerDEsOffre(id, offre, etat) {
        // alert(id+"//"+offre+"//"+etat);
        var confirme = confirm("Êtes-vous sûr de vouloir retirer ce demandeur d'emploi ?");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine + 'offres/retirerDE/'+id +'/'+offre+'/'+etat,
                success: function (data)
                {
                    
                    if(etat == 4)
                        $('.datatableshow6').DataTable().ajax.url(racine + "offres/getDEsSelectionnes/4/"   + offre).load();
                    else   
                        $('.datatableshow4').DataTable().ajax.url(racine + "offres/getDEsPreselect/2" + '/all/'  + offre).load();
                    
                   
                    $('.datatableshow5').DataTable().ajax.url(racine + "offres/getDEsSelectionnes/3/"   + offre).load();
                    
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


    //formation 
    //postuler un DE ou ajouter un DE libre a une offre  
    function postulerDE_form(type,element){
        // alert(type);
        if(type == 1)
            var msg =  'Êtes-vous sûr de vouloir postuler à cette formation ?';
        else
            var msg =  'Êtes-vous sûr de vouloir selectionner ces demandeurs d`emplois ?';

        var confirme = confirm(msg);
        if(confirme){
            var container = $(element).attr('container');
            $('#' + container + ' #form-errors').hide();
            $(element).attr('disabled','disabled');
            $('#' + container + ' .main-icon').hide();
            $('#' + container + ' .spinner-border').show();
            var data = $('#' + container + ' form').serialize();
            $.ajax({
                type: $('#' + container + ' form').attr("method"),
                url: $('#' + container + ' form').attr("action"),
                data: data,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    //$('.datatableshow').DataTable().ajax.reload();
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .answers-well-saved').show();
                    $(element).removeAttr('disabled');
                    /*$('.divMsg').html("Les données sont enregistrées avec succéss");
                    $('.divMsg').show();*/
                $('#second-modal').modal('toggle');

                    setTimeout(function () {
                        $('#' + container + ' .answers-well-saved').hide();
                        $('#' + container + ' .main-icon').show();
                        // $('.divMsg').hide();

                    }, 3500);
                    $('.datatableshow4').DataTable().ajax.reload();
                    $('.datatableshow5').DataTable().ajax.reload();
                    $('.datatableshow6').DataTable().ajax.reload();
                    // if(type)
                    // $('.datatableshow5').DataTable().ajax.url(racine + "formations/getDEs/6/all/"+ data).load();
                    // $('.btnAddDEslibres').hide();

                    // $('#second-modal').modal('toggle');
                    // getTheContent('offres/getTab/' + data + '/4/', '#tab4');


                },
                error: function (data) {
                    if (data.status === 422) {
                        var errors = data.responseJSON;
                        errorsHtml = '<ul class="list-group">';
                        var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                            errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        $('#' + container + ' #form-errors').show().html(errorsHtml);
                    } else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                    $('#' + container + ' .spinner-border').hide();
                    $('#' + container + ' .main-icon').show();
                    $(element).removeAttr('disabled');
                }
            });
        }
    }




    //permet de valider/selectionner les DEs to formation
    function selectDEsToObject(type, id, object, etat) {
        if(type == 1)//formation
            var lien = 'formations/selectDE/';
        else
            var lien = 'programmes/selectDE/';
        alert(racine + lien + id +'/'+object+'/'+etat);
        $.ajax({
            type: 'get',
            url: racine + lien + id +'/'+object+'/'+etat,
            success: function (data)
            {
                $('.datatableshow4').DataTable().ajax.reload();
                $('.datatableshow5').DataTable().ajax.reload();
                $('.datatableshow6').DataTable().ajax.reload();
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
    }


    function retirerDE (type, de,id,etat) {
        // alert(racine+'formations/retirerDE/'+element);
        if(type == 1)//formation
            var lien = 'formations/retirerDE/';
        else
            var lien = 'programmes/retirerDE/';
        // alert(racine + lien + de + "/" + id + "/" + etat);
        
        var confirme = confirm("Êtes-vous sûr de vouloir retire ce demadeur ? ");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine + lien + de + "/" + id + "/" + etat,
                success: function (data)
                {
                    $('.datatableshow4').DataTable().ajax.reload();
                    $('.datatableshow5').DataTable().ajax.reload();
                    $('.datatableshow6').DataTable().ajax.reload();
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




function getObjectPDF(lien)
{
    document.formpres.action = racine + lien;
    document.formpres.target = "_blank";    // Open in a new window
    document.formpres.submit();             // Submit the page
    return true;
}


 //changer le lieu d'employeur
    function changeTypeOffre(){
        type_offr = $("#type_offre").val();

        // alert("type_offr");
        $.ajax({
            type: 'get',
            url: racine + 'offres/getLieu/'+ type_offr,
            success: function (data) {
                // alert(data);
                $("#ref_commune_id").html(data);
                $('.selectpicker').selectpicker('refresh');

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }


    function filterCentreFormation()
    {
        commune = $("#commune").val();
        $('#datatableshow').DataTable().ajax.url(racine + "centres/getDT/" + centre  +  '/all').load();
    }


    function addAuthCentre(element){
        // $( '#addNewModal #form-errors' ).hide();
        // var element = $(this);
        var container = $(element).attr('container');
        $('#' + container + ' #form-errors').hide();
        $(element).attr('disabled','disabled');
        $('#' + container + ' .main-icon').hide();
        $('#' + container + ' .spinner-border').show();
        var data = $('#' + container + ' form').serialize();
        // alert($('#' + container + ' form').attr("action"));
        $.ajax({
            type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .answers-well-saved').show();
                $(element).removeAttr('disabled');
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                    $('#' + container + ' .main-icon').show();
                }, 3500);
                $('#second-modal').modal('toggle');
                // getTheContent('employeurs/getTab/' + data, '/1');
                // $('#main-modal').modal('toggle');

                // openObjectModal(data,"centres");

                getTheContent('centres/getTab/' + data + '/1/', '#tab1');


            },
            error: function(data){
                 if (data.status === 422) {
                    var errors = data.responseJSON;
                    errorsHtml = '<ul class="list-group">';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function (key, value) {
                        errorsHtml += '<li class="list-group-item list-group-item-danger">' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul>';
                    $('#' + container + ' #form-errors').show().html(errorsHtml);
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $('#' + container + ' .spinner-border').hide();
                $('#' + container + ' .main-icon').show();
                $(element).removeAttr('disabled');
            }

        });
    }

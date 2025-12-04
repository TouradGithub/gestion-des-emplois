 $(document).ready(function () {
    // Ajouter des specialite
    $('#addNewpecialiteModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var stagiaireid = button.data('stagiaireid');
      var header = button.data('header');
      var modal = $(this);
      modal.find('.modal-title').text(header);
      modal.find('.modal-body #stagiaireid').val(stagiaireid);
    })
 });
 // Ouvrire le modal d'un evaluateur
    function openEvaluateurModal(id,element=0,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'evaluateurs/getEvaluateurModal/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                getTheContent('evaluateurs/getEvaluateurTab/' + id + '/1', '#tab1');
                $('.eval-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
                // setDataTable('#datatableshow2');
                // setDataTable('#datatableshow1');
                // initmain();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal d'une mission
    function openMissionModal(id,element=0,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'missions/getMissionModal/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                getTheContent('missions/getEvaluateurTab/' + id + '/1', '#tab1');
                $('.main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
                // setDataTable('#datatableshow');
                // setDataTable('#datatableshow1');
                // init();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //modifier un evaluateur
    function editEvaluateur()
    {
        $( '#main-modal #form-errors' ).hide();
        var element = $(this);
        $("#main-modal .form-loading").show();
        var data = $('#main-modal form').serialize();
        $.ajax({
            type: $('#main-modal form').attr("method"),
            url: $('#main-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                var container = 'main-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#main-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#main-modal .form-loading").hide();
            }
        }); 
    }

    //ajouter une nouvelle mission 
    function addnewMission(){
        $( '#addNewModal #form-errors' ).hide();
        var element = $(this);
        $("#addNewModal .form-loading").show();
        var data = $('#addNewModal form').serialize();
        $.ajax({
            type: $('#addNewModal form').attr("method"),
            url: $('#addNewModal form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){  
              $('#addNewModal').modal('toggle');
              openMissionModal(data);
            },
            error: function(data){
               if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }

    // Ouvrire le modal du modification d'info
    function openInfoModal(info_id) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'infos/edit/' + info_id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                    $('.selectpicker').selectpicker('refresh');      

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal du modification d'info mobile 
    function openInfoMobilModal(niveau, info_id) {
        // alert(niveau+ 'm' + info_id);
        $.ajax({
            type: 'get',
            url: racine + 'mobile/edit/'+ niveau + '/' + info_id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                    $('.selectpicker').selectpicker('refresh');      

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //modification 
    function edit()
    {
        $( '#main-modal #form-errors' ).hide();
        var element = $(this);
        $("#main-modal .form-loading").show();
        var data = $('#main-modal form').serialize();
        $.ajax({
            type: $('#main-modal form').attr("method"),
            url: $('#main-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                // alert(data);
                var container = 'main-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
                    $('#datatableshow').DataTable().ajax.reload();

            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#main-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#main-modal .form-loading").hide();
            }
        }); 
    }
    function init(){
        // init du select picker
        $('.selectpicker').selectpicker({
            size: 10
        });
        // Collapse
        // $('.collapse').collapse();
        // Data tables to load General
        setDataTable('#datatableshow');
        setDataTable('#datatableshow1');        

        // init tooltips
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Filtre des groupes statistiques
    function filter_groupStat(){
        var categorie = $('#categorie').val();
        // alert(racine + "Compdec/formateurs/getformateursDT/" + organisme + '/' + diplome  + '/' + specialite  + '/'+langue + '/' + genre + '/none');
        $('#datatableshow').DataTable().ajax.url(racine + "groups/statistiques/getGroupStatDT/" + categorie + '/none').load();
    }
    // Filtre des info cartes web 
    function filter_infoWeb(){
        var niveau = $('#niveau').val();
        // alert(racine + "Compdec/formateurs/getformateursDT/" + organisme + '/' + diplome  + '/' + specialite  + '/'+langue + '/' + genre + '/none');
        $('#datatableshow').DataTable().ajax.url(racine + "infos/web/getInfoWebDT/" + niveau + '/none').load();
    }
    // Filtre des info mobile 
    function filter_mobile(){
        var niveau = $('#niveau').val();
        // alert(racine + "Compdec/formateurs/getformateursDT/" + organisme + '/' + diplome  + '/' + specialite  + '/'+langue + '/' + genre + '/none');
        $('#datatableshow').DataTable().ajax.url(racine + "mobile/getMobileDT/" + niveau + '/none').load();
    }
    // Ouvrire le modal du modification du groupe statistique 
    function openGroupStatModal(id,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'groups/statistiques/getGroupStatModal/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal .modal-dialog").addClass('modal-lg');
                $("#main-modal").modal();
                $('#datatableshow').DataTable().ajax.url(racine + "groups/statistiques/getGroupStatDT/all/"+id).load();
                
                getTheContent('groups/statistiques/getGroupStatTab/' + id + '/1', '#tab1');
                $('.main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
                // setDataTable('#datatableshow');
                // setDataTable('#datatableshow1');
                // init();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal du reponse des questions libres  
    function openRepQeslibreModal() {
        
        var idterr = $('.answerfreeqst').val();
        var niveau = $('#nivea').val();
        // alert(niveau);
        $.ajax({
            type: 'get',
            url: racine + 'questionslibres/freeanswers/'+idterr+'/'+niveau,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal .modal-dialog").addClass('modal-lg');
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal ajout sous groupe  du groupe de question  
    function openGroupQstModal() {
        
        var idgroupe = $('.group-add-sg').val();
        // alert(idgroupe);
        $.ajax({
            type: 'get',
            url: racine + 'groups/add/'+idgroupe,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal .modal-dialog").addClass('modal-lg');
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal du modification du groupe de question  
    function openGroupQstEditModal() {
        
        var idgroupe = $('.group-edit').val();
        // alert(idgroupe);
        $.ajax({
            type: 'get',
            url: racine + 'groups/edit/'+idgroupe,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal .modal-dialog").addClass('modal-lg');
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal du liste des qsts du groupe de question  
    function openGroupQstListModal() {
        var idgroupe = $('.group-qst-list').val();
        $.ajax({
            type: 'get',
            url: racine + 'grouping/'+idgroupe,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal .modal-dialog").addClass('modal-lg');
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //selectionner wilaya  filter fiche communales
    function selectionnerWilaya(){
        var wilaya = $('#wilayafiche').val();
        $.ajax({
            type: 'get',
            url: racine + 'Compdec/participants/wilayas/getmoughataas/' + wilaya,
            success: function (data) {
                // alert(data.mukattas);
                $("#mughataa").html(data.mukattas);
                $("#communefiche").html(data.commune);
                commune = $('#communefiche').val();
                mughataa = $('#mughataa').val();
                // alert(racine + "fichescommunales/getFichesDT/0/0/last/"  + wilaya + '/'+ mughataa  +'/'+ commune);
                $('#datatableshow').DataTable().ajax.url(racine + "fichescommunales/getFichesDT/0/0/last/"  + wilaya + '/' + mughataa  + '/' + commune + '/none').load();
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //selectionner mukataa 
    function selectionnerMukataa(){
        var mughataa = $('#mughataa').val();
        var wilaya = $('#wilayafiche').val();
        $.ajax({
            type: 'get',
            url: racine + 'Compdec/participants/mukataas/getcommunes/' + mughataa+'/'+wilaya,
            success: function (data) {
                $("#communefiche").html(data);
                commune = $('#communefiche').val();
                /*mughataa = $('#mughataa').val();
                wilaya = $('#wilaya').val();
               */ 
               $('#datatableshow').DataTable().ajax.url(racine +  "fichescommunales/getFichesDT/0/0/last/"  + wilaya + '/'+ mughataa  +'/'+ commune + '/none').load();
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //selectionner commune 
    function selectionnerCommune(){
        commune = $('#communefiche').val();
        mughataa = $('#mughataa').val();
        wilaya = $('#wilayafiche').val();
        $('#datatableshow').DataTable().ajax.url(racine +  "fichescommunales/getFichesDT/0/0/last/"  + wilaya + '/'+ mughataa  +'/'+ commune + '/none').load();
        $('.selectpicker').selectpicker('refresh');
    }
    //changer la valeur attribut du date
    
    function changedate(element,terrt=0) {
        // alert(element+"ff"+terrt);
        if (terrt) {
            $("#date_"+element+"_"+terrt).attr('date_changed',1);
        };
        $("#date_"+element).attr('date_changed',1);
        // $(this).hide();
        
    }
    //changer la date d'enregistrement 
    
    function changedateEnre() {
        var date_enreg = $('#date_enreg').val();
        // alert(date_enreg);
        $('.daterep').each(function(){
            // alert("date");
            var date_changed = $(this).attr('date_changed');
            if (date_changed == 0 ) {
                $(this).val(date_enreg);
            };
            // alert(date_r);
        });
        /*;
        $('.daterep').attr('dateterr',1);*/
        
    }
    function changeAgregation() {
        var agregation = $('#agregation').val();
        if (agregation == 1) {
            $('.formule-agregation').show();
        }
        else{
            $('.formule-agregation').hide();
        }
    }

    //filter des questions libres     
    /*function filterQstLibres()
    {
        niveau = $("#filter-niveau_reponse").val();
        agregation = $("#agreg").val();
        // alert(agregation);
        $('.btnsRepondreHistoriques').hide();
        $('#datatableshow').DataTable().ajax.url(racine + 'questionslibres/getQuestionsLibresDT/' + niveau + '/' + agregation+ '/' + id_objet).load();
    }*/
    function filterQstLibres()
    {
        niveau = $("#filter-niveau_reponse").val();
        agregation = $("#agreg").val();
        id_objet = $("#id_object").val();
        struct = $("#struct").val();
        // alert(struct);
        $('.btnsRepondreHistoriques').hide();
        if (niveau == 6) {
            // alert(id_objet);
            $('#datatableshow').DataTable().ajax.url(racine + 'questionslibres/getQuestionsLibresDT/' + niveau + '/' + agregation + '/' + id_objet+ '/' + struct).load();
            $(".obj").show();
        }
        else{
            // alert(id_objet);
            $(".obj").hide()
            $('#datatableshow').DataTable().ajax.url(racine + 'questionslibres/getQuestionsLibresDT/' + niveau + '/' + agregation + '/all' + '/' + struct ).load();
        }
    }

    
    //check all input in questions libres
    function checkAllQtions(element){
        if ($(element).is(':checked')) {
            $('.btnsRepondreHistoriques').show();
        }
        else
        {
            var str = 0;

            $(':checkbox').each(function() {
                str += this.checked ? 1 : 0;
            });
            if (str == 0) {
                $('.btnsRepondreHistoriques').hide();
            }
            else
                $('.btnsRepondreHistoriques').show();
            
        }
    }

    //ouvrir l'onglet du repondre des questions libres 
    function repondreQst(element){
        var questions_id = [];
        var showall=0;
        $("input:checked").each(function () {
            var id = $(this).val();
            questions_id.push(id);
            // alert("Do something for: " + id);
        });
        // alert(questions_id);
        $.ajax({
            type: 'get',
            url: racine + 'questionslibres/repondreQts/' + questions_id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                getTheContent('questionslibres/getRepHistorQstLibrTab/' + questions_id + '/1', '#tab1');
                $('.main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();
                });
                //get content on tab click
                $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        
    }
    //reponse objets
    function  getReponsesObjets(){
        var niveau = $('#niveau').val();
        var territoires = $('#objet').val();
        // alert(territoires);
        var ids_questions = $('#ids_questions').val();
        if (territoires.length <= 3) 
            var type = 1;
        else
            var type = 0;
        // alert(territoires);
        if (territoires == "") 
            var link =  'questionslibres/getQuestionsLibresWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
        else
            var link =  'questionslibres/getQuestionsLibresWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
        $('.form-loading').show();
        $.ajax({
            type: 'get',
            url: racine + link  ,
            success: function (data) {
                // alert(data);
               $('.form-loading').hide();
                $(".div-repondre").empty();
                $(".div-repondre").html(data);
                $("#btnsaveanswer").show();
                $(".datedefault").show();
                $("#territoires_id").val(territoires);
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        resetInit();
        // alert(ids_questions);
    }
    function  getReponsesCommunes(){
        var niveau = $('#niveau').val();
        var territoires = $('#comm').val();
        var ids_questions = $('#ids_questions').val();
        if (territoires.length <= 3) 
            var type = 1;
        else
            var type = 0;
        // alert(territoires);
        if (territoires == "") 
            var link =  'questionslibres/getQuestionsLibresWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
        else
            var link =  'questionslibres/getQuestionsLibresWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
        $('.form-loading').show();
        $.ajax({
            type: 'get',
            url: racine + link  ,
            success: function (data) {
                // alert(data);
               $('.form-loading').hide();
                $(".div-repondre").empty();
                $(".div-repondre").html(data);
                $("#btnsaveanswer").show();
                $(".datedefault").show();
                $("#territoires_id").val(territoires);
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        resetInit();
        // alert(ids_questions);
    }
    function  getReponsesMoughataas(){
        var niveau = $('#niveau').val();
        var territoires = $('#moughataas').val();
        var wilaya = $('#wilayaa').val();
        var ids_questions = $('#ids_questions').val();
        if (niveau == 2) {
            if (territoires.length <= 3) 
                var type = 1;
            else
                var type = 0;
            if (territoires == "") 
                var link =  'questionslibres/getQuestionsLibresWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
            else
                var link =  'questionslibres/getQuestionsLibresWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
            $.ajax({
                type: 'get',
                url: racine + link,
                success: function (data) {
                    $(".div-repondre").empty();
                    $(".div-repondre").html(data);
                    $("#btnsaveanswer").show();
                    $(".datedefault").show();
                    $("#territoires_id").val(territoires);
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
        else{
            $.ajax({
                type: 'get',
                url: racine + 'mukataas/getcommunes/' + territoires +'/'+ wilaya ,
                success: function (data) {
                    $("#comm").html(data);
                    $(".div-repondre").empty();
                    $('.selectpicker').selectpicker('refresh');      
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
        resetInit();
    }

    function  getReponsesWilayas(){
        var niveau = $('#niveau').val();
        var territoires = $('#wilayaa').val();
        var ids_questions = $('#ids_questions').val();
        if (niveau == 3) {
            if (territoires.length <= 3) 
                var type = 1;
            else 
                var type = 0;
            if (territoires == "") 
                var link =  'questionslibres/getQuestionsLibresWithInputs/' + 0 + '/' + ids_questions+ '/' + niveau+ '/' + type;
            else
                var link =  'questionslibres/getQuestionsLibresWithInputs/' + territoires + '/' + ids_questions+ '/' + niveau+ '/' + type;
            $.ajax({
                type: 'get',
                url: racine + link,
                success: function (data) {
                    $(".div-repondre").empty();
                    $(".div-repondre").html(data);
                    $("#btnsaveanswer").show();
                    $(".datedefault").show();
                    $("#territoires_id").val(territoires);
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
        else{
            $.ajax({
                type: 'get',
                url: racine + 'wilayas/getmoughataas/' + territoires + '/' + niveau,
                success: function (data) {
                    $("#moughataas").html(data.mukattas);
                    $("#comm").html(data.commune);
                    $(".div-repondre").empty();
                    $('.selectpicker').selectpicker('refresh');      
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
        resetInit();
    }

    //afficher structure si partagee
    function afficher_structure(){
        var is_public = $("#is_public").val();
        var b_strictures_id = $("#b_strictures_id").val();
        // alert(b_strictures_id);
        if (is_public == 2) {
            $.ajax({
                type: 'get',
                url: racine+'questionslibres/getStructuresPartagees/'+b_strictures_id,
                success: function (data) {
                    $("#structure_part").html(data);
                    $("#strucPartage").show();
                    $('.selectpicker').selectpicker('refresh');      
                },
                error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
            });
        }
        else{
            $("#strucPartage").hide();
        }
        // alert(id_qst+"op"+territoires+"op"+niveau);
    }


    // Ouvrire le modal d'une famille de question 
    function openFamillesQstModal(id,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'FamillesQst/getFamillesQstModal/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                getTheContent('FamillesQst/getFamillesQstTab/' + id + '/1', '#tab1');
                $('.main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
                // setDataTable('#datatableshow');
                // setDataTable('#datatableshow1');
                // init();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //ajouter une nouvelle famille 
    function addnewFamillesQst(){
        $( '#addNewModal #form-errors' ).hide();
        var element = $(this);
        $("#addNewModal .form-loading").show();
        var data = $('#addNewModal form').serialize();
        $.ajax({
            type: $('#addNewModal form').attr("method"),
            url: $('#addNewModal form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){  
                $('#addNewModal').modal('toggle');
                $('#addNewModal .form-loading').hide();
                $('#addNewModal .answers-well-saved').show();
                setTimeout(function () {
                    $('#addNewModal .answers-well-saved').hide();
                }, 3500);
                $('#datatableshow').DataTable().ajax.reload();
                openFamillesQstModal(data);
            },
            error: function(data){
               if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }
    //modifier une famille
    function editFamillesQst()
    {
        $( '#main-modal #form-errors' ).hide();
        var element = $(this);
        $("#main-modal .form-loading").show();
        var data = $('#main-modal form').serialize();
        $.ajax({
            type: $('#main-modal form').attr("method"),
            url: $('#main-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                var container = 'main-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#main-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#main-modal .form-loading").hide();
            }
        }); 
    }

    // updating a group des elements
function updateGroupeElementsFamille(element = null) {
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
                    $(".group-elements").append('<li class="list-group-item" id="' + idelt + '">' + libelle + '<button type="button" idelt="' + idelt + '" class="close" aria-hidden="true" onclick="updateGroupeElements(this)">&times;</button></li>');
                }
                if ($('.btn-drftval').length) {
                    if (qsts.length > 0)
                        $('.btn-drftval').show();
                    else
                        $('.btn-drftval').hide();
                }
            }
            $('.datatableshow3').DataTable().ajax.reload();
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

// Ouvrire le modal ajout type geo libre  
    function openTypeGeoLibre(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'typeGeoLibre/edit/'+id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal ajout objet geo libre  
    function openObjetGeoLibre(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'objeGeoLibre/edit/'+id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //recupere l'objet 
    function get_object(){
        var id_objet = $('#types_objets_geo_id').val();
        // alert(id_objet);
        $.ajax({
            type: 'get',
            url: racine + 'objeGeoLibre/get_object/' + id_objet,
            success: function (data) {
                // alert(data);
                $("#object_id").html(data);
                $(".objectdiv").show();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    function select_niveau() {
        var niveau_reponse = $('#niveau_reponse').val();
        if (niveau_reponse == 6) {
            $('.typeObjet').show();
        }
        else{
            $('.typeObjet').hide();
        }
    }
    function change_niveau() {
        var niveau_geo = $('#niveau_geo').val();
        if (niveau_geo == 6) {
            $('.typeObjet').show();
        }
        else{
            $('.typeObjet').hide();
        }
    }


    //modification d'info carte web 
    function editInfoCartWeb()
    {
        $( '#main-modal #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#main-modal .form-loading").show();
        var data = $('#main-modal form').serialize();
        $.ajax({
            type: $('#main-modal form').attr("method"),
            url: $('#main-modal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                // window.location.href = data;
                // $('#datatableshow').DataTable().ajax.reload();
                $('#main-modal .form-loading').hide();
                $('#main-modal .answers-well-saved').show();
                setTimeout(function () {
                    $('#main-modal .answers-well-saved').hide();
                }, 3500);
                $('#datatableshow').DataTable().ajax.url(racine + "infos/web/getInfoWebDT/all/" + data).load();
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#main-modal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#main-modal.form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }
   
   function afficher_stru(){
        var is_public = $("#is_pub").val();
        var b_strictures_id = $("#b_strictures_id").val();
        // alert(is_public);
        if (is_public == 2) {
            $.ajax({
                type: 'get',
                url: racine+'questionslibres/getStructuresPartagees/'+b_strictures_id,
                success: function (data) {
                    $("#structure_part").html(data);
                    $("#strucParta").show();
                    $('.selectpicker').selectpicker('refresh');      
                },
                error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
            });
        }
        else{
            $("#strucParta").hide();
        }
        // alert(id_qst+"op"+territoires+"op"+niveau);
}
//historiques des reponses des questions
function openReponsesHistory(id_qst) {
    // alert(id_qst);
    $.ajax({
        type: 'get',
        url: racine + 'questionslibres/openReponsesHistory/' + id_qst,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            init();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function openHistotyReponses(id_qst) {
    var territoires_id = $("#terrr").val();
    var niveau = $("#niveau").val();
    $.ajax({
        type: 'get',
        url: racine + 'questionslibres/getHistoriqueReponsesAll/' + territoires_id + '/' + niveau + '/' + id_qst,
        success: function (data) {
            $("#historique-modal .modal-header").html('<h4> Historique des réponses </h4>');
            $("#historique-modal .modal-body").html(data);
            // $(element).closest('tr').css('background-color','#efefef');
            $("#historique-modal").modal();
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
    // alert(id_qst+"op"+territoires+"op"+niveau);
}

function getReponses(id_qst, niveau) {
    
    if (niveau == 3) {
        var territoires_id = $('#wilayaa').val();
        if (territoires_id == "") {
            territoires_id = "all";
        }
        /*else 
            territoires_id = "all";;*/
        // alert(territoires_id);
        $.ajax({
            type: 'get',
            url: racine + 'wilayas/getmoughatas/' + territoires_id,
            success: function (data) {
                // alert(data.mukattas);
                $("#moughataas").html(data.mukattas);
                $("#comm").html(data.commune);
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
        });
    }
    else if (niveau == 2) {
        // var mughataa = $('#mughataa').val();
        var wilaya = $('#wilayaa').val();
        var territoires_id = $('#moughataas').val();
        if (territoires_id == "") {
            territoires_id = "all";
        }
        $.ajax({
            type: 'get',
            url: racine + 'mukataas/getcomunes/' + territoires_id + '/' + wilaya,
            success: function (data) {
                $("#comm").html(data);
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
        if (territoires_id == "all") {
            // alert("all");
            var terr = $("#wilayaa").val();
            get_reponse(terr, 3, id_qst);
        };
    }
    else if (niveau == 1) {
        var territoires_id = $('#comm').val();
        if (territoires_id == "") {
            territoires_id = "all";
        }
        // alert(territoires_id);
        if (territoires_id == "all") {
            // alert("all");
            var terr = $("#moughataas").val();
            get_reponse(terr, 2, id_qst);
        };
        
    }
    else if (niveau == 6) {
        var territoires_id = $('#objet').val();
        if (territoires_id == "") {
            territoires_id = "all";
        }
    }
    if (territoires_id != "all")
        get_reponse(territoires_id, niveau, id_qst);

}

function get_reponse(territoires_id, niveau, id_qst) {
    $.ajax({
        type: 'get',
        url: racine + 'questionslibres/getHistoriqueReponsesAll/' + territoires_id + '/' + niveau + '/' + id_qst,
        success: function (data) {
            // $(".reps").html('<h4> Réponse pour la :  </h4>');
            $(".historique").empty();
            $(".historique").html(data);
            // $(element).closest('tr').css('background-color','#efefef');
            // $("#historique-modal").modal();
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
    // alert(id_qst+"op"+territoires+"op"+niveau);
}

//ftp 
// Ouvrire le modal ajout type geo libre  
    function openEditRefModal(array)
    {
        var tableau=array.split(",");
        var ref=tableau[0];
        var id=tableau[1];
        /*alert(ref);
        alert(id);*/
       $.ajax({
           type: 'get',
           url: racine+'ref/edit/'+ref+'/'+id,
           // alert(url);
           success: function (data) {
            // alert(data);
            $("#main-modal .modal-body").html(data);
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

    // Ouvrire le modal ajout niveau formation  
    function openNiveauFormModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'niveauForms/edit/'+id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal personnel  
    function openPersonnelModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'personnels/edit/'+id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal specialite 
    function openSpecialiteModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'specialites/edit/'+id,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //ajouter une nouvelle etablissement 
    function addNewEtab(){
        $( '#addNewModal #form-errors' ).hide();
        var element = $(this);
        $("#addNewModal .form-loading").show();
        var data = $('#addNewModal form').serialize();
        $.ajax({
            type: $('#addNewModal form').attr("method"),
            url: $('#addNewModal form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){  
              $('#addNewModal').modal('toggle');
              openEtabModal(data);
              $('#datatableshow').DataTable().ajax.url(racine + "etablissements/getEtabsDT/all/all/"+ data).load();     
                /*setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
              },3500);*/
            },
            error: function(data){
               if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }

    // Ouvrire le modal d'une etab
    function openEtabModal(id,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'etablissements/getEtab/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                $('#datatableshow').DataTable().ajax.url(racine + "etablissements/getEtabsDT/all/all/"+id).load();
                getTheContent('etablissements/getEtabTab/' + id + '/1', '#tab1');
                $('.formation-main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.formations-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
                // setDataTable('#datatableshow2');
                // setDataTable('#datatableshow1');
                // init();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //filter des etabls    
    function filterEtab()
    {
        commune = $("#comm").val();
        type = $("#typeEtab").val();
        $('#datatableshow').DataTable().ajax.url(racine + 'etablissements/getEtabsDT/' + type + '/' + commune + '/none').load();

    }
    //filter des stagiaire    
    function filterStagiaire()
    {
        specialite = $("#specialite").val();
        etablissement = $("#etablissement").val();
        etat = $("#etat").val();
        genre = $("#genre").val();
        $('#datatableshow').DataTable().ajax.url(racine + 'stagiaires/getStagiairesDT/' + specialite + '/' + etablissement + '/' + genre + '/' + etat + '/none').load();

    }

    //modifier une etablissement
    function editEtab()
    {
        $( '#main-modal #form-errors' ).hide();
        var element = $(this);
        $("#main-modal .form-loading").show();
        var data = $('#main-modal form').serialize();
        $.ajax({
            type: $('#main-modal form').attr("method"),
            url: $('#main-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                var container = 'main-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
                $('#datatableshow').DataTable().ajax.url(racine + "etablissements/getEtabsDT/all/all/"+ data).load();     

            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#main-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#main-modal .form-loading").hide();
            }
        }); 
    }
    //ajouter un nouveau staagiaire 
    function addNewStagiare(){
        $( '#addNewModal #form-errors' ).hide();
        // var element = $(this);
        $("#addNewModal .form-loading").show();
        // var data = $('#addNewModal form').serialize();
        $.ajax({
            type: $('#addNewModal form').attr("method"),
            url: $('#addNewModal form').attr("action"),
            data: new FormData($('#addNewModal form')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){  
              $('#addNewModal').modal('toggle');
                openStagiaireModal(data);
                /*setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                },3500);*/
                $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/all/all/all/all/"+ data).load();     
                /*var form = document.getElementById("formSTAG");
                form.reset();*/
            },
            error: function(data){
               if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }

    // Ouvrire le modal d'une etab
    function openStagiaireModal(id,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/getStagiaireModal/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/all/all/all/all/"+id).load();
                getTheContent('stagiaires/getStagiaireTab/' + id + '/1', '#tab1');
                $('.formation-main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.formations-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    // alert(link);
                    // alert(container);
                    $(container).empty();
                    getTheContent(link, container);
                });
                // setDataTable('#datatableshow2');
                // setDataTable('#datatableshow1');
                // init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //modifier un stagiaire
    function editStagiare()
    {
        $( '#main-modal #form-errors' ).hide();
        var element = $(this);
        $("#main-modal .form-loading").show();
        var data = $('#main-modal form').serialize();
        $.ajax({
            type: $('#main-modal form').attr("method"),
            url: $('#main-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                var container = 'main-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
                $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/all/all/all/all/"+ data).load();     

            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#main-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#main-modal .form-loading").hide();
            }
        }); 
    }

    // add-specialite stagiare
    function addNewSpecialiteStag(){
        $( '#addNewpecialiteModal #form-errors' ).hide();
        // var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#addNewpecialiteModal .form-loading").show();
        var data = $('#addNewpecialiteModal form').serialize();
        $.ajax({
            type: $('#addNewpecialiteModal form').attr("method"),
            url: $('#addNewpecialiteModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                $('#addNewpecialiteModal').modal('toggle');
                $("#addNewpecialiteModal .form-loading").hide();
                $('#addNewpecialiteModal .answers-well-saved').show();
                setTimeout(function () {
                    $('#addNewpecialiteModal .answers-well-saved').hide();
                }, 3500);
                $('.datatableshow').DataTable().ajax.url(racine + 'stagiaires/getStagEtabDT/' + data).load();
                var form = document.getElementById("formSpec");
                form.reset();
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#addNewpecialiteModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#addNewpecialiteModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
       });
    }

     // edit specialite stagiaire
    function openSpecialiteStag(id) {
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/specialite/edit/' + id,
            success: function (data) {
                $("#specialite-modal .modal-header-body").html(data);
                $("#specialite-modal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //modification des formation 
    function editForm()
    {
        $( '#divFormation #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#divFormation .form-loading").show();
        var data = $('#divFormation form').serialize();
        $.ajax({
            type: $('#divFormation form').attr("method"),
            url: $('#divFormation form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){
                // alert(data);
                // window.location.href = data;
                // $('#datatableshow').DataTable().ajax.reload();
                $('#divFormation .form-loading').hide();
                $('#divFormation .answers-well-saved').show();
                setTimeout(function () {
                    $('#divFormation .answers-well-saved').hide();
                }, 3500);
                $('#datatableshow').DataTable().ajax.reload();
                getTheContent('stagiaires/getStagiaireTab/' + data + '/2', '#tab2');
                // $('.datatableshow').DataTable().ajax.url(racine + 'stagiaires/getStagEtabDT/' + data).load();
                $('.selectpicker').selectpicker('refresh');
                
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#divFormation #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#divFormation .form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }
    function changeLieu(){
        if ($('.checkLieu').is(':checked')){
            $( '.divPays' ).hide();
            $( '.divRIM' ).show();
        }
        else {
            $( '.divPays' ).show();
            $( '.divRIM' ).hide();
        }
    }

    //recupere les specialites 
    function get_specialite(){
        //element =1 : participant , element =2 : acterus metier, 3:acteurs formations
        // alert(element);
        var etab = $('#b_etablissements_id').val();
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/formation/getSpecialites/' + etab,
            success: function (data) {
                // alert(data.mukattas);
                $("#b_specialites_id").html(data);
                $('.selectpicker').selectpicker('refresh');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    // Ouvrire le modal suivi 
    function openSuiviModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/suivi/get_suivi/'+id,
            success: function (data) {
                $("#suivi-modal .modal-header-body").html(data);
                $("#suivi-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // Ouvrire le modal ajout du suivi 
    function openNewSuiviModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/suivi/NewSuivi/'+id,
            success: function (data) {
                $("#Newsuivi-modal .modal-header-body").html(data);
                $("#Newsuivi-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    // voir historie
    function openSuiviHistory(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/suivi/SuiviHistory/'+id,
            success: function (data) {
                $("#Newsuivi-modal .modal-header-body").html(data);
                $("#Newsuivi-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //ajouter new suivi
    function addNewSuivi()
    {
        $( '#Newsuivi-modal #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#Newsuivi-modal .form-loading").show();
        var data = $('#Newsuivi-modal form').serialize();
        $.ajax({
            type: $('#Newsuivi-modal form').attr("method"),
            url: $('#Newsuivi-modal form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){
                // alert(data);
                $('#Newsuivi-modal').modal('toggle');
                $('#Newsuivi-modal .form-loading').hide();
                $('#Newsuivi-modal .answers-well-saved').show();
                setTimeout(function () {
                    $('#Newsuivi-modal .answers-well-saved').hide();
                }, 3500);
                
                $('#datatableshow1').DataTable().ajax.url(racine + 'stagiaires/suivi/getSuiviDT/' + data).load();
                /*$("#suivi-modal").modal('toggle');
                $("#suivi-modal").modal();*/
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#Newsuivi-modal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#Newsuivi-modal .form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }
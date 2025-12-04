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

    /*$('#suivi-modal').on('hidden.bs.modal', function () {
        stag_id = $("#stagiaireid").val();
        // openStagiaireModal(stag_id);
        getTheContent('stagiaires/getStagiaireTab/' + stag_id + '/2', '#tab2');

    });*/
    // initialize with defaults
    $("#fichier").fileinput();
    $("#modiffichier").fileinput();

    for (i = new Date().getFullYear(); i > 1990; i--)
    {
        $('.yearpicker').append($('<option />').val(i).html(i));
        $('.selectpicker').selectpicker('refresh');      

    } 
    // with plugin options
    // $("#fichier").fileinput({'showUpload':false, 'previewFileType':'any'});
});

function closeDiv(element){
    // $('#close').on('click', function(e) { 
        if(element == 1) //new suivi
            $('.new_suivi').remove(); 
        else    
            $('#edit_suivi').remove(); 
    // });
}
function closeDivHist(){
    // $('#close').on('click', function(e) { 
        // $('#history_suivi').remove(); 
        $('.new_suivi').remove(); 
    // });
}
function closeNewSpecEtab(){
    $('.divSpecEtab').remove(); 
}
$("#fichier").fileinput({
        language: "fr",
        'showUpload': false,
        // uploadUrl: "/site/image-upload",
        allowedFileExtensions: ["jpg", "png", "gif"],
        maxImageWidth: 20,
        maxImageHeight: 10,
        resizePreference: 'height',
        maxFileCount: 1,
        resizeImage: true    
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
                    console.log(errors.errors);
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
                    console.log(errors.errors);
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
                var container = 'main-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                /*setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); */
                    $('#datatableshow').DataTable().ajax.reload();

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
        setDataTable('.datatableshow');
        setDataTable('.datatableshow1');        

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
                    console.log(errors.errors);
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
                    console.log(errors.errors);
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
                    console.log(errors.errors);
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
                $("#edit_specialite_secteur").hide();
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
              $('#addNewModal .form-loading').hide();
              openEtabModal(data);
              $('#datatableshow').DataTable().ajax.url(racine + "etablissements/getEtabsDT/all/all/"+ data).load();     
              var form = document.getElementById("formEtab");
              form.reset();
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
    function filterSortant(element)
    {
        specialite = $("#specialite").val();
        etablissement = $("#etablissement").val();
        niveau = $("#niveau").val();
        // alert(specialite + "ggg" + etablissement +"ggg"+ niveau);
        if (element == 1)  {//filter candiats pv
            $('.datatableshow5').DataTable().ajax.url(racine + 'pvs/sortants/getSortansDT/' + specialite + '/' + etablissement + '/' + niveau).load();
        }
        else {  //2 sortants pv
            id_pv = $("#id_pv").val();
            $('.datatableshow6').DataTable().ajax.url(racine + 'pvs/sortants/getStagiairesDT/' + id_pv + '/' + specialite +  '/' + etablissement + '/' + niveau  + '/none').load();
        }
    }
   //filter des stagiaire d'une etablissement    
    function filterStagEtab()
    {
        specialite = $("#spec").val();
        niveau = $("#niveaux").val();
        id_etab = $("#id_etab").val();
        // alert(specialite + "ggg" + id_etab +"ggg"+ niveau);
        $('.datatableshow5').DataTable().ajax.url(racine + 'etablissements/stagiaires/getStagiairesDT/' + id_etab + '/' + specialite + '/' + niveau + '/none').load();
        
    }
   //filter des sortants    
function filterStagiaire()
{
    specialite = $("#specialite").val();
    etablissement = $("#etablissement").val();
    etat = $("#etat").val();
    genre = $("#genre").val();
    niveau = $("#niveau_form").val();
    promo = $("#Promotion").val();
    date_1 = $("#date_1").val();
    date_2 = $("#date_2").val();
    if(etat==10)
        $("#promo").show();
    else
        $("#promo").hide();
    // alert(niveau);
    $('#datatableshow').DataTable().ajax.url(racine + 'stagiaires/getStagiairesDT/' + specialite + '/' + etablissement + '/' + genre  + '/' + etat  + '/' + niveau  + '/' + date_1  + '/' + date_2 + '/' + promo + '/none').load();
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
                    console.log(errors.errors);
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
                $('#addNewModal .form-loading').hide();
                openStagiaireModal(data);
                $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/all/all/all/all/all/all/all/all/"+ data).load();     
                var form = document.getElementById("formSTAG");
                form.reset();
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
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }

    // Ouvrire le modal d'une etab
    function openStagiaireModal(id,element=0,showall=0) {
        // alert(id+ 'm'  + element +'hg' + showall);
        specialite = $("#specialite").val();
        etablissement = $("#etablissement").val();
        etat = $("#etat").val();
        genre = $("#genre").val();
        promotion = $("#Promotion").val();

        niveau = $("#niveau_form").val();
        date_1 = $("#date_1").val();
        date_2 = $("#date_2").val();
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/getStagiaireModal/' + id + '/' + showall,
            success: function (data) {
                $("#stagiaire-modal .modal-header-body").html(data);
                $("#stagiaire-modal").modal();
                if (element == 0) {
                    $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/"+ specialite + '/' + etablissement + '/' + genre  + '/' + etat  + '/' + niveau  + '/all/all/'  + promotion + "/"+ id).load();
                    // $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/all/all/all/all/all/all/all/all/"+id).load();
                }
                getTheContent('stagiaires/getStagiaireTab/' + id + '/1', '#stagiaire-tab1');
                $('.formation-main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();
                });
                //get content on tab click
                $('.formations-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    $(container).empty();
                    getTheContent(link, container);

                });
                // setDataTable('#datatableshow2');
                // setDataTable('#datatableshow1');
                resetInit();
                $("#modiffichier").fileinput();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //modifier un stagiaire
    function editStagiare()
    {
        $( '#stagiaire-modal #form-errors' ).hide();
        var element = $(this);
        $("#stagiaire-modal .form-loading").show();
        var data = $('#stagiaire-modal form').serialize();
        $.ajax({
            type: $('#stagiaire-modal form').attr("method"),
            url: $('#stagiaire-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                var container = 'stagiaire-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
                $('#datatableshow').DataTable().ajax.url(racine + "stagiaires/getStagiairesDT/all/all/all/all/all/all/all/all/"+ data).load();     

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
                    $( '#stagiaire-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#stagiaire-modal .form-loading").hide();
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
                $('.datatableshow').DataTable().ajax.url(racine + 'etablissements/specialites/getSpecialitesDT/' + data).load();
                var form = document.getElementById("formSpec");
                form.reset();
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
        $('#divFormation #form-errors' ).hide();
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
                $('.selectpicker').selectpicker('refresh');
                if (data.formaEncour == 1) {
                    // $('#titlemod').empty();
                    $('#titlemod').html("Formation en cours");
                }
                else
                    // $('#titlemod').empty();
                    $('#titlemod').html("Nouvelle formation");
                
                getTheContent('stagiaires/getStagiaireTab/' + data.stagid + '/2', '#stagiaire-tab2');
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
    function getSpecialite(){
       //element =1 : participant , element =2 : acterus metier, 3:acteurs formations
        var etab = $('#b_etablissements_id').val();
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/formation/getSpecialites/' + etab,
            success: function (data) {
                // alert(data.speci);
                $("#b_specialites_id").html(data.speci);
                $("#ref_niveaux_formations_id").html(data.niveau);
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
                resetInit()
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
                $("#add_new_suivi").html(data);
                $("#add_new_suivi").show();
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
                $("#history_suivi").hide();
                $("#edit_suivi").hide();
                // $("#edit_suivi_form_cloture").hide();
                $("#history_suivi").html(data);
                $("#history_suivi").show();
                $("#add_new_suivi").hide();
                // scrollTop: $("#history_suivi").offset().top
                // $('#stagiaire-modal').animate({ scrollTop: $("#history_suivi").offset().top }, 500);
                var position = $("#history_suivi").offset().top;
                $("#stagiaire-modal").animate({ scrollTop: position }, position);
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //ajouter new suivi
    function addNewSuivi()
    {
        $( '#add_new_suivi #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#add_new_suivi .form-loading").show();
        var data = $('#add_new_suivi form').serialize();
        $.ajax({
            type: $('#add_new_suivi form').attr("method"),
            url: $('#add_new_suivi form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){
                // alert(data);
                // $('#add_new_suivi').modal('toggle');
                $('#add_new_suivi .form-loading').hide();
                $('#add_new_suivi .answers-well-saved').show();
                setTimeout(function () {
                    $('#add_new_suivi .answers-well-saved').hide();
                }, 3500);
                
                // $('#datatableshow1').DataTable().ajax.url(racine + 'stagiaires/suivi/getSuiviDT/' + data.stagEtabid).load();
                $("#suivi-modal").modal('toggle');
                if (data.formaEncour == 1) {
                    $('#titlemod').html("Formation en cours");
                }
                else
                    $('#titlemod').html("Nouvelle formation");
                getTheContent('stagiaires/getStagiaireTab/' + data.stagID + '/2', '#stagiaire-tab2');
                
                /*$("#main-modal").modal('toggle');
                $("#main-modal").modal();*/
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
                    $( '#add_new_suivi #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#add_new_suivi .form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }

    //ouvrir l'onglet du repondre des stagiaires 
    function repondreStagi(element){
        var id_stagiaire_etab = [];
        // var showall=0;
        $("input:checked").each(function () {
            var id = $(this).val();
            id_stagiaire_etab.push(id);
            // alert("Do something for: " + id);
        });
        alert(id_stagiaire_etab);
        /*$.ajax({
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
        });*/
        
    }
    // reprise d'une formation   
    function repriseFormation(id) {
        var reprise =confirm("Êtes-vous sûr de vouloir reprise cette formation?");
        if (reprise) {
            // alert(id);
            $.ajax({
                type: 'get',
                url: racine + 'stagiaires/reprise/'+id,
                success: function (data) {
                    getTheContent('stagiaires/getStagiaireTab/' + data + '/3', '#stagiaire-tab3');
                    $('#titlemod').html("Formation en cours");
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
    }

    //ajouter une nouvelle specialite
    function addNewSpecialite(){
        $( '#specialite_etab #form-errors' ).hide();
        var element = $(this);
        $("#specialite_etab .form-loading").show();
        var data = $('#specialite_etab form').serialize();
        $.ajax({
            type: $('#specialite_etab form').attr("method"),
            url: $('#specialite_etab form').attr("action"),
            data: data,
            success: function(data){  
                // $('#specialite_etab').modal('toggle');
                // openStagiaireModal(data);
                $('#specialite_etab .form-loading').hide();
                $('#specialite_etab .answers-well-saved').show();
                setTimeout(function () {
                    $('#specialite_etab .answers-well-saved').hide();
                }, 3500);

                $('.datatableshow').DataTable().ajax.url(racine + "etablissements/specialites/getSpecialitesDT/"+ data).load();     
                /*var form = document.getElementById("formSTAG");
                form.reset();*/
                $('#specialite_etab').hide();
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
                   $( '#specialite_etab #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#specialite_etab .form-loading").hide();
            }
        });
    }
    // Ouvrire le modal new specialite
    function openNewSpecModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'etablissements/specialites/newSpecialite/'+id,
            success: function (data) {
                $("#specialite_etab").html(data);
                $("#specialite_etab").show();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    // edit specialite etablissement
    function openSpecialiteEtab(id) {
        $.ajax({
            type: 'get',
            url: racine + 'etablissements/specialites/edit/' + id,
            success: function (data) {
                $("#EditspecialiteModal .modal-header-body").html(data);
                $("#EditspecialiteModal").modal();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //ajouter une nouvelle pv 
    function addNewPv(){
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
                openPvModal(data);
                $('#datatableshow').DataTable().ajax.url(racine + "pvs/getPvsDT/all/all/all/"+ data).load();     
                var form = document.getElementById("formPV");
                form.reset();

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
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }
    //modifier un pv
    function editPV()
    {
        $( '#pv-modal #form-errors' ).hide();
        var element = $(this);
        $("#pv-modal .form-loading").show();
        var data = $('#pv-modal form').serialize();
        $.ajax({
            type: $('#pv-modal form').attr("method"),
            url: $('#pv-modal form').attr("action"),
            data: data,
            success: function(data){ 
                /*detailExamen(data);            
                headExamen(data);*/
                var container = 'pv-modal';
                $('#' + container + ' .form-loading').hide();
                $('#' + container + ' .answers-well-saved').show();
                setTimeout(function () {
                    $('#' + container + ' .answers-well-saved').hide();
                }, 3500); 
                $('#datatableshow').DataTable().ajax.url(racine + "pvs/getPvsDT/all/all/all/"+ data).load();     

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
                    $( '#pv-modal #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#pv-modal .form-loading").hide();
            }
        }); 
    }
    // Ouvrire le modal d'une mission
    function openPvModal(id,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'pvs/getPvModal/' + id + '/' + showall,
            success: function (data) {
                $("#pv-modal .modal-header-body").html(data);
                $("#pv-modal").modal();
                $('#datatableshow').DataTable().ajax.url(racine + "pvs/getPvsDT/all/all/all/"+id).load();

                getTheContent('pvs/getPvTab/' + id + '/1', '#tabPv1');
                $('.pv-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();

                });
                //get content on tab click
                $('.formations-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
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

    // Save answers sortants 
    function addSortants()
    {
        $( '#divSortant #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#divSortant .form-loading").show();
        var data = $('#divSortant form').serialize();
        $.ajax({
            type: $('#divSortant form').attr("method"),
            url: $('#divSortant form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){
                // alert(data.msg);
                // window.location.href = data;
                // $('#datatableshow').DataTable().ajax.reload();
                $('#divSortant .form-loading').hide();
                $('#divSortant .answers-well-saved').show();
                setTimeout(function () {
                    $('#divSortant .answers-well-saved').hide();
                }, 3500);
                // $('#datatableshow').DataTable().ajax.reload();
                $('.datatableshow5').DataTable().ajax.url(racine + 'pvs/sortants/getSortansDT/all/all/all').load();
                $('.selectpicker').selectpicker('refresh');
                $('.message').html(data.msg);
                $('.message').show();
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
                    $( '#divSortant #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#divSortant .form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }


    //check all input in sortants 
    function checkAllSortants(element){
        // alert("fff");
        if ($(element).is(':checked')) {
            $('.btnAddSortant').show();
        }
        else
        {
            var str = 0;

            $(':checkbox').each(function() {
                str += this.checked ? 1 : 0;
            });
            if (str == 0) {
                $('.btnAddSortant').hide();
            }
            else
                $('.btnAddSortant').show();
            
        }
    }

    //permet de renomer les tabs 
    function renameTab(container, name){
        alert(container+ ' rt ' + name);
    }

    //ajouter un nouveau filiere 
    function addNewFilier(){
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
                $('#addNewModal .form-loading').hide();
                openFiliereModal(data);
                $('#datatableshow').DataTable().ajax.url(racine + "filieres/getFilieresDT/"+ data).load();     
                var form = document.getElementById("formFilier");
                form.reset();
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
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }
    // Ouvrire le modal d'une filiere
    function openFiliereModal(id,element=0,showall=0) {
        // alert(id+ 'm'  + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'filieres/getFiliereModal/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                if (element == 0) {
                    $('#datatableshow').DataTable().ajax.url(racine + "filieres/getFilieresDT/"+id).load();
                }
                getTheContent('filieres/getFiliereTab/' + id + '/1', '#tab1');
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
    

    // Ouvrire le modal new specialite in filiere
    function openSpecialiteModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'filieres/specialites/newSpecialite/'+id,
            success: function (data) {
                $("#specialite_secteur").html(data);
                $("#edit_specialite_secteur").hide();
                $("#specialite_secteur").show();
                
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //ajouter une nouvelle specialite dans une filiere
    function addSpecialite(){
        $( '#specialite_secteur #form-errors' ).hide();
        var element = $(this);
        $("#specialite_secteur .form-loading").show();
        var data = $('#specialite_secteur form').serialize();
        $.ajax({
            type: $('#specialite_secteur form').attr("method"),
            url: $('#specialite_secteur form').attr("action"),
            data: data,
            success: function(data){
                $('#specialite_secteur .form-loading').hide();
                $('#specialite_secteur .answers-well-saved').show();
                setTimeout(function () {
                    $('#specialite_secteur .answers-well-saved').hide();
                }, 3500);
                $('.datatableshow').DataTable().ajax.url(racine + "filieres/specialites/getSpecialitesDT/"+ data).load();     
                $('#specialite_secteur').hide();
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
                   $( '#specialite_secteur #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#specialite_secteur .form-loading").hide();
            }
        });
    }

    // edit specialite filier 
    function openSpecialite(id) {
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'filieres/specialites/edit/' + id,
            success: function (data) {
                $("#edit_specialite_secteur").html(data);
                $("#specialite_secteur").hide();
                $("#edit_specialite_secteur").show();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //annuller sortant
    function AnnulerSortant(id_sortant,id_pv)
    {
        // alert(id_sortant + "ff" + id_pv);
        var annuller =confirm("Êtes-vous sûr de vouloir annuler ce sortant ?");
        if (annuller) {
            $.ajax({
                type: 'get',
                url: racine + 'pvs/sortants/AnnulerSortant/' + id_sortant + '/' + id_pv,
                success: function (data) {
                    $('.datatableshow6').DataTable().ajax.url(racine + 'pvs/sortants/getStagiairesDT/' + data + '/' + 'all/all/all/none').load();
                    $('.selectpicker').selectpicker('refresh');      
                    
                },
                error: function () {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
            });
        }
    }
    

    // Ouvrire le modal d'evaluation
    function openEvaluation(id_sortant,id_enquete) {
        // alert(id_sortant+ 'm'  + id_enquete);
        $.ajax({
            type: 'get',
            url: racine + 'evaluations/getQuestionnaire/' + id_enquete + '/' + id_sortant,
            success: function (data) {
                $("#evaluation-modal .modal-header-body").html(data);
                $("#evaluation-modal").modal();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //add reponse  question 
    function saveReponse(id_qst,children_id)
    {
        var idgroup = $('#'+id_qst).attr('idelt');
        if(idgroup && ($('#'+id_qst).val() == ""))
            reponse = "none";        
        else
            reponse = $('#'+id_qst).val();
        if(reponse == "")    
            reponse = "none";
        enquete_stag_id = $('#enquetStagID').val();
        // alert(enquete_stag_id +"g" + id_qst + "gffg" + children_id +"g" +reponse);
        // if (reponse != "") {
            $("#divReponses .form-loading"+ id_qst).show();
            $.ajax({
                type: 'get',
                url: racine + 'evaluations/saveReponse/' + enquete_stag_id + '/' + children_id  + '/' + reponse,
                success: function (data) {
                    // alert(data.questions_id  + "gggf" + data.id_child );
                    $( '#divReponses #form-errors'+ id_qst ).hide()
                    $('#divReponses .form-loading'+ id_qst).hide();
                    $('#divReponses .answers-well-saved'+ id_qst).show();
                    setTimeout(function () {
                        $('#divReponses .answers-well-saved'+ id_qst).hide();
                    },3500);
                    if (data.enregistre) {
                        $('.btnSave'+id_qst).css("color","#2ece1b;");
                        $('.td_'+id_qst).css("background-color","#b8fbb8");
                        $('.btnSave'+data.id_ques).show();
                        if (data.declacheurs_id) {
                            var lists = data.declacheurs_id;
                            for (var i = 0; i < lists.length; i++) {
                                if(data.renvoie)
                                    $('.tr_'+lists[i]).hide();
                                else
                                    $('.tr_'+lists[i]).show();
                            }
                        };
                        if (data.questions_id) {
                            var lists = data.questions_id;
                            for (var i = 0; i < lists.length; i++) {
                                $('.ff'+lists[i]).attr('disabled','disabled');
                                $('.td_'+lists[i]).css("background-color","#fff;");;
                            }
                        };
                        if (data.fin_enquete == 0) {
                            $('#group'+data.parent).show();
                            $('.ff'+data.id_ques).removeAttr('disabled');
                        };
                        // alert(data.saute);

                        if (data.saute == 1) {
                            $('.td_'+id_qst).css("background-color","#f3f3ff;");
                        };
                        // alert(data.id_ques);
                        // $('#main-modal').animate({ scrollTop: $("#"+data.id_ques).offset().top }, 500);
                        /*$([document.documentElement, document.body]).animate({
                            scrollTop: $("#"+id_qst).offset().top
                        }, 2000);*/
                        // $(':"#"+id_qst:visible').first().focus();

                    };
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
                       $( '#divReponses #form-errors'+ id_qst ).show().html( errorsHtml );
                    } else {
                        alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                    $("#divReponses .form-loading"+ id_qst).hide();
                }
            });
        // }
    }

    
    //modifier specialite filier
    function editSpecialite()
    {
        $( '#edit_specialite_secteur #form-errors' ).hide();
        var element = $(this);
        $("#edit_specialite_secteur .form-loading").show();
        var data = $('#edit_specialite_secteur form').serialize();
        $.ajax({
            type: $('#edit_specialite_secteur form').attr("method"),
            url: $('#edit_specialite_secteur form').attr("action"),
            data: data,
            success: function(data){ 
                $('#edit_specialite_secteur .form-loading').hide();
                $('#edit_specialite_secteur .answers-well-saved').show();
                setTimeout(function () {
                    $('#edit_specialite_secteur .answers-well-saved').hide();
                }, 3500);
                $('.datatableshow').DataTable().ajax.url(racine + "filieres/specialites/getSpecialitesDT/"+ data).load();     
                $('#edit_specialite_secteur').hide();
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
                    $( '#edit_specialite_secteur #form-errors' ).show().html( errorsHtml );
                } 
                else 
                {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#edit_specialite_secteur .form-loading").hide();
            }
        }); 
    }

    //filter des sortants    
    function filterPV()
    {
        // alert("ggggg");
        annee = $(".yearpicker").val();
        $('#datatableshow').DataTable().ajax.url(racine + 'pvs/getPvsDT/all/' + annee + '/all/none').load();
    }

    // Ouvrire le modal du sortant libre 
    function openSortLibreModal(pv_id) {
        // alert(pv_id);
        $.ajax({
            type: 'get',
            url: racine + 'pvs/sortants/getSortLibre/' + pv_id,
            success: function (data) {
                $("#second-modal .modal-header-body").html(data);
                $("#second-modal").modal();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //ajouter un nouveau sortant libre 
    function addSortantLibre(){
        $( '#second-modal #form-errors' ).hide();
        // var element = $(this);
        $("#second-modal .form-loading").show();
        // var data = $('#second-modal form').serialize();
        $.ajax({
            type: $('#second-modal form').attr("method"),
            url: $('#second-modal form').attr("action"),
            data: new FormData($('#second-modal form')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){ 
                $('#second-modal').modal('toggle');
                $('#second-modal .form-loading').hide();
                // openStagiaireModal(data);
                $('.datatableshow3').DataTable().ajax.url(racine + "pvs/sortants/getStagiairesDT/" + data + "/all/all/all/none").load();     
                /*var form = document.getElementById("formSTAG");
                form.reset();*/
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
                   $( '#second-modal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#second-modal .form-loading").hide();
            }
        });
    }

    function changeSelect(id_qst)
    {
        inputId = $('#'+id_qst).val();
        // alert(inputId + "fggfg"+ id_qst);
        if (inputId == ""){
            // $('.btnSave'+id_qst).attr('disabled','disabled');
            // $('.btnSave'+id_qst).hide();
        }
        else {
            // $( '.btnSave'+id_qst ).show();
            // $('.btnSave'+id_qst).removeAttr('disabled');
            
        }   
    }

    // Ouvrire le modal ajout document
    function openDocumentModal(id_objet,type_obj) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'documents/get_document/'+id_objet +"/" + type_obj,
            success: function (data) {
                $("#document_div").html(data);
                $("#document_div").show();
                init();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //ajouter un document 
    

    function addDocument(){
        $( '#document_div #form-errors' ).hide();
        // var element = $(this);
        $("#document_div .form-loading").show();
        // var data = $('#document_div form').serialize();
        $.ajax({
            type: $('#document_div form').attr("method"),
            url: $('#document_div form').attr("action"),
            data: new FormData($('#document_div form')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){ 
                $('#document_div .form-loading').hide();
                $('#document_div .answers-well-saved').show();
                setTimeout(function () {
                    $('#document_div .answers-well-saved').hide();
                }, 3500); 
                var form = document.getElementById("addpiece");
                form.reset();
                $('.datatableshow4').DataTable().ajax.reload();
                $("#document_div").hide();
                
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
                   $( '#document_div #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#document_div .form-loading").hide();
            }
        });
    }
    function deleteDocument (element) { 
        // alert(element);
        var confirme = confirm("Êtes-vous sûr de vouloir supprimer ce document ? ");
        if(confirme){
            $.ajax({
                type: 'get',
                url: racine+'documents/deleteDocument/'+element,
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

    // Ouvrir le modal edit suivi
    function editSuiviModal(id_suivi,element) {
        
        // alert(id_suivi + "ggf" + element);
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/suivi/editSuivi/'+id_suivi,
            success: function (data) {
                if (element == 1) {
                    $("#add_new_suivi").html(data);
                    $("#add_new_suivi").show();
                    $('#stagiaire-modal').animate({ scrollTop: $("#add_new_suivi").offset().top }, 500);
                    
                }
                else if(element == 2){
                    $("#edit_suivi_form_cloture").html(data);
                    $("#edit_suivi_form_cloture").show();
                    $('#stagiaire-modal').animate({ scrollTop: $("#edit_suivi_form_cloture").offset().top }, 500);

                }
                // $( '#element').val(element);
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    //modifier suivi
    function editSuivi()
    {
        $( '#add_new_suivi #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#add_new_suivi .form-loading").show();
        var data = $('#add_new_suivi form').serialize();
        $.ajax({
            type: $('#add_new_suivi form').attr("method"),
            url: $('#add_new_suivi form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){
                // alert(data);
                // $('#add_new_suivi').modal('toggle');
                $('#add_new_suivi .form-loading').hide();
                $('#add_new_suivi .answers-well-saved').show();
                setTimeout(function () {
                    $('#add_new_suivi .answers-well-saved').hide();
                }, 3500);
                
                $('.datatableshow2').DataTable().ajax.url(racine + 'stagiaires/suivi/getSuiviDT/' + data).load();
                
                /*$("#main-modal").modal('toggle');
                $("#main-modal").modal();*/
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
                    $( '#add_new_suivi #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#add_new_suivi .form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }
    //modifier suivi dans formation cloture 
    function editSuiviFromCloture()
    {
        $( '#edit_suivi_form_cloture #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#edit_suivi_form_cloture .form-loading").show();
        var data = $('#edit_suivi_form_cloture form').serialize();
        $.ajax({
            type: $('#edit_suivi_form_cloture form').attr("method"),
            url: $('#edit_suivi_form_cloture form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){
                // alert(data);
                // $('#edit_suivi_form_cloture').modal('toggle');
                $('#edit_suivi_form_cloture .form-loading').hide();
                $('#edit_suivi_form_cloture .answers-well-saved').show();
                setTimeout(function () {
                    $('#edit_suivi_form_cloture .answers-well-saved').hide();
                }, 3500);
                
                $('.datatableshow3').DataTable().ajax.url(racine + 'stagiaires/suivi/getSuiviDT/' + data).load();
                
                /*$("#main-modal").modal('toggle');
                $("#main-modal").modal();*/
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
                    $( '#edit_suivi_form_cloture #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#edit_suivi_form_cloture .form-loading").hide();
                // $(element).removeAttr('disabled');
            }

        });
    }
    //ajouter une nouvelle equipe 
    function addNewEquipe(){
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
              openEquipeModal(data);
              $('#datatableshow').DataTable().ajax.url(racine + "equipes/getEquipesDT/"+ data).load();     
              var form = document.getElementById("formEtab");
              form.reset();
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
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }

    // Ouvrire le modal d'une etab
    function openEquipeModal(id,showall=0) {
        // alert(id+ 'm' + module+';;; ' + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'equipes/getEquipe/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                $('#datatableshow').DataTable().ajax.url(racine + "equipes/getEquipesDT/"+id).load();
                getTheContent('equipes/getEquipeTab/' + id + '/1', '#tab1');
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

    
    //ajouter un nouveau profil 
    function addNewProfil(){
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
                $('#addNewModal .form-loading').hide();
                openProfilModal(data);
                $('#datatableshow').DataTable().ajax.url(racine + "profilsAcces/getProfilsAccesDT/"+ data).load();     
                var form = document.getElementById("formProfil");
                form.reset();
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
                   $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#addNewModal .form-loading").hide();
            }
        });
    }

    //modifier un profil
    function editProfil()
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
                $('#datatableshow').DataTable().ajax.url(racine + "profilsAcces/getProfilsAccesDT/"+ data).load();     

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
    // Ouvrire le modal d'une etab
    function openProfilModal(id,showall=0) {
        // alert(id+ 'm'  + element +'hg' + showall);
        $.ajax({
            type: 'get',
            url: racine + 'profilsAcces/getProfil/' + id + '/' + showall,
            success: function (data) {
                $("#main-modal .modal-header-body").html(data);
                $("#main-modal").modal();
                $('#datatableshow').DataTable().ajax.url(racine + "profilsAcces/getProfilsAccesDT/"+id).load();
                getTheContent('profilsAcces/getProfilTab/' + id + '/1', '#tab1');
                $('.formation-main-modal').on('hidden.bs.modal', function () {
                    $('#datatableshow').DataTable().ajax.reload();
                });
                //get content on tab click
                $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                    var link = $(e.target).attr("link");
                    var container = $(e.target).attr("href");
                    $(container).empty();
                    getTheContent(link, container);

                });
                // setDataTable('#datatableshow2');
                // setDataTable('#datatableshow1');
                resetInit();     
                
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

    // Ouvrire le modal du mofification d'image 
    function openImageModal(id) {
        
        // alert(id);
        $.ajax({
            type: 'get',
            url: racine + 'stagiaires/image/openModalImage/'+id,
            success: function (data) {
                $("#image-modal .modal-header-body").html(data);
                $("#image-modal").modal();
                init();
                resetInit()
                $("#fichier").fileinput();

            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    //modifier l'image du staagiaire 
    function editImageStagiare(){
        $( '#image-modal #form-errors' ).hide();
        // var element = $(this);
        $("#image-modal .form-loading").show();
        // var data = $('#image-modal form').serialize();
        $.ajax({
            type: $('#image-modal form').attr("method"),
            url: $('#image-modal form').attr("action"),
            data: new FormData($('#image-modal form')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){ 
                $('#image-modal').modal('toggle');
                $('#image-modal .form-loading').hide();
                $('#image-modal .answers-well-saved').show();
                /*setTimeout(function () {
                    $('#image-modal .answers-well-saved').hide();
                }, 3500);*/
                // $('#stagiaire-modal').modal('toggle');
                // getTheContent('stagiaires/getStagiaireTab/' + data + '/1', '#stagiaire-tab2');
                // var form = document.getElementById("formImage");
                // form.reset();
                openStagiaireModal(data);
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
                   $( '#image-modal #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $("#image-modal .form-loading").hide();
            }
        });
    }

    function ExportExcel(){
        $.ajax({
            type: 'get',
            url: racine + 'ExportExcel',
            success: function (data) {
                window.open(racine + 'ExportExcel');
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }


    //ajouter les champs d'evaluation
    function addChampsEnquete(){
        $( '.divenquete #form-errors' ).hide();
        var element = $(this);
        $(".divenquete .form-loading").show();
        var data = $('.divenquete form').serialize();
        $.ajax({
            type: $('.divenquete form').attr("method"),
            url: $('.divenquete form').attr("action"),
            data: data,
            // dataType: 'json',
            success: function(data){  
              // $('.divenquete').modal('toggle');
              $('.divenquete .form-loading').hide();
              $('.divenquete .answers-well-saved').show();
                setTimeout(function () {
                    $('.divenquete .answers-well-saved').hide();
                }, 3500);
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
                   $( '.divenquete #form-errors' ).show().html( errorsHtml );
                } else {
                    alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $(".divenquete .form-loading").hide();
            }
        });
    }

    
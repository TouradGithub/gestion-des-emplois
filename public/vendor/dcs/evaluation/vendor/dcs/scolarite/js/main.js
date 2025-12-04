// var racine = '/';
$(document).ready(function () {

    // $('#side-menu-formation').metisMenu();
    // Chart pie/barre
    $("#type_chart").change(function(){
        if($(this).val() == "3")
            $(".default_chart").show();
        else
            $(".default_chart").hide();
        if($(this).val() == "2" || $(this).val() == "3")
            $(".autre_pie").show();
        else
            $(".autre_pie").hide();
            $(".autre_pie input").val('');

    });

    // Si 'Notation' du groupe de question afficher 'Formule de calcul' | Formulaire
    $("#notation").change(function(){
        if($(this).val() == "1") $(".formule_calcul").show();
        else $(".formule_calcul").hide();
    });
    $("#agregation_moughataa").change(function(){
        if($(this).val() == "1"){
            // $(".formule_calcul option:selected").removeAttr("selected");
            $(".formule_calcul").show();
            $(".niveaux").show();
        }
        else{
            $(".niveaux").hide();
            $(".formule_calcul").hide();
            $(".base_calcul").hide();
        }
    });
    $("#type_info").change(function () {
        if ($(this).val() == 1) {
            $(".formule_calcul").hide();
        }
        else if($("#niveau_geo").val()>1)
            $(".formule_calcul").show();
    });
    $("#niveau_geo").change(function(){
        if ($(this).val() ==2 || $(this).val() ==3) {
            $(".formule_calcul").show();
            if ($("#type_info").length && $("#type_info").val()==1) {
                $(".formule_calcul").hide();
                $(".nom_champ_commune").hide();
                $(".nom_champ_moughataa_wilaya").show();
                $(".nom_champ_objet").hide();
                $(".info-groups").hide();
                $(".info-questions").hide();
            }
            if($(this).val()==4)
                $("#champ_table").hide();
            else
                $("#champ_table").show();
        }
        else if($(this).val() == 6){
            $(".formule_calcul").hide();
            if ($("#type_info").length && $("#type_info").val()==1) {
                $(".nom_champ_objet").show();
                $(".nom_champ_commune").hide();
                $(".nom_champ_moughataa_wilaya").hide();
                $(".info-groups").hide();
                $(".info-questions").hide();
            }
        }
        else{
            $(".formule_calcul").hide();
            if ($("#type_info").length && $("#type_info").val()==1) {
                $(".nom_champ_commune").show();
                $(".nom_champ_moughataa_wilaya").hide();
                $(".nom_champ_objet").hide();
                $(".info-groups").hide();
                $(".info-questions").hide();
            }
        }
        $("#ordre").val($('#counter' + $(this).val()).attr('nbr'));
    });
    $("#filtertype").change(function(){
        if( $(this).val() == "0" ){
            $(".filter-groups").hide();
            $(".filter-questions").show();
        }
        else{
            $(".filter-questions").hide();
            $(".filter-groups").show();
        }
        $("#form-errors").hide();
    });
    $("#type_info").change(function(){
        if( $(this).val() == "2" ){
            $(".info-groups").hide();
            $(".info-questions").show();
            $(".nom_champ_commune").hide();
            $(".nom_champ_moughataa_wilaya").hide();
            $(".nom_champ_objet").hide();
        }
        else if( $(this).val() == "3" ){
            $(".info-questions").hide();
            $(".info-groups").show();
            $(".nom_champ_commune").hide();
            $(".nom_champ_moughataa_wilaya").hide();
            $(".nom_champ_objet").hide();
        }
        else{
            if ($('#niveau_geo').val()==1) {
                $(".nom_champ_commune").show();
                $(".nom_champ_moughataa_wilaya").hide();
                $(".nom_champ_objet").hide();
            }
            else if ($('#niveau_geo').val()==6) {
                $(".nom_champ_commune").hide();
                $(".nom_champ_objet").show();
                $(".nom_champ_moughataa_wilaya").hide();
            }
            else{
                $(".nom_champ_commune").hide();
                $(".nom_champ_moughataa_wilaya").show();
                $(".nom_champ_objet").hide();
            }
            $(".info-questions").hide();
            $(".info-groups").hide();
        }
        $("#form-errors").hide();
    });
    $("#formule_calcul_stat").change(function(){
        if($(this).val() == "2" || $(this).val() == "6"){
            $(".base_calcul").html($("#content26").html());
            $(".baseqst_calcul").hide();
            $(".base_calcul").show();
        }
        else if( $(this).val() == "5" || $(this).val() == "8" ){
            $(".base_calcul").html($("#content5").html());
            $(".baseqst_calcul").hide();
            $(".base_calcul").show();
        }
        else if( $(this).val() == "7" ){
            $(".base_calcul").hide();
            $(".baseqst_calcul").show();
        }
        else{
            $(".base_calcul").hide();
            $(".baseqst_calcul").hide();
        }
    });

    // Afficher moughataa quand on selectionne une wilaya
    $("#select-wilaya select").change(function(){
        if ($(this).val()!=0) {
            var link = racine+'territoires/getMoughataasOfWilaya/'+$(this).val();
            var link = racine+'territoires/getMoughataasOfWilaya/'+$(this).val();
            $.ajax({
                type: 'GET',
                url: link,
                success: function (data) {
                    $("#select-moughataa select").html(data);
                    // $("#select-moughataa select").selectpicker({
                    //     liveSearch: true,
                    //     title: 'selectionnez une moughataa'
                    // });
                    $("#select-moughataa").show();
                    $("#select-moughataa select").focus();
                    $("#select-commune").hide();
                    $(".addfiche").attr('disabled','disabled');
                },
                error: function () { console.log('La requête n\'a pas abouti'); }
            });
        }
        else {
            $(".addfiche").attr('disabled','disabled');
            $("#select-moughataa").hide();
            $("#select-commune").hide();
        }
    });

    // Afficher communes quand on selectionne une moughataa
    $("#select-moughataa select").change(function(){
        if ($(this).val()!=0) {
            var link = racine+'territoires/getCommunesOfMoughataa/'+$(this).val();
            $.ajax({
                type: 'GET',
                url: link,
                success: function (data) {
                    $("#select-commune select").html(data);
                    // $("#select-commune select").selectpicker({
                    //     liveSearch: true,
                    //     title: 'selectionnez une commune'
                    // });
                    $("#select-commune").show();
                    $("#select-commune select").focus();
                    $(".addfiche").attr('disabled','disabled');
                },
                error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
            });
        }
        else {
            $(".addfiche").attr('disabled','disabled');
            $("#select-commune").hide();
        }
    });

    // Remove 'disbled' attribute from submit btn when selecting 'commune'
    $("#select-commune select").change(function(){
        if ($(this).val()!=0)
            $(".addfiche").removeAttr('disabled');
        else
            $(".addfiche").attr('disabled','disabled');
    });

    // Editer un mode de calcul
    $('#editModeModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var nb_sous_criteres = button.data('nbscriteres');
      var nb_reponses_pos = button.data('nbrepos');
      var note = button.data('note');
      var idmode = button.data('idmode');
      var btntxt = button.data('btntxt');
      var titletxt = button.data('titletxt');
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      // modal.find('.modal-title').text('Modification');
      modal.find('.modal-body #nb_sous_criteres').val(nb_sous_criteres);
      modal.find('.modal-body #nb_reponses_pos').val(nb_reponses_pos);
      modal.find('.modal-body #note').val(note);
      modal.find('.modal-body #idmode').val(idmode);
      modal.find('.modal-body .edit-mode').text(btntxt);
      modal.find('.modal-title').text(titletxt);
    })
    // Editer une categorie de filtre
    $('#editCategModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var categid = button.data('categid');
      var btntxt = button.data('btntxt');
      var titletxt = button.data('titletxt');
      var libelle = button.data('libelle');
      var libellear = button.data('libellear');
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      // modal.find('.modal-title').text('Modification');
      modal.find('.modal-body .edit-categfilter').text(btntxt);
      modal.find('.modal-body #categid').val(categid);
      modal.find('.modal-body #libelle').val(libelle);
      modal.find('.modal-body #libelle_ar').val(libellear);
      modal.find('.modal-title').text(titletxt);
    })

    // Ajouter un intervale au filtre
    $('#addIntervaleModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var filterid = button.data('filterid');
      var categid = button.data('categid');
      var header = button.data('header');
      var lastorder = button.data('lastorder');
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this);
      modal.find('.modal-body #libelle').val('');
      modal.find('.modal-body #libelle_ar').val('');
      modal.find('.modal-body #valeur_max').val('');
      modal.find('.modal-body #valeur_min').val('');
      modal.find('.modal-body #ordre').val('');
      modal.find('.modal-body #color').val('');
      modal.find('.modal-title').text(header);
      modal.find('.modal-body #filterid').val(filterid);
      modal.find('.modal-body #categid').val(categid);
      modal.find('.modal-body #ordre').val(lastorder);
    })
    // Ajouter un intervale au filtre
    $('#addIntervaleModal').on('hidden.bs.modal', function (e) {
        $(this).removeData();
    })
    // add spiner after menu link clicking
    $("#side-menu a").on('click', function () {
        if ($(this).attr('href') != "#!")
            $(this).append('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
    });
    // Add new fiche by ajax
    $(".addfiche").on('click', function(){
        $( '#form-errors' ).hide();
        $(".addfiche").attr('disabled','disabled');
        $(".form-loading").show();
        var data = $('#addNewFicheModal form').serialize();
        $.ajax({
            type: 'post',
            url: racine+'fichescommunales/add',
            data: data,
            dataType: 'json',
            success: function(data){
                window.location.href = data;
            },
            error: function(data){
                if( data.status === 422 ) {
                    var errors = data.responseJSON;
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                }
                $(".form-loading").hide();
                $(".addfiche").removeAttr('disabled');
            }
       });
    });

    // Show loading on calcul
    $(".btn-calcul").on('click', function () {
        $(this).children(".fa-save").hide();
        $(this).children(".fa-refresh").show();
    });

    // Add new form by ajax
    $(".addnew").on('click', function(){
        $( '#addNewModal #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#addNewModal .form-loading").show();
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
                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                    errorsHtml += '</ul></div>';
                    $( '#addNewModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#addNewModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
       });
    });
    // edit-mode
    $(".edit-mode").on('click', function(){
        $( '#editModeModal #form-errors' ).hide();
        // var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#editModeModal .form-loading").show();
        var data = $('#editModeModal form').serialize();
        $.ajax({
            type: $('#editModeModal form').attr("method"),
            url: $('#editModeModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                window.location.href = data;
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
                    $( '#editModeModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#editModeModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
       });
    });
    // edit-cetegorie-filtre
    $(".edit-categfilter").on('click', function(){
        $( '#editCategModal #form-errors' ).hide();
        // var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#editCategModal .form-loading").show();
        var data = $('#editCategModal form').serialize();
        $.ajax({
            type: $('#editCategModal form').attr("method"),
            url: $('#editCategModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                window.location.href = data;
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
                    $( '#editCategModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#editCategModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
       });
    });
    // add-intervale
    $(".add-intervale").on('click', function(){
        $( '#addIntervaleModal #form-errors' ).hide();
        // var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#addIntervaleModal .form-loading").show();
        var data = $('#addIntervaleModal form').serialize();
        $.ajax({
            type: $('#addIntervaleModal form').attr("method"),
            url: $('#addIntervaleModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                var id = data.id;
                var filterid = $('#addIntervaleModal form #filterid').val();
                var categid = $('#addIntervaleModal form #categid').val();
                var link = "filtres/getFiltreTab/" + filterid + "/2";
                var container = "#tab2";
                $(container).empty();
                getTheContent(link, container);
                // $('.desactivate-filter'+filterid).hide();
                // $('.activate-filter'+filterid).show();
                // var html = '<tr class="dlt-element" idgroup="3">';
                //     html +='<td>'+$('#addIntervaleModal form #libelle').val()+'</td>';
                //     html +='<td>'+$('#addIntervaleModal form #valeur_min').val()+'</td>';
                //     html +='<td>'+$('#addIntervaleModal form #valeur_max').val()+'</td>';
                //     html +='<td><i style="color:'+$('#addIntervaleModal form #color').val()+'" class="fa fa-square"></i> '+$('#addIntervaleModal form #color').val()+'</td>';
                //     html +='<td>';
                //     html +='    <div class="btn-group">';
                //     html +='        <a href="'+racine+'legends/edit/'+id+'" class="btn btn-default"><i class="fa fa-edit"></i></a>';
                //     html +='        <a href="'+racine+'legends/delete/'+id+'" class="btn btn-default delete" idgroup="3"><i class="fa fa-trash-o"></i></a>';
                //     html +='    </div>';
                //     html +='</td>';
                //     html +='</tr>';
                // $('#collapse'+categid+'-'+filterid+' table tbody').append(html);
                $('.msg-from-ajax .msg-content').html("L'intervale a été bien ajouté");
                $( '.msg-from-ajax' ).show();
                $("#addIntervaleModal .form-loading").hide();
                $('#addIntervaleModal').modal('toggle');
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
                    $( '#addIntervaleModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#addIntervaleModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
       });
    });

    
    // Add new statistic by ajax
    $(".addnewstat").on('click', function(){
        $( '#addNewStatModal #form-errors' ).hide();
        var element = $(this);
        // $(element).attr('disabled','disabled');
        $("#addNewStatModal .form-loading").show();
        var data = $('#addNewStatModal form').serialize();
        $.ajax({
            type: $('#addNewStatModal form').attr("method"),
            url: $('#addNewStatModal form').attr("action"),
            data: data,
            dataType: 'json',
            success: function(data){
                $("#addNewStatModal .form-loading").hide();
                $('#addNewStatModal').modal('toggle');
                $('#addNewStatModal form').trigger("reset");;
                openGroupStatModal(data);
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
                    $( '#addNewStatModal #form-errors' ).show().html( errorsHtml );
                } else {
                    $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#addNewStatModal .form-loading").hide();
            // $(element).removeAttr('disabled');
        }
       });
    });

    // Si 'Nature' de la question est 'Selection' afficher 'Valeurs possibles' | Formulaire
    $("#nature_question").change(function(){
        if($(this).val() == "5"){
            $(".valeurs").show();
            $(".is_multiple").show();
            $(".minmax").hide();
            $(".if_numeric").hide();
        }
        else if($(this).val() == "2"){
            $(".if_numeric").show();
            $(".minmax").show();
            $(".valeurs").hide();
            $(".is_multiple").hide();
        }
        else{
            $(".valeurs").hide();
            $(".is_multiple").hide();
            $("#valeurs").val("");
            $(".if_numeric").hide();
            $(".if_numeric input").val("");
            $(".minmax input").val("");
            $(".minmax").val("");
            $(".minmax").hide();
        }
    });

    // init of Jquery-Sortable
    $( ".group-questions" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            updateGroupeQuestions();
        }
    });
    $( ".equipe-mission" ).sortable({
        axis: 'y',
        update: function (event, ui) {
            updateEquipeMission();
        }
    });
    $( ".equipe-mission" ).disableSelection();


    // Confirmation Alert lors de suppression du groupe
    $('.delete').confirm({
        title: 'Confirmation',
        content: 'Êtes-vous sûr de vouloir supprimer cet élément?',
        buttons: {
            ok: {
                text: 'Oui',
                btnClass: 'btn-default',
                action: function(){
                    var element = this.$target;
                    var idgroup = this.$target.attr('idgroup');
                    $.ajax({
                        type: 'GET',
                        url: this.$target.attr('href'),
                        success: function (data) {
                            if (data.success=="true") {
                                element.closest('.dlt-element').remove();
                                $('.nodelink[idgroup="'+idgroup+'"]').remove();
                                hideBtn(".btn-groups");
                                $.alert(data.msg,'Elément supprimé');
                            }
                            else $.alert(data.msg,'Erreur');
                        },
                        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
                    });
                }
            },
            cancel: { text: 'Non', btnClass: 'btn-default' }
        }
    });

    // init du select picker
    $('#valeurs').tagsInput({
       'height':'80px',
       'width':'100%',
       'interactive':true,
       'defaultText':'Nouvelle valeur',
       'placeholderColor' : '#666666'
    });

    // init du datatable
    //$('.datatable').DataTable({
      //  responsive: true,
      //  "oLanguage": {"sUrl": racine+"vendor/datatables/js/datatable-fr.json"},
      //  'bSort':false,
      //  "iDisplayLength": 10
    //});

    // ajout de question au groupe
    $('.addtogroup').on('click', function(){
        addToGroup($(this));
    });

    // Visualiser la fiche d'un territoire
    $('.terr_selector').on('click', function(){
        var idterr = $(this).attr('idterr');
        var terr = $(this).attr('terr');
        if (terr == "Commune") {
            var niv = 1;
        }
        else if (terr == "Moughataa") {
            var niv = 2;
        }
        if (terr == "Wilaya") {
            var niv = 3;
        }
        // $('#idterr').val(idterr);
        // $('#terr').val(terr);
        // $('#carteModalLuncher').show();
        var link = racine+'territoires/getFiche'+terr+'/'+idterr;
        // if(terr=="Commune"){
            $('.answerfreeqst').show();
            // $('.answerfreeqst').attr('href',racine+'questionslibres/freeanswers/'+idterr);
            $('.answerfreeqst').val(idterr);
            $('#nivea').val(niv);
            $('#nivea').show();
            // $('.btn-fiches-com').show();
            // $('.btn-fiches-com').attr('href',racine+'fichescommunales/commune/'+idterr);
        /*}
        else $('.btn-fiches-com').hide();*/
        $.ajax({
            type: 'get',
            url: link,
            success: function (data) {
                $(".fiche-terr").html(data);
            },
            error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
        });
    });
    // affichage treeview
    $("#navigation").treeview();
    $('.nodelink').on('click', function(){
        var id = $(this).attr('idgroup');
        $(".treeview a").css("color","#337ab7");
        $(".treeview a").css("font-weight","normal");
        $(this).css("color","#009688");
        $(this).css("font-weight","bold");
        var link;
        hideBtn(".btn-groups");
        $(".btn-groups").each(function( index ) {
          link = $(this).attr('defaultlink')+id;
          $(this).attr('href',link);
          $(this).attr('idgroup',id);
        });
        if($(this).attr('nbqst')<1) {
            showBtn(".group-add-sg");
            showBtn(".group-qst-list");
            $('.group-add-sg').val(id);
                showBtn(".group-qst-list");
            if(!$(this).siblings("ul").length){
                showBtn(".group-delete");
                showBtn(".group-qst-list");
                $('.group-qst-list').val(id);
            }
        }
        else
            showBtn(".group-qst-list");
            showBtn(".group-edit");
            $('.group-edit').val(id);
            $('.group-qst-list').val(id);
        return false;
    });

    // Data tables to load General
    // setDataTable('.datatableshow');
    setDataTable('#datatableshow');

    var tabluncher2 = 0, tabluncher3 = 0, tabluncher4 = 0;
    // Datatable de Questions SC
    setDataTable('#datatableshow1');
    // Datatable pan
    $(".panlunch").on('click', function(){
        var typepan = $(this).attr('typepan');
        setDataTable('#datatableshow-'+typepan);
    });

    // Datatable de Questions SG
    $(".tabluncher2").on('click', function(){
        if (tabluncher2==0) {
            setDataTable('#datatableshow2');
            tabluncher2++;
        }
    });
    // Datatable de Questions Maire
    $(".tabluncher3").on('click', function(){
        if (tabluncher3==0) {
            setDataTable('#datatableshow3');
            tabluncher3++;
        }
    });
    // Datatable de Données statiques
    $(".tabluncher4").on('click', function(){
        if (tabluncher4==0) {
            setDataTable('#datatableshow4');
            tabluncher4++;
        }
    });

    // Filter pour afficher les questions (non) répondues seulement
    $(".questions-filter").change(function(){
        var numtab = $(this).attr("numtab");
        var idfiche = $(this).attr("idfiche");
        var etat = $(this).val();
        $('#datatableshow'+numtab).DataTable().ajax.url(racine+"questions/getFicheGroupeQuestionsDT/"+idfiche+"/"+numtab+"/"+etat).load();
    });
    // Filter des évaliations
    $("#fiches-filter").change(function(){
        var etat = $(this).val();
        // alert(racine+"fichescommunales/getFichesDT/0/0/"+etat+"/all/all/all");
        $('#datatableshow').DataTable().ajax.url(racine+"fichescommunales/getFichesDT/0/0/"+etat+"/all/all/all/none").load();
    });

    //filtre des filtres
    $("#filre-cat-filtre,#filre-niveau-filtre").change(function () {
        var categorie = $('#filre-cat-filtre').val();
        var niveau = $('#filre-niveau-filtre').val();
        $('#datatableshow').DataTable().ajax.url(racine + "filtres/getFiltersDT/" + categorie + '/' + niveau + "/none").load();
    });

});

// Effets lors de l'apparition ou de disparition des boutons
function showBtn(element) {
    $(element).css('visibility', 'visible');
    $(element).css('opacity', '1');
    $(element).css('height', 'auto');
    $(element).css('padding', '10px 16px');
    $(element).css('overflow', 'visible');
    $(element).addClass('margin5');
}
function hideBtn(element) {
    $(element).css('visibility', 'hidden');
    $(element).css('opacity', '0');
    $(element).css('height', '0');
    $(element).css('padding', '0');
    $(element).css('overflow', 'hidden');
    $(element).removeClass('margin5');
}

// add a question to a group
function addToGroup(element){
    var typegroupe = $(".group-questions").attr('typegroupe');
    var childscount = $(".group-questions").attr('childscount');
    if (typegroupe=="stat" && childscount>99) {
        $('.groupe-stat-alert').show();
    } else {
        if ($('.groupe-stat-alert').length) $('.groupe-stat-alert').hide();
        updateGroupeQuestions(element);
    }
}

// updating a group questions
function updateGroupeQuestions(element = null) {
    var questions = $(".group-questions").sortable('toArray');
    var childscount = $(".group-questions li").length;
    var idgroup = $(".group-questions").attr('idgroup');
    var typegroupe = $(".group-questions").attr('typegroupe');
    var link = racine+"grouping";
    if (typegroupe=="stat")
        link = racine+"groups/statistiques/grouping";
    else if (typegroupe=="front") {
        link = racine + "groups/frontend/grouping/" + $(".group-questions").attr('typecontenu');
        $('.groupingtv').html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
    }
    if(element){
        if ($(element).hasClass("close")){
            questions = jQuery.grep(questions, function(value) {
              return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        }
        else{
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            questions.push($(element).attr('idqst'));
        }
    }
    $.ajax({
        data: {list:questions,id:idgroup},
        type: 'POST',
        url: link,
        success: function (data) {
            if(element){
                if ($(element).hasClass("close")){
                    $(element).parent().remove();
                    $('#datatableshow').DataTable().ajax.reload();
                }
                else{
                    var idqst = $(element).attr('idqst');
                    var libelle = $(element).attr('libelle');
                    var code = $(element).attr('code');
                    $(element).parents('tr').remove();
                    $(".group-questions").append('<li class="list-group-item" id="'+idqst+'">'+libelle+'<button type="button" idqst="'+idqst+'" class="close deletefromgroupe" aria-hidden="true" onclick="updateGroupeQuestions(this)">&times;</button></li>');
                }
            }
            $(".group-questions").attr('childscount',childscount);
            if ($('.groupe-stat-alert').length) $('.groupe-stat-alert').hide();
            if (typegroupe=="front") {
                // $('#datatableshow').DataTable().ajax.reload();
                $('.groupingtv').html(data);
                $("#navigation").treeview();
            }
        },
        error: function () {
            if(element){
                if($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
        $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
}

// add an evaluateur to mission
function addToMission(element){
    updateEquipeMission(element)
}

// updating a mission's evaluateurs
function updateEquipeMission(element=null) {
    var evaluateurs = $(".equipe-mission").sortable('toArray');
    // console.log("Before: "+evaluateurs);
    var idmission = $(".equipe-mission").attr('idmission');
    if(element){
        if ($(element).hasClass("close")){
            evaluateurs = jQuery.grep(evaluateurs, function(value) {
              return value != $(element).parent().attr('id');
            });
            $(element).html('<i style="font-size:13px" class="fa fa-refresh fa-spin fa-fw"></i>');
        }
        else{
            $(element).children('i').removeClass('fa-arrow-right').addClass('fa-refresh fa-spin');
            evaluateurs.push($(element).attr('ideval'));
        }
    }
    $.ajax({
        data: {list:evaluateurs,id:idmission},
        type: 'POST',
        url: racine+'missions/updateEquipe',
        success: function () {
            if(element){
                if ($(element).hasClass("close"))
                    $(element).parent().remove();
                else{
                    var ideval = $(element).attr('ideval');
                    var libelle = $(element).attr('libelle');
                    $(element).parents('tr').remove();
                    $(".equipe-mission").append('<li class="list-group-item" id="'+ideval+'">'+libelle+'<button type="button" ideval="'+ideval+'" class="close" aria-hidden="true" onclick="updateEquipeMission(this)">&times;</button></li>');
                }
            }
        },
        error: function () {
            if(element){
                if($(element).hasClass("close"))
                    $(element).html('&times;');
                else {
                    $(element).children('i').removeClass('fa-refresh fa-spin').addClass('fa-arrow-right');
                }
            }
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Visualiser une question dans un modal
function openQuestionModalOld(idqst){
    $.ajax({
        type: 'get',
        url: racine+'questions/get/'+idqst,
        success: function (data) {
            $("#question-modal .modal-header").html('<h4>Détail de la question</h4>');
            $("#question-modal .modal-body").html(data);
            $("#question-modal").modal();
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
}
// Visualiser l'historique des réponse d'une question dans un modal
function openHistoriqueReponsesModal(element,id_territoire,niveau,idqst,libelle,code){
    // alert('questionslibres/getHistoriqueReponses/'+id_territoire+'/'+niveau+'/'+idqst);
    $.ajax({
        type: 'get',
        url: racine+'questionslibres/getHistoriqueReponses/'+id_territoire+'/'+niveau+'/'+idqst,
        success: function (data) {
            $("#historique-modal .modal-header").html('<h4>Historique des réponses de '+libelle+' (Code: '+code+')</h4>');
            $("#historique-modal .modal-body").html(data);
            // $(element).closest('tr').css('background-color','#efefef');
            $("#historique-modal").modal();
        },
        error: function () { $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!"); }
    });
}

// Afficher la modal du fiche communalle
function showfichemodal(element){
    var id_fiche = $(element).attr('id_fiche');
    var id_com = $(element).attr('id_com');
    var date_ref = $(element).attr('date_ref');
    var mod = $(element).attr('mod');
    $("#resutClick2").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>chargement en cours</p></div>').fadeIn('fast');
    $.ajax({
        type: 'GET',
        url: racine + 'detaitInfoCommune1/' + id_com + ',' + id_fiche + ',' + date_ref + ',' + mod,
        cache: false,
        success: function (data) {
            $('#basicModal2').modal('show');
            $("#resutClick2").html(data);
        },
        error: function () {
            console.log('La requête n\'a pas abouti');
        }
    });
}
// Ouvrire le modal d'une question
function showEditFicheModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'fichescommunales/showModal/' + id,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            getTheContent('fichescommunales/getFicheTab/' + id + '/1/all', '#tab1');
            $('#main-modal').on('hidden.bs.modal', function () {
                $('.datatableshow').DataTable().ajax.reload();
            });
            //get content on tab click
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'une legend
function openLegendModal(legend_id, categ_id, filter_id) {
    $.ajax({
        type: 'get',
        url: racine + 'filtres/getLegend/' + legend_id,
        success: function (data) {
            $("#filter-modal .modal-header-body").html(data);
            $("#filter-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'une categorie stat
function openCategStatModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'statcategories/getCategStat/' + id,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            getTheContent('statcategories/getCategStatTab/' + id + '/1', '#tab1');
            $('#main-modal').on('hidden.bs.modal', function () {
                if ($('.datatableshow').length) $('.datatableshow').DataTable().ajax.reload();
                if ($('#datatableshow').length) $('#datatableshow').DataTable().ajax.reload();
            });
            //get content on tab click
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Ouvrire le modal d'une structure
function openStructureModal(id) {
    $.ajax({
        type: 'get',
        url: racine + 'structures/getStructure/' + id,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            getTheContent('structures/getStructureTab/' + id + '/1', '#tab1');
            $('#main-modal').on('hidden.bs.modal', function () {
                if ($('.datatableshow').length) $('.datatableshow').DataTable().ajax.reload();
                if ($('#datatableshow').length) $('#datatableshow').DataTable().ajax.reload();
            });
            //get content on tab click
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var link = $(e.target).attr("link");
                var container = $(e.target).attr("href");
                $(container).empty();
                getTheContent(link, container);
            });
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
// Save answers from fiche communale
function savefreeanswers()
{
    // $( '#addNewModal #form-errors' ).hide();
    // var element = $(this);
    // var idgroup = $(this).attr("idgroup");
    // $(element).attr('disabled','disabled');
    $('.loading').show();
    $('.answers-well-saved').hide();
    var data = $('.freeanswersinputs form').serialize();
    $.ajax({
        type: $('.freeanswersinputs form').attr("method"),
        url: $('.freeanswersinputs form').attr("action"),
        data: data,
        dataType: 'json',
        success: function(data){
            // console.log(data);
            $('.freeanswersinputs .loading').hide();
            $('.freeanswersinputs .answers-well-saved').show();
            setTimeout(function(){
                $('.freeanswersinputs .answers-well-saved').hide();
            }, 3500);
        },
        error: function(data){
            if( data.status === 422 ) {
                var errors = data.responseJSON;
                errorsHtml = '<ul>';
                var erreurs = (errors.errors) ? errors.errors : errors; $.each( erreurs, function( key, value ) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul>';
                $('.freeanswersinputs .answers-alert').html(errorsHtml);
            } else
                $('.freeanswersinputs .answers-alert').text('La requête n\'a pas abouti');
            $('.freeanswersinputs .answers-alert').show();
            setTimeout(function(){
                $('.freeanswersinputs .answers-alert').hide();
            }, 10000);
            $('.freeanswersinputs .loading').hide();
        }
    });
}


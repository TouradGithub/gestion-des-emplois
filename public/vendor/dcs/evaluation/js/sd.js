function get_etabs() {

    id_pays = $("#pays_etude").val();
    if (id_pays != 'all') {
        $.ajax({
            type: 'get',
            url: racine + 'DE/etudes/pays_etabs/' + id_pays,
            cache: false,
            success: function (data) {
                $("#etabs_pays").empty();
                $('#etabs_pays').html(data);
                resetInit();
            },
            error: function () {

                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                //$.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });

    } else {
        $("#etabs_pays").html('');
    }
}

function addObject_sd(element, lemodule, datatable = "#datatableshow", modal = "add-modal", tab = 1, largeModal = 'lg') {
    saveform(element, function (id) {
        $(datatable).DataTable().ajax.reload();
        //link=$(datatable).attr('link');
        //alert(link);
        //$(datatable).DataTable().ajax.url(link + "/" + id).load();
        $(element).attr('disabled', 'disabled');
        setTimeout(function () {
            $('#add-modal').modal('toggle');
            openObjectModal_sd(id, lemodule, datatable, modal, tab, largeModal);
        }, 1500);
    });
    //return false;
}

function addCompetence(element, lemodule, datatableshow = "#datatableshow", id_de) {


    link = $(datatableshow).attr('link');
    //alert(link);
    var container = $(element).attr('container');

    $('#' + container + ' #form-errors').hide();
    $(element).attr('disabled', 'disabled');
    $('#' + container + ' .main-icon').hide();
    $('#' + container + ' .spinner-border').show();
    var data = $('#' + container + ' form').serialize();

    $.ajax({
        type: $('#' + container + ' form').attr("method"),
        url: $('#' + container + ' form').attr("action"),
        data: data,
        dataType: 'json',
        success: function (data) {

            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
            var cat = $('#' + container + ' form #cat select').serialize()

            update_competence(id_de, data.cat);
            $(datatableshow).DataTable().ajax.url(link + "/" + data.id).load();
            //
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                errorsHtml = '<ul class="list-group">';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
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
    return false;
}

function update_competence(id_de, cat) {
    $.ajax({
        type: 'get',
        url: racine + 'DE/competences/auther_competences/' + id_de + '/' + cat,
        cache: false,
        success: function (data) {
            $("#competences").empty();
            $('#competences').html(data);
            resetInit();

        },
        error: function () {

            //loading_hide();
            //$meg="Un problème est survenu. veuillez réessayer plus tard";
            //$.alert("Un problème est survenu. veuillez réessayer plus tard");
        }
    });
    return false;
}

//openObjectModal_sd(2,'DE','#datatableshow','de-modam',1,'xl')
function openObjectModal_sd(id, lemodule, datatableshow = "#datatableshow", modal = "main", tab = 1, largeModal = false) {

    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {
            if (largeModal) $("#" + modal + "-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#" + modal + "-modal .modal-header-body").html(data);
            $("#" + modal + '-modal').modal();
            container = "#" + modal + '-modal';
            if (tab != false)
                setMainTabs(tab, container,true);
            link = $(datatableshow).attr('link');
            //alert(link);
            $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
    return false;
}

function getform() {
    if ($('#type_ajout').is(':checked')) {
        $('#form_standard').hide();
        $('#btn_standard').hide();
        $('#form_etat_civil').show();
        $('#btn_stat_civil').show();

    } else {
        $('#form_standard').show();
        $('#btn_standard').show();
        $('#form_etat_civil').hide();
        $('#btn_stat_civil').hide();
    }
}

function encours() {
    if ($('#en_cours').is(':checked')) {
        $('.fin').hide();
    } else {
        $('.fin').show();
    }
}

function filterDE() {
    niveau = $("#niveau").val();
    sit_prof = $("#sit_prof").val();
    etat = $("#etat").val();
    genre = $("#genre").val();

    $('#datatableshow_ind').DataTable().ajax.url(racine + 'DE/getDT/' + niveau + '/' + sit_prof + '/' + genre + '/' + etat).load();
}

function filterCat_comp(id) {
    cat = $("#cat_comp").val();
    $('.datatableshow_ind3').DataTable().ajax.url(racine + 'DE/competences/getDT/' + cat + '/' + id).load();
}

function get_competences_cat(id_de) {
    cat = $("#cat_competence").val();
    update_competence(id_de, cat);
}

function valider_de_db(id, name, msg) {
    var datatableshow = '#datatableshow';
    var conf = confirm(msg + ': ' + name);

    if (conf) {

        //loading_show();
        url = 'DE/valider_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                $(datatableshow).DataTable().ajax.reload();
            }
        });
        return false;

    }
}

function devalider_de_db(id, name, msg) {
    var datatableshow = '#datatableshow';
    var conf = confirm(msg + ': ' + name);
    if (conf) {
        //loading_show();
        url = 'DE/devalider_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                $(datatableshow).DataTable().ajax.reload();
            }
        });
        return false;

    }
}


function devalider_de(id, name, msg) {
    var datatableshow = '#datatableshow_ind';
    var conf = confirm(msg + ': ' + name);
    if (conf) {
        //loading_show();
        url = 'DE/devalider_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
            }
        });
        return false;

    }
}
function valider_de(id, name, msg,togle=false) {

    var datatableshow = '#datatableshow_ind';
    var conf = confirm(msg + ': ' + name);
    if (conf) {
        //loading_show();
        url = 'DE/valider_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
                // lead('listeServices1');
                if(togle != false)
                $('#de-modal').modal('toggle');
            }
        });
        return false;
    }
}

function activer_de(id, name, msg) {
    var datatableshow = '#datatableshow_ind';
    var conf = confirm(msg + ': ' + name);
    if (conf) {
        //loading_show();
        url = 'DE/activer_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
            }
        });
        return false;

    }
}
function desactiver_de(id, name, msg) {
    var datatableshow = '#datatableshow_ind';
    var conf = confirm(msg + ': ' + name);
    if (conf) {
        //loading_show();
        url = 'DE/desactiver_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
            }
        });
        return false;

    }
}
function reprendre_de(id, name, msg) {
    var datatableshow = '#datatableshow_ind';
    var conf = confirm(msg + ': ' + name);
    if (conf) {
        //loading_show();
        url = 'DE/reprendre_de/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                link = $(datatableshow).attr('link');
                $(datatableshow).DataTable().ajax.url(link + "/" + id).load();
            }
        });
        return false;

    }
}

function update_datatable(data) {
    // alert(data.datatable);
    $(data.datatable).DataTable().ajax.reload();
}


function annoterDE(element, msg) {
    var conf = confirm(msg);
    if (conf) {
        saveform(element, update_datatable)
    }
}

function valider_profil(id, msg) {
    var conf = confirm(msg);
    if (conf) {

        //loading_show();
        url = 'DE/valider_profile/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                $("#btn_valide").hide();
            }
        });
        return false;

    }
}

function valider_profil_de(id, msg) {
    var conf = confirm(msg);
    if (conf) {

        //loading_show();
        url = 'DE/valider_profile/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                $("#btn_valide").hide();
            }
        });
        return false;

    }
}

function valider_modif_profil(id, msg) {
    var conf = confirm(msg);
    if (conf) {

        //loading_show();
        url = 'DE/valider_modif_profile/' + id;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $("#" + res).html(data).show("slow").delay(4000).hide("slow");
                $("#btn_valide").hide();
            }
        });
        return false;

    }
}
function form_reset_compte()
{
    isDisabled = $('#myfieldset_reset').is('[disabled=""]');
    if (isDisabled)
    {
        $('#myfieldset_reset').prop('disabled', false);
        $("#psw").show();
        $("#rest_compt").show();
    }
    else
    {
        $('#myfieldset_reset').prop('disabled', true);
        $("#psw").hide();
        $("#rest_compt").hide();
    }
}
function disabled_form() {
    //$('#myfieldset').prop('disabled', true);
    isDisabled = $('#myfieldset').is('[disabled=""]');
    if (isDisabled)
        $('#myfieldset').prop('disabled', false)
    else
        $('#myfieldset').prop('disabled', true)
}

function lieu_naissance() {
    id_pays = $("#pays_nais").val();
    if (id_pays == 1) {
        $("#div_commune").show();
        $("#div_lieu_nais").hide();
    } else {
        $("#div_commune").hide();
        $("#div_lieu_nais").show();
    }
}

function lieu_residance() {
    id_pays = $("#pays_res").val();
    if (id_pays == 1) {
        $("#div_commune_res").show();
        $("#div_lieu_res").hide();
    } else {
        $("#div_commune_res").hide();
        $("#div_lieu_res").show();
    }
}

function openImageModal(id) {

    // alert(id);
    $.ajax({
        type: 'get',
        url: racine + 'DE/openModalImage/' + id,
        success: function (data) {
            $("#image-modal .modal-header-body").html(data);
            $("#image-modal").modal();
            resetInit()
            $("#fichier").fileinput();

        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function addImageModal(id) {


    url = racine + "DE" + '/openModalImage/' + id;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {
            //modified by Medyahya
            $("#add-modal .modal-dialog").addClass("modal-lg");

            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function addCVModal(id) {
    url = racine + "DE" + '/openModalCV/' + id;
    $.ajax({
        type: 'get',
        url: url,
        success: function (data) {

            $("#add-modal .modal-dialog").addClass("modal-lg");

            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function previewFile() {
    const preview = document.querySelector('img');
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        // convert image file to base64 string
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}

function editImageStagiare() {
    //alert(1)
    $('#add-modal #form-errors').hide();
    // var element = $(this);
    $("#add-modal .form-loading").show();

    // var data = $('#image-modal form').serialize();
    $.ajax({
        type: $('#add-modal form').attr("method"),
        url: $('#add-modal form').attr("action"),
        data: new FormData($('#image-modal form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#add-modal').modal('toggle');
            $('#add-modal .form-loading').hide();
            $('#add-modal .answers-well-saved').show();
            $('#avatar').attr('src', racine + data);
            previewFile();
            //openStagiaireModal(data);
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors.errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#add-modal #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#image-modal .form-loading").hide();
        }
    });
}

function charger_cv_de() {
    //alert(1)
    $('#add-modal #form-errors').hide();
    // var element = $(this);
    $("#add-modal .form-loading").show();

    // var data = $('#image-modal form').serialize();
    $.ajax({
        type: $('#add-modal form').attr("method"),
        url: $('#add-modal form').attr("action"),
        data: new FormData($('#image-modal form')[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#add-modal').modal('toggle');
            $('#add-modal .form-loading').hide();
            $('#add-modal .answers-well-saved').show();

            //alert(racine+data);
            $('#cv_de').attr('href', racine + data);
            $('#cv_de').prop('target', '_blank');
            //previewFile();
            //openStagiaireModal(data);
        },
        error: function (data) {
            if (data.status === 422) {
                var errors = data.responseJSON;
                console.log(errors.errors);
                errorsHtml = '<div class="alert alert-danger"><ul>';
                var erreurs = (errors.errors) ? errors.errors : errors;
                $.each(erreurs, function (key, value) {
                    errorsHtml += '<li>' + value[0] + '</li>';
                });
                errorsHtml += '</ul></div>';
                $('#add-modal #form-errors').show().html(errorsHtml);
            } else {
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
            $("#image-modal .form-loading").hide();
        }
    });
}

function ExportExcel() {
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

function addOffreTODE(de,offre)
{
    url = 'DE/addDeToOffre/' + de +'/'+offre;
    type_methode = 'get';

    $.ajax({
        type: type_methode,
        url: racine + url,
        success: function (data) {
            $("#datatableshow_ind4").DataTable().ajax.reload();
            $("#datatableshow_ind5").DataTable().ajax.reload();
           // $('#' + datatableshow).DataTable().ajax.url(racine + "DE/offres/getDT/" + de + '/' + employeur + '/' + agence + '/' + secteur + '/all').load();

        }
    });
}
function postuler(offre,name,msg) {

    var datatableshow = '#datatableshow_ind';
    var conf = confirm(msg + ': ' + name);
    res="resultat";
    if (conf) {


        //loading_show();
        url = 'DE/postuler/' + offre;
        type = 'get';
        res = 'resultat_msg';
        $.ajax({
            type: type,
            url: racine + url,
            success: function (data) {
                $('#main-modal').modal('toggle');
                $("#" + res).html(data.msg).show("slow").delay(4000).hide("slow");

                $('#link11').trigger('click');
                // alert(element);

                /*$(element).on('show.bs.tab', function (e) {
                    $($(e.target).attr("href")).empty();
                    getTheContent($(e.target).attr("link"), $(e.target).attr("href"));

                });*/

                //setMainTabs(11, '#horPop-page',true);
               // link = $(datatableshow).attr('link');
                openObjectModal_sd(data.id,'DE/offres/candidatures','.datatableshow_ind1','de_tab',1,'xl');

            }
        });
        return false;
    }
}


function imprimerInfoAuth() {


}

function message_compte(data) {
    // alert(data.datatable);
    $('#myfieldset').prop('disabled', true);
    $('#reset_compte').show();
    $('#active_compte').hide();

}
function after_reset_compte(data) {
    // alert(data.datatable);


    $("#resultRest").html(data);
    $('#myfieldset').prop('disabled', true);
    $('#reset_compte').show();
    $('#active_compte').hide();

}

function reset_compte(element)
{
    saveform(element, after_reset_compte)
}

function active_compe(element)
{
    saveform(element, message_compte)
}

function memoriser(element,offre,lib1,lib2)
{


  if($(element).hasClass('btn-success'))
    {
        $(element).removeClass('btn-success').addClass('btn-warning');

        var url = 'DE/memoriser/' + offre + '/1';
        $("#lib_meme").html(lib1);

    }
    else {
    $(element).removeClass('btn-warning').addClass('btn-success');
      var url = 'DE/memoriser/' + offre + '/0';
      $("#lib_meme").html(lib2);
}


    var type = 'get';
    var res = 'resultat';

    $.ajax({
        type: type,
        url: racine + url,
        success: function (data) {
            //$("#" + res).html(data).show("slow");

           resetInit();
            // lead('listeServices1');
        }
    });
   // return false;

}

function filterOffreDE(datatableshow, de) {


    employeur = $("#employeur").val();
    agence = $("#agence").val();
    // commune = $("#commune").val();
    secteur = $("#secteur").val();
    $('#' + datatableshow).DataTable().ajax.url(racine + "DE/offres/getDT/" + de + '/' + employeur + '/' + agence + '/' + secteur + '/all').load();
}

function genererRecue() {

    document.formst.action = racine + "DE/recu_de/";
    //document.recu.method = "post";
    document.formst.target = "_blank";    // Open in a new window

    document.formst.submit();             // Submit the page

    return true;
}


function get_contente_action(element) {

    var type = 'get';
    var res = 'resultat';
    switch (element) {
        case 1:
            url = 'DE/annoter/1'; // annoter

            break;

        case 2:
            url = 'DE/annoter/2'; // attente de validation
            break;
        case 3:
            url = 'DE/annoter/3';  // attente de validation des modifications
            break;

    }
    $.ajax({
        type: type,
        url: racine + url,
        success: function (data) {
            $("#" + res).html(data).show("slow");
            resetInit();
            // lead('listeServices1');
        }
    });
}

function AnnotteAllDEs(element) {


    if ($(element).is(':checked')) {
        $('.btnAnnoterall').show();

    } else {
        var str = 0;

        $(':checkbox').each(function () {
            str += this.checked ? 1 : 0;
        });
        if (str == 0) {
            $('.btnAnnoterall').hide();
        } else
            $('.btnAnnoterall').show();

    }

}

function get_contente_action_cons(element) {

    var type = 'get';
    var res = 'resultat';
    switch (element) {
        case 1:
            url = 'DE/validerDE_cos/1'; // valider de

            break;

        case 2:
            url = 'DE/validerDE_cos/2'; // attente de validation
            break;

    }
    $.ajax({
        type: type,
        url: racine + url,
        success: function (data) {
            $("#" + res).html(data).show("slow");
            resetInit();
            // lead('listeServices1');
        }
    });
}
function getDEPDF()
{
    document.formst.action = racine+'DE/listeDEPDF';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

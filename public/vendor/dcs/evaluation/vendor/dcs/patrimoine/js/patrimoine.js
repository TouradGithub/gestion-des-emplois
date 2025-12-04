
function showEditElementForm(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/typesEquipements/EditElts/'+id,
        success: function (data) {
            $("#edit").html(data);
            $("#edit").show();
            $("#create").hide();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function showEditCoordonneeForm(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/localites/editCoordonneeEd/'+id,
        success: function (data) {
            $("#edit").html(data);
            $("#edit").show();
            $("#create").hide();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function editChoixElement(element) {
    saveform(element, function () {
    });
}
function editElement(element) {
    saveform(element, function () {
        $('.datatableshow2').DataTable().ajax.reload();
        $("#edit").hide();
    });
}


function createElementActeMDL(id){
    $.ajax({
        type: 'get',
        url: racine + 'modeles/addElement/'+id,
        success: function (data) {
            $("#createElmtActe").html(data);
            $("#createElmtActe").show();
            $("#editElmtActe").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function createCordonneeGPS(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/localites/ajoutCoordonnee/'+id,
        success: function (data) {
            $("#create").html(data);
            $("#create").show();
            $("#edit").hide();
            $('.datatableshow2').DataTable().ajax.url().load();


        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function createElementType(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/typesEquipements/addElement/'+id,
        success: function (data) {
            $("#create").html(data);
            $("#create").show();
            $("#edit").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}


function saveElement(element) {
    saveform(element, function (id) {
        $('.datatableshow2').DataTable().ajax.reload();
        $("#create").hide();
    });
}

function saveElementActe(element) {
    saveform(element, function (id) {
        $('.datatableshow2').DataTable().ajax.reload();
        $("#createElmtActe").hide();
    });
}
function natureElementEquipement(){
    var nature=$('#nature').val();
    if(nature == 1){
        $('#varriable').show();
        $("#style_element").hide();
    }
    else{
        $("#style_element").show();
        $('#varriable').hide();
        $('#divchoix').hide();
    }
}

function typeElementEquipement(){
    var type=$('#type').val();
    if(type == 3){
        $('#divchoix').show();
    }
    else{
        $('#divchoix').hide();
    }
}

function showEquipements(cas){
    if($('#remplace').is(':checked')== true) {
        $('select[name="equipement_an"]').empty();
        $.ajax({
            type: 'get',
            url: racine + 'patrimoine/equipements/getEquipements',
            success: function (data) {
                if (data !=''){
                    $('#list_eq').show();
                    $('select[name="equipement_an"]').append('<option  value=""></option>');
                    $.each(data, function(key, value) {
                        $('select[name="equipement_an"]').append('<option value="'+ value.id +'"> '+value.libelle+'</option>');
                    });
                }

                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else{
        $('select[name="equipement_an"]').empty();
        $('#list_eq').hide();
    }

}
function showPatrimoinePublic(cas){
    if (cas == 'add')
    {
        if($('#patrimoine_public').is(':checked')== true) {
            $('#divNum_deliberation').show();
            $('#divDate_deliberation').show();
            $('#divDeliberation').addClass('col-md-12');

        }
        else{
            $('#divNum_deliberation').hide();
            $('#divDate_deliberation').hide();
            $('#divDeliberation').removeClass('col-md-12');
        }
    }
    if (cas == 'edit')
    {
        if($('#patrimoine_publicedit').is(':checked')== true) {
            $('#divNum_deliberationedit').show();
            $('#divDate_deliberationedit').show();
            $('#divDeliberationedit').addClass('col-md-12');

        }
        else{
            $('#divNum_deliberationedit').hide();
            $('#divDate_deliberationedit').hide();
            $('#divDeliberationedit').removeClass('col-md-12');
        }
    }
}

function get_elements(){
    var type=$('#typeEq').val();
    $("#elements_type").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/getElts/'+type,
        success: function (data) {
            $("#elements_type").html('');
            $("#elements_type").html(data);
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function disableinputs(){
    $('#libelle').attr('disabled',true);
    $('#libelle_ar').attr('disabled',true);
    $('#code').attr('disabled',true);
    $('#date_acquisition').attr('disabled',true);
    $('#secteur').attr('disabled',true);
    $('#localite').attr('disabled',true);
    $('#patrimoine_publicedit').attr('disabled',true);
    $('#num_deliberation').attr('disabled',true);
    $('#date_deliberation').attr('disabled',true);
    $('#deliberation').attr('disabled',true);
    $('#latitude').attr('disabled',true);
    $('#longitude').attr('disabled',true);
    $('#eau').attr('disabled',true);
    $('#electricite').attr('disabled',true);
    $('#hygiene').attr('disabled',true);
    $('#acessibilite').attr('disabled',true);
    $('#situation_en').attr('disabled',true);
    $('#btnsave').attr('disabled',true);
}

function showinputs(){
    $('#libelle').attr('disabled',false);
    $('#libelle_ar').attr('disabled',false);
    $('#code').attr('disabled',false);
    $('#date_acquisition').attr('disabled',false);
    $('#secteur').attr('disabled',false);
    $('#localite').attr('disabled',false);
    $('#patrimoine_publicedit').attr('disabled',false);
    $('#num_deliberation').attr('disabled',false);
    $('#date_deliberation').attr('disabled',false);
    $('#deliberation').attr('disabled',false);
    $('#latitude').attr('disabled',false);
    $('#longitude').attr('disabled',false);
    $('#eau').attr('disabled',false);
    $('#electricite').attr('disabled',false);
    $('#hygiene').attr('disabled',false);
    $('#acessibilite').attr('disabled',false);
    $('#situation_en').attr('disabled',false);
    $('#btnsave').attr('disabled',false);
}

function getElementType() {
    var type = $('#typeE').val();
    var oldtype = $('#oldtype').val();
    if (type != oldtype){
        $("#showelements").html(loading_content);
        $.ajax({
            type: 'get',
            url: racine + 'patrimoine/equipements/getElt/' + type,
            success: function (data) {
                $("#showelements").html('');
                $("#showelements").html(data);
                showinputs();
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
    else{
        alert('Deja la meme type d\'equipement');
        disableinputs();
        var type = $('#id').val();
        $.ajax({
            type: 'get',
            url: racine + 'patrimoine/equipements/getEltexistent/' + type,
            success: function (data) {
                $("#showelements").html('');
                $("#showelements").html(data);
                resetInit();
            },
            error: function () {
                $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}



function createbBatiment(id){
    $("#createBatiment").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/addBatiment/'+id,
        success: function (data) {
            $("#createBatiment").html(data);
            $("#createBatiment").show();
            $("#editBatiment").hide();
            $("#changeBatiment").hide();
            $("#afficheBatiment").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function createSuivi(id){
    $("#createItemsSuivi").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/plans/addSuivi/'+id,
        success: function (data) {
            $("#createItemsSuivi").html(data);
            $("#createItemsSuivi").show();
            $("#editItemsSuivi").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function createItemsPlan(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/plans/addItem/'+id,
        success: function (data) {
            $("#createItemsPlan").html(data);
            $("#createItemsPlan").show();
            $("#editItemsPlan").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function createPlan(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/addPlan/'+id,
        success: function (data) {
            $("#createPlan").html(data);
            $("#createPlan").show();
            $("#editPlan").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function saveItemPlan(element) {
    saveform(element, function (id) {
        $('.datatableshow4').DataTable().ajax.reload();
        $("#createItemsPlan").hide();
    });
}

function saveSuiviItem(element) {
    saveform(element, function (id) {
        $('.datatableshow5').DataTable().ajax.reload();
        $("#createItemsSuivi").hide();
    });
}

function saveBatiment(element) {
    saveform(element, function (id) {
        $('.datatableshow2').DataTable().ajax.reload();
        $("#createBatiment").hide();
    });
}

function savePlan(element) {
    saveform(element, function (id) {
        $('.datatableshow3').DataTable().ajax.reload();
        $("#createPlan").hide();
    });
}
function editBatiment(element) {
    saveform(element, function (id) {
        $('.datatableshow2').DataTable().ajax.reload();
        $("#editBatiment").hide();
    });
}

function changeBatiment(element) {
    saveform(element, function (id) {
        $('.datatableshow2').DataTable().ajax.reload();
        $("#changeBatiment").hide();
    });
}

function saveTypeEquipemet(element) {
    saveform(element, function (id) {
    });
}

function editPlan(element) {
    saveform(element, function (id) {
        $('.datatableshow3').DataTable().ajax.reload();
        $("#editPlan").hide();
    });
}

function editItemPlan(element) {
    saveform(element, function (id) {
        $('.datatableshow4').DataTable().ajax.reload();
        $("#editItemsPlan").hide();
    });
}

function editSuiviItem(element) {
    saveform(element, function (id) {
        $('.editSuiviItem5').DataTable().ajax.reload();
        $("#editItemsSuivi").hide();
    });
}

function showEditBatimentForm(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/editBatiments/'+id,
        success: function (data) {
            $("#editBatiment").html(data);
            $("#editBatiment").show();
            $("#createBatiment").hide();
            $("#changeBatiment").hide();
            $("#afficheBatiment").hide();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });

}
function showEditBatimentForm2(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/editBatiments/'+id,
        success: function (data) {
            $("#editBatiment1").html(data);
            $("#editBatiment1").show();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function changerBatimentForm(id){
    $("#changeBatiment").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/changeBatiments/'+id,
        success: function (data) {
            $("#changeBatiment").html(data);
            $("#changeBatiment").show();
            $("#afficheBatiment").hide();
            $("#editBatiment").hide();
            $("#createBatiment").hide();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function visualiserEchangeEquipement(id){
    $("#afficheBatiment").html(loading_content);
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/afficheBatiment/'+id,
        success: function (data) {
            $("#afficheBatiment").html(data);
            $("#afficheBatiment").show();
            $("#changeBatiment").hide();
            $("#editBatiment").hide();
            $("#createBatiment").hide();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function showEditPlanForm(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/EditPlans/'+id,
        success: function (data) {
            $("#editPlan").html(data);
            $("#editPlan").show();
            $("#createPlan").hide();
            $('.datatableshow2').DataTable().ajax.url($(".datatableshow2").attr('link') + "/" + id).load();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function closeDivItemPlan(){
    $("#divItemPlan").hide();
    $("#divitems").show();
}

function openImgContainer() {
    $('.image-div').toggle('slow');
}

function suiviItemPlan(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/plans/suiviItem/'+id,
        success: function (data) {
            $("#divItemPlan").html(data);
            $("#divItemPlan").show();
            $("#divitems").hide();
            $("#editItemsPlan").hide();
            $("#createItemsPlan").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function showEditItem(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/plans/editItem/'+id,
        success: function (data) {
            $("#editItemsPlan").html(data);
            $("#editItemsPlan").show();
            $("#createItemsPlan").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function showEditSuivi(id){
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/plans/editSuivi/'+id,
        success: function (data) {
            $("#editItemsSuivi").html(data);
            $("#editItemsSuivi").show();
            $("#createItemsSuivi").hide();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}
function deleteBatiment(id){
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement");
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'patrimoine/equipements/DeleteBatiment/'+id,
            success: function (data)
            {
                $('.datatableshow2').DataTable().ajax.reload();
            },
            error: function(data){
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }

}

function deleteElement(id){
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement");
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'patrimoine/typesEquipements/deleteElement/'+id,
            success: function (data)
            {
                $('.datatableshow2').DataTable().ajax.reload();
            },
            error: function(data){
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function deleteCoordonness (id,msg){
    var confirme = confirm(msg);
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'patrimoine/localites/deleteCoordonnees/'+id,
            success: function (data)
            {
                $('.datatableshow2').DataTable().ajax.reload();
            },
            error: function(data){
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}


function deletePlan(id){
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement");
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'patrimoine/equipements/DeletePlan/'+id,
            success: function (data)
            {
                $('.datatableshow3').DataTable().ajax.reload();
            },
            error: function(data){
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function deleteItem(id){
    $("#editItemsPlan").hide();
    $("#createItemsPlan").hide();
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement");
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'patrimoine/equipements/plans/deleteItem/'+id,
            success: function (data)
            {
                $('.datatableshow4').DataTable().ajax.reload();
            },
            error: function(data){
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}

function deleteSuivi(id){
    $("#editItemsSuivi").hide();
    $("#createItemsSuivi").hide();
    var confirme = confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement");
    if(confirme){
        $.ajax({
            type: 'get',
            url: racine+'patrimoine/equipements/plans/deleteSuivi/'+id,
            success: function (data)
            {
                $('.datatableshow5').DataTable().ajax.reload();
            },
            error: function(data){
                alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
            }
        });
    }
}
function showchoixElement(id, lemodule, tab = 3, largeModal = 'lg'){
    $.ajax({
        type: 'get',
        url: racine + lemodule + '/showChoiceValue/' + id,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();

            setMainTabs(tab);
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function suiviHistorique(id) {
    url = racine + 'patrimoine/equipements/suiviHistorique/'+id;
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

function suiviHistoriqueBatiment(id) {
    url = racine + 'patrimoine/equipements/suiviHistoriqueBt/'+id;
    $.ajax({
        type: 'get',
        url: url,
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
function modifierTypeEquipement(id) {
    url = racine + 'patrimoine/equipements/modifierTypeEquipement/'+id;
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


function openMaintenanceModal(id, lemodule, tab = 11, largeModal = 'xl') {
    $.ajax({
        type: 'get',
        url: racine + lemodule + '/get/' + id,
        success: function (data) {
            $("#second-modal .modal-dialog").addClass("modal-" + largeModal);
            $("#second-modal .modal-header-body").html(data);
            $("#second-modal").modal();
            setMainTabs(tab,true);
            $("#second-modal").attr('link');
            $('#datatableshow').DataTable().ajax.url($("#datatableshow").attr('link') + "/" + id).load();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function exportPDFBatiment(id) {
    //alert(id);
    $.ajax({
        type: 'get',
        url: racine + 'patrimoine/equipements/exportPDFBatiment/'+id,
        success: function (data) {
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function excelEquipement1(){

    type = $("#typeEquipement").val();
    secteur=$("#secteurEquipement").val();
    localite=$("#localiteEquipement").val();
    if(localite==''){
        localite = 'all';
    }
    if(secteur==''){
        secteur = 'all';
    }
    if(type==''){
        type = 'all';
    }

    document.formst.action = racine+'patrimoine/equipements/exportExcel/'+type+'/'+ secteur+'/'+ localite+'';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;
}

function pdfEquipement(){
    type = $("#typeEquipement").val();
    secteur=$("#secteurEquipement").val();
    localite=$("#localiteEquipement").val();
    if(localite==''){
        localite = 'all';
    }
    if(secteur==''){
        secteur = 'all';
    }
    if(type==''){
        type = 'all';
    }
    document.formst.action = racine+'patrimoine/equipements/exportPDF/' + type +'/'+ secteur+'/'+ localite+'';
    document.formst.target = "_blank";    // Open in a new window
    document.formst.submit();             // Submit the page
    return true;

}

function cartesEquipement(){

    document.formsteq.action = 'carte/';
    document.formsteq.target = "_blank";    // Open in a new window
    document.formsteq.submit();             // Submit the page
    return true;

}

function filterTypeEquipement() {
    type = $("#typeEquipement").val();
    secteur=$("#secteurEquipement").val();
    localite=$("#localiteEquipement").val();
    if(localite==''){
        localite = 'all';
    }
    if(secteur==''){
        secteur = 'all';
    }
    $('#datatableshow').DataTable().ajax.url(racine + 'patrimoine/equipements/getDTE/' + type +'/'+ secteur+'/'+ localite).load();
}

function filterSecteurEquipement() {
    secteur=$("#secteurEquipement").val();
    type = $("#typeEquipement").val();
    localite=$("#localiteEquipement").val();
    if(localite==''){
        localite = 'all';
    }
    if(type==''){
        type = 'all';
    }
    $('#datatableshow').DataTable().ajax.url(racine + 'patrimoine/equipements/getDTE/' + type +'/'+ secteur+'/'+ localite).load();
}

function filterLocaliteEquipement() {
    secteur=$("#secteurEquipement").val();
    type = $("#typeEquipement").val();
    localite=$("#localiteEquipement").val();
    if(secteur==''){
        secteur = 'all';
    }
    if(type==''){
        type = 'all';
    }
    $('#datatableshow').DataTable().ajax.url(racine + 'patrimoine/equipements/getDTE/' + type +'/'+ secteur+'/'+ localite).load();
}


function exportequipementPDF(id){
    document.formspdf.action = racine+'patrimoine/equipements/exportequipementPDF/'+id+'';
    document.formspdf.target = "_blank";    // Open in a new window
    document.formspdf.submit();             // Submit the page
    return true;
}

function getlocaliteCommune()
{
    id=$("#commune").val();
    $.ajax({
        type: 'get',
        url: racine+'patrimoine/localites/getLocalitesCommune/'+id,
        success: function (data) {
            $('#localites_commune').html(data);
            resetInit();
             },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function getCarteCommune(element)
{

    if($(element).val() != 'all')
        window.location = racine+'patrimoine/equipements/carte/commune/'+ $(element).val();
    else
        window.location = racine+'patrimoine/equipements/carte';

}


function filtreEquipement(ele){

    commune = $("#commune").val();
    type_equipement = $("#typeEquipement").val();
    equipement=$("#equipement").val();
    localite=$("#localiteEquipement").val();
    date_debut = $("#date_debut").val();
    date_fin = $("#date_fin").val();
    typeMaintenances = $("#typeMaintenances").val();
    bailler = $("#bailler").val();
    taux_avancement = $("#taux_avancement").val();
    taux_execution = $("#taux_execution").val();
    budget_avant = $("#budget_avant").val();
    budget_apres = $("#budget_apres").val();
    if (budget_avant == '') {
        budget_avant = "null";
    }
    if (date_debut == '') {
        date_debut = "null";
    }
    if (budget_apres == '') {
        budget_apres = "null";
    }
    if (date_fin == '') {
        date_fin = "null";
    }

    url = racine + 'patrimoine/maintenance/getSuiviGlobalDT/' + commune + '/' + type_equipement + '/' + equipement + '/' + localite + '/'+ typeMaintenances + '/' + date_debut + '/' + date_fin + '/' + bailler + '/' + taux_avancement + '/' + taux_execution + '/' + budget_avant + '/' + budget_apres;
    urlPDF = racine + 'patrimoine/maintenance/getSuiviExport/' + commune + '/' + type_equipement + '/' + equipement + '/' + localite + '/'+ typeMaintenances + '/' + date_debut + '/' + date_fin + '/' + bailler + '/' + taux_avancement + '/' + taux_execution + '/' + budget_avant + '/' + budget_apres;
    urlEXCEL = racine + 'patrimoine/maintenance/getSuiviExport/' + commune + '/' + type_equipement + '/' + equipement + '/' + localite + '/'+ typeMaintenances + '/' + date_debut + '/' + date_fin + '/' + bailler + '/' + taux_avancement + '/' + taux_execution + '/' + budget_avant + '/' + budget_apres + '/1';
    $('#exportSuiviMaintenancePdf').attr('href', urlPDF);
    $('#exportSuiviMaintenanceExcel').attr('href', urlEXCEL);
    $('#datatableshow').DataTable().ajax.url(url).load();
}

function filterLocalite() {
    commune = $("#user_commune").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'patrimoine/localites/getDT/' + commune+'/all').load();
}


function filterCourriers() {

    type=$("#type_cr").val();
    service = $("#service").val();
    origine = $("#origine").val();
    niveau = $("#niveau_importance").val();
    date_min = $("#date_debut").val();
    date_max = $("#date_fin").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'cr/courriers/getDT/' + type +'/'+ origine +'/'+ service +'/'+ niveau +'/'+ date_min +'/'+ date_max).load();
}

function changeTypeCourrier(){
    var type = $('#type').val();
    if (type == 1)
        $('#orig').html("Origine");
    else
        $('#orig').html("Destination");

}


function filterArchives() {

    service = $("#service").val();
    date_min = $("#date_debut").val();
    date_max = $("#date_fin").val();
    etat = $("#etat").val();
    categorie = $("#categorie").val();
    $('#datatableshow').DataTable().ajax.url(racine + 'cr/archives/getDT/' +  service +'/'+ date_min +'/'+ date_max+'/'+ etat+'/'+ categorie).load();
}

function changeType(){
    var type = $('#type').val();
    // alert(type);
    if (type == 1){
        $( '.emplm' ).show();
        $( '.divfile' ).hide();
    }
    else {
        $( '.emplm' ).hide();
        $( '.divfile' ).show();
    }
}






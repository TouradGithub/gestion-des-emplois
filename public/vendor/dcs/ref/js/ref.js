function TypeChampReferentiel(element){
    var nature = $(element).val();
    $('.form_titre_champ_fr').show();
    $('.form_titre_champ_ar').show();

    if([1,2,4,5].includes(parseInt(nature))){
        $('.form_champ_sup').show();
        $('.form_model_table').hide();
        $('.form_path_model_table').hide();
        $('.form_champ_table_afficher').hide();
    }
    else{
        $('.form_model_table').show();
        $('.form_path_model_table').show();
        $('.form_champ_sup').show();
        $('.form_champ_table_afficher').show();
    }



}

function presenceChampSuplementaire(element){
    var type = $(element).val();
    $('.form_champ_table_afficher').hide();
    if(type == 1){
        $('.form_champ_sup').hide();
        $('.form_model_table').hide();
        $('.form_path_model_table').hide();
        $('.form_champ_sup_boolean').hide();
        $('.form_natureChamp').show();
    }
    else{
        $('.form_champ_sup').hide();
        $('.form_model_table').hide();
        $('.form_path_model_table').hide();
        $('.form_champ_sup_boolean').hide();
        $('.form_natureChamp').hide();
        $('.form_titre_champ_fr').hide();
        $('.form_titre_champ_ar').hide();
    }
}

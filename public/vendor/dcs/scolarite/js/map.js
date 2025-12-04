function plus_info_commune(element,arr,module,ref)
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
                ref:ref
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


        /*$.ajax({
            type: 'get',
            url: racine+'plus_info_commune',
            data:arr,
            //('tyru')->result,
            cache: false,
            success: function(data)
            {
                $("#resultat_detait_info").html(data);
            },
            error: function () {
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });*/
    }
    else{

    }
}
$(function () {
    var CurrentNode;
    $('#jstree')
        .on('changed.jstree', function (e, data) {
            var i, j, r = [], s = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                // r.push(data.instance.get_node(data.selected[i]).text);
                CurrentNode = data.selected[0];
                console.log(data.selected[0]);

            }
            //node = data.node;
            //alert(node.id);
           /* var i, j, r = [];
            for(i = 0, j = data.selected.length; i < j; i++) {
                r.push(data.instance.get_node(data.selected[i]).data);
            }
            $('#event_result').html('Selected: ' + r.join(', '));*/
            //$(this).jstree(true).refresh();
        })
        .jstree({
            'core' : {
                "themes":{
                    "icons":false
                },
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return racine+'groupes/get_groupes'
                    },
                    'data' : function (node) {
                        return { 'id' : node.id };
                    },
                    dataType: "json",
                }
            }
        })
        .on("select_node.jstree", function (e, data) {
            var id = data.node.id;
            //alert(11);
            // $(".treeitem a").css("color","#337ab7");
            // $(".treeitem a").css("font-weight","normal");
            // $(this).css("color","#009688");
            // $(this).css("font-weight","bold");
            var link;
            hideBtn(".btn-groups");
            $(".btn-groups").each(function( index ) {
                link = $(this).attr('defaultlink')+id;
                $(this).attr('href',link);
                $(this).attr('idgroup',id);
            });
            showBtn(".btn-groups");
            // if($(this).attr('nbqst')<1) {
            //     showBtn(".group-add-sg");
            //     showBtn(".group-qst-list");
            //     $('.group-add-sg').val(id);
            //         showBtn(".group-qst-list");
            //     if(!$(this).siblings("ul").length){
            //         showBtn(".group-delete");
            //         showBtn(".group-qst-list");
            //         $('.group-qst-list').val(id);
            //     }
            // }
            // else
            //     showBtn(".group-qst-list");
            // showBtn(".group-edit");
            $('.group-edit').val(id);
            $('.group-qst-list').val(id);
            $('.group-add-sg').val(id);
        });
});
function openSousGroupeModal() {
        
    var idgroupe = $('.group-add-sg').val();
    // alert(idgroupe);
    $.ajax({
        type: 'get',
        url: racine + 'groupes/addSG/'+idgroupe,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            // $("#main-modal .modal-dialog").addClass('modal-lg');
            $("#main-modal").modal();
            init();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

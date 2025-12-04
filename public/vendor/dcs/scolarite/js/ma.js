//*************** Javascript

$(function () {
    var CurrentNode;
    $('#jstree')
        .on('changed.jstree', function (e, data) {
            var i, j, r = [], s = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                CurrentNode = data.selected[0];
                console.log(data.selected[0]);

            }
        })
        .jstree({
            'core' : {
                "themes":{
                    "icons":false
                },
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                        return racine+'secteurs/get_secteurs'
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
            var link;
            $(".btn-groups").hide();
            $(".btn-groups").each(function( index ) {
                link = $(this).attr('defaultlink')+id;
                $(this).attr('href',link);
                $(this).attr('idgroup',id);
            });
            $(".btn-groups").show();
            $('.group-edit').val(id);
            $('.group-add-sg').val(id);
            $('.group-delete').val(id);
        });
});

function openFormAddSecteurInModal(lemodule) {
    $.ajax({
        type: 'get',
        url: racine + lemodule + '/add/',
        success: function (data) {
            $("#add-modal .modal-header-body").html(data);
            $("#add-modal").modal();
            resetInit();
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function openSousGroupeModal(lemodule,id) {
        
    var idsecteur = $('.group-add-sg').val();
    // alert(idsecteur);
    $.ajax({
        type: 'get',
        url: racine + 'secteurs/add/' + idsecteur,
        success: function (data) {
            $("#main-modal .modal-header-body").html(data);
            // $("#main-modal .modal-dialog").addClass('modal-lg');
            $("#main-modal").modal();
            
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function addSecteur(element,lemodule) {
    saveform(element, function(id){
        $('#jstree').jstree("refresh");
        $(element).attr('disabled','disabled');
        setTimeout(function () {
            $('#add-modal').modal('toggle');
            openObjectModal(id,lemodule);
        }, 1500);
    });
}

function opensecteurModal(id,lemodule,tab=1, largeModal=false) {
    var id = $('.group-edit').val();
    $.ajax({
        type: 'get',
        url: racine + 'secteurs/get/' + id ,
        success: function (data) {
            if(largeModal) $("#main-modal .modal-dialog").addClass("modal-lg");
            $("#main-modal .modal-header-body").html(data);
            $("#main-modal").modal();
            $('.main-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                $($(e.target).attr("href")).empty();
                getTheContent($(e.target).attr("link"), $(e.target).attr("href"));
            });
            if(tab==1)
                getTheContent(lemodule + '/getTab/' + id + '/1', '#tab1');
            else
                $('#link'+tab).trigger('click');
            $("#jstree").attr('link');
             //$('#datatableshow').DataTable().ajax.url($("#datatableshow").attr('link')+"/"+id).load();
            
        },
        error: function () {
            $.alert("Une erreur est survenue veuillez réessayer ou actualiser la page!");
        }
    });
}

function savesecteur(element, aftersave=null) {
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
            $('#jstree').jstree("refresh");
            $('#' + container + ' .spinner-border').hide();
            $('#' + container + ' .answers-well-saved').show();
            $(element).removeAttr('disabled');
            setTimeout(function () {
                $('#' + container + ' .answers-well-saved').hide();
                $('#' + container + ' .main-icon').show();
            }, 3500);
            if (aftersave) {
                aftersave(data);
            }
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

function confirmActionSecteur(text, aftersave=null) {
    var id = $('.group-delete').val();
    $.confirm({
        title: 'Confirmation',
        content: text,
        buttons: {
            confirm: function () {
                $.ajax({
                    type: 'GET',
                    url: racine + 'secteurs/delete/' + id ,
                    success: function (data) {
                        if (data.success == "true") {
                            $('#jstree').jstree("refresh");
                            $.dialog(data.msg, 'Confirmation');
                            if (aftersave) {
                                aftersave(data);
                            }
                        }
                        else $.dialog(data.msg, 'Erreur');
                    },
                    error: function () {
                        $.dialog("Une erreur est survenue veuillez réessayer ou actualiser la page!");
                    }
                });
            },
            close: function () {
            }
        }
    });
}

/*$.fn.extend({
    treed: function (o) {

        var openedClass = 'glyphicon-minus-sign';
        var closedClass = 'glyphicon-plus-sign';

        if (typeof o != 'undefined'){
            if (typeof o.openedClass != 'undefined'){
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined'){
                closedClass = o.closedClass;
            }
        };

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
}); 
              
function selectSecteurNode(element){
    let secteur_id = $(element).attr("secteurId");
    alert(secteur_id);
}

function selectSecteur($id){
   $("#secteurActions").show();
   alert($id);

}

$(".jstree-clicked").click(function (e) {
        var node = $(this).jstree('get_selected').text();
        alert(node);
});

//function showDiv(divId, element)
//{
   // document.getElementById(divId).style.display = element.value == 1 ? 'block' : 'none';
//}

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});*/
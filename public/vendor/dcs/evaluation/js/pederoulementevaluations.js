$(document).ready(function () {
  // Get the content of each tab od groupes
  $(".evaluation-tabs a[data-toggle='tab']").on('click', function () {
      link = $($(this)).attr("link");
      href = $($(this)).attr("href");
      getTheContent(link, href);
  });
  $('#base-tab1').trigger('click');

  if ($('#etatEvaluation').val() == 3 || $('#etatEvaluation').val() == 6) {
    $('#clock').countdown(finalDate).on('update.countdown', function(event) {
      var $this = $(this).html(event.strftime(''
        + '<span class="text-success">%H</span> heure%!H '
        + '<span class="text-success">%M</span> minute%!M '
        + '<span class="text-success">%S</span> seconde%!S'));
        $('#timeleft').val(event.strftime('%-T'));
        if (event.strftime('%-T') < 60) {
          $('#alert-countdown').show();
          $('#alert-countdown .sencondes-restantes').html(event.strftime('%-T'));
        }
    }).on('finish.countdown', function () {
      location.reload(true);
    });
  }

  setInterval( getEvaluationStat, 36000 );
});

// Date & Time Range
// $('.datetime').daterangepicker({
//     timePicker: true,
//     timePickerIncrement: 30,
//     locale: {
//         format: 'MM/DD/YYYY h:mm A'
//     }
// });

function showAssociations(id) {
    $('.associations-valeur').hide();
    $('.association' + id).show();
    $('.association' + id +' .select2-selection__rendered').trigger('click');
}

function hideAssociations() {
    $('.associations-valeur').hide();
}

function saveFormateurQCMAnswer(reponse_id, groupe_id, valeur_id) {

    var values = [];
    $.each($('input[name="checkbox' + reponse_id + '"]:checked'), function () {
        values.push($(this).val());
    });
    // console.log(values);
    saveFormateurAnswer(reponse_id, groupe_id, valeur_id, values);
}

function saveFormateurClassementAnswer(reponse_id, groupe_id, valeur_id) {
    var values = $("[reponse_id=" + reponse_id + "]").sortable('toArray').join();
    // console.log(values);
    saveFormateurAnswer(reponse_id, groupe_id, valeur_id, values);
}

function saveFormateurAssociationAnswer(reponse_id, groupe_id, valeur_id) {
    var values = '';
    $.each($('#reponse'+reponse_id+' .associations-valeur'), function () {
        var options = [];
        $.each($(this).find(' .select2 option:selected'), function () {
            options.push($(this).val());
        });
        if (options.length){
            values = (values) ? values + ';' : values;
            values = values + $(this).attr('valeur_id') + '-' + options;
        }
    });
    // console.log(values);
    saveFormateurAnswer(reponse_id, groupe_id, valeur_id, values);
    // next step is to show numbers in badges after selecting them or diselecting ;)
}

function saveFormateurAnswer(reponse_id, groupe_id, valeur_id, values) {
    $('.test-lights .spinner-border').show();
    $('.test-lights .answers-well-saved').hide();
    // alert(racine + 'evaluations/saveFormateurAnswer/' + reponse_id + '/' + groupe_id + '/' + valeur_id + '/' + values);
    $.ajax({
        type: 'get',
        url: racine + 'eval/evaluations/saveFormateurAnswer/' + reponse_id + '/' + groupe_id + '/' + valeur_id + '/' + values,
        success: function (data) {
            $('#reponse'+reponse_id+' .etat-reponse .badge').hide();
            $('#reponse'+reponse_id+' .etat-reponse .badge-success').show();
            $('#nbQstRepondusGrp' + data.groupe_id).html(data.nb_groupe_reponses);
            $('#evaluationProgressBar .progress-bar').css('width', data.progress + '%').attr('aria-valuenow', data.progress).html(data.progress + '%');
            // $('#nbQstRepondus').html(data.nb_qst_repondus);
            $('#btnAssociationValeur' + valeur_id).html(data.associations);
            console.log('#btnAssociationValeur' + valeur_id + ' = ' + data.associations);
            $('.test-lights .answers-well-saved').show();
            $('.test-lights .spinner-border').hide();
            setTimeout(function () {
                $('.test-lights .answers-well-saved').hide();
            }, 500);
        },
        error: function () {
            alert(msg_erreur);
        }
    });
}
function resetInitModule() {
    // Wizard tabs with numbers setup
    $(".number-tab-steps").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        enableFinishButton: false,
        enableAllSteps: true,
        labels: {
            finish: 'Submit',
            cancel: "Annuler",
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: 'Suivant <i class="fas fa-angle-right"></i>',
            previous: '<i class="fas fa-angle-left"></i> Précédent',
            loading: "Chargement en cours ..."
        },
        onContentLoaded: function (event, currentIndex) {
            $(".select2").select2({
              dropdownAutoWidth: true,
              width: '100%',
              placeholder: "Select State",
            });
            // Reset classement sortable
            $(".group-elements-classement").sortable({
                axis: 'y',
                update: function (event, ui) {
                    // console.log($(this).attr('reponse_id')+', '+$(this).attr('groupe_id')+', '+ui.item.attr('id'));
                    saveFormateurClassementAnswer($(this).attr('reponse_id'), $(this).attr('groupe_id'), ui.item.attr('valeur_id'));
                    var ordre = 1;
                    $(".group-elements-classement li").each(function (i, item) {
                        $(item).children('.valeur-ordre').html(ordre);
                        ordre++;
                    });
                }
            });
        },
        onFinished: function (event, currentIndex) {
            alert("Form submitted.");
        }
    });
}
var offline_tries = 0;
function getEvaluationStat() {
  if (($('#etatEvaluation').val() == 3 || $('#etatEvaluation').val() == 6) && $('#timeleft').val() > 0) {
      $.ajax({
          type: 'get',
          url: racine + 'eval/evaluations/getEvaluationStat/' + $('#instance_id').val() + '/' + $('#timeleft').val(),
          success: function (data) {
              offline_tries = 0;
              $('#nbQstVues').html(data.nb_qst_vues);
              $('#nbQstRepondus').html(data.nb_qst_rep);
              $('.connexion-stat').removeClass('text-secondary');
              $('.connexion-stat').addClass('text-success');
          },
          errorr: function (data) {
              // if(offline_tries > 15){
                  // location.reload();
              // }
              offline_tries = offline_tries + 1;
              $('.connexion-stat').addClass('text-secondary');
              $('.connexion-stat').removeClass('text-success');
          }
      });
  }
}

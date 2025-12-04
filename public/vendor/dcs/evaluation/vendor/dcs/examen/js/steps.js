var form = $(".steps-validation-custom").show();

$(".steps-validation-custom").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        current: "Etape courante:",
        pagination: "Pagination",
        finish: "Terminer",
        next: "Suivant",
        previous: "Précédent",
        loading: "Chargement ..."
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }
        // Forbid next action on "Warning" step if the user is too young
        if (newIndex === 3 && Number($("#age-2").val()) < 18) {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex) {
        $.confirm({
            type: 'blue',
            title: 'Confirmation!',
            content: 'Voulez-vous vraiment valider et calculer la moyenne ?',
            buttons: {
                confirm: function () {
                    // add overlay to the form and disable
                    var $overlay = $('#overlay');

                    // Show the overlay
                    $overlay.show();
                    $.ajax({
                        url: racine + 'examens/jury/specialites/saveConfigurationMatieresAndValiderDeliberation',
                        type: 'POST',
                        data: form.serialize(),
                        success: function (data) {
                            $overlay.hide(); // Hide the overlay after the task is complete
                            $.confirm({
                                title: 'Résultat '+ data.specialite + '!',
                                content: data.message,
                                buttons: {
                                    "Voir Résultats": function () {
                                        // target _blank
                                        window.open(racine + 'examens/jury/specialites/resultats/'+data.id, '_blank');

                                    },
                                    "Fermer": function () {

                                    }
                                }
                            })
                        },
                        oncomplete: function () {
                            $overlay.hide(); // Hide the overlay after the task is complete
                        },
                        error: function (data) {
                            $overlay.hide();
                        }
                    })
                },
                cancel: function () {

                }
            }
        })
    }
});

// Initialize validation
$(".steps-validation-custom").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    rules: {
        email: {
            email: true
        }
    }
});

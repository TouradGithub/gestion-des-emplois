function basedFilter(value) {
    switch (value) {
        case '1':
            $('#specialites_filter').removeClass('d-none');
            break;
        case '2':
            $('#niveau_formations_filter').removeClass('d-none');
            break;
        case '3':
            $('#etablissement_filter').removeClass('d-none');
            break;
        case '4':
            $('#genres_filter').removeClass('d-none');
            break;
        case '5':
            $('#annee_filter').removeClass('d-none');
            break;
        case '6':
            $('#age_filter').removeClass('d-none');
            break;
    }
    $('#filtres_select option').each(function () {
        if ($(this).val() == value) {
            $(this).attr('disabled', true);
        } else {
            $(this).attr('disabled', false);
        }
    });
}

function basedFilterSortant(value) {
    switch (value) {
        case '1':
            $('#specialites_filter_sortants').removeClass('d-none');
            break;
        case '2':
            $('#niveau_formations_filter_sortants').removeClass('d-none');
            break;
        case '3':
            $('#etablissement_filter_sortants').removeClass('d-none');
            break;
        case '4':
            $('#genres_filter_sortants').removeClass('d-none');
            break;
        case '5':
            $('#annee_filter_sortants').removeClass('d-none');
            break;
        case '6':
            $('#age_filter_sortants').removeClass('d-none');
            break;
    }
    $('#filtres_select_sortants option').each(function () {
        if ($(this).val() == value) {
            $(this).attr('disabled', true);
        } else {
            $(this).attr('disabled', false);
        }
    });
}

function basedFilterInsertion(value) {
    switch (value) {
        case '1':
            $('#specialites_filter_insertion').removeClass('d-none');
            break;
        case '2':
            $('#niveau_formations_filter_insertion').removeClass('d-none');
            break;
        case '3':
            $('#etablissement_filter_insertion').removeClass('d-none');
            break;
        case '4':
            $('#genres_filter_insertion').removeClass('d-none');
            break;
        case '5':
            $('#annee_filter_insertion').removeClass('d-none');
            break;
        case '6':
            $('#age_filter_insertion').removeClass('d-none');
            break;
        case '7':
            $("#enquete_filter_insertion").removeClass("d-none");
    }
    $('#filtres_select_insertion option').each(function () {
        if ($(this).val() == value) {
            $(this).attr('disabled', true);
        } else {
            $(this).attr('disabled', false);
        }
    });
}

function filterRes() {
    $('#btn-icon').addClass('d-none');
    $('#btn-spinner').removeClass('d-none');
    $('#results_filters_capacites').removeClass('d-none');
    $('#results_filters_capacites').html(loading_content);
    $.ajax({
        url: racine + "new_dashboard/filtres",
        type: "POST",
        data: $('#filtres_cnt_capacite form').serialize(),
        success: function (data) {
            $('#results_filters_capacites').html(data);
        },
        error: function () {
            $('#results_filters_capacites').html('');

            $.alert({
                title: 'Erreur',
                content: 'Une erreur est survenue',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    close: {
                        text: 'Fermer',
                        btnClass: 'btn-red',
                        action: function () {
                        }
                    }
                }
            });
        },
        complete: function (xhr, status, error) {
            $('#btn-icon').removeClass('d-none');
            $('#btn-spinner').addClass('d-none');
        }
    });
}

function filterSortants() {
    $('#btn-icon_sortants').addClass('d-none');
    $('#btn-spinner_sortants').removeClass('d-none');
    $('#results_filters_sortants').removeClass('d-none');
    $('#results_filters_sortants').html(loading_content);
    $.ajax({
        url: racine+"new_dashboard/filtres_sortants",
        type: "POST",
        data: $('#filtres_cnt_sortants form').serialize(),
        success: function (data) {
            $('#results_filters_sortants').html(data);
        },
        error: function () {
            $('#results_filters_sortants').html('');

            $.alert({
                title: 'Erreur',
                content: 'Une erreur est survenue',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    close: {
                        text: 'Fermer',
                        btnClass: 'btn-red',
                        action: function () {
                        }
                    }
                }
            });
        },
        complete: function (xhr, status, error) {
            $('#btn-icon_sortants').removeClass('d-none');
            $('#btn-spinner_sortants').addClass('d-none');
        }
    });
}

function filterInsertion() {
    $('#btn-icon_insertion').addClass('d-none');
    $('#btn-spinner_insertion').removeClass('d-none');
    $('#results_filters_insertion').removeClass('d-none');
    $('#results_filters_insertion').html(loading_content);
    $.ajax({
        url: racine+"new_dashboard/filtres_insertions",
        type: "POST",
        data: $('#filtres_cnt_insertion form').serialize(),
        success: function (data) {
            $('#results_filters_insertion').html(data);
        },
        error: function () {
            $('#results_filters_insertion').html('');

            $.alert({
                title: 'Erreur',
                content: 'Une erreur est survenue',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    close: {
                        text: 'Fermer',
                        btnClass: 'btn-red',
                        action: function () {
                        }
                    }
                }
            });
        },
        complete: function (xhr, status, error) {
            $('#btn-icon_insertion').removeClass('d-none');
            $('#btn-spinner_insertion').addClass('d-none');
        }
    });
}

function filtreChange(ele) {
    // selected value to array
    var selected = $(ele).val();
    var options = [{
        id: '1',
        selector: '#specialites_filter'
    },
        {
            id: '2',
            selector: '#niveau_formations_filter'
        },
        {
            id: '3',
            selector: '#etablissement_filter'
        },
        {
            id: '4',
            selector: '#genres_filter',

        },
        {
            id: '5',
            selector: '#annee_filter',

        },
        {
            id: '6',
            selector: '#age_filter',

        }
    ];
    // show filter based on value in options using selector
    options.forEach(function (option) {
        if (selected.includes(option.id)) {
            $(option.selector).removeClass('d-none');
        } else {
            if ($('#filtres').val() !== option.id)
                $(option.selector).addClass('d-none');
        }
    });
}

function filtreChangeSortants(ele) {
    // selected value to array
    var selected = $(ele).val();
    var options = [{
        id: '1',
        selector: '#specialites_filter_sortants'
    },
        {
            id: '2',
            selector: '#niveau_formations_filter_sortants'
        },
        {
            id: '3',
            selector: '#etablissement_filter_sortants'
        },
        {
            id: '4',
            selector: '#genres_filter_sortants',

        },
        {
            id: '5',
            selector: '#annee_filter_sortants',

        },
        {
            id: '6',
            selector: '#age_filter_sortants',

        }
    ];
    // show filter based on value in options using selector
    options.forEach(function (option) {
        if (selected.includes(option.id)) {
            $(option.selector).removeClass('d-none');
        } else {
            if ($('#filtres_sortants').val() !== option.id)
                $(option.selector).addClass('d-none');
        }
    });
}

function filtreChangeInsertion(ele) {
    // selected value to array
    var selected = $(ele).val();
    var options = [{
        id: '1',
        selector: '#specialites_filter_insertion'
    },
        {
            id: '2',
            selector: '#niveau_formations_filter_insertion'
        },
        {
            id: '3',
            selector: '#etablissement_filter_insertion'
        },
        {
            id: '4',
            selector: '#genres_filter_insertion',

        },
        {
            id: '5',
            selector: '#annee_filter_insertion',

        },
        {
            id: '6',
            selector: '#age_filter_insertion',

        },
        {
            'id': '7',
            'selector': '#enquete_filter_insertion'
        },
    ];
    // show filter based on value in options using selector
    options.forEach(function (option) {
        if (selected.includes(option.id)) {
            $(option.selector).removeClass('d-none');
        } else {
            if ($('#filtres_insertion').val() !== option.id)
                $(option.selector).addClass('d-none');
        }
    });
}


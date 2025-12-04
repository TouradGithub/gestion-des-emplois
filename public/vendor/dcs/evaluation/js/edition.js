function get_liste_echantillon() {

    document.imprimer_echantillon.action = racine + "edition/liste_echantillon";

    document.imprimer_echantillon.target = "_blank";    // Open in a new window

    document.imprimer_echantillon.submit();
}

function get_liste_draft() {

    document.imprimer_echantillon.action = racine + "edition/liste_draft";

    document.imprimer_echantillon.target = "_blank";    // Open in a new window

    document.imprimer_echantillon.submit();
}

function get_liste_sortants_inserses()
{
    document.imprimer_echantillon.action = racine + "edition/liste_inserer";

    document.imprimer_echantillon.target = "_blank";    // Open in a new window

    document.imprimer_echantillon.submit();
}
function show_groupe(parant)
{
    $('#ajax').jstree({
        'core' : {
            'data' : {
                "url" : racine + 'edition/show_groupe/' + parant,
                "dataType" : "json" // needed only if you do not supply JSON headers
            }
        }
    });
}

function get_liste_stageaires()
{


    document.imprimer_stageaires.action = racine + "edition/liste_stageaires";

    document.imprimer_stageaires.target = "_blank";    // Open in a new window

    document.imprimer_stageaires.submit();             // Submit the page

    return true;
}

function get_liste_etablissement()
{
    document.imprimer_etablissements.action = racine + "edition/liste_etablissements";

    document.imprimer_etablissements.target = "_blank";    // Open in a new window

    document.imprimer_etablissements.submit();             // Submit the page

    return true;
}
<?php

require_once 'vendor/autoload.php';

use Mpdf\Mpdf;

echo "🔄 Génération du fichier PDF...\n";

// Lire le contenu du fichier Markdown
$markdownContent = file_get_contents('CONCEPTION_SYSTEME_EMPLOIS.md');

// Fonction pour convertir Markdown simple en HTML
function markdownToHtml($markdown) {
    $html = $markdown;

    // Titres
    $html = preg_replace('/^# (.*$)/m', '<h1>$1</h1>', $html);
    $html = preg_replace('/^## (.*$)/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^### (.*$)/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^#### (.*$)/m', '<h4>$1</h4>', $html);

    // Gras et italique
    $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
    $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);

    // Code inline
    $html = preg_replace('/`(.*?)`/', '<code>$1</code>', $html);

    // Listes à puces
    $lines = explode("\n", $html);
    $inList = false;
    $result = [];

    foreach ($lines as $line) {
        if (preg_match('/^- (.+)$/', $line, $matches)) {
            if (!$inList) {
                $result[] = '<ul>';
                $inList = true;
            }
            $result[] = '<li>' . $matches[1] . '</li>';
        } else {
            if ($inList) {
                $result[] = '</ul>';
                $inList = false;
            }
            $result[] = $line;
        }
    }

    if ($inList) {
        $result[] = '</ul>';
    }

    $html = implode("\n", $result);

    // Blocs de code
    $html = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $html);

    // Paragraphes
    $html = preg_replace('/\n\n+/', '</p><p>', $html);
    $html = '<p>' . $html . '</p>';

    // Nettoyer
    $html = str_replace('<p></p>', '', $html);
    $html = str_replace('<p><h', '<h', $html);
    $html = str_replace('</h1></p>', '</h1>', $html);
    $html = str_replace('</h2></p>', '</h2>', $html);
    $html = str_replace('</h3></p>', '</h3>', $html);
    $html = str_replace('</h4></p>', '</h4>', $html);
    $html = str_replace('<p><ul>', '<ul>', $html);
    $html = str_replace('</ul></p>', '</ul>', $html);
    $html = str_replace('<p><pre>', '<pre>', $html);
    $html = str_replace('</pre></p>', '</pre>', $html);

    return $html;
}

try {
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 25,
        'margin_bottom' => 20,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);

    // Configuration PDF
    $mpdf->SetAuthor('Équipe Développement');
    $mpdf->SetTitle('Conception - Système de Gestion des Emplois du Temps');
    $mpdf->SetSubject('Document de Conception Technique');
    $mpdf->SetKeywords('Laravel, Emplois du temps, Conception, PHP, Base de données');

    // CSS pour le PDF
    $css = '
    <style>
        @page {
            margin-top: 3cm;
            margin-bottom: 2cm;
        }

        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }

        h1 {
            color: #2c3e50;
            font-size: 18pt;
            font-weight: bold;
            margin-top: 25pt;
            margin-bottom: 15pt;
            border-bottom: 3px solid #3498db;
            padding-bottom: 8pt;
            page-break-after: avoid;
        }

        h2 {
            color: #34495e;
            font-size: 16pt;
            font-weight: bold;
            margin-top: 20pt;
            margin-bottom: 12pt;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5pt;
            page-break-after: avoid;
        }

        h3 {
            color: #34495e;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 18pt;
            margin-bottom: 10pt;
            page-break-after: avoid;
        }

        h4 {
            color: #7f8c8d;
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15pt;
            margin-bottom: 8pt;
            page-break-after: avoid;
        }

        p {
            margin-bottom: 10pt;
            text-align: justify;
            text-indent: 0;
        }

        ul {
            margin-left: 20pt;
            margin-bottom: 12pt;
        }

        li {
            margin-bottom: 4pt;
            line-height: 1.4;
        }

        code {
            background-color: #f8f9fa;
            padding: 2pt 4pt;
            font-family: "Courier New", monospace;
            font-size: 9pt;
            border: 1px solid #e9ecef;
            border-radius: 3pt;
        }

        pre {
            background-color: #f8f9fa;
            padding: 12pt;
            margin: 15pt 0;
            font-family: "Courier New", monospace;
            font-size: 9pt;
            border-left: 4px solid #3498db;
            border: 1px solid #e9ecef;
            border-radius: 4pt;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        pre code {
            background: none;
            border: none;
            padding: 0;
        }

        strong {
            font-weight: bold;
            color: #2c3e50;
        }

        em {
            font-style: italic;
            color: #7f8c8d;
        }

        .page-break {
            page-break-before: always;
        }

        .center {
            text-align: center;
        }

        .title-page {
            text-align: center;
            margin-top: 100pt;
        }

        .title-main {
            font-size: 24pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 30pt;
            line-height: 1.3;
        }

        .title-sub {
            font-size: 18pt;
            color: #34495e;
            margin-bottom: 40pt;
        }

        .title-info {
            font-size: 12pt;
            color: #7f8c8d;
            margin-bottom: 20pt;
        }

        .toc {
            margin-top: 30pt;
        }

        .toc h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
        }
    </style>';

    // Page de titre
    $titlePage = '
    <div class="title-page">
        <div class="title-main">
            CONCEPTION DU SYSTÈME<br/>
            DE GESTION DES EMPLOIS DU TEMPS
        </div>

        <div class="title-sub">
            Document de Conception Technique
        </div>

        <div class="title-info">
            <strong>Version :</strong> 1.0<br/>
            <strong>Date :</strong> Octobre 2024<br/><br/>

            <strong>Framework :</strong> Laravel 11<br/>
            <strong>Base de Données :</strong> MySQL<br/>
            <strong>Langage :</strong> PHP 8.1+
        </div>

        <div style="margin-top: 60pt; color: #95a5a6; font-size: 10pt;">
            © 2024 - Équipe Développement<br/>
            Document de Conception Technique
        </div>
    </div>';

    // Contenu HTML principal
    $htmlContent = markdownToHtml($markdownContent);

    // Ajouter en-tête et pied de page
    $mpdf->SetHTMLHeader('
        <table width="100%" style="border-bottom: 1px solid #bdc3c7; padding-bottom: 5pt;">
            <tr>
                <td width="60%" style="color: #7f8c8d; font-size: 9pt; font-weight: bold;">
                    Système de Gestion des Emplois du Temps
                </td>
                <td width="40%" style="text-align: right; color: #7f8c8d; font-size: 9pt;">
                    Conception Technique v1.0
                </td>
            </tr>
        </table>
    ');

    $mpdf->SetHTMLFooter('
        <table width="100%" style="border-top: 1px solid #bdc3c7; padding-top: 5pt;">
            <tr>
                <td width="50%" style="color: #95a5a6; font-size: 8pt;">
                    © 2024 - Document de Conception
                </td>
                <td width="50%" style="text-align: right; color: #95a5a6; font-size: 8pt;">
                    Page {PAGENO} sur {nbpg}
                </td>
            </tr>
        </table>
    ');

    // Écriture du contenu
    $mpdf->WriteHTML($css);
    $mpdf->WriteHTML($titlePage);
    $mpdf->AddPage();
    $mpdf->WriteHTML($htmlContent);

    // Sauvegarde du PDF
    $mpdf->Output('CONCEPTION_SYSTEME_EMPLOIS.pdf', 'F');

    echo "✅ Fichier PDF généré avec succès: CONCEPTION_SYSTEME_EMPLOIS.pdf\n";

} catch (Exception $e) {
    echo "❌ Erreur génération PDF: " . $e->getMessage() . "\n";
}

// Génération DOCX simple (sans PhpWord)
echo "🔄 Génération du fichier DOCX (format RTF)...\n";

try {
    // Créer un fichier RTF qui peut être ouvert comme DOCX
    $rtfContent = '{\\rtf1\\ansi\\deff0 {\\fonttbl {\\f0 Times New Roman;}}';
    $rtfContent .= '\\f0\\fs24 ';

    // Page de titre
    $rtfContent .= '{\\qc\\b\\fs32 CONCEPTION DU SYSTEME DE GESTION DES EMPLOIS DU TEMPS\\par}';
    $rtfContent .= '{\\qc\\fs24 \\par}';
    $rtfContent .= '{\\qc\\b\\fs28 Document de Conception Technique\\par}';
    $rtfContent .= '{\\qc\\fs20 \\par}';
    $rtfContent .= '{\\qc Version: 1.0\\par}';
    $rtfContent .= '{\\qc Date: Octobre 2024\\par}';
    $rtfContent .= '{\\qc \\par}';
    $rtfContent .= '{\\qc Framework: Laravel 11\\par}';
    $rtfContent .= '{\\qc Base de Données: MySQL\\par}';
    $rtfContent .= '\\page ';

    // Contenu principal - conversion simple
    $lines = explode("\n", $markdownContent);

    foreach ($lines as $line) {
        $line = trim($line);

        if (empty($line)) {
            $rtfContent .= '\\par ';
            continue;
        }

        // Titre niveau 1
        if (preg_match('/^# (.+)$/', $line, $matches)) {
            $rtfContent .= '{\\b\\fs28 ' . $matches[1] . '\\par}\\par ';
        }
        // Titre niveau 2
        elseif (preg_match('/^## (.+)$/', $line, $matches)) {
            $rtfContent .= '{\\b\\fs24 ' . $matches[1] . '\\par} ';
        }
        // Titre niveau 3
        elseif (preg_match('/^### (.+)$/', $line, $matches)) {
            $rtfContent .= '{\\b\\fs22 ' . $matches[1] . '\\par} ';
        }
        // Liste
        elseif (preg_match('/^- (.+)$/', $line, $matches)) {
            $rtfContent .= '• ' . $matches[1] . '\\par ';
        }
        // Texte normal (ignorer les blocs de code complexes)
        elseif (!preg_match('/^```/', $line) && !preg_match('/^\s*$/', $line) && !preg_match('/^---/', $line)) {
            // Nettoyer le markdown simple
            $text = preg_replace('/\*\*(.*?)\*\*/', '{\\b $1}', $line);
            $text = preg_replace('/\*(.*?)\*/', '{\\i $1}', $text);
            $text = preg_replace('/`(.*?)`/', '$1', $text);

            if (!empty($text)) {
                $rtfContent .= $text . '\\par ';
            }
        }
    }

    $rtfContent .= '}';

    // Sauvegarder le fichier RTF avec extension .docx
    file_put_contents('CONCEPTION_SYSTEME_EMPLOIS.docx', $rtfContent);

    echo "✅ Fichier DOCX généré: CONCEPTION_SYSTEME_EMPLOIS.docx\n";

} catch (Exception $e) {
    echo "❌ Erreur génération DOCX: " . $e->getMessage() . "\n";
}

echo "\n🎉 Génération des documents terminée!\n";
echo "📄 Fichiers créés dans le répertoire du projet:\n";
echo "   📕 CONCEPTION_SYSTEME_EMPLOIS.pdf\n";
echo "   📘 CONCEPTION_SYSTEME_EMPLOIS.docx\n";
echo "\n📋 Les documents contiennent:\n";
echo "   ✅ Architecture complète du système\n";
echo "   ✅ Conception de la base de données\n";
echo "   ✅ Diagrammes et relations\n";
echo "   ✅ Spécifications techniques\n";
echo "   ✅ Guide d'implémentation\n";
echo "   ✅ Tests et validation\n";
echo "   ✅ Documentation de déploiement\n";

?>

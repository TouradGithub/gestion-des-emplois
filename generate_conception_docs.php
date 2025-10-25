<?php

require_once 'vendor/autoload.php';

use Mpdf\Mpdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;

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

    // Listes
    $html = preg_replace('/^- (.*$)/m', '<li>$1</li>', $html);
    $html = preg_replace('/(<li>.*<\/li>\n?)+/s', '<ul>$0</ul>', $html);

    // Blocs de code
    $html = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $html);

    // Paragraphes
    $html = preg_replace('/\n\n/', '</p><p>', $html);
    $html = '<p>' . $html . '</p>';

    // Nettoyer
    $html = str_replace('<p></p>', '', $html);

    return $html;
}

// Génération PDF avec mPDF
try {
    echo "🔄 Génération du fichier PDF...\n";

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 20,
        'margin_bottom' => 20,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);

    // Configuration PDF
    $mpdf->SetAuthor('Équipe Développement');
    $mpdf->SetTitle('Conception - Système de Gestion des Emplois du Temps');
    $mpdf->SetSubject('Document de Conception Technique');
    $mpdf->SetKeywords('Laravel, Emplois du temps, Conception, PHP');

    // CSS pour le PDF
    $css = '
    <style>
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
        }
        h1 {
            color: #2c3e50;
            font-size: 18pt;
            margin-top: 20pt;
            margin-bottom: 10pt;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5pt;
        }
        h2 {
            color: #34495e;
            font-size: 16pt;
            margin-top: 15pt;
            margin-bottom: 8pt;
        }
        h3 {
            color: #34495e;
            font-size: 14pt;
            margin-top: 12pt;
            margin-bottom: 6pt;
        }
        h4 {
            color: #7f8c8d;
            font-size: 12pt;
            margin-top: 10pt;
            margin-bottom: 5pt;
        }
        p {
            margin-bottom: 8pt;
            text-align: justify;
        }
        ul {
            margin-left: 15pt;
        }
        li {
            margin-bottom: 3pt;
        }
        code {
            background-color: #f8f9fa;
            padding: 2pt 4pt;
            font-family: "Courier New", monospace;
            font-size: 9pt;
        }
        pre {
            background-color: #f8f9fa;
            padding: 10pt;
            margin: 10pt 0;
            font-family: "Courier New", monospace;
            font-size: 9pt;
            border-left: 3px solid #3498db;
        }
        .page-break { page-break-before: always; }
        .center { text-align: center; }
        .highlight {
            background-color: #fff3cd;
            padding: 8pt;
            border-left: 4px solid #ffc107;
            margin: 10pt 0;
        }
    </style>';

    // Contenu HTML
    $htmlContent = $css . markdownToHtml($markdownContent);

    // Ajouter en-tête et pied de page
    $mpdf->SetHTMLHeader('
        <table width="100%">
            <tr>
                <td width="50%" style="color: #7f8c8d; font-size: 9pt;">
                    <strong>Système de Gestion des Emplois du Temps</strong>
                </td>
                <td width="50%" style="text-align: right; color: #7f8c8d; font-size: 9pt;">
                    Conception Technique v1.0
                </td>
            </tr>
        </table>
    ');

    $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td width="50%" style="color: #7f8c8d; font-size: 8pt;">
                    © 2024 - Document de Conception
                </td>
                <td width="50%" style="text-align: right; color: #7f8c8d; font-size: 8pt;">
                    Page {PAGENO} sur {nbpg}
                </td>
            </tr>
        </table>
    ');

    $mpdf->WriteHTML($htmlContent);
    $mpdf->Output('CONCEPTION_SYSTEME_EMPLOIS.pdf', 'F');

    echo "✅ Fichier PDF généré: CONCEPTION_SYSTEME_EMPLOIS.pdf\n";

} catch (Exception $e) {
    echo "❌ Erreur génération PDF: " . $e->getMessage() . "\n";
}

// Génération DOCX avec PhpWord
try {
    echo "🔄 Génération du fichier DOCX...\n";

    $phpWord = new PhpWord();

    // Configuration du document
    $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('fr-FR'));

    // Styles
    $phpWord->addFontStyle('titleStyle', array('bold' => true, 'size' => 18, 'color' => '2c3e50'));
    $phpWord->addFontStyle('heading1Style', array('bold' => true, 'size' => 16, 'color' => '34495e'));
    $phpWord->addFontStyle('heading2Style', array('bold' => true, 'size' => 14, 'color' => '34495e'));
    $phpWord->addFontStyle('heading3Style', array('bold' => true, 'size' => 12, 'color' => '7f8c8d'));
    $phpWord->addFontStyle('normalStyle', array('size' => 11));
    $phpWord->addFontStyle('codeStyle', array('name' => 'Courier New', 'size' => 9));

    $phpWord->addParagraphStyle('centerStyle', array('alignment' => 'center'));
    $phpWord->addParagraphStyle('justifyStyle', array('alignment' => 'both'));

    // Ajouter une section
    $section = $phpWord->addSection();

    // Page de titre
    $section->addText('CONCEPTION DU SYSTÈME DE GESTION DES EMPLOIS DU TEMPS', 'titleStyle', 'centerStyle');
    $section->addTextBreak(2);

    $section->addText('Document de Conception Technique', 'heading1Style', 'centerStyle');
    $section->addTextBreak(1);

    $section->addText('Version 1.0', 'normalStyle', 'centerStyle');
    $section->addText('Octobre 2024', 'normalStyle', 'centerStyle');
    $section->addTextBreak(2);

    $section->addText('Framework: Laravel 11', 'normalStyle', 'centerStyle');
    $section->addText('Base de Données: MySQL', 'normalStyle', 'centerStyle');

    $section->addPageBreak();

    // Traitement du contenu Markdown
    $lines = explode("\n", $markdownContent);

    foreach ($lines as $line) {
        $line = trim($line);

        if (empty($line)) {
            $section->addTextBreak(1);
            continue;
        }

        // Titre niveau 1
        if (preg_match('/^# (.+)$/', $line, $matches)) {
            $section->addTextBreak(1);
            $section->addText($matches[1], 'heading1Style');
            $section->addTextBreak(1);
        }
        // Titre niveau 2
        elseif (preg_match('/^## (.+)$/', $line, $matches)) {
            $section->addTextBreak(1);
            $section->addText($matches[1], 'heading2Style');
        }
        // Titre niveau 3
        elseif (preg_match('/^### (.+)$/', $line, $matches)) {
            $section->addText($matches[1], 'heading3Style');
        }
        // Liste
        elseif (preg_match('/^- (.+)$/', $line, $matches)) {
            $section->addListItem($matches[1], 0, 'normalStyle');
        }
        // Texte normal (ignorer les blocs de code pour simplifier)
        elseif (!preg_match('/^```/', $line) && !preg_match('/^\s*$/', $line) && !preg_match('/^---/', $line)) {
            // Nettoyer le markdown simple
            $text = preg_replace('/\*\*(.*?)\*\*/', '$1', $line);
            $text = preg_replace('/\*(.*?)\*/', '$1', $text);
            $text = preg_replace('/`(.*?)`/', '$1', $text);

            if (!empty($text)) {
                $section->addText($text, 'normalStyle', 'justifyStyle');
            }
        }
    }

    // Sauvegarder le document DOCX
    $writer = IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save('CONCEPTION_SYSTEME_EMPLOIS.docx');

    echo "✅ Fichier DOCX généré: CONCEPTION_SYSTEME_EMPLOIS.docx\n";

} catch (Exception $e) {
    echo "❌ Erreur génération DOCX: " . $e->getMessage() . "\n";
}

echo "\n🎉 Génération des documents terminée!\n";
echo "📄 Fichiers créés:\n";
echo "   - CONCEPTION_SYSTEME_EMPLOIS.pdf\n";
echo "   - CONCEPTION_SYSTEME_EMPLOIS.docx\n";

?>

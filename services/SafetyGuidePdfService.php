<?php
/**
 * Generate downloadable safety guide PDFs using FPDF.
 */
class SafetyGuidePdfService
{
    public static function outputDownload(string $slug): void
    {
        require_once ROOT_PATH . 'lib/fpdf/fpdf.php';
        require_once ROOT_PATH . 'models/safety_guides_data.php';

        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));
        $guides = get_safety_guides_catalog();
        if ($slug === '' || !isset($guides[$slug])) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=UTF-8');
            echo 'Guide not found.';
            exit;
        }

        $g = $guides[$slug];
        $pdf = new FPDF();
        $pdf->SetMargins(18, 18, 18);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->MultiCell(0, 8, self::enc('BuligDiretso — Safety Guide'), 0, 'L');
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->MultiCell(0, 5, self::enc($g['category'] . ' · ' . ($g['read'] ?? '')), 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(4);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->MultiCell(0, 7, self::enc($g['title']), 0, 'L');
        $pdf->Ln(3);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->MultiCell(0, 5, self::enc($g['intro']), 0, 'L');
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->MultiCell(0, 6, self::enc('Step-by-step instructions'), 0, 'L');
        $pdf->Ln(1);
        $pdf->SetFont('Helvetica', '', 10);
        foreach ($g['steps'] as $step) {
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(0, 5, self::enc($step['num'] . '. ' . $step['title']), 0, 'L');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->MultiCell(0, 5, self::enc($step['desc']), 0, 'L');
            $pdf->Ln(2);
        }
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->MultiCell(0, 6, self::enc('Important tips'), 0, 'L');
        $pdf->Ln(1);
        $pdf->SetFont('Helvetica', '', 10);
        foreach ($g['tips'] as $tip) {
            $pdf->MultiCell(0, 5, self::enc('• ' . $tip), 0, 'L');
        }
        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 4, self::enc('This guide is for education only. In an emergency, call local emergency services and use BuligDiretso to report.'), 0, 'L');

        $filename = 'BuligDiretso-' . $slug . '.pdf';
        while (ob_get_level()) {
            ob_end_clean();
        }
        $pdf->Output('D', $filename);
        exit;
    }

    private static function enc(string $text): string
    {
        $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
        if ($converted === false) {
            $converted = @utf8_decode($text);
            if ($converted === false) {
                $converted = $text;
            }
        }
        $converted = str_replace('�', '?', $converted);
        $converted = preg_replace('/[^\t\n\r\x20-\xFF]/', '?', $converted);
        if ($converted === null) {
            $converted = $text;
        }
        return (string)$converted;
    }
}

<?php
/**
 * Generate downloadable reports PDF using FPDF.
 */
class ReportPdfService
{
    public static function outputDownload(): void
    {
        require_once ROOT_PATH . 'lib/fpdf/fpdf.php';
        require_once ROOT_PATH . 'models/chart_data.php';

        $pdf = new FPDF();
        $pdf->SetMargins(18, 18, 18);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        // Title
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->MultiCell(0, 8, self::enc('BuligDiretso - Reports & Analytics'), 0, 'L');
        $pdf->Ln(5);

        // Date
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->MultiCell(0, 5, self::enc('Generated on: ' . date('F j, Y \a\t g:i A')), 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(5);

        // Summary Statistics
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->MultiCell(0, 6, self::enc('Summary Statistics'), 0, 'L');
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 10);
        $stats = [
            'Total Emergencies: 247 (+12% this year)',
            'Resolved Cases: 185 (74.9% resolution rate)',
            'Average Response Time: 3.4 minutes (Best month on record)',
            'Total Responders: 23 (3 added this month)'
        ];

        foreach ($stats as $stat) {
            $pdf->MultiCell(0, 5, self::enc('• ' . $stat), 0, 'L');
        }
        $pdf->Ln(5);

        // Charts Data with fallback hardcoded data
        $charts = [
            'monthly_volume' => [
                'title' => 'Monthly Emergency Volume',
                'fallback' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    'datasets' => [
                        ['label' => 'Medical', 'data' => [18, 22, 19, 25, 30, 28, 35, 32, 28, 24, 20, 18]],
                        ['label' => 'Fire', 'data' => [8, 10, 12, 9, 14, 18, 20, 15, 11, 13, 9, 7]],
                        ['label' => 'Accident', 'data' => [12, 15, 18, 20, 22, 25, 28, 24, 19, 16, 14, 11]]
                    ]
                ]
            ],
            'type_distribution' => [
                'title' => 'Emergency Type Distribution',
                'fallback' => [
                    'labels' => ['Medical', 'Fire', 'Accident', 'Other'],
                    'datasets' => [
                        ['label' => 'Types', 'data' => [42, 28, 18, 12]]
                    ]
                ]
            ],
            'response_time_trend' => [
                'title' => 'Response Time Trend',
                'fallback' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'datasets' => [
                        ['label' => 'Avg Response Time (min)', 'data' => [4.2, 3.8, 4.1, 3.5, 3.2, 3.4]]
                    ]
                ]
            ],
            'status_breakdown' => [
                'title' => 'Status Breakdown',
                'fallback' => [
                    'labels' => ['Resolved', 'Pending', 'In Progress', 'Cancelled'],
                    'datasets' => [
                        ['label' => 'Status', 'data' => [185, 45, 12, 5]]
                    ]
                ]
            ],
            'peak_hours' => [
                'title' => 'Peak Hours',
                'fallback' => [
                    'labels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                    'datasets' => [
                        ['label' => 'Emergencies', 'data' => [5, 8, 25, 35, 28, 15]]
                    ]
                ]
            ]
        ];

        foreach ($charts as $chartKey => $chartInfo) {
            $pdf->SetFont('Helvetica', 'B', 12);
            $pdf->MultiCell(0, 6, self::enc($chartInfo['title']), 0, 'L');
            $pdf->Ln(2);

            $chartData = ChartData::getChartJs($chartKey);
            if (empty($chartData['datasets']) || empty($chartData['labels'])) {
                $chartData = $chartInfo['fallback'];
            }

            if ($chartData && isset($chartData['datasets'])) {
                $pdf->SetFont('Helvetica', '', 9);

                // For each dataset
                foreach ($chartData['datasets'] as $dataset) {
                    if (isset($dataset['label'])) {
                        $pdf->SetFont('Helvetica', 'B', 9);
                        $pdf->MultiCell(0, 4, self::enc($dataset['label']), 0, 'L');
                        $pdf->SetFont('Helvetica', '', 9);
                    }

                    // Create a simple table with labels and values
                    if (isset($chartData['labels']) && isset($dataset['data'])) {
                        $labels = $chartData['labels'];
                        $data = $dataset['data'];

                        for ($i = 0; $i < count($labels) && $i < count($data); $i++) {
                            $pdf->MultiCell(0, 4, self::enc('  ' . $labels[$i] . ': ' . $data[$i]), 0, 'L');
                        }
                    }
                    $pdf->Ln(2);
                }
            }
            $pdf->Ln(3);
        }

        // Responder Performance
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->MultiCell(0, 6, self::enc('Responder Performance'), 0, 'L');
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', '', 9);
        $responders = [
            ['Ana Cruz', '58 responses', '2.1 min avg', '98% resolution', '4.9 rating'],
            ['Ben Santos', '51 responses', '2.6 min avg', '95% resolution', '4.7 rating'],
            ['Clara Reyes', '47 responses', '3.0 min avg', '92% resolution', '4.5 rating'],
            ['Mike Johnson', '42 responses', '2.8 min avg', '88% resolution', '4.3 rating'],
            ['Jane Smith', '38 responses', '4.1 min avg', '84% resolution', '4.1 rating']
        ];

        foreach ($responders as $responder) {
            $pdf->MultiCell(0, 4, self::enc('• ' . $responder[0] . ': ' . implode(', ', array_slice($responder, 1))), 0, 'L');
        }

        // Output PDF
        $filename = 'BuligDiretso_Reports_' . date('Y-m-d_H-i-s') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }

    private static function enc(string $str): string
    {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
    }
}
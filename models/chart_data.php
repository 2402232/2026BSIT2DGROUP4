<?php
/**
 * ChartData model
 * Handles chart_datasets and chart_data_points tables.
 */

if (!function_exists('db')) {
    require_once dirname(__DIR__) . '/config/config.php';
}

class ChartData {

    /**
     * Return all datasets, ordered by parent_chart + display_order.
     */
    public static function getAllDatasets(): array {
        $pdo  = db();
        $stmt = $pdo->query(
            "SELECT * FROM chart_datasets ORDER BY parent_chart, display_order, id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return all datasets grouped by parent_chart key.
     * [ 'monthly_volume' => [ dataset, dataset ], … ]
     */
    public static function getDatasetsGrouped(): array {
        $rows   = self::getAllDatasets();
        $groups = [];
        foreach ($rows as $row) {
            $groups[$row['parent_chart']][] = $row;
        }
        return $groups;
    }

    /**
     * Return all data points for a dataset, ordered by display_order.
     */
    public static function getPoints(int $dataset_id): array {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "SELECT * FROM chart_data_points WHERE dataset_id = ? ORDER BY display_order, id"
        );
        $stmt->execute([$dataset_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return full chart data for a parent_chart key as a Chart.js-ready structure.
     * Returns: { labels:[], datasets:[{ label, data:[], backgroundColor, borderColor }] }
     */
    public static function getChartJs(string $parent_chart): array {
        $pdo = db();

        // Get all datasets for this chart
        $stmt = $pdo->prepare(
            "SELECT * FROM chart_datasets WHERE parent_chart = ? ORDER BY display_order, id"
        );
        $stmt->execute([$parent_chart]);
        $datasets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($datasets)) return ['labels' => [], 'datasets' => []];

        // Get labels from first dataset (they share labels across series)
        $firstPoints = self::getPoints((int)$datasets[0]['id']);
        $labels      = array_column($firstPoints, 'label');

        $chartDatasets = [];
        foreach ($datasets as $ds) {
            $points = self::getPoints((int)$ds['id']);
            $values = array_map(fn($p) => (float)$p['value'], $points);

            $entry = [
                'label'           => $ds['dataset_label'],
                'data'            => $values,
                'backgroundColor' => $ds['color'],
                'borderColor'     => $ds['color'],
                'borderRadius'    => 4,
                'tension'         => 0.4,
                'fill'            => false,
            ];
            // For line charts, add fill
            if ($ds['chart_type'] === 'line') {
                $entry['fill']            = false;
                $entry['backgroundColor'] = self::hexToRgba($ds['color'], 0.08);
                $entry['pointRadius']     = 4;
                $entry['pointBackgroundColor'] = $ds['color'];
            }
            // For doughnut, backgroundColor is array of colors per point
            if ($ds['chart_type'] === 'doughnut' || $ds['chart_type'] === 'pie') {
                $colors = array_map(fn($p) => $p['point_color'] ?: $ds['color'], $points);
                $entry['backgroundColor'] = $colors;
                $entry['borderWidth']     = 2;
                $entry['borderColor']     = '#fff';
            }
            $chartDatasets[] = $entry;
        }

        return [
            'labels'   => $labels,
            'datasets' => $chartDatasets,
            'type'     => $datasets[0]['chart_type'],
        ];
    }

    private static function hexToRgba(string $hex, float $alpha): string {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        return "rgba($r,$g,$b,$alpha)";
    }

    /**
     * Upsert a data point (insert or update by id).
     * $data: ['id'=>int|null, 'dataset_id'=>int, 'label'=>str, 'value'=>float, 'display_order'=>int, 'point_color'=>str|null]
     */
    public static function savePoint(array $data): int {
        $pdo = db();
        if (!empty($data['id'])) {
            $stmt = $pdo->prepare(
                "UPDATE chart_data_points
                 SET label=?, value=?, display_order=?, point_color=?, updated_at=NOW()
                 WHERE id=? AND dataset_id=?"
            );
            $stmt->execute([
                $data['label'],
                $data['value'],
                $data['display_order'] ?? 0,
                $data['point_color'] ?? null,
                (int)$data['id'],
                (int)$data['dataset_id'],
            ]);
            return (int)$data['id'];
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO chart_data_points (dataset_id, label, value, display_order, point_color)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                (int)$data['dataset_id'],
                $data['label'],
                $data['value'],
                $data['display_order'] ?? 0,
                $data['point_color'] ?? null,
            ]);
            return (int)$pdo->lastInsertId();
        }
    }

    /**
     * Delete a data point by id.
     */
    public static function deletePoint(int $id): bool {
        $pdo  = db();
        $stmt = $pdo->prepare("DELETE FROM chart_data_points WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Replace ALL points for a dataset in one transaction.
     * $rows: [['label'=>str, 'value'=>float, 'point_color'=>str|null], …]
     */
    public static function replacePoints(int $dataset_id, array $rows): bool {
        $pdo = db();
        try {
            $pdo->beginTransaction();

            // Delete existing
            $pdo->prepare("DELETE FROM chart_data_points WHERE dataset_id = ?")
                ->execute([$dataset_id]);

            // Re-insert
            $stmt = $pdo->prepare(
                "INSERT INTO chart_data_points (dataset_id, label, value, display_order, point_color)
                 VALUES (?, ?, ?, ?, ?)"
            );
            foreach ($rows as $i => $row) {
                $stmt->execute([
                    $dataset_id,
                    $row['label'],
                    (float)($row['value'] ?? 0),
                    $i,
                    $row['point_color'] ?? null,
                ]);
            }

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            $pdo->rollBack();
            error_log("ChartData::replacePoints error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update dataset meta (label, color, chart_type).
     */
    public static function updateDataset(int $id, array $data): bool {
        $pdo  = db();
        $stmt = $pdo->prepare(
            "UPDATE chart_datasets SET dataset_label=?, color=?, chart_type=?, updated_at=NOW() WHERE id=?"
        );
        return $stmt->execute([
            $data['dataset_label'],
            $data['color'],
            $data['chart_type'],
            $id,
        ]);
    }
}

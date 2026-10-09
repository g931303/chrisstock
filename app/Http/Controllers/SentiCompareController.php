<?php

namespace App\Http\Controllers;

class SentiCompareController extends Controller
{
    // written by /home/chris/AI_project/compare_senti.py
    private string $dataDir = '/home/chris/AI_project/results/SENTI_COMPARE';

    private const LOWER_IS_BETTER = [
        'MAPE %'      => true,
        'MAE'         => true,
        'RMSE'        => true,
        'R2'          => false,
        'Direction %' => false,
    ];

    // JSON for the index page panel: latest before/after summary for one stock
    public function data(string $stock)
    {
        $prefix = $this->latestPrefix($stock);
        if ($prefix === null) {
            return response()->json(['stock' => $stock, 'error' => "No sentiment comparison yet for {$stock}."], 404);
        }

        $models = [];
        foreach ($this->readCsv("{$prefix}_summary.csv") as $row) {
            $change = (float) $row['change'];
            $lower  = self::LOWER_IS_BETTER[$row['metric']] ?? true;
            $models[$row['model']][$row['subset']][] = [
                'metric'         => $row['metric'],
                'before'         => (float) $row['before'],
                'after'          => (float) $row['after'],
                'change'         => $change,
                'seeds_improved' => $row['seeds_improved'],
                'verdict'        => $change == 0 ? 'same' : (($lower ? $change < 0 : $change > 0) ? 'better' : 'worse'),
                // naive "tomorrow = today" (Direction %: 50% coin flip); null for runs made before it existed
                'baseline'       => isset($row['baseline']) && $row['baseline'] !== '' ? (float) $row['baseline'] : null,
                'before_beats'   => $row['before_beats_baseline'] ?? null,
                'after_beats'    => $row['after_beats_baseline'] ?? null,
            ];
        }

        $preds    = $this->readCsv("{$prefix}_predictions.csv");
        $newsDays = count(array_filter($preds, fn($r) => strtolower($r['news_input_day']) === 'true'));

        return response()->json([
            'stock'      => $stock,
            'run_date'   => substr(basename($prefix), 0, 8),
            'test_start' => $preds[0]['date'] ?? null,
            'test_end'   => end($preds)['date'] ?? null,
            'test_days'  => count($preds),
            'news_days'  => $newsDays,
            'models'     => $models,
            'plot_url'   => route('senti_compare.plot', ['stock' => $stock]),
        ]);
    }

    // the PNG lives outside public/, so serve it through Laravel
    public function plot(string $stock)
    {
        $prefix = $this->latestPrefix($stock);
        if ($prefix === null || !file_exists("{$prefix}_plot.png")) {
            abort(404);
        }
        return response()->file("{$prefix}_plot.png", ['Cache-Control' => 'no-cache']);
    }

    // newest run for this stock, e.g. .../20261003_2330_senti_compare
    private function latestPrefix(string $stock): ?string
    {
        $files = glob("{$this->dataDir}/*_{$stock}_senti_compare_summary.csv");
        if (empty($files)) {
            return null;
        }
        sort($files);   // names start with YYYYMMDD, so the last one is the newest
        return substr(end($files), 0, -strlen('_summary.csv'));
    }

    private function readCsv(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }
        $data   = array_map('str_getcsv', file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        $header = array_map('trim', array_shift($data));
        $rows   = [];
        foreach ($data as $row) {
            $row    = array_pad(array_map('trim', $row), count($header), null);
            $rows[] = array_combine($header, $row);
        }
        return $rows;
    }
}

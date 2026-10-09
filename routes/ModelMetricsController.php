<?php

namespace App\Http\Controllers;

class ModelMetricsController extends Controller
{
    // last finished run per stock, written by process.py (step 7) once all four models are done
    private string $snapshotPath = '/home/chris/AI_project/model_performance.txt';
    // live scores written by metrics_shared.py as each model finishes; fallback for a stock not in the snapshot yet
    private string $metricsPath = '/home/chris/AI_project/model_metrics.json';

    private const MODELS = [
        'lstm'      => 'LSTM',
        'cnn_lstm'  => 'CNN_LSTM',
        'gru'       => 'GRU',
        'gru_keras' => 'GRU_Plus',
    ];

    // JSON for the index page panel: test-set scores of the 4 models for one stock, plus the best fit
    public function data(string $stock)
    {
        $snapshot = $this->readJson($this->snapshotPath)[$stock] ?? null;
        $entries  = $snapshot['models'] ?? ($this->readJson($this->metricsPath)[$stock] ?? []);
        $runTime  = $snapshot['run_time'] ?? null;

        $models = [];
        foreach (self::MODELS as $key => $label) {
            if (!isset($entries[$key])) {
                continue;
            }
            $m = $entries[$key];
            $models[] = [
                'key'         => $key,
                'model'       => $label,
                'mape'        => $m['mape'] ?? null,
                'mae'         => $m['mae'] ?? null,     // null for runs made before MAE was saved
                'rmse'        => $m['rmse'] ?? null,
                'r2'          => $m['r2'] ?? null,
                'naive_mape'  => $m['naive_mape'] ?? null,
                'beats_naive' => $m['beats_naive'] ?? null,
                'direction'   => $m['direction'] ?? null,
                'always'      => $m['always'] ?? null,
                'n_days'      => $m['n_days'] ?? null,
                'dir_skill'   => $this->directionSkill($m),
                'updated'     => $m['updated'] ?? null,
                'data_date'   => $m['data_date'] ?? ($m['updated'] ?? null),
                // the same scores from the data date before the newest one (metrics_shared keeps one)
                'previous'    => isset($m['previous']) ? [
                    'mape'      => $m['previous']['mape'] ?? null,
                    'mae'       => $m['previous']['mae'] ?? null,
                    'rmse'      => $m['previous']['rmse'] ?? null,
                    'r2'        => $m['previous']['r2'] ?? null,
                    'direction' => $m['previous']['direction'] ?? null,
                    'data_date' => $m['previous']['data_date'] ?? ($m['previous']['updated'] ?? null),
                ] : null,
            ];
        }

        if (empty($models)) {
            return response()->json(['stock' => $stock, 'error' => "No model scores yet for {$stock}."], 404);
        }

        // best fit for this stock = lowest one-day-ahead error on the test days
        $scored = array_filter($models, fn($m) => $m['mape'] !== null);
        usort($scored, fn($a, $b) => $a['mape'] <=> $b['mape']);
        $best = $scored[0] ?? null;

        return response()->json([
            'stock'    => $stock,
            'run_time' => $runTime,
            'models'   => $models,
            'best'   => $best ? [
                'model'     => $best['model'],
                'mape'      => $best['mape'],
                // real skill = beats "tomorrow = today" on error AND calls up/down better than the "always" guess beyond luck
                'has_skill' => $best['beats_naive'] === true && $best['dir_skill'] === true,
            ] : null,
        ]);
    }

    // JSON for the animated "prediction vs real" chart: the last test days of each model, lined up by date
    // (results/BACKTEST/{stock}_{model}.json, written by metrics_shared.save_backtest after each training run)
    public function backtest(string $stock)
    {
        $newest = $this->backtestSeries($stock, '');
        if ($newest === null) {
            return response()->json(['stock' => $stock, 'error' => "No day-by-day test results yet for {$stock}."], 404);
        }
        // the run on the data date before the newest one ({stock}_{model}_prev.json), for the comparison
        $newest['stock']    = $stock;
        $newest['previous'] = $this->backtestSeries($stock, '_prev');
        return response()->json($newest);
    }

    // one run's saved test days for all models, lined up on the dates every model has; null when none saved
    private function backtestSeries(string $stock, string $suffix): ?array
    {
        $series = [];
        foreach (self::MODELS as $key => $label) {
            $s = $this->readJson("/home/chris/AI_project/results/BACKTEST/{$stock}_{$key}{$suffix}.json");
            if (!empty($s['date'])) {
                $series[$key] = $s;
            }
        }
        if (empty($series)) {
            return null;
        }

        // keep only the days every saved model has, so all lines share one date axis
        $dates = array_reduce(array_values($series), fn($keep, $s) => $keep === null ? $s['date'] : array_values(array_intersect($keep, $s['date'])));
        $first = reset($series);
        $byDate = fn($s, $field) => array_combine($s['date'], $s[$field]);
        $real  = $byDate($first, 'real');
        $prev  = $byDate($first, 'prev');

        $models = [];
        foreach ($series as $key => $s) {
            $pred = $byDate($s, 'pred');
            $models[] = ['key' => $key, 'model' => self::MODELS[$key], 'pred' => array_map(fn($d) => $pred[$d], $dates)];
        }

        return [
            'dates'  => $dates,
            'real'   => array_map(fn($d) => $real[$d], $dates),
            'prev'   => array_map(fn($d) => $prev[$d], $dates),   // the naive guess: tomorrow = today
            'models' => $models,
        ];
    }

    private function readJson(string $path): array
    {
        if (!is_readable($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true) ?: [];
    }

    // true when the direction % beats the "always the more common move" guess by more than luck would
    // (one-sided binomial test, normal approximation, p < 0.05); null when the score predates these fields
    private function directionSkill(array $m): ?bool
    {
        if (!isset($m['direction'], $m['always'], $m['n_days']) || $m['n_days'] < 1) {
            return null;
        }
        $p  = $m['always'] / 100;
        $se = sqrt($p * (1 - $p) / $m['n_days']);
        return $se > 0 && ($m['direction'] / 100 - $p) / $se > 1.645;
    }
}

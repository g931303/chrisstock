<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CnnLstmController extends Controller
{
    public function cnn_lstm_cal(Request $request)
    {
        $stock   = $request->input('stock_number');
        $dataDir = '/home/chris/AI_project/results/CNN_LSTM';

        // find the newest result file matching {date}_{stock}_cnn_lstm_result.csv
        $pattern = "{$dataDir}/*_{$stock}_cnn_lstm_result.csv";
        $files   = glob($pattern);

        if (empty($files)) {
            return view('cnn_lstm', ['list' => [], 'error' => "No result found for {$stock}.", 'stock' => $stock]);
        }

        // sort by file modification time descending to get the newest file
        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
        $csvPath = $files[0];

        $list = $this->readResultCsv($csvPath);
        return view('cnn_lstm', ['list' => $list, 'file' => basename($csvPath), 'stock' => $stock]);
    }

    private function readResultCsv(string $path): array
    {
        $rows   = array_map('str_getcsv', file($path));
        $header = array_shift($rows); // remove header row
        $prices = [];
        foreach ($rows as $row) {
            if (isset($row[1])) {
                $prices[] = trim($row[1]); // 'price' column
            }
        }
        return $prices;
    }
}

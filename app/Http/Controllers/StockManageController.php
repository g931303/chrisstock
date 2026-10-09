<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockManageController extends Controller
{
    private string $listPath;

    public function __construct()
    {
        $this->listPath = storage_path('app/stock_list.json');
    }

    private function loadStocks(): array
    {
        if (!file_exists($this->listPath)) {
            $default = ['2317', '2330', '2353', '2357'];
            file_put_contents($this->listPath, json_encode($default));
            return $default;
        }
        $stocks = json_decode(file_get_contents($this->listPath), true);
        return is_array($stocks) ? $stocks : [];
    }

    private function saveStocks(array $stocks): void
    {
        file_put_contents($this->listPath, json_encode(array_values($stocks)));
    }

    public function manage()
    {
        return view('manage', ['stocks' => $this->loadStocks()]);
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'stock_number' => 'required|digits:4',
        ]);

        $stocks = $this->loadStocks();
        $new    = $request->input('stock_number');

        if (!in_array($new, $stocks, true)) {
            $stocks[] = $new;
            $this->saveStocks($stocks);
        }

        return redirect()->route('manage.index')->with('status', "Added stock {$new}.");
    }

    public function deleteStock(Request $request)
    {
        $request->validate([
            'stock_number' => 'required',
        ]);

        $stocks = $this->loadStocks();
        $target = $request->input('stock_number');
        $stocks = array_filter($stocks, fn($s) => $s !== $target);
        $this->saveStocks($stocks);

        return redirect()->route('manage.index')->with('status', "Removed stock {$target}.");
    }
}

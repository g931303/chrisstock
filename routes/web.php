 
<?php
/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

use Illuminate\Http\Request;

Route::get('/','StocksController@index');

Route::post('/lstm', [StocksController::class, 'lstm_cal'])->name('lstm.lstm_cal');


Route::get('cnn_lstm', 'StocksController@cnn_lstm_cal')->name('cnn_lstm.cnn_lstm_cal');

Route::get('gru', 'StocksController@gru_cal')->name('gru.gru_cal');

Route::get('gru_plus', 'StocksController@gru_keras_cal')->name('gru_keras.gru_keras_cal');
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LstmController;      // ← missing?
use App\Http\Controllers\CnnLstmController;   // ← missing?
use App\Http\Controllers\GruController;       // ← missing?
use App\Http\Controllers\GruKerasController;  // ← missing?

Route::post('/lstm', [LstmController::class, 'lstm_cal'])->name('lstm.lstm_cal');
Route::post('/cnn_lstm', [CnnLstmController::class, 'cnn_lstm_cal'])->name('cnn_lstm.cnn_lstm_cal');
Route::post('/gru', [GruController::class, 'gru_cal'])->name('gru.gru_cal');
Route::post('/gru_keras', [GruKerasController::class, 'gru_keras_cal'])->name('gru_keras.gru_keras_cal');
 */



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LstmController;
use App\Http\Controllers\CnnLstmController;
use App\Http\Controllers\GruController;
use App\Http\Controllers\GruKerasController;
use App\Http\Controllers\StockManageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SentiCompareController;
use App\Http\Controllers\ModelMetricsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $counterFile = storage_path('app/visit_counter.txt');
    if (!file_exists($counterFile)) {
        file_put_contents($counterFile, 0);
    }
    $visitCount = (int) file_get_contents($counterFile) + 1;
    file_put_contents($counterFile, $visitCount);

    $stockListFile = storage_path('app/stock_list.json');
    if (!file_exists($stockListFile)) {
        file_put_contents($stockListFile, json_encode(['2317', '2330', '2353', '2357']));
    }
    $stocks = json_decode(file_get_contents($stockListFile), true) ?: [];

    // Music for the navbar Audio menu: any audio file dropped into public/audio
    // Keyed by file name, value = modified time, so a replaced file gets a new URL and browsers don't reuse a stale cached copy
    // Any upper/lower case extension; unfinished uploads (e.g. "song.mp3.filepart") are skipped
    $tracks = collect(glob(public_path('audio') . '/*') ?: [])
        ->filter(fn ($path) => is_file($path) && preg_match('/\.(mp3|m4a|aac|ogg|oga|opus|wav|flac|webm)$/i', $path))
        // substr instead of basename(): basename() is locale-dependent and can drop Chinese characters
        ->mapWithKeys(fn ($path) => [substr($path, strrpos($path, '/') + 1) => filemtime($path)])
        ->sortKeys(SORT_NATURAL | SORT_FLAG_CASE)
        ->all();

    return view('index', ['visitCount' => $visitCount, 'stocks' => $stocks, 'tracks' => $tracks]);
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Stock list management (protected)
Route::middleware('admin.auth')->group(function () {
    Route::get('/manage', [StockManageController::class, 'manage'])->name('manage.index');
    Route::post('/manage/add', [StockManageController::class, 'addStock'])->name('manage.add');
    Route::post('/manage/delete', [StockManageController::class, 'deleteStock'])->name('manage.delete');
});

// LSTM
Route::post('/lstm', [LstmController::class, 'lstm_cal'])->name('lstm.lstm_cal');

// CNN_LSTM
Route::post('/cnn_lstm', [CnnLstmController::class, 'cnn_lstm_cal'])->name('cnn_lstm.cnn_lstm_cal');

// GRU
Route::post('/gru', [GruController::class, 'gru_cal'])->name('gru.gru_cal');

// GRU_Plus
Route::post('/gru_keras', [GruKerasController::class, 'gru_keras_cal'])->name('gru_keras.gru_keras_cal');

// Test-set scores of the 4 models (model_metrics.json)
Route::get('/model-metrics/{stock}', [ModelMetricsController::class, 'data'])
    ->where('stock', '[0-9]+')->name('model_metrics.data');
Route::get('/model-backtest/{stock}', [ModelMetricsController::class, 'backtest'])
    ->where('stock', '[0-9]+')->name('model_metrics.backtest');

// Sentiment before vs after (results of compare_senti.py)
Route::get('/senti-compare/{stock}', [SentiCompareController::class, 'data'])
    ->where('stock', '[0-9]+')->name('senti_compare.data');
Route::get('/senti-compare/{stock}/plot', [SentiCompareController::class, 'plot'])
    ->where('stock', '[0-9]+')->name('senti_compare.plot');











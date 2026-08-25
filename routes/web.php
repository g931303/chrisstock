 
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
    return view('index', ['visitCount' => $visitCount]);
});

// LSTM
Route::post('/lstm', [LstmController::class, 'lstm_cal'])->name('lstm.lstm_cal');

// CNN_LSTM
Route::post('/cnn_lstm', [CnnLstmController::class, 'cnn_lstm_cal'])->name('cnn_lstm.cnn_lstm_cal');

// GRU
Route::post('/gru', [GruController::class, 'gru_cal'])->name('gru.gru_cal');

// GRU_Plus
Route::post('/gru_keras', [GruKerasController::class, 'gru_keras_cal'])->name('gru_keras.gru_keras_cal');











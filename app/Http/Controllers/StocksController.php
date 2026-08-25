<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class StocksController extends Controller
{
   public function index()
    {
        return view('index');
    }
   public function lstm_cal(Request $request) 
   {
    $stock = $request->input('stock_number');
    $process = new Process(['python3', '/home/chris/AI_project/LSTM/lstm.py', $stock]);                  
    $process->setTimeout(120000);  // 600 seconds
    $process->run();
    if (!$process->isSuccessful()) {
         throw new ProcessFailedException($process);
    }
    $list=explode(" ",$process->getOutput());
     return view('lstm', ['list' => $list]);     
   }
   public function cnn_lstm_cal(Request $request)
   {
    $stock = $request->input('stock_number');
    $process =new Proess(['python3', '/home/chris/AI_project/CNN_LSTM/cnn_lstm.py', $stock]);	       
    $process->setTimeout(120000);  // 600 seconds
    $process->run();
    if (!$process->isSuccessful()) {
         throw new ProcessFailedException($process);
    }
    $list=explode(" ",$process->getOutput());
     return view('cnn_lstm', ['list' => $list]);    
   }
   public function gru_cal(Request $request)
   {
    $stock = $request->input('stock_number');  
    $process = new Process(['python3','/home/chris/AI_project/GRU/gru.py',$stock]);
    $process->setTimeout(120000);  // 600 seconds
    $process->run();
    if (!$process->isSuccessful()) {
         throw new ProcessFailedException($process);
    }
    $list=explode(" ",$process->getOutput());
    return view('gru', ['list' => $list]);
   }
   public function gru_kera_calRequest $request()
   {
    $stock = $request->input('stock_number');
    $process = new Process(['python3','/home/chris/AI_project/GRU/gru_keras.py',$stock]);
    $process->setTimeout(120000);  // 600 seconds
    $process->run();
    if (!$process->isSuccessful()) {
         throw new ProcessFailedException($process);
    }
    $list=explode(" ",$process->getOutput());
    return view('gru_keras', ['list' => $list]);
   }
}

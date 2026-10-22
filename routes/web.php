<?php
use App\Http\Controllers\Mensagemcontroller;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROTA COM RETONO DIRETO

Route::get('/opa' ,[Mensagemcontroller::class, 'mostrarMensagem']
);

Route::get('/mensagem',
[Mensagemcontroller::class, 'enviarMensagem']
);
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Mensagemcontroller extends Controller
{
    public function mostrarMensagem(){
        return 'fala, cuiudo';
    }

    public function enviarMensagem(){
        $mensagem = 'fala, cuiudo de novo';

        return view('mensagens.index', ['texteo' => $mensagem ]);
    }
}

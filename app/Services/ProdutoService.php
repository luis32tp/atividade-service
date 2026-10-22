<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Support\Facades\Validator;

class ProdutoService
{
    public function create(array $dados): Produto
    {
        return Produto::create($this->validar($dados));
    }

    public function update(int $id, array $dados): Produto
    {
        $produto = Produto::findOrFail($id);

        $dadosValidados = $this->validar(array_merge(
            $produto->only(['nome', 'preco']),
            $dados,
        ));

        $produto->update($dadosValidados);

        return $produto;
    }

    private function validar(array $dados): array
    {
        return Validator::make($dados, [
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
        ], [
            'preco.gt' => 'O preço deve ser maior que zero.',
            'preco.decimal' => 'O preço deve ter no máximo duas casas decimais.',
        ])->validate();
    }
}

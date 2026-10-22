<?php

namespace Tests\Feature;

use App\Models\Produto;
use App\Services\ProdutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProdutoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_cria_produto_com_preco_invalido(): void
    {
        $service = new ProdutoService;

        try {
            $service->create(['nome' => 'Caderno', 'preco' => 0]);
            $this->fail('O cadastro deveria rejeitar o preço inválido.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('preco', $exception->errors());
        }

        $this->assertDatabaseCount('produtos', 0);
        $this->assertDatabaseMissing('produtos', ['nome' => 'Caderno']);
    }

    public function test_atualiza_produto_com_dados_validos(): void
    {
        $service = new ProdutoService;
        $produto = $service->create(['nome' => 'Caderno', 'preco' => 20]);

        $atualizado = $service->update($produto->id, [
            'nome' => 'Caderno universitário',
            'preco' => 35.90,
        ]);

        $this->assertInstanceOf(Produto::class, $atualizado);
        $this->assertSame($produto->id, $atualizado->id);
        $this->assertSame('Caderno universitário', $atualizado->nome);
        $this->assertSame('35.90', $atualizado->preco);
        $this->assertSame('Caderno universitário', $produto->fresh()->nome);
        $this->assertSame('35.90', $produto->fresh()->preco);
        $this->assertDatabaseCount('produtos', 1);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Caderno universitário',
            'preco' => 35.90,
        ]);
    }

    public function test_nao_atualiza_produto_com_preco_invalido(): void
    {
        $service = new ProdutoService;
        $produto = $service->create(['nome' => 'Caderno', 'preco' => 20]);
        $dadosOriginais = $produto->fresh()->getAttributes();

        try {
            $service->update($produto->id, [
                'nome' => 'Nome que não deve ser salvo',
                'preco' => -10,
            ]);
            $this->fail('A atualização deveria rejeitar o preço inválido.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('preco', $exception->errors());
        }

        $this->assertSame($dadosOriginais, $produto->fresh()->getAttributes());
        $this->assertDatabaseCount('produtos', 1);
        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Caderno',
            'preco' => 20,
        ]);
        $this->assertDatabaseMissing('produtos', ['nome' => 'Nome que não deve ser salvo']);
    }
}

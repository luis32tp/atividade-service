<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nome', 'preco'])]
class Produto extends Model
{
    protected $table = 'produtos';

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
        ];
    }
}

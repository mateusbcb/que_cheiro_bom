<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'imagem',
        'ingredientes',
        'modo_preparo',
        'tempo_preparo',
        'qtd_porcao',
        'observacao',
        'categorias',
        'tipo',
    ];

    public function rules()
    {
        return [];
    }

    public function feedback()
    {
        return [];
    }
}

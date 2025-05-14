<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (fillable).
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'answer'
    ];
}
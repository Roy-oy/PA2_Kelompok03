<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthArticle extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_health_articles';

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'published_at',
        'id_admin',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }
}

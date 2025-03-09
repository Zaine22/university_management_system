<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chapter_ids', 
        'name',        
        'description', 
        'thumbnail', 
    ];

    protected $casts = [
        'chapter_ids' => 'array',
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
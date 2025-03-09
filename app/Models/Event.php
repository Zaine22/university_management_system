<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'batch_ids',
        'slug',
        'description',
        'images',
        'date',
        'place',
    ];

    protected $casts = ['batch_ids' => 'array', 'images' => 'array'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
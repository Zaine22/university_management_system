<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'relationship',
        'phone',
        'address',
        'profession',
        'income',
        'memberable_id',
        'memberable_type',
    ];

    public function memberable()
    {
        return $this->morphTo();
    }
}
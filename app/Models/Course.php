<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'price',
        'category',
        'description',
        'installable',
        'installment_price',
        'down_payment',
        'months',
        'monthly_payment_amount',
    ];

    protected $casts = [
        'installable'            => 'boolean',
        'price'                  => 'integer',
        'installment_price'      => 'integer',
        'monthly_payment_amount' => 'integer',
        'months'                 => 'integer',
    ];

    public static function getCourseType($id)
    {
        return Course::find($id)->type;
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class)->orderBy('id', 'DESC');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public static function getInstallableCourses()
    {
        return self::where('installable', true)->pluck('name', 'id');
    }

    // public static function getSelectPermission($model, $permissions = [])
    // {
    //     //use Spatie\Permission\Models\Permission;
    //     $selectedPermission = [];
    //     if ($permissions) {
    //         foreach ($permissions as $permission) {
    //             $add = Permission::where('name', 'LIKE', '%' . $model . '%')->where('name', 'LIKE', '%' . $permission . '%')->where('guard_name', 'web')->get();
    //             $selectedPermission[] = $add;
    //         }
    //         return $selectedPermission;
    //     }

    //     return Permission::where('name', 'LIKE', '%' . $model . '%')->where('guard_name', 'web')->get();
    // }

}
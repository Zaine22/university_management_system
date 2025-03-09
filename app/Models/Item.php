<?php
namespace App\Models;

use App\Enums\Departments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'assetable_id',
        'assetable_type',
        'item_id',
        'employee_id',
        'description',
        'department',
    ];

    protected $casts = [
        'department' => Departments::class,
    ];

    public function assetable(): MorphTo
    {
        return $this->morphTo();
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Assets::class);
    }

    protected function getLatestItemId()
    {
        return $this->whereNotNull('item_id')
            ->orderBy('id', 'desc')
            ->value('item_id');
    }

    public function generateUniqueItemId()
    {
        $latestItem = $this->getLatestItemId();

        if ($latestItem) {
            $latestNumber = (int) str_replace('RI-Item-', '', $latestItem);
            $newId        = $latestNumber + 1;
        } else {
            $newId = 500;
        }

        // Return the new Item ID with a prefix
        return 'RI-Item-' . $newId;
    }

    public static function deleteItem($record)
    {
        $record->assetable->count -= 1;
        $record->assetable->save();
        $record->delete();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
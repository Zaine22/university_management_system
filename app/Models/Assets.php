<?php
namespace App\Models;

use App\Enums\Departments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'asset_id',
        'count',
        'description',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function items()
    {
        return $this->morphMany(Item::class, 'assetable');
    }

    public function createItems()
    {
        for ($i = 0; $i < $this->count; $i++) {
            $item = new Item;
            $item->assetable()->associate($this);
            $item->item_id     = $item->generateUniqueItemId();
            $item->employee_id = null;
            $item->description = null;
            $item->department  = Departments::Property;
            $item->save();
        }
    }

    public function editItems()
    {
        $existingItems = $this->items()->get();   // Get the existing items associated with the asset
        $existingCount = $existingItems->count(); // Get the current count of items

        // If the count has increased, create new items
        if ($this->count > $existingCount) {
            $itemsToCreate = $this->count - $existingCount;
            for ($i = 0; $i < $itemsToCreate; $i++) {
                $item = new Item;
                $item->assetable()->associate($this);               // Associate the item with this asset
                $item->item_id     = $item->generateUniqueItemId(); // Generate a unique item ID
                $item->employee_id = null;                          // Set employeeID or modify as necessary
                $item->description = null;                          // Set description if necessary
                $item->department  = Departments::Property;         // Set department
                $item->save();
            }
        }

        if ($this->count < $existingCount) {
            $itemsToRemove = $existingCount - $this->count;
            $itemsToDelete = $existingItems->where('department', Departments::Property)->whereNull('employee_id')->take($itemsToRemove);
            foreach ($itemsToDelete as $item) {
                $item->delete(); // Delete the excess items
            }
            $setcount    = $existingCount - $itemsToDelete->count();
            $this->count = $setcount;
            $this->save();
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($asset) {
            $asset->items()->delete();
        });
    }
}
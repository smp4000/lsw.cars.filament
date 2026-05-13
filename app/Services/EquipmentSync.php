<?php

namespace App\Services;

use App\Models\EquipmentCategory;
use App\Models\EquipmentItem;
use App\Models\Vehicle;

class EquipmentSync
{
    public static function syncFromImport(Vehicle $vehicle, array $equipmentNames): void
    {
        if (empty($equipmentNames)) return;

        $itemIds = [];

        foreach ($equipmentNames as $entry) {
            $categoryName = $entry['category'];
            $itemName = $entry['name'];

            $category = EquipmentCategory::firstOrCreate(
                ['name' => $categoryName],
                ['sortierung' => EquipmentCategory::max('sortierung') + 1]
            );

            $item = EquipmentItem::firstOrCreate(
                ['category_id' => $category->id, 'name' => $itemName],
                ['sortierung' => EquipmentItem::where('category_id', $category->id)->max('sortierung') + 1]
            );

            $itemIds[] = $item->id;
        }

        $vehicle->equipment()->sync($itemIds);
    }
}

<?php

namespace App\Resource;

class PaginatorResource
{
    public static function toArray(int $totalItems, int $currentPage, int $itemsPerPage): array
    {
        return [
            'paginator' => [
                'total_items' => $totalItems,
                'current_page' => $currentPage,
                'items_per_page' => $itemsPerPage,
                'total_pages' => ceil($totalItems / $itemsPerPage),
            ],
        ];
    }
}

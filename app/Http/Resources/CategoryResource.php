<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    // public function toArray(Request $request): array
    // {
    //     return [
    //         'id' => $this->id,
    //         'parent_id' => $this->parent_id,
    //         'name' => $this->name,
    //         'slug' => $this->slug,
    //         'children' => $this->relationLoaded('children')
    //         ? $this->children
    //         : $this->descendants
    //     ];
    // }

    public function toArray($request)
    {
        return $this->mapCategory($this);
    }

    protected function mapCategory($category)
    {
        $data = [
            'id' => $category->id,
            'parent_id' => $category->parent_id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];

        if ($category->relationLoaded('children')) {
            $data['children'] = $category->children->map(fn ($child) => $this->mapCategory($child) );
        } elseif ($category->relationLoaded('descendants')) {
            $data['children'] = $category->descendants->map(fn ($child) => $this->mapCategory($child) );
        }

        return $data;
    }
}

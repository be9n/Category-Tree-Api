<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSubCategoriesTrait
{
    public function scopeLoadDescendants(Builder $query, int $depth = null)
    {
        return $this->loadRelation(
            depth: $depth, // This is the depth of subcategories
            query: $query
        );
    }

    public function loadDescendants(int $depth = null)
    {
        return $this->loadRelation(
            depth: $depth,
            load: true // If it's an object of the model like this $category->loadDescendants(3); the action will be load()
        );
    }

    public function loadRelation(int $depth, bool $load = false, Builder $query = null): Builder | self
    {
        $relation = 'descendants';

        if ($depth) {
            for ($i = 0; $i < $depth; $i++) {
                if ($i == 0) {
                    $relation = 'children';
                    continue;
                }

                $relation .= '.children';
            }
        }

        if ($load) {
            return $this->load($relation);
        }

        return $query->with($relation);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants'); // This retrieves the entire Category Tree
    }
}

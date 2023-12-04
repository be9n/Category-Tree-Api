<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSubCategoriesTrait
{
    public function scopeLoadDescendants(Builder $query, ?int $depth = null) // This function runs when you call it in the query like Category::loadDescendants()->paginate();
    {
        return $this->loadRelation(
            depth: $depth,
            query: $query
        );
    }

    public function loadDescendants(?int $depth = null) // This function runs when you call it by an object of the model like this $category->loadDescendants();
    {
        return $this->loadRelation(
            depth: $depth,
            load: true // If it's an object of the model like this $category->loadDescendants(3); the action will be load()
        );
    }

    public function loadRelation(?int $depth, Builder $query = null, bool $load = false): Builder | self
    {
        $relation = 'descendants';

        if ($depth) { // This is the depth of subcategories
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
        return $this->children()->with('descendants'); // This retrieves the entire Category Tree in a recursive way
    }
}

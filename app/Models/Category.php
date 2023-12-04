<?php

namespace App\Models;

use App\Traits\HasSubCategoriesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Str;

class Category extends Model
{
    use HasFactory, HasSlug, HasSubCategoriesTrait;

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
    ];

    protected static function boot(){
        parent::boot();

        static::deleting(function($model){
            $model->children->map(fn($child) => $child->delete());
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeParent($query)
    {
        return $query->where('parent_id', null);
    }

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = $value;
    //     $this->attributes['slug'] = Str::slug($value);
    // }
}

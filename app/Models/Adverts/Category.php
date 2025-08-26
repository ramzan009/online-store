<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;
    use HasFactory;

    protected $table = 'advert_categories';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    public function getPath(): string
    {
        return implode('/', array_merge($this->ancestors()->defaultOrder()->pluck('slug')->toArray(), [$this->slug]));
    }

    /**
     * @return array
     */
    public function parentAttributes(): array
    {
        return $this->parent ? $this->parent->allAttributes() : [];
    }

    /**
     * @return Attribute[]
     */
    public function allAttributes(): array
    {
        return array_merge($this->parentAttributes(), $this->attributes()->orderBy('sort')->getModels());
    }

    /**
     * @return HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }
}

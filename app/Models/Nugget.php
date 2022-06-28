<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;

class Nugget extends Model
{
    use HasFactory;

    public const NUGGET_TYPE_REFUTE = 0;

    public const NUGGET_TYPE_SUPPORT = 1;

    public const NUGGET_TYPE_GENERAL = 2;

    public const NUGGET_TYPES = [
        'refute',
        'support',
        'general',
        'contradiction',
    ];

    public const NUGGETS_FROM = [
        'religions',
        'denominations',
        'doctrines',
    ];

    public const NUGGET_FROM_CLASS_MAP = [
        Religion::class => 'religions',
        Denomination::class => 'denominations',
        Doctrine::class => 'doctrines',
    ];

    // Attributes

    public function nuggetType(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                $this->getNuggetTypeById(
                    $this->getRelation('nuggetable')
                        ->pluck('nugget_type_id')
                        ->all()
                );
            }
        );
    }

    public function feedItemType(): Attribute
    {
        // Nugget can be a different type for different models
        return new Attribute(
            get: function ($value, $attributes) {
                $str = '';
                $relations = collect();

                foreach (Arr::only($this->getRelations(), self::NUGGETS_FROM) as $relation) {
                    $relations->merge($this->getRelation($relation));
                }
            }
        );
    }

    // Helper functions

    /**
     * @param  array  $types
     * @return string
     *
     * @throws \Exception
     */
    protected static function separateTypes(array $types): string
    {
        $count = count($types);
        $types = array_map(
            fn ($type) => self::NUGGET_TYPES[$type] ??
                throw new \Exception('Unknown nugget type'),
            $types
        );

        if ($count === 1) {
            return $types[0].' Nugget';
        } elseif ($count === 2) {
            return $types[0].' & '.$types[1].' Nugget';
        } elseif ($count > 2) {
            $str = '';

            for ($i = 0; $i < $count; $i++) {
                if ($i !== $count - 1) {
                    $str .= $types[$i].', ';
                } else {
                    $str .= 'and '.$types[$i].' Nugget';
                }
            }

            return $str;
        } else {
            throw new \Exception('Empty nugget types array');
        }
    }

    /**
     * @param  string|array  $nugget
     * @return string
     *
     * @throws \Exception
     */
    public static function getNuggetTypeById($nugget): string
    {
        return match (gettype($nugget)) {
            'string', 'integer' => self::NUGGET_TYPES[$nugget] ??
                throw new \Exception('Unknown nugget type'),
            'array' => self::separateTypes($nugget),
            default => throw new \Exception('Unknown nugget type')
        };
    }

    public static function getNuggetTypeIdByString(string $nugget): string
    {
        return array_flip(self::NUGGET_TYPES)[$nugget] ??
            throw new \Exception('Unknown nugget type');
    }

    public function availableNuggetableRelations(): array
    {
        $relations = array_keys($this->relations);

        $related = [];

        foreach ($relations as $relation) {
            if (in_array($relation, self::NUGGETS_FROM) &&
                $this->relations[$relation]->isNotEmpty()) {
                $related[] = $relation;
            }
        }

        return $related;
    }

    public function nuggetTypeStatement(
        string|array $relations,
        bool $all = false,
        ?int $id = null
    ): array {
        $statements = [];

        if (is_string($relations)) {
            $relations = [$relations];
        }

        if (count(array_diff($relations, self::NUGGETS_FROM)) > 0) {
            return [];
        }

        foreach ($relations as $relation) {
            $statements[] = $this->getStatementFromRelationItem(
                relationName: $relation,
                all: $all
            );
        }

        return $statements;
    }

    public function getStatementFromRelationItem(
        string $relationName,
        ?int $id = null,
        bool $all = false
    ): string {
        /** @var \Illuminate\Database\Eloquent\Collection $relation */
        $relation = $this->getRelation($relationName);
        /** @var \Illuminate\Database\Eloquent\Collection $nuggetables */
        $nuggetables = $this->getRelation('nuggetable');
        $types = $nuggetables->where(
            'nuggetable_type',
            array_flip(self::NUGGET_FROM_CLASS_MAP)[$relationName]
        )->pluck('nugget_type_id')->unique();
        $singleType = $types->count() === 1;

        $for = $relation->count() === 1 ?
            $relation->first()->getAttribute('link_title') :
            'multiple items';

        return $singleType ?
            ucfirst(self::getNuggetTypeById($types->first())).' Nugget for ' . $for :
            'Mixed Nugget type for ' . $for;
    }

    // Relationships

    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function deletedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function denominations(): MorphToMany
    {
        return $this->morphedByMany(Denomination::class, 'nuggetable');
    }

    public function religions(): MorphToMany
    {
        return $this->morphedByMany(Religion::class, 'nuggetable');
    }

    public function doctrines(): MorphToMany
    {
        return $this->morphedByMany(Doctrine::class, 'nuggetable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function follows(): MorphMany
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    public function nuggetable(): HasMany
    {
        return $this->hasMany(Nuggetable::class, 'nugget_id');
    }

    // Inverse Relationships

    public function denominationNuggets(): MorphToMany
    {
        return $this->morphedByMany(Denomination::class, 'nuggetable');
    }

    public function doctrineNuggets(): MorphToMany
    {
        return $this->morphedByMany(Doctrine::class, 'nuggetable');
    }

    public function religionNuggets(): MorphToMany
    {
        return $this->morphedByMany(Religion::class, 'nuggetable');
    }
}

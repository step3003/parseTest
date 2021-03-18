<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class Parse extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'title',
        'price',
        'date',
        'phone',
        'views',
        'location',
        'info_large',
        'desc'
    ];




    public function images()
    {
        return $this->hasMany(Image::class);
    }


    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }


}

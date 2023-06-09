<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'title',
        'start',
        'end',
        'status'
    ];

    public function scopeFilter(Builder $query,array $filters) : void
    {
        if(isset($filters['status'])){
           $query->where('status',$filters['status']);
        }
        if(isset($filters['from'])){
            $query->where('start','>=',$filters['from']);
        }
        if(isset($filters['to'])){
            $query->where('end','<=',$filters['to']);
        }
    }

}

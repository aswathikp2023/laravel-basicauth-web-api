<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;


class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'logo',
        'website'
    ];


    /**
     * Get the employees under the company
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return config('app.url_port').'/storage/'.$this->logo;
        // return Storage::url($this->logo);
    }
}

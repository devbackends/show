<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowsPromoter extends Model
{
    /** @var string  */
    protected $table = 'shows_promoters';
    /** @var array  */
    protected $casts = [
        'contact' => 'array'
    ];
    /** @var array  */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'contact',
        'hash_index',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shows()
    {
        return $this->hasMany(Show::class, 'promoter_id');
    }
}

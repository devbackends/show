<?php

namespace App\Models;

use Devvly\ElasticSearch\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CountryState;

class Show extends Model
{

    /** @var array  */
    protected $casts = [
        'dates' => 'array',
        'hours' => 'array'
    ];
    /** @var array  */
    protected $fillable = [
        'title',
        'dates',
        'state',
        'city',
        'location',
        'hours',
        'admission',
        'hash_index',
        'is_cancelled',
        'promoter_id',
        'latitude',
        'longitude',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function promoter()
    {
        return $this->belongsTo(ShowsPromoter::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stateRelation()
    {
        return $this->belongsTo(CountryState::class, 'state', 'code')->whereCountryId(CountryState::USA_COUNTRY_ID);
    }
}

<?php

namespace App\Models;

use Devvly\ElasticSearch\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CountryState;

class GunRange extends Model
{

    /** @var array  */
    protected $fillable = [
        'name',
        'address1',
        'address2',
        'address3',
        'city',
        'state',
        'zip_code',
        'zip_code_2',
        'phone',
        'email',
        'web',
        'hours',
        'contact_name',
        'contact_phone',
        'contact_email',
        'range_category',
        'range_access',
        'club_number',
        'comments',
        'facilities',
        'latitude',
        'longitude',
        'distance',
        'hash_index',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stateRelation()
    {
        return $this->belongsTo(CountryState::class, 'state', 'code')->whereCountryId(CountryState::USA_COUNTRY_ID);
    }
}

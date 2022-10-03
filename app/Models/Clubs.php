<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CountryState;

class Clubs extends Model
{
    /** @var array  */
    protected $fillable = [
        'type',
        'org_desc',
        'club_name',
        'origination_order_date',
        'address_type_desc',
        'phone',
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'zip',
        'country_id',
        'country',
        'email',
        'web',
        'mailing_flag',
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

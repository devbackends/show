<?php

namespace App\Models;

use Devvly\ElasticSearch\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CountryState;

class FirearmTraining extends Model
{

    /** @var array  */
    protected $fillable = [
        'external_course_id',
        'title',
        'email',
        'instructor',
        'external_instructor_id',
        'address1',
        'address2',
        'address3',
        'city',
        'state',
        'zip',
        'contact_phone',
        'contact_email',
        'cost',
        'idw_cost',
        'hours',
        'class_time',
        'gender',
        'offering',
        'spans',
        'granted',
        'publish_notes',
        'date_stamp',
        'class_date',
        'approved',
        'zip_code',
        'city_name',
        'latitude',
        'longitude',
        'is_course_blended',
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

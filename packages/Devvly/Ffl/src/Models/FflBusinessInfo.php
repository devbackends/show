<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CountryState;


/**
 * Devvly\Ffl\Models\FflBusinessInfo
 *
 * @property int $id
 * @property int $ffl_id
 * @property string $company_name
 * @property string $contact_name
 * @property int $retail_store_front
 * @property int $importer_exporter
 * @property string|null $website
 * @property string|null $social
 * @property string $street_address
 * @property string $city
 * @property int $zip_code
 * @property int $phone
 * @property string $email
 * @property string $business_hours
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Devvly\Ffl\Models\Ffl $ffl
 * @property-read \Devvly\Ffl\Models\Ffl $state
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereBusinessHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereFflId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereImporterExporter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereRetailStoreFront($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereStreetAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflBusinessInfo whereZipCode($value)
 * @mixin \Eloquent
 */
class FflBusinessInfo extends Model
{
    /**
     * @var string
     */
    protected $table = 'ffl_business_info';

    /**
     * @var string[]
     */
    protected $fillable = [
        'company_name', 'contact_name', 'retail_store_front',
        'importer_exporter', 'website', 'street_address',
        'city', 'zip_code', 'phone', 'email', 'business_hours',
        'latitude', 'longitude', 'state','social'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ffl()
    {
        return $this->belongsTo(Ffl::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function countryState()
    {
        return $this->belongsTo(CountryState::class, 'state','id');
    }
}

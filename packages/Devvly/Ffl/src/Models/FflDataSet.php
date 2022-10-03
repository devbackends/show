<?php


namespace Devvly\Ffl\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Devvly\Ffl\Models\FflDataSet
 *
 * @property int $id
 * @property string|null $license_region
 * @property string|null $license_district
 * @property int|null $license_fips
 * @property string|null $license_type
 * @property string|null $license_expire_date
 * @property int|null $license_sequence
 * @property string|null $license_name
 * @property string|null $business_name
 * @property string|null $premise_street
 * @property string|null $premise_city
 * @property string|null $premise_state
 * @property string|null $premise_zip_code
 * @property string|null $mail_street
 * @property string|null $mail_city
 * @property string|null $mail_state
 * @property string|null $mail_zip_code
 * @property string|null $voice_phone
 * @property float|null $latitude
 * @property float|null $longitude
 * @property float|null $accuracy_score
 * @property string|null $accuracy_type
 * @property string|null $number
 * @property string|null $street
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country_born
 * @property int|null $zip
 * @property string|null $country_current
 * @property string|null $source
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereAccuracyScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereAccuracyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereCountryBorn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereCountryCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseFips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLicenseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereMailCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereMailState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereMailStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereMailZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet wherePremiseCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet wherePremiseState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet wherePremiseStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet wherePremiseZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereVoicePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflDataSet whereZip($value)
 * @mixin \Eloquent
 */
class FflDataSet extends Model
{
    /**
     * @var string
     */
    protected $table = 'ffl_data_set';

    /**
     * @var string[]
     */
    protected $fillable = [
        'license_region', 'license_district', 'license_fips',
        'license_type', 'license_expire_date', 'license_sequence', 'license_name',
        'business_name', 'premise_street', 'premise_city',
        'premise_state', 'premise_zip_code', 'mail_street',
        'mail_city', 'mail_state', 'mail_zip_code',
        'voice_phone', 'latitude', 'longitude',
        'accuracy_score', 'accuracy_type', 'number',
        'street', 'city', 'state',
        'country_born', 'zip', 'country_current', 'source',
    ];
}

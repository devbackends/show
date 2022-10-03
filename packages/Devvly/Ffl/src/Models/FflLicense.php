<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Devvly\Ffl\Models\FflLicense
 *
 * @property int $id
 * @property int $ffl_id
 * @property string $license_number
 * @property string|null $license_region
 * @property string|null $license_district
 * @property int|null $license_fips
 * @property string|null $license_type
 * @property string|null $license_expire_date
 * @property int|null $license_sequence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Devvly\Ffl\Models\Ffl $ffl
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereFflId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseFips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $license_name
 * @property string $license_file
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflLicense whereLicenseName($value)
 */
class FflLicense extends Model
{
    /**
     *
     */
    const STORAGE_FOLDER = 'licenses/';

    /**
     * @var string
     */
    protected $table = 'ffl_license';

    /**
     * @var string[]
     */
    protected $fillable = [
        'license_number', 'license_region', 'license_district',
        'license_fips', 'license_type', 'license_expire_date',
        'license_sequence', 'license_file', 'license_name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ffl()
    {
        return $this->belongsTo(Ffl::class);
    }
}

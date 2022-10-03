<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Ffl\Contracts\Ffl as Contract;
use Webkul\Core\Models\CountryState;

/**
 * Devvly\Ffl\Models\Ffl
 *
 * @property int $id
 * @property int $is_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Devvly\Ffl\Models\FflBusinessInfo|null $businessInfo
 * @property-read \Devvly\Ffl\Models\FflLicense|null $license
 * @property-read \Devvly\Ffl\Models\FflTransferFees|null $transferFees
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $source
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\Ffl whereSource($value)
 */
class Ffl extends Model implements Contract
{
    const ON_BOARDING_FORM = 'on_boarding_form';

    const ON_BOARDING_ADMIN = 'merchant_seller';
    /**
     * @var string
     */
    protected $table = 'ffl';

    protected $fillable = ['is_approved', 'source'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function businessInfo()
    {
        return $this->hasOne(FflBusinessInfo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function license()
    {
        return $this->hasOne(FflLicense::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transferFees()
    {
        return $this->hasOne(FflTransferFees::class);
    }
}

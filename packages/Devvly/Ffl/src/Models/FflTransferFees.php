<?php

namespace Devvly\Ffl\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Devvly\Ffl\Models\FflTransferFees
 *
 * @property int $id
 * @property int $ffl_id
 * @property float $long_gun
 * @property string|null $long_gun_description
 * @property float $hand_gun
 * @property string|null $hand_gun_description
 * @property float $nics
 * @property string|null $nics_description
 * @property string|null $other
 * @property string|null $other_description
 * @property string $payment
 * @property string|null $comments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Devvly\Ffl\Models\Ffl $ffl
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereFflId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereHandGun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereHandGunDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereLongGun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereLongGunDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereNics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereNicsDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereOtherDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Devvly\Ffl\Models\FflTransferFees whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FflTransferFees extends Model
{
    /**
     * @var string
     */
    protected $table = 'ffl_transfer_fees';

    /**
     * @var string[]
     */
    protected $fillable = [
        'long_gun', 'long_gun_description', 'hand_gun',
        'hand_gun_description', 'nics', 'nics_description', 'other',
        'other_description', 'payment', 'comments',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ffl()
    {
        return $this->belongsTo(Ffl::class);
    }
}

<?php

namespace Devvly\Ffl\Repositories;

use Devvly\Ffl\Models\Ffl;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;

class FflRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Ffl::class;
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed|void
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function create(array $attributes)
    {
        /** @var \Devvly\Ffl\Models\Ffl $model */

        $model = $this->makeModel();
        return DB::transaction(function () use ($model, $attributes) {
            $model->is_approved = $attributes['is_approved'];
            $model->source = $attributes['source'];
            $model->save();
            $model->businessInfo()->create($attributes);
            $model->transferFees()->create($attributes);
            $model->license()->create($attributes);
              return $model;
        });

    }

    public function update(array $attributes, $id)
    {
        /** @var \Devvly\Ffl\Models\Ffl $model */
        $model = $this->find($id);
        $model->update($attributes['ffl'] ?? []);

        $model->businessInfo()->update($attributes['businessInfo'] ?? []);
        $model->transferFees()->update($attributes['transferFees'] ?? []);
        $model->license()->update($attributes['license'] ?? []);
    }

    /**
     * @param string $licenseNumber
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findByLicenseNumber(string $licenseNumber)
    {
        return $this->makeModel()->newModelQuery()
            ->whereHas('license', function ($query) use ($licenseNumber) {
                /** @var Builder $query */
                $query->where('license_number', '=', $licenseNumber);
            })->first();
    }

    public function findAllByLicenseNumber(string $licenseNumber)
    {
        return $this->makeModel()->newModelQuery()
            ->whereHas('license', function ($query) use ($licenseNumber) {
                /** @var Builder $query */
                $query->where('license_number', '=', $licenseNumber);
            });
    }

    /**
     * @param float $lat
     * @param float $lng
     * @param int $limit
     * @param int $offset
     * @param int $zip
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|\Illuminate\Support\Collection
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function findClosestByCoordinates(float $lat, float $lng, int $limit, int $offset, int $zip)
    {
        $preferred_ffls= $this->makeModel()->newQuery()
            ->join('ffl_business_info', 'ffl.id', '=', 'ffl_business_info.ffl_id')
            ->join('ffl_transfer_fees', 'ffl.id', '=', 'ffl_transfer_fees.ffl_id')
            ->join('ffl_license', 'ffl.id', '=', 'ffl_license.ffl_id')
            ->selectRaw('*, sqrt(POW(69.1 * (latitude - ' . $lat . '), 2) + pow(69.1 * (' . $lng . ' - longitude) * cos(latitude / 57.3), 2)) as `distance`, (select code from country_states where id=ffl_business_info.state) as state_name')
            ->where('is_approved', '=', '1')
            ->where('source', '=', 'on_boarding_form')
            ->orderBy('ffl_license.license_type','Asc')
            ->orderBy('distance','Asc')
            ->limit($limit)
            ->offset($offset)
            ->get()->toArray();

        $ffls= $this->makeModel()->newQuery()
            ->join('ffl_business_info', 'ffl.id', '=', 'ffl_business_info.ffl_id')
            ->join('ffl_transfer_fees', 'ffl.id', '=', 'ffl_transfer_fees.ffl_id')
            ->join('ffl_license', 'ffl.id', '=', 'ffl_license.ffl_id')
            ->selectRaw('*, sqrt(POW(69.1 * (latitude - ' . $lat . '), 2) + pow(69.1 * (' . $lng . ' - longitude) * cos(latitude / 57.3), 2)) as `distance`, (select code from country_states where id=ffl_business_info.state) as state_name')
            ->where('is_approved', '=', '1')
            ->orderBy('ffl_license.license_type','Asc')
            ->orderBy('distance','Asc')
            ->limit($limit)
            ->offset($offset)
            ->get()->toArray();
        $res = array_merge($preferred_ffls, $ffls);

        return $res;
    }

    public function findByZipCodeInHundredMilesDistance(float $lat, float $lng, int $zip, int $limit, int $offset, string $state)
    {
        /** @var \Devvly\Ffl\Models\Ffl $first */
        $first = $this->makeModel()
            ->newQuery()
            ->join('ffl_business_info', 'ffl.id', '=', 'ffl_business_info.ffl_id')
            ->where('zip_code', 'like', $zip . '%')
            ->where('is_approved', '=', '1')
            ->first();
        if (!$first) {
            return [];
        }
        $state_id=0;
        if($state){
            $countryState = app('Webkul\Core\Repositories\CountryStateRepository')
                ->findWhere(['code' => $state])->first();
            if($countryState){
                $state_id=$countryState->id;
            }
        }
        $qb= $this->makeModel()->newQuery()
            ->join('ffl_business_info', 'ffl.id', '=', 'ffl_business_info.ffl_id')
            ->join('ffl_license', 'ffl.id', '=', 'ffl_license.ffl_id')
            ->selectRaw('*, sqrt(POW(69.1 * (latitude - ' . $lat . '), 2) + pow(69.1 * (' . $lng . ' - longitude) * cos(latitude / 57.3), 2)) as `distance`, (select code from country_states where id=ffl_business_info.state) as state_name,
                sqrt(POW(69.1 * (latitude - ' . $first['latitude'] . '), 2) + pow(69.1 * (' . $first['longitude'] . ' - longitude) * cos(latitude / 57.3), 2)) as `radius`')
            ->where('zip_code', 'like', $zip . '%')
            ->where('is_approved', '=', '1');
            if($state_id > 0){
                $qb=$qb->where('state', '=', $state_id);
            }
            $qb=$qb->limit($limit)
            ->offset($offset)
            ->orderBy('ffl_license.license_type','Asc')
            ->orderBy('distance','Asc')
            ->having('radius', '<', '100')
            ->get();
            return $qb;
    }

    public function findBy()
    {
        return $this->makeModel()
            ->newModelQuery()
            ->with('businessInfo')
            ->with('license')
            ->with('transferFees')
            ->first();
    }

}

<?php

namespace Webkul\BookingProduct\Repositories;

use Illuminate\Container\Container as App;
use Carbon\Carbon;
use Webkul\Core\Eloquent\Repository;

class BookingProductRepository extends Repository
{
    /**
     * @return array
     */
    protected $typeRepositories = [];

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\BookingProduct\Repositories\BookingProductDefaultSlotRepository  $bookingProductDefaultSlotRepository
     * @param  \Webkul\BookingProduct\Repositories\BookingProductEventTicketRepository  $bookingProductEventTicketRepository
     * @param  \Webkul\BookingProduct\Repositories\BookingProductRentalSlotRepository  $bookingProductRentalSlotRepository
     * @return void
     */
    public function __construct(
        BookingProductDefaultSlotRepository $bookingProductDefaultSlotRepository,
        BookingProductEventTicketRepository $bookingProductEventTicketRepository,
        BookingProductRentalSlotRepository $bookingProductRentalSlotRepository,
        App $app
    )
    {
        parent::__construct($app);

        $this->bookingProductDefaultSlotRepository = $bookingProductDefaultSlotRepository;


        $this->bookingProductEventTicketRepository = $bookingProductEventTicketRepository;

        $this->bookingProductRentalSlotRepository = $bookingProductRentalSlotRepository;


    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\BookingProduct\Contracts\BookingProduct';
    }

    /**
     * @param  array  $data
     * @return \Webkul\BookingProduct\Contracts\BookingProduct
     */
    public function create(array $data)
    {

        $data=$this->validateBookingProduct($data);
        $bookingProduct = parent::create($data);

        if(strtolower($bookingProduct->type!='rental')){
            $this->bookingProductDefaultSlotRepository->create(array_merge($data, ['booking_product_id' => $bookingProduct->id]));
        }
        if(strtolower($bookingProduct->type=='event')){
            $this->bookingProductEventTicketRepository->create(array_merge($data, ['booking_product_id' => $bookingProduct->id]));
        }
        if(strtolower($bookingProduct->type=='rental')){
            $this->bookingProductRentalSlotRepository->create(array_merge($data, ['booking_product_id' => $bookingProduct->id]));
        }

        return $bookingProduct;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\BookingProduct\Contracts\BookingProduct
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $data = $this->validateBookingProduct($data);

        $bookingProduct = parent::update($data, $id, $attribute);


        if (strtolower($bookingProduct->type != 'rental')) {
            $bookingProductTypeSlot = $this->bookingProductDefaultSlotRepository->findOneByField('booking_product_id', $id);
        } else {
            $bookingProductTypeSlot = $this->bookingProductRentalSlotRepository->findOneByField('booking_product_id', $id);
        }
        if (strtolower($bookingProduct->type == 'event')) {
            $bookingProductEventTicket = $this->bookingProductEventTicketRepository->findOneByField('booking_product_id', $id);
            $this->bookingProductEventTicketRepository->update($data, $bookingProductEventTicket->id);
        }
        if (isset($data['slots'])) {
            $data['slots'] = $this->formatSlots($data);

            $data['slots'] = $this->validateSlots($data);
        }
        if (!$bookingProductTypeSlot) {
            if (strtolower($bookingProduct->type != 'rental')) {
                $bookingProductTypeSlot = $this->bookingProductDefaultSlotRepository->create(array_merge($data, ['booking_product_id' => $id]));
            } else {
                $bookingProductTypeSlot = $this->bookingProductRentalSlotRepository->create(array_merge($data, ['booking_product_id' => $id]));
            }
        } else {
            if (strtolower($bookingProduct->type != 'rental')) {
                $bookingProductTypeSlot = $this->bookingProductDefaultSlotRepository->update($data, $bookingProductTypeSlot->id);
            } else {
                $bookingProductTypeSlot = $this->bookingProductRentalSlotRepository->update($data, $bookingProductTypeSlot->id);
            }
        }

    }
    public function validateBookingProduct($data){

        if(!isset($data['repeated_event'])){
            $data['repeated_event']=0;
        }
        if (isset($data['leaders'])) {
            if($data['leaders']){
                $data['leaders'] = explode(',',$data['leaders']);
            }
        }
        if (isset($data['tags'])) {
            if($data['tags']){
                $data['tags']=explode(',',$data['tags']);
            }
        }
        if (isset($data['levels'])) {
            if($data['levels']){
                $data['levels'] = explode(',',$data['levels']);
            }
        }
        if (isset($data['type'])) {
            $data['type'] = strtolower($data['type']);
        }
        if(isset($data['slots'])){
            $data['slots']=json_encode($data['slots']);
        }
        if(isset($data['repetition_sequence'])){
            $data['repetition_sequence']=json_encode($data['repetition_sequence']);
        }
        if(isset($data['leaders'])){
            if($data['leaders']){
                $data['leaders']=json_encode($data['leaders']);
            }else{
                $data['leaders']=NUll;
            }
        }
        if(isset($data['tags'])){
            if($data['tags']){
                $data['tags']=json_encode($data['tags']);
            }else{
                $data['tags']=NUll;
            }
        }
        if(isset($data['levels'])) {
            if($data['levels']){
                $data['levels'] = json_encode($data['levels']);
            }else{
                $data['levels'] = Null;
            }
        }

        return $data;
    }
    /**
     * @param  array  $data
     * @return array
     */
    public function formatSlots($data)
    {
        if (isset($data['same_slot_all_days']) && ! $data['same_slot_all_days']) {
            for ($i = 0; $i < 7; $i++) {
                if (! isset($data['slots'][$i])) {
                    $data['slots'][$i] = [];
                } else {
                    $count = 0;

                    $slots = [];

                    foreach ($data['slots'][$i] as $slot) {
                        $slots[] = array_merge($slot, ['id' => $i . '_slot_' . $count]);

                        $count++;
                    }

                    $data['slots'][$i] = $slots;
                }
            }

            ksort($data['slots']);
        }

        return $data['slots'];
    }

    /**
     * @param  array  $data
     * @return array
     */
    public function validateSlots($data)
    {
        if (! isset($data['same_slot_all_days'])) {
            return $data['slots'];
        }

        if (! $data['same_slot_all_days']) {
            foreach ($data['slots'] as $day => $slots) {
                $data['slots'][$day] = $this->skipOverLappingSlots($slots);
            }
        } else {
            $data['slots'] = $this->skipOverLappingSlots($data['slots']);
        }

        return $data['slots'];
    }

    /**
     * @param  array  $data
     * @return array
     */
    public function skipOverLappingSlots($slots)
    {
        $tempSlots = [];

        foreach ($slots as $key => $timeInterval) {
            $from = Carbon::createFromTimeString($timeInterval['from'])->getTimestamp();

            $to = Carbon::createFromTimeString($timeInterval['to'])->getTimestamp();

            if ($from > $to) {
                unset($slots[$key]);

                continue;
            }

            $isOverLapping = false;

            foreach ($tempSlots as $slot) {
                if (($slot['from'] <= $from && $slot['to'] >= $from)
                    || ($slot['from'] <= $to && $slot['to'] >= $to)
                ) {
                    $isOverLapping = true;

                    unset($slots[$key]);
                }
            }

            if (! $isOverLapping) {
                $tempSlots[] = [
                    'from' => $from,
                    'to'   => $to,
                ];
            }
        }

        return $slots;
    }
}
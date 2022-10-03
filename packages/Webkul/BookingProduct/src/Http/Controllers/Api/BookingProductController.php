<?php

namespace Webkul\BookingProduct\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Webkul\BookingProduct\Repositories\BookingProductRepository;
use Webkul\BookingProduct\Helpers\DefaultSlot as DefaultSlotHelper;
use Webkul\BookingProduct\Helpers\AppointmentSlot as AppointmentSlotHelper;
use Webkul\BookingProduct\Helpers\RentalSlot as RentalSlotHelper;
use Webkul\BookingProduct\Helpers\EventTicket as EventTicketHelper;
use Webkul\BookingProduct\Helpers\TableSlot as TableSlotHelper;

class BookingProductController extends Controller
{
    /**
     * @var BookingProductRepository
     */
    protected $bookingProductRepository;

    /**
     * @return array
     */
    protected $bookingHelpers = [];

    /**
     * Create a new helper instance.
     *
     * @param BookingProductRepository $bookingProductRepository
     * @param DefaultSlotHelper $defaultSlotHelper
     * @param AppointmentSlotHelper $appointmentSlotHelper
     * @param RentalSlotHelper $rentalSlotHelper
     * @param EventTicketHelper $eventTicketHelper
     * @param TableSlotHelper $tableSlotHelper
     */
    public function __construct(
        BookingProductRepository $bookingProductRepository,
        DefaultSlotHelper $defaultSlotHelper,
        AppointmentSlotHelper $appointmentSlotHelper,
        RentalSlotHelper $rentalSlotHelper,
        EventTicketHelper $eventTicketHelper,
        TableSlotHelper $tableSlotHelper
    )
    {
        $this->bookingProductRepository = $bookingProductRepository;

        $this->bookingHelpers['default'] = $defaultSlotHelper;

        $this->bookingHelpers['appointment'] = $appointmentSlotHelper;

        $this->bookingHelpers['rental'] = $rentalSlotHelper;

        $this->bookingHelpers['event'] = $eventTicketHelper;

        $this->bookingHelpers['table'] = $tableSlotHelper;
    }

    /**
     *
     * @param $bookingProductId
     * @return JsonResponse
     */
    public function getSlots($bookingProductId): JsonResponse
    {
        $bookingProduct = $this->bookingProductRepository->find($bookingProductId);

        if ($bookingProduct) {
            $response = [
                'status' => true,
                'data' => $this->bookingHelpers[$bookingProduct->type]->getSlotsByDate($bookingProduct, request()->get('date')),
            ];
        } else {
            $response = [
                'status' => false,
                'data' => [],
            ];
        }

        return response()->json($response);
    }

    /**
     * @param $bookingProductId
     * @return JsonResponse
     */
    public function getWeekSlotDurations($bookingProductId): JsonResponse
    {
        $bookingProduct = $this->bookingProductRepository->find($bookingProductId);
        if ($bookingProduct && $bookingProduct !== 'appointment') {
            $response = [
                'status' => true,
                'data' => $this->bookingHelpers['appointment']->getWeekSlotDurations($bookingProduct)
            ];
        } else {
            $response = [
                'status' => false,
                'data' => [],
            ];
        }

        return response()->json($response);
    }

    /**
     * @param $bookingProductId
     * @return JsonResponse
     */
    public function getTickets($bookingProductId): JsonResponse
    {
        $bookingProduct = $this->bookingProductRepository->find($bookingProductId);
        if ($bookingProduct && $bookingProduct !== 'event') {
            $response = [
                'status' => true,
                'data' => $this->bookingHelpers['event']->getTickets($bookingProduct)
            ];
        } else {
            $response = [
                'status' => false,
                'data' => [],
            ];
        }

        return response()->json($response);
    }
}
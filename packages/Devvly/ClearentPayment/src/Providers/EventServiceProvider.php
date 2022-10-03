<?php


namespace Devvly\ClearentPayment\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Theme\ViewRenderEventManager;

class EventServiceProvider extends ServiceProvider
{

  /**
   * Bootstraps services
   *
   * @return void
   */
  public function boot()
  {
    Event::listen('clearent_payment.*', 'Devvly\Clearent\Listeners\ClearentMainListener@run');
  }
}
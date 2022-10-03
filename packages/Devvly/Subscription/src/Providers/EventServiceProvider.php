<?php


namespace Devvly\Subscription\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{

  /**
   * Bootstraps services
   *
   * @return void
   */
  public function boot()
  {
    Event::listen('clearent.*', 'Devvly\Clearent\Listeners\ClearentMainListener@run');
  }
}
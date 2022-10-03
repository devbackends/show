<?php

namespace Mega\HeaderFooter\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use function foo\func;

class EventServiceProvider extends ServiceProvider
{
    public function boot(){
        Event::listen('bagisto.shop.layout.body.before',function ($viewRenderEventManager){
            $viewRenderEventManager->addTemplate('megaheaderfooter::shop.layout.body.before.header');
        });

        Event::listen('bagisto.shop.layout.body.after',function ($viewRenderEventManager){
            $viewRenderEventManager->addTemplate('megaheaderfooter::shop.layout.body.after.footer');
        });
        Event::listen('bagisto.shop.layout.head',function($viewRenderEventManager){
            $viewRenderEventManager->addTemplate('megaheaderfooter::shop.layout.head.script');
        });

    }
}

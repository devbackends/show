<?php

namespace Mega\Phonelogin\Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Mega\Phonelogin\Exception\WrongVerificationCodeException;

class WrongVerificationCodeHandler extends ExceptionHandler
{

    public function render($request, Throwable   $exception)
    {
        // custom error message
        if ($exception instanceof \ErrorException) {
            $PLATFORM_BRANCH = getenv('APP_ENV');
            if($PLATFORM_BRANCH && $PLATFORM_BRANCH == 'prod') {
                return response()->view('marketplace::shop.market.errors.500', [], 500);
            }

        }
        if ($exception instanceof WrongVerificationCodeException) {
            return redirect()->back();;
        }
        return parent::render($request, $exception);
    }

}

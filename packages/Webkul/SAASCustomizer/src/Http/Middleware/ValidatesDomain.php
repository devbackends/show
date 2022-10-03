<?php

namespace Webkul\SAASCustomizer\Http\Middleware;

use Closure;
use Validator;

class ValidatesDomain
{

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, Closure $next)
    {

        $primaryServerName = config('app.url');

        $currentURL = $_SERVER['SERVER_NAME'];

        $params['domain'] = $currentURL;

        $validator = Validator::make($params, [
            'domain' => 'required|ip'
        ]);



        
        if (str_contains($primaryServerName, 'http://')) {
            $primaryServerNameWithoutProtocol = explode('http://', $primaryServerName)[1];
        } else if (str_contains($primaryServerName, 'https://')) {
            $primaryServerNameWithoutProtocol = explode('https://', $primaryServerName)[1];
        }

        //restricts the IP address usage to access the system
        if ($validator->fails()) {
            //case where IP validation fails
            if (str_contains($currentURL, 'http://')) {
                $currentURL = explode('http://', $currentURL)[1];
            } else if (str_contains($currentURL, 'http://')) {
                $currentURL = explode('http://', $currentURL)[1];
            }
        } else {
            //case where IP validation passes then it should redirect to the main domain

            return redirect()->route('shop.home.index');
        }

        if (str_contains($primaryServerNameWithoutProtocol, '/')) {
            $primaryServerNameWithoutProtocol = explode('/', $primaryServerNameWithoutProtocol)[0];
        }

        if ($currentURL == $primaryServerNameWithoutProtocol) {
/*            if (request()->is('company/*') || request()->is('super/*')) {
                return $next($request);
            } else {

                return redirect()->route('saas.home.index');
            }*/
            return $next($request);
        } else {
            if ((request()->is('company/*') || request()->is('super/*')) && ! request()->is('company/seed-data')) {
                throw new \Exception('not_allowed_to_visit_this_section', 400);
            } else {
                return $next($request);
            }
        }
    }
}
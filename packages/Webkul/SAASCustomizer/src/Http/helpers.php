<?php
    use Webkul\SAASCustomizer\Company;

    if (! function_exists('company')) {
        /** @return Company */
        function company()
        {
            return app(Company::class);
        }
    }
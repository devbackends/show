<?php
    use Webkul\Core\Core;

    if (! function_exists('core')) {
        /** @return Core */
        function core()
        {
            return app(Core::class);
        }
    }

    if (! function_exists('array_permutation')) {
        function array_permutation($input)
        {
            $results = [];

            foreach ($input as $key => $values) {
                if (empty($values)) {
                    continue;
                }

                if (empty($results)) {
                    foreach ($values as $value) {
                        $results[] = [$key => $value];
                    }
                } else {
                    $append = [];

                    foreach ($results as &$result) {
                        $result[$key] = array_shift($values);

                        $copy = $result;

                        foreach ($values as $item) {
                            $copy[$key] = $item;
                            $append[] = $copy;
                        }

                        array_unshift($values, $result[$key]);
                    }

                    $results = array_merge($results, $append);
                }
            }

            return $results;
        }
    }

    if (! function_exists('get_app_class_prefix')) {
        /**
         * Returns the app logo class.
         *
         * @return string
         */
        function get_app_class_prefix(): string
        {
            $prefix = is_whalut()? 'whalut': 'twoa';
            return $prefix;
        }
    }

    if (! function_exists('is_whalut')) {
        /**
         * Determines whether the app is Whalut or not
         * @return bool
         */
        function is_whalut(): bool{
            return env('IS_WHALUT', false);
        }
    }
?>
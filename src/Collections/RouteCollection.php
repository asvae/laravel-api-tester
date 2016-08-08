<?php

namespace Asvae\ApiTester\Collections;

use Illuminate\Support\Collection;

/**
 * Class RouteCollection
 *
 * @package \Asvae\ApiTester
 */
class RouteCollection extends Collection
{
    /**
     * Include routes that match patterns.
     *
     * @param array <array|string> $patterns
     *
     * @return static
     */
    public function filterMatch($patterns)
    {
        $patterns = is_string($patterns) ? [$patterns] : $patterns;

        // String pattern is assumed to be path.
        foreach ($patterns as $key => $pattern) {
            if (is_string($pattern)) {
                $patterns[$key] = ['path' => $pattern];
            }
        }

        return $this->filter(function ($route) use ($patterns) {
            // If any of patterns matches - route passes.
            foreach ($patterns as $pattern) {
                if ($this->isRouteMatchesPattern($route, $pattern)) {
                    return true;
                }
            }

            // If all patterns don't match - route is filtered out.
            return false;
        });
    }


    /**
     * Exclude routes that match patterns.
     *
     * @param array <array|string> $patterns
     *
     * @return static
     */
    public function filterExcept(array $patterns = [])
    {
        if (empty($patterns)) {
            return $this;
        }

        $toExclude = $this->filterMatch($patterns)->keys()->toArray();

        return $this->except($toExclude);
    }

    /**
     * @param array $route
     * @param array $pattern
     * @return bool
     */
    private function isRouteMatchesPattern(array $route, array $pattern)
    {
        foreach ($route as $key => $value) {
            if (! array_key_exists($key, $pattern)) {
                continue;
            }

            if(is_array($value)){
                $value = implode(',', $value);
            }

            $regex = '#'.$pattern[$key].'#';

            return ! ! preg_match($regex, $value);
        }

        return true;
    }
}

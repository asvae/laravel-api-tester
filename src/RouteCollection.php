<?php

namespace Asvae\ApiTester;

use Illuminate\Support\Collection;

/**
 * Class RouteCollection
 *
 * @package \Asvae\ApiTester
 */
class RouteCollection extends Collection
{
    /**
     * Return collection of routes that matched pattern
     *
     * @param array $matches
     *
     * @return static
     */
    public function filterMatch(array $matches = [])
    {
        if (!empty($matches)) {
            return $this->filter(function ($route, $key) use ($matches) {
                $isMatched = true;

                foreach ($matches as $match) {

                    foreach ($route as $index => $value) {
                        if (!array_key_exists($index, $match)) {
                            continue;
                        }

                        if (!preg_match($match[$index], $value)) {
                            $isMatched = false;
                        }
                    }
                }

                return $isMatched;
            });
        }

        return $this;
    }

    /**
     * Return collection of routes except matched pattern
     *
     * @param array $exceptions
     *
     * @return static
     */
    public function filterExcept(array $exceptions = [])
    {
        if (!empty($exceptions)) {
            return $this->except($this->filterMatch($exceptions)->keys()->toArray());
        }

        return $this;
    }
}

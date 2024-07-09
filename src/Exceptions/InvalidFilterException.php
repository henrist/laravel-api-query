<?php

namespace Henrist\LaravelApiQuery\Exceptions;

class InvalidFilterException extends ApiQueryException
{
    protected string $filter;

    /**
     * @return $this
     */
    public function setFilter(string $filter)
    {
        $this->filter = $filter;

        return $this;
    }
}

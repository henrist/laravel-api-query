<?php

namespace Henrist\LaravelApiQuery\Exceptions;

use Illuminate\Database\Eloquent\Model;

class InvalidModelException extends ApiQueryException
{
    protected \Illuminate\Database\Eloquent\Model $model;

    /**
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}

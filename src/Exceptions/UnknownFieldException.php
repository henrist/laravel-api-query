<?php

namespace Henrist\LaravelApiQuery\Exceptions;

use Illuminate\Database\Eloquent\Model;

class UnknownFieldException extends ApiQueryException
{
    protected \Illuminate\Database\Eloquent\Model $model;

    protected string $field;

    /**
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return $this
     */
    public function setField(string $field)
    {
        $this->field = $field;

        return $this;
    }
}

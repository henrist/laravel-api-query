<?php

namespace Henrist\LaravelApiQuery\Exceptions;

use Illuminate\Database\Eloquent\Model;

class UnknownRelationException extends ApiQueryException
{
    protected \Illuminate\Database\Eloquent\Model $model;

    protected string $relation;

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
    public function setRelation(string $relation)
    {
        $this->relation = $relation;

        return $this;
    }
}

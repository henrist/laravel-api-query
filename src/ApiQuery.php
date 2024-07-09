<?php

namespace Henrist\LaravelApiQuery;

use Henrist\LaravelApiQuery\Processors\ProcessorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ApiQuery
{
    /**
     * List of default processors
     */
    protected $processors = [];

    /**
     * Run a query for a Eloquent Builder model
     *
     * The model must implement ApiQueryInterface
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Henrist\LaravelApiQuery\Handler
     */
    public function processCollection(Builder $builder, ?Request $request = null)
    {
        $obj = new Handler;

        $obj->setBuilder($builder);
        $obj->setRequest($request ?: FacadesRequest::instance());
        $obj->setProcessors($this->processors);

        return $obj;
    }

    /**
     * Add default processor
     */
    public function addDefaultProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }
}

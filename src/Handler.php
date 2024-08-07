<?php

namespace Henrist\LaravelApiQuery;

use Henrist\LaravelApiQuery\Processors\ProcessorInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Handler implements \JsonSerializable, Arrayable, Jsonable
{
    protected Builder $builder;

    protected \Illuminate\Database\Query\Builder $query;

    /**
     * Collection of processors
     */
    protected $processors = [];

    /**
     * Request
     */
    protected Request $request;

    /**
     * Set Eloquent Builder
     */
    public function setBuilder(Builder $builder)
    {
        $this->builder = $builder;
        $this->query = $builder->getQuery();
    }

    /**
     * Get Eloquent Builder
     */
    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    /**
     * Get query builder
     */
    public function getQuery(): \Illuminate\Database\Query\Builder
    {
        return $this->query;
    }

    /**
     * Add processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Set processors list
     */
    public function setProcessors($processors)
    {
        $this->processors = $processors;
    }

    /**
     * Set request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Process request
     */
    public function processBefore()
    {
        // TODO: improve this somehow
        static $processed = false;
        if ($processed) {
            return;
        }
        $processed = true;

        foreach ($this->processors as $processor) {
            $processor->processBefore($this, $this->request);
        }
    }

    /**
     * Process end array
     */
    public function processAfter($data)
    {
        foreach ($this->processors as $processor) {
            $processor->processAfter($this, $data);
        }

        return $data;
    }

    /**
     * Convert the object into something JSON serializable.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the object instance to an array.
     */
    public function toArray(): array
    {
        $this->processBefore();
        $result = $this->processAfter($this->builder->get()->toArray());

        if ($this->query->limit) {
            $count = $this->query->getCountForPagination();

            return [
                'pagination' => [
                    'offset' => $this->query->offset ?: 0,
                    'limit' => $this->query->limit ?: 0,
                    'total' => $count,
                ],
                'result' => $result,
            ];
        }

        return $result;
    }

    /**
     * Convert the object to its string representation.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Convert the object instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}

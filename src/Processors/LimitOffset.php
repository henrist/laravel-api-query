<?php

namespace Henrist\LaravelApiQuery\Processors;

use Henrist\LaravelApiQuery\Handler;
use Illuminate\Http\Request;

class LimitOffset implements ProcessorInterface
{
    /**
     * Default limit of results
     * TODO: config option?
     */
    protected ?int $defaultPageLimit = null;

    /**
     * @override
     */
    public function processBefore(Handler $apiquery, Request $request)
    {
        if ($request->has('limit')) {
            $apiquery->getQuery()->limit((int) $request->get('limit'));
        }

        if (! $apiquery->getQuery()->limit && $this->defaultPageLimit) {
            $apiquery->getQuery()->limit($this->defaultPageLimit);
        }

        if ($request->has('offset') && $apiquery->getQuery()->limit) {
            $apiquery->getQuery()->offset((int) $request->get('offset'));
        }
    }

    public function processAfter(Handler $handler, array &$data) {}
}

<?php

namespace Henrist\LaravelApiQuery\Processors;

use Henrist\LaravelApiQuery\ApiQueryInterface;
use Henrist\LaravelApiQuery\Exceptions\InvalidModelException;
use Henrist\LaravelApiQuery\Exceptions\UnknownFieldException;
use Henrist\LaravelApiQuery\Handler;
use Illuminate\Http\Request;

class Order implements ProcessorInterface
{
    /**
     * @override
     */
    public function processBefore(Handler $apiquery, Request $request)
    {
        if (! $request->has('order')) {
            return;
        }

        $model = $apiquery->getBuilder()->getModel();
        if (! ($model instanceof ApiQueryInterface)) {
            throw (new InvalidModelException)->setModel($model);
        }

        $allowedFields = $model->getApiAllowedFields();

        foreach (explode(',', $request->get('order')) as $order) {
            $dir = 'asc';
            $null = null;
            if ($order[0] == '-') {
                $dir = 'desc';
                $order = substr($order, 1);
            }

            // checking for null values
            if (preg_match('/^(.*):(NOT)?NULL$/', $order, $matches)) {
                $null = $matches[2] == '';
                $order = $matches[1];
            }

            if (! in_array($order, $allowedFields)) {
                throw (new UnknownFieldException('Filter field is not in allowed list'))->setModel($model)->setField($order);
            }

            if ($null !== null) {
                $apiquery->getQuery()->orderByRaw(sprintf('%s is%s null %s',
                    $apiquery->getQuery()->getGrammar()->wrap($order),
                    ($null ? '' : ' not'),
                    $dir
                ));
            } else {
                $apiquery->getQuery()->orderBy($order, $dir);
            }
        }
    }

    public function processAfter(Handler $handler, array &$data) {}
}

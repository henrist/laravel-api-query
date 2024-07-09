<?php

namespace Henrist\LaravelApiQuery\Processors;

use Henrist\LaravelApiQuery\ApiQueryInterface;
use Henrist\LaravelApiQuery\Exceptions\InvalidFilterException;
use Henrist\LaravelApiQuery\Exceptions\InvalidModelException;
use Henrist\LaravelApiQuery\Exceptions\UnknownFieldException;
use Henrist\LaravelApiQuery\Exceptions\UnknownRelationException;
use Henrist\LaravelApiQuery\Handler;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Filter implements ProcessorInterface
{
    /**
     * @override
     */
    public function processBefore(Handler $apiquery, Request $request)
    {
        if (! $request->has('filter')) {
            return;
        }

        foreach (explode(',', $request->get('filter')) as $filter) {
            if (preg_match('/^(.+?)(=|!=|<|>|<=|>=)(.+?)$/m', $filter, $match)) {
                [$field, $operator, $value] = array_slice($match, 1);
            } else {
                $fields = explode(':', $filter, 3);

                if (count($fields) < 3 && (count($fields) != 2 || in_array($fields[0], ['NULL', 'NOTNULL']))) {
                    throw (new InvalidFilterException('Invalid filter supplied'))->setFilter($filter);
                }

                $field = $fields[0];
                $operator = $fields[1];
                $value = isset($fields[2]) ? $fields[2] : null;
            }

            $this->filterField($apiquery->getBuilder(), $field, $operator, $value);
        }
    }

    /**
     * Filter by a specific field
     *
     * @throws InvalidModelException
     * @throws UnknownFieldException
     * @throws UnknownRelationException
     */
    protected function filterField(Builder $builder, $field, $operator, $value)
    {
        $model = $builder->getModel();
        if (! ($model instanceof ApiQueryInterface)) {
            throw (new InvalidModelException)->setModel($model);
        }
        $allowedFields = $model->getApiAllowedFields();
        $allowedRelations = $builder->getModel()->getApiAllowedRelations();

        // field from relation?
        if (($pos = strpos($field, '.')) !== false) {
            $relation = substr($field, 0, $pos);
            if (! in_array($relation, $allowedRelations)) {
                throw (new UnknownRelationException('Relation to filter is not in allowed list'))->setModel($model)->setRelation($relation);
            }

            $relfield = substr($field, $pos + 1);
            $builder->whereHas($relation, function ($q) use ($relfield, $operator, $value) {
                $this->filterField($q, $relfield, $operator, $value);
            });
        } else {
            if (! in_array($field, $allowedFields)) {
                throw (new UnknownFieldException('Filter field is not in allowed list'))->setModel($model)->setField($field);
            }

            if ($operator == 'NULL') {
                $builder->getQuery()->whereNull($field);
            } elseif ($operator == 'NOTNULL') {
                $builder->getQuery()->whereNotNull($field);
            } else {
                $builder->getQuery()->where($field, $operator, $value);
            }
        }
    }

    public function processAfter(Handler $handler, array &$data) {}
}

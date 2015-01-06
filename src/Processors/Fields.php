<?php namespace Henrist\LaravelApiQuery\Processors;

use Henrist\LaravelApiQuery\ApiQueryInterface;
use Henrist\LaravelApiQuery\Exceptions\InvalidModelException;
use Henrist\LaravelApiQuery\Exceptions\UnknownRelationException;
use Henrist\LaravelApiQuery\Handler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Fields implements ProcessorInterface {
    /**
     * @override
     */
    public function processBefore(Handler $apiquery, Request $request) {}

    public function processAfter(Handler $handler, array &$data) {
        $request = $handler->getRequest();
        if ($request->has('fields')) {
            $fields = explode(",", $request->get('fields'));
            foreach ($data as &$item) {
                $this->intersectFields($fields, $item);
            }
        }
    }

    protected function intersectFields($fields, &$data) {
        $subfields = array();

        foreach ($fields as &$field) {
            $pos = strpos($field, '.');
            if ($pos !== false) {
                $subfield = substr($field, $pos+1);
                $field = substr($field, 0, $pos);

                $subfields[$field][] = $subfield;
            }
        }
        unset($field);

        foreach ($subfields as $field => $subs) {
            if (isset($data[$field]) && is_array($data[$field])) {
                if (isset($data[$field][0])) {
                    // arraylist and not object representation
                    foreach ($data[$field] as &$item) {
                        $this->intersectFields($subs, $item);
                    }
                } else {
                    $this->intersectFields($subs, $data[$field]);
                }
            }
        }

        $data = array_intersect_key($data, array_fill_keys($fields, true));
    }
}

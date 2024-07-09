<?php

namespace Henrist\LaravelApiQuery\Processors;

use Henrist\LaravelApiQuery\Handler;
use Illuminate\Http\Request;

interface ProcessorInterface
{
    public function processBefore(Handler $handler, Request $request);

    public function processAfter(Handler $handler, array &$data);
}

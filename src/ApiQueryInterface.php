<?php

namespace Henrist\LaravelApiQuery;

interface ApiQueryInterface
{
    /**
     * Get fields we can use in filtering and selecting
     */
    public function getApiAllowedFields(): array;

    /**
     * Get fields we can use as relations
     */
    public function getApiAllowedRelations(): array;
}

<?php

namespace Paulund\EloquentLifetime\Traits;

use Carbon\Carbon;

trait EloquentLifetime
{
    /**
     * Return the lifetime of the model.
     */
    abstract public function lifetime(): Carbon;
}

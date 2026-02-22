<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id'; // We still use 'id' as the key name, but Laravel will use getRouteKey() for the value
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $decoded = Hashids::decode($value);

        if (empty($decoded)) {
            return null;
        }

        return $this->where($field ?? $this->getRouteKeyName(), $decoded[0])->first();
    }

    /**
     * Get the hashid for the model.
     *
     * @return string
     */
    public function getHashidAttribute()
    {
        return Hashids::encode($this->getKey());
    }
}

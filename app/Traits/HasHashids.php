<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HasHashids
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
     * Accessor for the hashid.
     *
     * @return string
     */
    public function getHashidAttribute()
    {
        return $this->getRouteKey();
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
        if (empty($value)) {
            return null;
        }

        // Decode the hashid
        $decoded = Hashids::decode($value);

        if (empty($decoded)) {
            abort(404);
        }

        // Find the model using the decoded ID
        return $this->where($field ?? $this->getRouteKeyName(), $decoded[0])->firstOrFail();
    }
}

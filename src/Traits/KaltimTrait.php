<?php

namespace Novay\SSOKaltim\Traits;

trait KaltimTrait
{
    public function token_sso()
    {
        return $this->hasOne(\Novay\SSOKaltim\Models\OAuth::class);
    }
}
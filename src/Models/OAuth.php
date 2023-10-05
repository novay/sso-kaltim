<?php

namespace Novay\SSOKaltim\Models;

use Illuminate\Database\Eloquent\Model;

class OAuth extends Model
{
    protected $table = 'user_providers';
    
    protected $guarded = [];

    public function hasExpired()
    {
        return now()->gte($this->updated_at->addSeconds($this->expires_in));
    }
}

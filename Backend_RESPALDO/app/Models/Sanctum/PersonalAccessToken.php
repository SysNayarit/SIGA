<?php

namespace App\Models\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * La tabla asociada al modelo en el esquema system.
     *
     * @var string
     */
    protected $table = 'system.personal_access_tokens';
}

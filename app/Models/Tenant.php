<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
// use Stancl\Tenancy\Contracts\TenantWithDatabase;
// use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

// class Tenant extends BaseTenant implements TenantWithDatabase // It is a  single database concept
class Tenant extends BaseTenant 
{
    // use HasDatabase, HasDomains;// It is a  single database concept
    use  HasDomains;
}

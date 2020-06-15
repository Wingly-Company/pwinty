<?php

namespace Wingly\Pwinty\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Wingly\Pwinty\Orderer;

class User extends Model
{
    use Orderer;
}

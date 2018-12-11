<?php

namespace Wazly\Revelation;

use Wazly\Revelation;

function reveal($obj, ...$args)
{
    return Revelation::wrap($obj, ...$args);
}

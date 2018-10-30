<?php

namespace Wazly\Revelation;

use Wazly\Revelation;

function reveal($obj, ...$args)
{
    return Revelation::new($obj, ...$args);
}

<?php

namespace Tests\Util;

enum TestDatabaseTypeEnum: string
{
    case MariaDB = 'MariaDB';

    case MongoDB = 'MongoDB';
}

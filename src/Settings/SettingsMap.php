<?php

declare(strict_types=1);

namespace Core\Settings;

use Northrook\Dot;

final class SettingsMap extends Dot
{
    public function __construct( array $map = [] )
    {
        parent::__construct( $map, true );
    }
}

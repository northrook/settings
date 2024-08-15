<?php

declare( strict_types = 1 );

namespace Northrook;

use Northrook\Settings\AbstractSettings;

final class Settings extends AbstractSettings
{

    public function __construct(
        array $settings = [],
        bool  $lockInjected = false,
        ?bool $freeze = null,
        bool  $throwOnError = false,
    ) {
        parent::__construct( $settings, $lockInjected, $freeze, $throwOnError );
    }
}
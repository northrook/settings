<?php

declare(strict_types=1);

namespace Core\Settings;

use _Dev\Attribute\TODO;

#[TODO]
final class Setting
{
    /** @var string `setting.dot.notated.key` */
    public readonly string $key;

    /** @var string Setting soft-name */
    public readonly string $name;

    /** @var null|array<array-key, scalar>|scalar */
    public readonly null|array|bool|float|int|string $value;

    /** @var array<int,null|array<array-key, scalar>|scalar> */
    protected array $versions = [];

    public function __construct( string $key )
    {
        $this->key = $key;
    }
}

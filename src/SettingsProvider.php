<?php

declare(strict_types=1);

namespace Core;

use _Dev\Attribute\Experimental;
use Core\Interface\SettingsInterface;
use Core\Settings\Setting;
use Core\Exception\NotSupportedException;
use SplFileInfo;
use function Support\{is_path};

#[Experimental]
final class SettingsProvider implements SettingsInterface
{
    // private ?string $hash = null;

    /** @var array<string, null|array<array-key, scalar>|scalar> */
    private readonly array $defaults;

    /** @var array<string, null|array<array-key, scalar>|scalar> */
    protected array $map = [];

    /** @var array<string, Setting> */
    protected array $settings = [];

    protected readonly ?string $filePath;

    /**
     * @param null|string                                         $filePath
     * @param array<string, null|array<array-key, scalar>|scalar> $defaults
     * @param bool                                                $assignMissingDefaults
     * @param bool                                                $allowSettingsReset
     */
    public function __construct(
        ?string                 $filePath = null,
        array                   $defaults = [],
        protected readonly bool $assignMissingDefaults = false,
        protected readonly bool $allowSettingsReset = false,
    ) {
        $this->filePath = $this->parseCachePath( $filePath );
        $this->defaults = $defaults;
        $this->map      = $this->defaults;
    }

    public function has( string $setting ) : bool
    {
        \assert( $this->validateKey( $setting ) );
        return \array_key_exists( $setting, $this->map )
               || \array_key_exists( $setting, $this->settings )
               || \array_key_exists( $setting, $this->defaults );
    }

    public function get(
        string $setting,
        mixed  $default,
    ) : mixed {
        \assert( $this->validateKey( $setting ) );

        if ( \array_key_exists( $setting, $this->map ) ) {
            return $this->map[$setting];
        }

        if ( $this->assignMissingDefaults ) {
            return $this->map[$setting] = $default;
        }
        return $default;
    }

    public function set( string $setting, mixed $set ) : self
    {
        \assert( $this->validateKey( $setting ) );
        $this->map[$setting] = $set;

        // $method = __METHOD__;
        // dump( \get_defined_vars() );

        return $this;
    }

    public function add( string $setting, mixed $add ) : self
    {
        \assert( $this->validateKey( $setting ) );

        $this->map[$setting] ??= $add;

        $method = __METHOD__;
        dump( \get_defined_vars() );

        return $this;
    }

    public function all() : array
    {
        \assert(
            ( function() : bool {
                $map      = \array_keys( $this->map );
                $settings = \array_keys( $this->settings );
                // dump( \get_defined_vars() );
                \ksort( $settings );
                \ksort( $map );
                // dump( \get_defined_vars() );

                return $map === $settings;
            } )(),
        );
        // Validate `$map` contains all `$settings`.
        return $this->map;
    }

    public function reset() : void
    {
        throw new NotSupportedException( __METHOD__.' not implemented yet.' );
    }

    private function parseCachePath( ?string $filePath ) : ?string
    {
        if ( ! $filePath ) {
            return null;
        }

        \assert(
            is_path( $filePath ) && \pathinfo( $filePath, PATHINFO_EXTENSION ),
            "A valid file path is required, '{$filePath}' was provided.",
        );

        if ( ! \file_exists( $filePath ) ) {
            $path = new SplFileInfo( $filePath );
            if ( ! \file_exists( $path->getPath() ) ) {
                \mkdir( $path->getPath(), 0777, true );
            }
            \assert(
                \is_writable( $path->getPath() ),
                "The location '{$path->getPathname()}' is not writable.",
            );
            $filePath = $path->getRealPath() ?: $path->getPathname();
        }

        return $filePath;
    }

    private function validateKey( string $key ) : bool
    {
        return \ctype_alnum( \str_replace( ['.', '_'], '', $key ) );
    }
}

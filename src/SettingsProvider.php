<?php

declare(strict_types=1);

namespace Core;

use _Dev\Attribute\Experimental;
use Core\Exception\NotSupportedException;
use Core\Interface\SettingsProviderInterface;
use Core\Settings\{Setting};
use SplFileInfo;
use function Support\{is_path};

#[Experimental]
final class SettingsProvider implements SettingsProviderInterface
{
    // private ?string $hash = null;

    protected readonly ?string $filePath;

    /** @var array<string, null|array<array-key, scalar>|scalar> */
    protected array $map = [];

    /** @var array<string, Setting> */
    protected array $settings = [];

    /**
     * @param null|string                                         $filePath
     * @param array<string, null|array<array-key, scalar>|scalar> $defaults
     * @param bool                                                $assignMissingDefaults
     * @param bool                                                $allowSettingsReset
     */
    public function __construct(
        ?string                 $filePath = null,
        private readonly array  $defaults = [],
        protected readonly bool $assignMissingDefaults = false,
        protected readonly bool $allowSettingsReset = false,
    ) {
        $this->setDirectory( $filePath );
        $this->map = $this->defaults;
    }

    public function has( string $setting ) : bool
    {
        \assert( $this->validateKey( $setting ) );
        return \array_key_exists( $setting, $this->map )
               || \array_key_exists( $setting, $this->settings )
               || \array_key_exists( $setting, $this->defaults );
    }

    public function get(
        string                           $setting,
        float|array|bool|int|string|null $default,
    ) : null|array|bool|float|int|string {
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

        $method = __METHOD__;
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

    private function setDirectory( ?string $filePath ) : void
    {
        if ( $filePath === null ) {
            return;
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

        $this->filePath = $filePath;
    }

    private function validateKey( string $key ) : bool
    {
        return \ctype_alnum( \str_replace( ['.', '_'], '', $key ) );
    }
}

<?php

declare( strict_types = 1 );

namespace Northrook\Settings;

abstract class AbstractSettings
{
    private static self $instance;

    /** @var SettingsMap Stores the settings, accessible via dot notation syntax */
    public readonly SettingsMap $settings;

    /** @var bool Whether the entire {@see static::$settings} array is locked. */
    protected readonly bool $frozen;

    protected array $locked = [];

    /**
     * @param array  $settings  Initial defaults
     */
    public function __construct(
        array                   $settings = [],
        bool                    $lockInjected = false,
        ?bool                   $freeze = null,
        protected readonly bool $throwOnError = false,
    ) {
        $this->settings = new SettingsMap( $settings, true );


        if ( $lockInjected ) {
            $this->locked = [ ...\array_keys( $settings ) ];
        }

        // Do not set a frozen state by default,
        // the extending class should decide.
        if ( $freeze !== null ) {
            $this->frozen = $freeze;
        }

        // Static access
        $this::$instance = $this;
    }

    /**
     * @param string|array  $setting
     * @param mixed|null    $value
     *
     * @return bool `true` if added, `false` if the setting exists
     */
    final public static function add( string | array $setting, mixed $value = null ) : bool {
        return true;
    }

    /**
     * @param string|array  $setting
     * @param mixed|null    $value
     *
     * @return bool `true` if added, `false` if locked
     */
    final public static function set( string | array $setting, mixed $value = null ) : bool {
        return true;
    }

    /**
     * @param string  $setting
     *
     * @return mixed Returns null on invalid $setting by default
     */
    final public static function get( string $setting ) : mixed {
        return static::settings()->get( $setting );
    }

    // :::: Static Class ::::::::::::

    final public static function instance() : self {
        return self::$instance ??= new static();
    }

    final protected static function settings() : SettingsMap {
        return static::instance()->settings;
    }

    /**
     * Singletons should not be cloned.
     *
     * @return void
     */
    private function __clone() : void {}
}
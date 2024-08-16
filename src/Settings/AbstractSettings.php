<?php

declare( strict_types = 1 );

namespace Northrook\Settings;

use Closure;

abstract class AbstractSettings
{
    private static self $instance;
    /** @var bool Whether the entire {@see static::$settings} array is locked. */
    protected readonly bool $frozen;
    protected array         $locked = [];
    /** @var SettingsMap Stores the settings, accessible via dot notation syntax */
    protected readonly SettingsMap $settings;

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
        return static::instance()->addSetting( $setting, $value );
    }

    /**
     * @param string|array  $setting
     * @param mixed|null    $value
     *
     * @return bool `true` if added, `false` if locked
     */
    final public static function set( string | array $setting, mixed $value = null ) : bool {
        return static::instance()->setSetting( $setting, $value );
    }

    /**
     * @param string  $setting
     *
     * @return mixed Returns null on invalid $setting by default
     */
    final public static function get( string | array $setting ) : mixed {
        return static::instance()->getSetting( $setting );
    }


    public function addSetting( string | array $setting, mixed $value = null ) : bool {

        if ( $this->isLocked( $setting ) || $this->settings->has( $setting ) ) {
            return false;
        }

        if ( $value instanceof Closure ) {
            $value = $value();
        }

        $this->settings->add( $setting, $value );

        return true;
    }

    public function setSetting( string | array $setting, mixed $value = null ) : bool {

        if ( $this->isLocked( $setting ) ) {
            return false;
        };


        $this->settings->set( $setting, $value );

        return true;
    }

    public function getSetting( string | array $setting ) : mixed {

        if ( \is_array( $setting ) ) {
            $settings = [];
            foreach ( $setting as $key ) {
                $settings[ $key ] = $this->getSetting( $key );
            }
            return $settings;
        }

        return $this->settings->get( $setting );
    }

    final public function isFrozen() : bool {
        return $this->frozen ?? false;
    }

    final protected function isLocked( string | array $setting ) : bool {
        foreach ( (array ) $setting as $key ) {
            if ( \in_array( $key, $this->locked ) ) {
                return $this->throwOnError
                    ? throw new \ValueError(
                        "The setting '{$key}' is frozen, and cannot be set or modified at runtime.",
                    )
                    : true;
            }
        }
        return false;
    }


    // :::: Static Class ::::::::::::

    final protected static function instance() : self {
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
<?php

declare( strict_types = 1 );

namespace Core;

use Core\Settings\AbstractSettings;
use InvalidArgumentException;
use LogicException;
use Northrook\Logger\Log;
use Core\SettingsInterface;
use Psr\Log\LoggerInterface;
use Support\Interface\ActionInterface;
use Support\Normalize;
use UnitEnum;
use function Support\getProjectRootDirectory;

final class Settings implements SettingsInterface, ActionInterface
{

    // NOTE: Auto-generation only occurs on missing values
    private const array DEFAULTS = [
            'charset'                 => 'UTF-8',
            'language'                => null,
            'language.locale'         => 'en',
            'language.locale.listAll' => [],
            'dir.root'                => null, // auto-generate - ./
            'dir.var'                 => null, // auto-generate - ./var
            'dir.cache'               => null, // auto-generate - ./var/cache
            'dir.storage'             => null, // auto-generate - ./storage
            'dir.uploads'             => null, // auto-generate - ./storage/uploads
            'dir.assets'              => null, // auto-generate - ./assets
            'dir.public'              => null, // auto-generate - ./public
            'dir.public.assets'       => null, // auto-generate - ./public/assets
            'dir.public.uploads'      => null, // auto-generate - ./public/uploads
    ];

    private const array GENERATE_PATH = [
            'dir.root'           => null,
            'dir.var'            => '/var',
            'dir.cache'          => '/var/cache',
            'dir.storage'        => '/storage',
            'dir.uploads'        => '/storage/uploads',
            'dir.assets'         => '/assets',
            'dir.public'         => '/public',
            'dir.public.assets'  => '/public/assets',
            'dir.public.uploads' => '/public/uploads',
    ];

    public function __construct(
            // array                             $settings = [],
        // bool                              $lockInjected = false,
        // ?bool                             $freeze = null,
        // bool                              $throwOnError = false,
        // private readonly ?LoggerInterface $logger = null,
    )
    {
        // parent::__construct(
        //         $settings, $lockInjected, $freeze, $throwOnError,
        // );
    }

    public function injectSettings( array $settings, bool $lock = false ) : self
    {
        dump( 'TODO ' . __METHOD__ );

        // if ( $lock ) {
        //     $this->locked = [ ...$this->locked, ...\array_keys( $settings ) ];
        // }
        // $this->settings->add( $settings );
        return $this;
    }

    // TODO ::
    // public function getSetting( string | array $setting ) : mixed
    // {
    //     Log::info( 'Requested setting: ' . $setting );
    //     $get = parent::getSetting( $setting )
    //            ?? $this->generate( $setting )
    //               ?? $this::DEFAULTS[ $setting ]
    //                  ?? null;
    //     Log::notice( 'Setting ' . $setting . ' resolved, returning ' . $get );
    //     return $get;
    // }

    private function generate( string $setting ) : mixed
    {
        dump( 'TODO ' . __METHOD__ );
        return null;
        // if ( $setting === 'language' ) {
        //     if ( $this->isFrozen() ) {
        //         return self::DEFAULTS[ 'language.locale' ];
        //     }
        //     else {
        //         $this->settings->set( $setting, self::DEFAULTS[ 'language.locale' ] );
        //     }
        // }
        // elseif ( \array_key_exists( $setting, self::GENERATE_PATH ) ) {
        //     $generated = Normalize::path(
        //             [
        //                     $this->settings->get( 'dir.root' ) ?? getProjectRootDirectory(),
        //                     $this::GENERATE_PATH[ $setting ],
        //             ],
        //     );
        //
        //     $this->logger?->notice(
        //             "Generated {setting}: {result}",
        //             [ 'setting' => $setting, 'result' => $generated, ],
        //     );
        //
        //     if ( $this->isFrozen() ) {
        //         return $generated;
        //     }
        //     else {
        //         $this->settings->set( $setting, $generated );
        //     }
        // }
        //
        // return $this->settings->get( $setting );
    }

    /**
     * @param string                                      $setting
     * @param mixed|null                                  $default
     * @param null|string                                 $set
     * @param null|\UnitEnum|float|int|bool|array|string  $value
     *
     * @return mixed
     */
    public function get(
            string                                                $setting, mixed $default = null,
            ?string                                               $set = null,
            UnitEnum | float | int | bool | array | string | null $value = null,
    ) : mixed
    {
        dump( 'TODO ' . __METHOD__ );
        return null;
    }

    /**
     * @param string    $settings
     * @param null|int  $limit
     *
     * @return array
     */
    public function versions( string $settings, ?int $limit = null ) : array
    {
        dump( 'TODO ' . __METHOD__ );
        return [];
    }

    /**
     * @param string  $setting
     * @param int     $versionId
     *
     * @return bool
     */
    public function restore( string $setting, int $versionId ) : bool
    {
        dump( 'TODO ' . __METHOD__ );
        return false;
    }

    /**
     * @param array  $parameters
     *
     * @return void
     */
    public function add( array $parameters ) : void
    {
        dump( 'TODO ' . __METHOD__ );
    }

    /**
     * @param string  $setting
     *
     * @return bool
     */
    public function has( string $setting ) : bool
    {
        dump( 'TODO ' . __METHOD__ );
        return false;
    }

    /**
     * @return array
     */
    public function all() : array
    {
        dump( 'TODO ' . __METHOD__ );
        return [];
    }

    /**
     * @return void
     */
    public function reset() : void
    {
        dump( 'TODO ' . __METHOD__ );
    }

    /**
     * @param string  $name
     *
     * @return void
     */
    public function remove( string $name ) : void
    {
        dump( 'TODO ' . __METHOD__ );
    }

    /**
     * @param string                                      $name
     * @param null|array|bool|string|int|float|\UnitEnum  $value
     *
     * @return void
     */
    public function set( string $name, UnitEnum | float | int | bool | array | string | null $value ) : void
    {
        dump( 'TODO ' . __METHOD__ );
    }
}

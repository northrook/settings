<?php

declare( strict_types = 1 );

namespace Northrook;

use Northrook\Logger\Log;
use Northrook\Settings\AbstractSettings;
use Northrook\Settings\SettingsInterface;
use Psr\Log\LoggerInterface;


final class Settings extends AbstractSettings implements SettingsInterface
{

    // NOTE: Auto-generation only occurs on missing values
    private const DEFAULTS = [
        'dir.root'              => null, // auto-generate - ./
        'dir.var'               => null, // auto-generate - ./var
        'dir.cache'             => null, // auto-generate - ./var/cache
        'dir.storage'           => null, // auto-generate - ./storage
        'dir.uploads'           => null, // auto-generate - ./storage/uploads
        'dir.assets'            => null, // auto-generate - ./assets
        'dir.public'            => null, // auto-generate - ./public
        'dir.public.assets'     => null, // auto-generate - ./public/assets
        'dir.public.uploads'    => null, // auto-generate - ./public/uploads
        'unusual.default.value' => 'dingleberries',
    ];

    private const GENERATE_PATH = [
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
        array                             $settings = [],
        bool                              $lockInjected = false,
        ?bool                             $freeze = null,
        bool                              $throwOnError = false,
        private readonly ?LoggerInterface $logger = null,
    ) {
        parent::__construct(
            $settings, $lockInjected, $freeze, $throwOnError,
        );
    }

    public function injectSettings( array $settings, bool $lock = false ) : self {
        if ( $lock ) {
            $this->locked = [ ...$this->locked, ...\array_keys( $settings ) ];
        }
        $this->settings->add( $settings );
        return $this;
    }

    public function getSetting( string | array $setting ) : mixed {
        Log::info( 'Requested setting: ' . $setting );
        $get = parent::getSetting( $setting )
               ?? $this->generate( $setting )
                  ?? $this::DEFAULTS[ $setting ]
                     ?? null;
        Log::notice( 'Setting ' . $setting . ' resolved, returning ' . $get );
        return $get;
    }

    private function generate( string $setting ) : mixed {

        if ( \array_key_exists( $setting, self::GENERATE_PATH ) ) {

            $generated = normalizePath(
                [
                    $this->settings->get( 'dir.root' ) ?? getProjectRootDirectory(),
                    $this::GENERATE_PATH[ $setting ],
                ],
            );

            $this->logger?->notice(
                "Generated {setting}: {result}",
                [ 'setting' => $setting, 'result' => $generated, ],
            );

            if ( $this->isFrozen() ) {
                return $generated;
            }
            else {
                $this->settings->set( $setting, $generated );
            }
        }

        return $this->settings->get( $setting );
    }
}
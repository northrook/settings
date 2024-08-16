<?php

namespace Northrook\Settings;

interface SettingsInterface
{
    public function addSetting( string | array $setting, mixed $value = null ) : bool;

    public function setSetting( string | array $setting, mixed $value = null ) : bool;

    public function getSetting( string | array $setting ) : mixed;

    public function injectSettings( array $settings, bool $lock = false ) : self;
}
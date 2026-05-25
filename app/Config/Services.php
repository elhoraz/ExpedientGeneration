<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function authService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('authService');
        }
        return new \App\Services\AuthService();
    }

    public static function profileService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('profileService');
        }
        return new \App\Services\ProfileService();
    }

    public static function radarService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('radarService');
        }
        return new \App\Services\RadarService();
    }

    public static function gamificationService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('gamificationService');
        }
        return new \App\Services\GamificationService();
    }

    public static function nexusService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('nexusService');
        }
        return new \App\Services\NexusService();
    }

    public static function berandaService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('berandaService');
        }
        return new \App\Services\BerandaService();
    }

    public static function pusherService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('pusherService');
        }
        return new \App\Services\PusherService();
    }

    public static function wasiatService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('wasiatService');
        }
        return new \App\Services\WasiatService();
    }

    public static function syndicateService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('syndicateService');
        }
        return new \App\Services\SyndicateService();
    }

    public static function analyticsService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('analyticsService');
        }
        return new \App\Services\AnalyticsService();
    }

    public static function whatsAppService($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('whatsAppService');
        }
        return new \App\Services\WhatsAppService();
    }
}

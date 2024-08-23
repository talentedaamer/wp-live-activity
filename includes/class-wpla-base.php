<?php
/**
 * Base class for all wpla classes.
 *
 * @author talentedaamer
 */
abstract class WplaBase {
    protected static $version;
    protected static $cache_key_users;
    protected static $cache_key_comments;
    protected static $cache_users_expiry;
    protected static $cache_comments_expiry;
    protected static $date_format;
    protected static $time_format;

    public function __construct() {}

    public function get_version() {
        return self::$version;
    }

    public function get_cache_key_users() {
        return self::$cache_key_users;
    }

    public function get_cache_key_comments() {
        return self::$cache_key_comments;
    }

    public function get_cache_users_expiry() {
        return self::$cache_users_expiry;
    }

    public function get_cache_comments_expiry() {
        return self::$cache_comments_expiry;
    }

    public function get_date_format() {
        return self::$date_format;
    }

    public function get_time_format() {
        return self::$time_format;
    }
}

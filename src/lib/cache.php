<?php

//修改时间2020年7.7日5.14
    !defined('CACHE_PATH') && define('CACHE_PATH', sys_get_temp_dir().'/');
    class cache
    {
        // 驱动方式（支持filecache/memcache/secache/redis）
        public static $type = 'filecache';

        // 返回缓存实例
        protected static function c()
        {
            static $instance = null;
            if (!is_null($instance)) {
                return $instance;
            }

            list($type, $config) = explode(':', self::$type, 2);

            $type .= '_';
            if (in_array($type, array('filecache_', 'memcache_', 'secache_', 'redis_'))) {
                $file = str_replace('\\', '/', dirname(__FILE__)).'/cache/'.$type.'.php';
                include_once $file;
                $instance = new $type($config);

                return $instance;
            }
        }

        // 获取缓存
        public static function get($key, $default = null, $expire = 99999999)
        {
            $value = self::c()->get($key);
            if (!is_null($value) && $value != false) {
                return $value;
            } elseif (is_callable($default)) {
                $value = $default();
                self::set($key, $value, $expire);

                return $value;
            } elseif (!is_null($default)) {
                self::set($key, $default, $expire);

                return $default;
            }
        }

        // 设置缓存
        public static function set($key, $value, $expire = 99999999)
        {
            return self::c()->set($key, $value, $expire);
        }

        // 清空缓存
        public static function clear()
        {
            return self::c()->clear();
        }

        // 删除缓存
        public static function del($key)
        {
            return self::set($key, null);
        }

        public static function gettime($key)
        {
            return self::c()->gettime($key);
        }

        // 判断缓存是否设置
        public static function has($key)
        {
            if (is_null(self::get($key))) {
                return false;
            } else {
                return true;
            }
        }

        // 读取并删除缓存
        public static function pull($key)
        {
            $value = self::get($key);
            self::del($key);

            return $value;
        }

        //刷新缓存
        public static function refresh_cache($path, $next = true)
        {
            $path2 = onedrive::urlencode($path);
            set_time_limit(0);
            if (php_sapi_name() == 'cli') {
                echo $path2.PHP_EOL;
            }
            $items = onedrive::dir($path2);
            if (is_array($items)) {
                self::set('dir_'.$path2, $items, config('cache_expire_time'));
            }
            if ($next) {
                foreach ((array) $items as $item) {
                    if ($item['folder']) {
                        self::refresh_cache($path.$item['name'].'/');
                    }
                }
            }
        }

        public static function clear_opcache()
        {
            // 清除php文件缓存
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
        }
    }

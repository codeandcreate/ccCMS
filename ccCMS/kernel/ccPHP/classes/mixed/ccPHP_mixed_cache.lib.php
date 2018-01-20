<?php
/**
 * ccPHP_core_settings - Mixed Caching Lib, for a simple file based cache
 *
 * @version 0.0 - initial version
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_mixed_cache extends ccPHP_base
{
	/*
	 * default cache folder
	 */
	private static $cachePrefix = "ccPHP_cache/";
	
    /**
	 * Drops a cache
	 *
	 * @param $cacheKey
	 * @return bool
	 */
	public static function drop($cacheKey)
	{
		$cachePath = GLOBAL_CACHE_DIR . self::$cachePrefix . self::generateInternalCacheKey($cacheKey);
		if (file_exists($cachePath)) {
			unlink($cachePath);

			return false;
		}

		return true;
	}

	/**
	 * Returns cached data, if exists.
	 *
	 * @param $cacheKey
	 * @return integer
	 */
	public static function getDate($cacheKey)
	{
		$cachedDataTimeStamp = 0;
		$cachePath = GLOBAL_CACHE_DIR . self::$cachePrefix . self::generateInternalCacheKey($cacheKey);

		if (file_exists($cachePath)) {
			include $cachePath;
			return $cachedDataTimeStamp;
		}

		return false;
	}

	/**
	 * Returns cached data, if exists.
	 * if $cacheTime is null, cached data is never invalid
	 *
	 * @param              $cacheKey
	 * @param null|integer $cacheTime
	 * @return bool|mixed
	 */
	public static function get($cacheKey, $cacheTime = null)
	{
		$cachePath = GLOBAL_CACHE_DIR . self::$cachePrefix . self::generateInternalCacheKey($cacheKey);

		$cachedData          = false;
		$cachedDataIsObject  = false;
		$cachedDataTimeStamp = 0;
		$persistentCache     = false;
		if (file_exists($cachePath)) {
			include $cachePath;
		}

		if (file_exists($cachePath)) {
			if (($cacheTime === null OR ($cachedDataTimeStamp + $cacheTime) > time())) {
				if ($cachedDataIsObject) {
					$cachedData = unserialize($cachedData);
				}

				return $cachedData;
			} else if ($persistentCache != true) {
				unlink($cachePath);
			}
		}

		return false;
	}

	/**
	 * Saves $cachedData
	 *
	 * @param string $cacheKey
	 * @param mixed $cacheData
	 * @return bool
	 */
	public static function set($cacheKey, $cacheData, $persistentCache = false)
	{
		if (!empty($cacheKey)) {

			$cachedDataIsObject = false;
			if (is_object($cacheData)) {
				$cachedDataIsObject = true;
				$cacheData          = serialize($cacheData);
			}

			$cachePath     = GLOBAL_CACHE_DIR . self::$cachePrefix . self::generateInternalCacheKey($cacheKey);
			$exportedArray = "<?php \$cachedDataTimeStamp = " . time() . "; \$creationtime = '" . date(
					"Y-m-d H:i:s"
				) . "'; \$persistentCache = ".($persistentCache ? "true" : "false")."; \$cachedDataIsObject = " . (int)$cachedDataIsObject . "; \$cachedData = " . var_export(
					$cacheData, true
				) . "; ?>";
			file_put_contents($cachePath . "_temp", $exportedArray);

			if (file_exists($cachePath)) {
				unlink($cachePath);
			}

			rename($cachePath . "_temp", $cachePath);

			return true;
		}

		return false;
	}

	/**
	 * To find a cache file
	 *
	 * @param $cacheKey
	 * @return string
	 */
	public static function getHachedCacheKey($cacheKey)
	{
		return self::generateInternalCacheKey($cacheKey);
	}

	/**
	 * Generates a hash of a $cacheKey
	 *
	 * @param string $cacheKey
	 * @return string
	 */
	private static function generateInternalCacheKey($cacheKey)
	{
		if (!defined("GLOBAL_CACHE_DIR")) {
			die("no GLOBAL_CACHE_DIR defined");
		}
		
		$cacheKey = sha1($cacheKey);
		$chachedPath = substr($cacheKey, 0, 2) . "/";
		if (!is_dir(GLOBAL_CACHE_DIR . self::$cachePrefix . $chachedPath )) {
			mkdir(GLOBAL_CACHE_DIR . self::$cachePrefix . $chachedPath, 0777, true);
		}

		return $chachedPath . $cacheKey;
	}

	/**
	 * Drops all non-persistent Caches
	 */
	public static function cleanUpCaches()
	{
		$mainDirList = scandir(GLOBAL_CACHE_DIR . self::$cachePrefix);
		foreach ($mainDirList AS $subDir) {
			if (
				($subDir !== "." || $subDir !== "..") &&
				is_dir(GLOBAL_CACHE_DIR . self::$cachePrefix . $subDir)
			) {
				$subdirList = scandir(GLOBAL_CACHE_DIR . self::$cachePrefix. $subDir);
				foreach ($subdirList AS $cacheFileName) {
					if (
						($cacheFileName !== "." || $cacheFileName !== "..") &&
						!is_dir(GLOBAL_CACHE_DIR . self::$cachePrefix . $subDir . "/" . $cacheFileName)
					) {
						$persistentCache     = false;
						include (GLOBAL_CACHE_DIR . self::$cachePrefix . $subDir . "/" . $cacheFileName);

						if ($persistentCache != true) {
							unlink(GLOBAL_CACHE_DIR . self::$cachePrefix . $subDir . "/" . $cacheFileName);
						}
					}
				}
			}
		}
	}
}
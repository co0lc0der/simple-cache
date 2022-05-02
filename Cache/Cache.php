<?php
/**
 * Class Cache
 */
class Cache
{
	/**
	 * default cache period in seconds
	 */
	const DEFAULT_PERIOD = 3600;

	/**
	 * singleton instance
	 * @var null|Cache $instance
	 */
	private static $instance;

	/**
	 * a path to store cache files
	 * @var string
	 */
	protected static string $cachePath;

	/**
	 * @param string $path
	 * @return Cache
	 */
	public static function getInstance(string $path = './cache'): Cache
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}

		self::$cachePath = $path;

		return self::$instance;
	}

	/**
	 * @param string $key
	 * @param $data
	 * @param int $period
	 * @return bool
	 */
  public function set(string $key, $data, int $period = self::DEFAULT_PERIOD): bool
	{
		if (!is_dir(self::$cachePath)) {
			mkdir(self::$cachePath);
			chmod(self::$cachePath, 0777);
		}

    if ($period) {
	    $cacheFile = self::$cachePath . '/' . md5($key) . '.txt';
      $cache['data'] = $data;
	    $cache['end'] = time() + $period;

      if (file_put_contents($cacheFile, serialize($cache))) {
				chmod($cacheFile, 0777);
        return true;
      }
    }

    return false;
  }

	/**
	 * @param string $key
	 * @return false|mixed
	 */
  public function get(string $key)
	{
	  $cacheFile = self::$cachePath . '/' . md5($key) . '.txt';

    if (file_exists($cacheFile)) {
      $cached = unserialize(file_get_contents($cacheFile));

			if (time() <= $cached['end']) return $cached['data'];

      unlink($cacheFile);
    }

    return false;
  }

	/**
	 * @param string $key
	 * @param callable $dataFunc
	 * @param int $period
	 * @return mixed
	 */
	public function getOrSet(string $key, callable $dataFunc, int $period = self::DEFAULT_PERIOD)
	{
		if (!$data = $this->get($key)) {
			$data = call_user_func($dataFunc);
			$this->set($key, $data, $period);
		}

		return $data;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
  public function delete(string $key): bool
	{
    $cacheFile = self::$cachePath . '/' . md5($key) . '.txt';

		if (file_exists($cacheFile)) {
      unlink($cacheFile);
			return true;
    }

		return false;
  }

	/**
	 * @return void
	 */
	public static function clear(): void
	{
		foreach (glob(self::$cachePath . '/*.txt') as $file) {
			unlink($file);
		}
	}
}

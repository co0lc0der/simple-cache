<?php

class Cache
{
	const DEFAULT_PERIOD = 3600;
	private static $instance;
	public static string $cache_path = '';

	public static function getInstance(string $path): Cache
	{
		if (self::$instance === null) {
			self::$instance = new self;
		}

		self::$cache_path = $path;

		return self::$instance;
	}

  public function set(string $key, $data, int $period = self::DEFAULT_PERIOD): bool
	{
		if (!is_dir(self::$cache_path)) {
			mkdir(self::$cache_path);
			chmod(self::$cache_path, 0777);
		}

    if ($period) {
	    $cache_file = self::$cache_path . '/' . md5($key) . '.txt';
      $cache['data'] = $data;
	    $cache['end'] = time() + $period;

      if (file_put_contents($cache_file, serialize($cache))) {
				chmod($cache_file, 0777);
        return true;
      }
    }

    return false;
  }

  public function get(string $key)
	{
	  $cache_file = self::$cache_path . '/' . md5($key) . '.txt';

    if (file_exists($cache_file)) {
      $cached = unserialize(file_get_contents($cache_file));

			if (time() <= $cached['end']) return $cached['data'];

      unlink($cache_file);
    }

    return false;
  }

  public function delete(string $key): bool
	{
    $cache_file = self::$cache_path . '/' . md5($key) . '.txt';

		if (file_exists($cache_file)) {
      unlink($cache_file);
			return true;
    }

		return false;
  }
}

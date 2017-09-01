<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rewrite the CI_Cache_redis  Class
 * @author  libinyan@mofly.cn
 */
class CI_Cache_redis extends CI_Driver
{
	/**
	 * Default config
	 *
	 * @static
	 * @var	array
	 */
	protected static $_default_config = array(
		'socket_type' => 'tcp',
		'host' => '127.0.0.1',
		'password' => NULL,
		'port' => 6379,
		'timeout' => 0
	);

	/**
	 * Redis connection
	 *
	 * @var	Redis
	 */
	protected $_redis;

	/**
	 * An internal cache for storing keys of serialized values.
	 *
	 * @var	array
	 */
	protected $_serialized = array();

	/**
	 * Get cache
	 *
	 * @param	string	Cache ID
	 * @return	mixed
	 */
	public function get($key)
	{
		$value = $this->_redis->get($key);
		if ($value !== FALSE && isset($this->_serialized[$key])) {
			return unserialize($value);
		}
		return $value;
	}

	/**
	 * Save cache
	 *
	 * @param	string	$id	Cache ID
	 * @param	mixed	$data	Data to save
	 * @param	int	$ttl	Time to live in seconds
	 * @param	bool	$raw	Whether to store the raw value (unused)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function save($id, $data, $ttl = 60, $raw = FALSE)
	{
		if (is_array($data) OR is_object($data)) {
			if ( ! $this->_redis->sIsMember('_ci_redis_serialized', $id) 
			    && ! $this->_redis->sAdd('_ci_redis_serialized', $id)) {
				return FALSE;
			}

			isset($this->_serialized[$id]) OR $this->_serialized[$id] = TRUE;
			$data = serialize($data);
			
		} elseif (isset($this->_serialized[$id])) {
			$this->_serialized[$id] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $id);
		}

		return ($ttl)
			? $this->_redis->setex($id, $ttl, $data)
			: $this->_redis->set($id, $data);
	}

	/**
	 * Delete from cache
	 * @param	string	Cache key
	 * @return	bool
	 */
	public function delete($key)
	{
		if ($this->_redis->delete($key) !== 1) {
			return FALSE;
		}

		if (isset($this->_serialized[$key])) {
			$this->_serialized[$key] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $key);
		}

		return TRUE;
	}

	/**
	 * Increment a raw value
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to add
	 * @return	mixed	New value on success or FALSE on failure
	 */
	public function increment($id, $offset = 1)
	{
		return $this->_redis->incr($id, $offset);
	}

	/**
	 * Decrement a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to reduce by
	 * @return	mixed	New value on success or FALSE on failure
	 */
	public function decrement($id, $offset = 1)
	{
		return $this->_redis->decr($id, $offset);
	}

	/**
	 * Clean cache
	 * @return	bool
	 * @see		Redis::flushDB()
	 */
	public function clean()
	{
		return $this->_redis->flushDB();
	}

	/**
	 * Get cache driver info
	 *
	 * @param	string	Not supported in Redis.
	 *			Only included in order to offer a
	 *			consistent cache API.
	 * @return	array
	 * @see		Redis::info()
	 */
	public function cache_info($type = NULL)
	{
		return $this->_redis->info();
	}

	/**
	 * Get cache metadata
	 * @param	string	Cache key
	 * @return	array
	 */
	public function get_metadata($key)
	{
		$value = $this->get($key);
		if ($value) {
			return array(
				'expire' => time() + $this->_redis->ttl($key),
				'data' => $value
			);
		}
		return FALSE;
	}

	/**
	 * Check if Redis driver is supported
	 * @return	bool
	 */
	public function is_supported()
	{
		if ( ! extension_loaded('redis')) {
			log_message('debug', 'The Redis extension must be loaded to use Redis cache.');
			return FALSE;
		}
		return $this->_setup_redis();
	}

	/**
	 * Setup Redis config and connection
	 *
	 * Loads Redis config file if present. Will halt execution
	 * if a Redis connection can't be established.
	 * @return	bool
	 * @see		Redis::connect()
	 */
	protected function _setup_redis()
	{
		$config = array();
		$CI =& get_instance();

		if ($CI->config->load('redis', TRUE, TRUE)) {
			$config += $CI->config->item('redis');
		}
		$config = array_merge(self::$_default_config, $config);
		$this->_redis = new Redis();

		try {
			if ($config['socket_type'] === 'unix') {
				$success = $this->_redis->connect($config['socket']);
				
			} else {
				$success = $this->_redis->connect($config['host'], $config['port'], $config['timeout']);
			}

			if ( ! $success) {
				log_message('debug', 'Cache: Redis connection refused. Check the config.');
				return FALSE;
			}
		} catch (RedisException $e) {
			log_message('debug', 'Cache: Redis connection refused ('.$e->getMessage().')');
			return FALSE;
		}

		if (isset($config['password'])) {
		    $this->_redis->auth($config['password']);
		}
		
		if (isset($config['cachedb'])) {
			$this->_redis->select($config['cachedb']);
		}

		// Initialize the index of serialized values.
		$serialized = $this->_redis->sMembers('_ci_redis_serialized');
		if ( ! empty($serialized)) {
			$this->_serialized = array_flip($serialized);
		}

		return TRUE;
	}

	/**
	 * Class destructor
	 * Closes the connection to Redis if present.
	 * @return	void
	 */
	public function __destruct()
	{
		if ($this->_redis) {
			$this->_redis->close();
		}
	}

	/** user defind function *******************/

	//定义使用的数据库
	public function select_db($db)
	{
	    $this->_redis->select($db);
	    return $this;
	}

	//调用此方法获得redis实例，使用后必须调用 $redis->close() 释放资源
	public function redis_instance()
	{
	    return $this->_redis;
	}
	
	
}

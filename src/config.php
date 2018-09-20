<?php

namespace dsda\config;

class config {

	private		$dbtype				= 'sqlite';			// sqlite/mysql
	private		$cookiename			= 'dsda';
	/**
	 * BELOW THIS LINE DO NOT EDIT ANYTHING
	 */
	
	protected	$themepath	= false;
	protected	$themeurl	= false;
	private		$url		= false;
	private		$path		= false;
	private		$libPath	= null;

	private		$debug		= true;
	
	private		$salt		= NULL; 

	private		$dbconfig	= array(
		'dbpath'		=> 'data/sqlite.db',
		'dbpassword'	=> '',
		'dbattributes'	=>	array(
			'ATTR_ERRMODE'					=> 'ERRMODE_EXCEPTION',
		),
		'dbsqliteattr'	=>	SQLITE3_OPEN_READWRITE,
		'dbname'		=>	'databasename',
		'charset'		=>	'utf8',
		'dboptions' 	=>	array(
			'PDO::MYSQL_ATTR_INIT_COMMAND'	=> 'set names utf8',
		),
	);



	function __construct(){
        $this->url = $this->get__global_url();
		$this->path = $this->get__global_path();
		$this->libPath = dirname(__FILE__);
		if (!file_exists($this->libPath.'/data') || !file_exists($this->libPath.'/data/salt.php')) {
			$salt = '';
			for($i=0;$i<=32;$i++){ 
				$skparr = [34,39,47,92]; $z=rand(33,126); if (!in_array($z,$skparr)) 
				$salt .= chr($z); 
			}
			if (!file_exists($this->libPath.'/data')) {
				mkdir($this->libPath.'/data');
			}
			file_put_contents($this->libPath.'/data/salt.php', '<?php $salt_pregen="'.$salt.'";');
		}
		include($this->libPath.'/data/salt.php');
		$this->set('salt', $salt_pregen);
	}

    private function get__global_url(){
        $scheme = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
            $scheme = 'https://';
        }

        $url = false;
        if (isset($_SERVER['HTTP_HOST'])) {
            $url = $scheme.$_SERVER['HTTP_HOST'];
        } else {
            $url = $scheme.'localhost';
        }
        return $url;
    }

    private function get__global_path(){
		$reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
		$vendorDir = dirname(dirname(dirname($reflection->getFileName())));
        return $vendorDir;
    }

	function get($valuename) {
		return isset($this->$valuename) ? $this->$valuename : false;
	}

	function set($valuename, $valuedata) {
		return $this->$valuename = $valuedata;
	}
	
}

?>
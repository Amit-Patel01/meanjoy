<?php
// Load environment-specific DB credentials if provided.
// Copy includes/db_config.php.sample to includes/db_config.php and fill with your InfinityFree (or other) credentials.
if (file_exists(__DIR__ . '/db_config.php')) {
	include __DIR__ . '/db_config.php';
} else {
	// Defaults for local development (XAMPP)
	$db_host = 'localhost';
	$db_name = 'ecomm';
	$db_user = 'root';
	$db_pass = '';
}

Class Database{

	private $server;
	private $username;
	private $password;
	private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
	protected $conn;
    
	public function __construct()
	{
		// use globals set by includes/db_config.php or the defaults above
		global $db_host, $db_name, $db_user, $db_pass;
		$this->server = "mysql:host={$db_host};dbname={$db_name}";
		$this->username = $db_user;
		$this->password = $db_pass;
	}

	public function open(){
		try{
			$this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
			return $this->conn;
		}
		catch (PDOException $e){
			echo "There is some problem in connection: " . $e->getMessage();
		}
 
	}
 
	public function close(){
		$this->conn = null;
	}
 
}

$pdo = new Database();
 
?>
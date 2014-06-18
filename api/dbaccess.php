<?php

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'takada');
define('DB_USERNAME', 'ogbeks');
define('DB_PASSWORD', 'samchuks');

 class DbAccess {
	private static $_Instance = null;
	private function __Construct()
	{

	}

	// A method to get our singleton instance
public static function getInstance()
{
if (!(self::$_Instance instanceof DbAccess)) {
self::$_Instance = new DbAccess();
}
return self::$_Instance;
}
function getPDO() {
static $pdo;
if (!isset($pdo)) {
$dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT .
';dbname=' . DB_NAME;
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
$pdo->exec('set session sql_mode = traditional');
}
return $pdo;
}

public function query($sql, $input_parameters = null, &$insert_id = null) {
$pdo = $this->getPDO();
$insert_id = null;
if (is_null($input_parameters))
$stmt = $pdo->query($sql);
else {
$stmt = $pdo->prepare($sql);
$stmt->execute($input_parameters);
}
if (stripos($sql, 'insert ') === 0)

$insert_id = $pdo->lastInsertId();
return $stmt;
}

public function insertupdate($table, $pkfield, $fields, $data,
&$row_count = null) {
$input_parameters = array();
$upd = '';
foreach ($fields as $f) {
if (!isset($data[$f]) || is_null($data[$f]))
$v = 'NULL';
else {
$v = ":$f";
$input_parameters[$f] = $data[$f];
}
$upd .= ", $f=$v";
}
$upd = substr($upd, 2);
if (empty($data[$pkfield]))
$sql = "insert $table set $upd";
else {
$input_parameters[$pkfield] = $data[$pkfield];
$sql = "update $table set $upd
where $pkfield = :$pkfield";
}

$stmt = $this->query($sql, $input_parameters, $insert_id);
$row_count = $stmt->rowCount();
return $insert_id;
}
}
?>
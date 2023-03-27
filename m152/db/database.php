<?php
/**
 * @remark Mettre le bon chemin d'accès à votre fichier contenant les constantes
 */
require_once './config/conparam.php';

/**
 * @brief	Helper class encapsulating the PDO object
 * @author 	M.Aigroz
 * @remark	
 */
class EDatabase {
	/**
	 * @var PDO The static PDO object instance created within getInstance()
	 */
	private static $objInstance;
	/**
	 * @brief	Class Constructor - Create a new database connection if one doesn't exist
	 * 			Set to private so no-one can create a new instance via ' = new EDatabase();'
	 */
	private function __construct() {}
	/**
	 * @brief	Like the constructor, we make __clone private so nobody can clone the instance
	 */
	private function __clone() {}
	/**
	 * @brief	Returns DB instance or create initial connection
	 * @return $objInstance;
	 */
	private static function getInstance() {
		if(!self::$objInstance){
			try{
					
				$dsn = EDB_DBTYPE.':host='.EDB_HOST.';port='.EDB_PORT.';dbname='.EDB_DBNAME;
			   	self::$objInstance = new PDO($dsn, EDB_USER, EDB_PASS);
				self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$objInstance->exec("SET CHARACTER SET utf8");
			}catch(PDOException $e ){
				echo "EDatabase Error: ".$e;
			}
		}
		return self::$objInstance;
	} # end method
	/**
	 * @brief	Passes on any static calls to this class onto the singleton PDO instance
	 * @param 	$chrMethod		The method to call
	 * @param 	$arrArguments	The method's parameters
	 * @return 	$mix			The method's return value
	 */
	final public static function __callStatic($chrMethod, $arrArguments) {
		$objInstance = self::getInstance();
		return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
	} # end method
}

<?php
/** * Author: Siddiqui Noor, Technical Director, TechnoDhaka.com
	* www.SiddiquiNoor.com
	* This is a Database wrapper class usages the PHP PDO object
	* 
	*  
	* Licensed under the Apache License, Version 2.0 (the "License"); 
	* you may not use this file except in compliance with the License. 
	* You may obtain a copy of the License at 
	* http://www.apache.org/licenses/LICENSE-2.0 
	*  
	* Unless required by applicable law or agreed to in writing, software 
	* distributed under the License is distributed on an "AS IS" BASIS, 
	* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
	* See the License for the specific language governing permissions and 
	* limitations under the License. 
	*/


if ( ! defined('DSN')) exit('No direct script access allowed');

class DBEngine {
    
    private $dsn    = DSN;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $debug  = FALSE;
 
    private $db;
    private $error;
    private $stmt;

    public function query($query){
        $this->stmt = $this->db->prepare($query);
    }
    
    
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    
    public function execute(){
        return $this->stmt->execute();
    }
    
    public function resultset(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function rowCount(){
        return $this->stmt->rowCount();
    }
    
    public function lastInsertId(){
        return $this->db->lastInsertId();
    }
    
    public function beginTransaction(){
        return $this->db->beginTransaction();
    }
    
    public function endTransaction(){
        return $this->db->commit();
    }
    
    public function cancelTransaction(){
        return $this->db->rollBack();
    }
    
    public function debugDumpParams(){
        return $this->stmt->debugDumpParams();
    }
    
    
    public function __construct()
    {
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );
        // Create a new PDO instanace
        try{
            $this->db = new PDO($this->dsn, $this->user, $this->pass);
			//$this->db = new PDO("dblib:host=lnv7cnxmuy.database.windows.net", "sqladmin", "P@ssw0rd123");
        }
        // Catch any errors
        catch(PDOException $e){
            $this->error = $e->getMessage();
        }
    }
    
    
    public function set_debug($debug)
    {
        $this->debug = $debug;
    }
    
    private function get_debug()
    {
        return $this->debug();
    }
    
    public function print_here($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }
    
} // End class DBEngine
?>
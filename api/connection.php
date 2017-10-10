<?php
/** * Author: Siddiqui Noor, Technical Director, TechnoDhaka.com
	* www.SiddiquiNoor.com
	* This file contains the connection parameter
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
	
	/**
	*************************************************************
	************************ LIVE SERVER ************************
	*************************************************************

	*********************
	* WINDOWS CONFIGURATION
	*********************
	*/

	# the host used to access DB
	define('DB_HOST', "tcp:cc9lyrxk6o.database.windows.net,1433");
	# for local connection
	//define('DB_HOST', "FAISAL\SQLEXPRESS");

	# the username used to access DB
	define('DB_USER', 'pciglobal');
	# loca; user name used to access DB
	//define('DB_USER', 'sa');

	# the password for the username
	define('DB_PASS', 'University1');
	# local password for the username
	//define('DB_PASS', '123');

	# the name of the databse 
	
	define('DB_NAME', 'GpathDBUAT');
	# local the name of the database 	
	//define('DB_NAME', 'GPathDB-2016-6-1-12-30'); 
	
	//define('DB_NAME', 'GPathDB'); // this is live db

	# the dsn used to access DB
	define('DSN', "sqlsrv:Server= " . DB_HOST . " ; Database = " . DB_NAME );

?>
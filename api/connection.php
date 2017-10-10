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
 
 	define('DB_HOST', "WIN-PCH511LD625\SQLEXPRESS");

 	# the username used to access DB
 
 	define('DB_USER', 'sa');

 	# the password for the username
 
 	define('DB_PASS', 'TD@haka');

 	# the name of the databse
	define('DB_NAME', 'GpathDBUAT');
	

	# the dsn used to access DB
	define('DSN', "sqlsrv:Server= " . DB_HOST . " ; Database = " . DB_NAME );

?>
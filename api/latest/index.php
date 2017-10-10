<?php

/** * Author: Siddiqui Noor, Technical Director, TechnoDhaka.com
	* www.SiddiquiNoor.com
	* This controller is to handle all API requests of the PCI App
	* Accepts GET and POST
	* 
	* Each request will be identified by Key and func
	* Response will be JSON data
	* This is how can check GET request
	* if(isset($_GET['check']))
	*	{
	*		$_POST['key'] = 'PhEUT5R251';
	*		$_POST['task'] = 'is_valid_user';
	*		
	*		$_POST['user_name'] = 'test';
	*		$_POST['password'] = '1';
	*	}
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
 * check for POST request 
 */

if( isset($_GET['check']) ){
	$_POST['key'] = 'PhEUT5R251';
	$_POST['task']= 'is_valid_user';
	$_POST['user_name'] = 'nkalam';
	$_POST['password'] = 'p3';
}

if ( isset($_POST['key']) && !empty( $_POST['key'] ) )
{
	$key = $_POST['key'];
	$data = array();
	
	require_once 'connection.php';
	require_once "class.DBEngine.php";
	require_once "class.PCIModel.php";
	
	if( validate_key( $key ) )
	{
		if ( isset( $_POST['task'] ) && $_POST['task'] != '' ) 
		{
			$task = $_POST['task'];
			
			$db = new PCIModel();
			
			switch ($task){
				
				case 'add_registration_data':
					
					//$ID 			= $_POST['ID'];
					
					$C_CODE 		= $_POST['C_CODE'];
					
					//$StfCode 		= $_POST['StfCode'];
					
					$DistrictName 	= $_POST['DistrictName'];
					$UpazillaName 	= $_POST['UpazillaName'];
					$UnitName 		= $_POST['UnitName'];
					$VillageName 	= $_POST['VillageName'];
					
					$RegistrationID = $_POST['RegistrationID'];
					$RegDate 		= $_POST['RegDate'];
					$PersonName 	= $_POST['PersonName'];
					$SEX 			= $_POST['SEX'];
					$HHSize			= $_POST['HHSize'];
					$Latitude 		= $_POST['Latitude'];
					$Longitude 		= $_POST['Longitude'];
					$AGLand 		= $_POST['AGLand'];
					$VStatus 		= $_POST['VStatus'];
					$MStatus 		= $_POST['MStatus'];
					$EntryBy 		= $_POST['EntryBy'];
					$EntryDate 		= $_POST['EntryDate'];
					$MembersData 	= $_POST['MembersData'];
					
					
					$result = $db->add_registration_data( $C_CODE, $DistrictName, $UpazillaName, $UnitName, $VillageName, $RegistrationID, $RegDate, $PersonName, $SEX, $HHSize, $Latitude, $Longitude, $AGLand, $VStatus, $MStatus, $EntryBy, $EntryDate, $MembersData );
					
					//$result = $MembersData;
					
					if($result != false){
						$data["error"] = FALSE;
						$data["Last_ID"] = "Insert ID = ".$result;
					} else {
						$data["error"] = TRUE;
						$data["error_msg"] = "Failed to synchronize!";
					}
					
					_json($data);
					
					break;
					
					
			
				case 'is_valid_user':
					
					$user_name = $_POST['user_name'];
					$password = $_POST['password'];
					
					
					$result = $db->is_valid_user($user_name,$password);
					
					
					if ($result != false) {
						
						if( $result['user'] != false ){
							// user found
							$data["error"] = FALSE;
							$data["UsrID"] 							= $result['user']["StfCode"];
							$data["user"]["AdmCountryCode"]			= $result['user']["AdmCountryCode"];
							$data["user"]["UsrLogInName"] 			= $result['user']["UsrLogInName"];
							$data["user"]["UsrLogInPW"] 			= $result['user']["UsrLogInPW"];
							$data["user"]["UsrFirstName"] 			= $result['user']["StfName"];
							$data["user"]["UsrLastName"] 			= $result['user']["StfName"];
							$data["user"]["UsrEmail"] 				= $result['user']["UsrEmail"];
							$data["user"]["UsrEmailVerification"] 	= $result['user']["UsrEmail"];
							$data["user"]["UsrStatus"] 				= $result['user']["StfStatus"];
							$data["user"]["EntryBy"] 				= $result['user']["EntryBy"];
							$data["user"]["EntryDate"] 				= $result['user']["EntryDate"];
						}
						
						$data["countries"] 		= $result['countries'];
						$data["valid_dates"] 	= $result['valid_dates'];
						$data["district"] 		= $result['district'];
						$data["upazilla"] 		= $result['upazilla'];
						$data["unit"] 			= $result['unit'];
						$data["village"] 		= $result['village'];
						$data["relation"] 		= $result['relation'];
						$data["registration"] 	= $result['registration'];
						$data["members"] 		= $result['members'];
						
						
						_json($data);
						
					} else {
						// user not found
						$data["error"] = TRUE;
						$data["error_msg"] = "Incorrect Username or password!";
						_json($data);
					}
					
					break;
				
				default:
					$data["error"] = TRUE;
					$data["error_msg"] = "Unknown Task!";
					_json($data);
			}
		}
		else
		{
			$data["error"] = TRUE;
			$data["error_msg"] = "Task not found!";
			_json($data);
		} // End Task missing
	}
	else
	{
		$data["error"] = TRUE;
		$data["error_msg"] = "Invalid API key!";
		_json($data);
	} // End valid_key
}
else
{
	$data["error"] = TRUE;
	$data["error_msg"] = "Required parameter is missing!";
	_json($data);
}

function validate_key($key){
	// need to check API key from Cloud Server
	if($key == 'PhEUT5R251')
		return true;
	else
		return false;
}

function _json($data){
	header('Content-Type: application/json');
	echo json_encode($data);
}    
    
?>
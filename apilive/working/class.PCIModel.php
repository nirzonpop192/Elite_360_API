<?php
/** * Author: Siddiqui Noor, Technical Director, TechnoDhaka.com
	* www.SiddiquiNoor.com
	* This is the Model of PCI App to process all database related query
	* It extends a PDO Database wrapper class
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

class PCIModel extends DBEngine
{
	//public function add_registration_data( $StfCode, $DistrictName, $UpazillaName, $VillageName, $RegistrationID, $PersonName, $SEX, $DOB, $MOccuName, $SOccuName, $Income, $DOC, $Photo, $Latitude, $Longitude){
	public function add_registration_data( $C_CODE, $DistrictName, $UpazillaName, $UnitName, $VillageName, $RegistrationID, $RegDate, $PersonName, $SEX, $HHSize, $Latitude, $Longitude, $AGLand, $VStatus, $MStatus, $EntryBy, $EntryDate, $MembersData ){
				
		//$sql = "INSERT INTO [dbo].[Registration] ( [StfCode], [DistrictName], [UpazillaName], [VillageName], [RegistrationID], [PersonName], [SEX], [DOB], [MOccuName], [SOccuName], [Income], [DOC], [Photo], [Latitude], [Longitude] ) VALUES( '$StfCode', '$DistrictName', '$UpazillaName', '$VillageName', '$RegistrationID', '$PersonName', '$SEX', '$DOB', '$MOccuName', '$SOccuName', '$Income', '$DOC', '$Photo', '$Latitude', '$Longitude' )";
		$sql = "INSERT INTO [dbo].[RegNHHTable] ( 
			[AdmCountryCode] 
			,[LayR1ListCode] 
			,[LayR2ListCode] 
			,[LayR3ListCode]
			,[LayR4ListCode]
			,[HHID]
			,[RegNDate]
			,[HHHeadName]
			,[HHHeadSex]
			,[HHSize]
			,[GPSLat]
			,[GPSLong]
			,[AGLand]
			,[VStatus]
			,[MStatus]
			,[EntryBy]
			,[EntryDate]
		) VALUES( '$C_CODE', '$DistrictName', '$UpazillaName', '$UnitName', '$VillageName', '$RegistrationID', '$RegDate', '$PersonName', '$SEX', '$HHSize', '$Latitude', '$Longitude', '$AGLand', '$VStatus', '$MStatus', '$EntryBy', '$EntryDate' )";

		
		$this->query($sql);
		$this->execute();
		
		$last_id = true; // need to check why this is missing '$this->lastInsertId()'
		
		
		//$members = '[{"mem_unit":"01","mem_village":"01","mem_district":"01","mem_name":"23","entry_by":"0001","entry_date":"05-15-2015 11:53:00","mem_sex":"M","mem_c_code":"0002","mem_hhID":"00001","mem_relation":"01","mem_upazilla":"01","mem_id":"0000102"},{"mem_unit":"01","mem_village":"01","mem_district":"01","mem_name":"98","entry_by":"0001","entry_date":"05-15-2015 11:53:00","mem_sex":"M","mem_c_code":"0002","mem_hhID":"00001","mem_relation":"01","mem_upazilla":"01","mem_id":"0000103"}]';
		
		$jData = json_decode($MembersData);
		
		//print_r( $jData );
		
		foreach($jData as $key => $value){
		
			$mem_c_code		= $value->mem_c_code;
			$mem_district	= $value->mem_district;
			$mem_upazilla	= $value->mem_upazilla;
			$mem_unit		= $value->mem_unit;
			$mem_village	= $value->mem_village;
			$mem_hhID	    = $value->mem_hhID;
			$mem_id	        = $value->mem_id;
			$mem_name	    = $value->mem_name;
			$mem_sex	    = $value->mem_sex;
			$mem_relation	= $value->mem_relation;
			$entry_by	 	= $value->entry_by;
			$entry_date		= $value->entry_date;
		
			$sqlM = "INSERT INTO
			[dbo].[RegNHHMem]
			([AdmCountryCode]
			,[LayR1ListCode]
			,[LayR2ListCode]
			,[LayR3ListCode]
			,[LayR4ListCode]
			,[HHID]
			,[HHMemID]
			,[MemName]
			,[MemSex]
			,[HHRelation]
			,[EntryBy]
			,[EntryDate])
			VALUES
			('$mem_c_code'
			,'$mem_district'
			,'$mem_upazilla'
			,'$mem_unit'
			,'$mem_village'
			,'$mem_hhID'
			,'$mem_id'
			,'$mem_name'
			,'$mem_sex'
			,'$mem_relation'
			,'$entry_by'
			,'$entry_date')";
			
			$this->query($sqlM);
			$this->execute();
		
		}			
				
		
		return $last_id;
	}
	
    public function is_valid_user( $user, $pass )
    {  
    	$data = array();
    	$data['district'] = array();
    	$data['upazilla'] = array();
    	$data['unit'] = array();
    	$data['village'] = array();
    	$data['relation']= array();
		$data["registration"] = array();
		$data["members"] = array();
	  
        $sql = '				
				SELECT DISTINCT 
						s.[StfCode], 
						sa.[AdmCountryCode],
                        s.[UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s
				JOIN [dbo].[StaffGeoInfoAccess] AS sa
						ON s.[StfCode] = sa.[StfCode]
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass
        ';        
        $this->query($sql);
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);
         
        $data['user'] = $this->single();
        
        if ( $data['user'] != false ) {
        	// $result["StfCode"]
        	
        	$psql ='
        			SELECT DISTINCT sa.[LayRListCode] AS list_code
                	FROM [dbo].[StaffMaster] AS u
					JOIN [dbo].[StaffGeoInfoAccess] AS sa
						ON u.[StfCode] = sa.[StfCode]
               		WHERE u.[StfCode] = :user_id
        	';
			$staff_code = $data['user']["StfCode"];
			
        	$this->query($psql);
        	$this->bind(':user_id', $staff_code);
        	
        	$permission = $this->resultset();
        	
        	// e.g. 01010101, 01010102, 01010201
        	$district = "";
        	$upazilla = "";
        	$unit = "";
        	$village = "";
        	
        	if ($permission != false) {
        		
        		foreach ($permission as $key => $value){
        			
        			$d = substr( $value['list_code'], 0, 2 );
        			if (strpos($district, $d) === false)
        			$district 	.= "'".$d."',";
        			
        			$t = substr( $value['list_code'], 2, 2 );
        			if (strpos($upazilla, $t) === false)
        			$upazilla		.= "'".$t."',";
        			
        			$u = substr( $value['list_code'], 4, 2 );
        			if (strpos($unit, $u) === false)
        			$unit	 	.= "'".$u."',";
        			
        			$v = substr( $value['list_code'], 6, 2 );
        			if (strpos($village, $v) === false)
        			$village 	.= "'".$v."',";
        			
        		} // end permission
        		
        		$district = substr($district,0,-1);
        		$upazilla = substr($upazilla,0,-1);
        		$unit = substr($unit,0,-1);
        		$village = substr($village,0,-1);
        		
        		
        		// District
        		if($district != false){
        			$dsql = "SELECT DISTINCT [AdmCountryCode],[LayRListCode], [LayRListName]
        					FROM [dbo].[GeoLayR1List]
        					WHERE [LayRListCode] IN ($district)
        			";
        			
        			$this->query($dsql);        			 
        			$district = $this->resultset();
        			
        			foreach ($district as $key => $value){
        				$data['district'][$key]['AdmCountryCode'] 	= $value['AdmCountryCode'];
        				$data['district'][$key]['LayRListCode'] 	= $value['LayRListCode'];
        				$data['district'][$key]['LayRListName'] 	= $value['LayRListName'];
        			}
        		} // end district
        		
        		if($upazilla != false){
        			$tsql = "SELECT DISTINCT [AdmCountryCode],[LayR1ListCode],[LayR2ListCode], [LayR2ListName]
        					FROM [dbo].[GeoLayR2List]
        					WHERE [LayR2ListCode] IN ($upazilla)
        			";
        			
        			$this->query($tsql);        			 
        			$upazilla = $this->resultset();
        			
        			foreach ($upazilla as $key => $value){
        				$data['upazilla'][$key]['AdmCountryCode']= $value['AdmCountryCode'];
        				$data['upazilla'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
        				$data['upazilla'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
        				$data['upazilla'][$key]['LayR2ListName'] = $value['LayR2ListName'];
        			}
        		} // end upazilla
        		
        		
        		// Unit
        		if($unit != false){
        			$usql = "SELECT DISTINCT [AdmCountryCode],[LayR1ListCode],[LayR2ListCode], [LayR3ListCode], [LayR3ListName]
        					FROM [dbo].[GeoLayR3List]
        					WHERE [LayR3ListCode] IN ($unit)
        			";
        			
        			$this->query($usql);
        			$unit = $this->resultset();        			
        			
        			foreach ($unit as $key => $value){
        				$data['unit'][$key]['AdmCountryCode']= $value['AdmCountryCode'];
        				$data['unit'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
        				$data['unit'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
        				$data['unit'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
        				$data['unit'][$key]['LayR3ListName'] = $value['LayR3ListName'];
        			}
        		} // end unit
        		
        		if($village != false){
        			$vsql = "SELECT DISTINCT [AdmCountryCode],[LayR1ListCode],[LayR2ListCode], [LayR3ListCode], [LayR4ListCode], [LayR4ListName]
        					FROM [dbo].[GeoLayR4List]
        					WHERE [LayR4ListCode] IN ($village)
        			";
        			
        			$this->query($vsql);  			 
        			$village = $this->resultset();
        			
        			foreach ($village as $key => $value){
        				$data['village'][$key]['AdmCountryCode']= $value['AdmCountryCode'];
        				$data['village'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
        				$data['village'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
        				$data['village'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
        				$data['village'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
        				$data['village'][$key]['LayR4ListName'] = $value['LayR4ListName'];
        			}
        		} // end village
        		
        	}
        	
        	// getting data from Relation table
        	$relsql = "SELECT [HHRelationCode],[RelationName] FROM [dbo].[LUP_RegNHHRelation]";
        	 
        	$this->query($relsql);
        	$relation = $this->resultset();
        	
        	if($relation != false){
        		foreach ($relation as $key => $value){
        			$data['relation'][$key]['HHRelationCode'] = $value['HHRelationCode'];
        			$data['relation'][$key]['RelationName'] = $value['RelationName'];
        		}
        	} // end Relation        	
			
			
			// Getting Registration data which all SyncStatus = 1 for this user
			$rsql = "SELECT    [AdmCountryCode]
							  ,[LayR1ListCode]
							  ,[LayR2ListCode]
							  ,[LayR3ListCode]
							  ,[LayR4ListCode]
							  ,[HHID]
							  ,[RegNDate]
							  ,[HHHeadName]
							  ,[HHHeadSex]
							  ,[HHSize]
							  ,[GPSLat]
							  ,[GPSLong]
							  ,[AGLand]
							  ,[VStatus]
							  ,[MStatus]
							  ,[EntryBy]
							  ,[EntryDate]
				  FROM [dbo].[RegNHHTable]
				  WHERE [EntryBy] = :user_id 
				  ";
				  //AND [SyncStatus]=1
        	 
        	$this->query($rsql);
			$this->bind(':user_id', $staff_code);
        	$registration = $this->resultset();        	
        	
        	if($registration != false){
        		foreach ($registration as $key => $value){
        			$data['registration'][$key]['AdmCountryCode'] 	= $value['AdmCountryCode'];
        			$data['registration'][$key]['DistrictName'] 	= $value['LayR1ListCode'];
        			$data['registration'][$key]['UpazillaName'] 	= $value['LayR2ListCode'];
        			$data['registration'][$key]['UnitName'] 		= $value['LayR3ListCode'];
        			$data['registration'][$key]['VillageName'] 		= $value['LayR4ListCode'];
        			$data['registration'][$key]['RegistrationID'] 	= $value['HHID'];
					$data['registration'][$key]['RegNDate'] 		= $value['RegNDate'];
        			$data['registration'][$key]['PersonName'] 		= $value['HHHeadName'];
        			$data['registration'][$key]['SEX'] 				= $value['HHHeadSex'];
        			$data['registration'][$key]['HHSize'] 			= $value['HHSize'];
					$data['registration'][$key]['Latitude'] 		= $value['GPSLat'];
        			$data['registration'][$key]['Longitude'] 		= $value['GPSLong'];
        			$data['registration'][$key]['AGLand'] 			= $value['AGLand'];        			
        			$data['registration'][$key]['VStatus'] 			= $value['VStatus'];
        			$data['registration'][$key]['MStatus'] 			= $value['MStatus'];
        			$data['registration'][$key]['EntryBy'] 			= $value['EntryBy'];					
        			$data['registration'][$key]['EntryDate'] 		= $value['EntryDate'];
        			
        		}
        	} // end registration
        	
        	
        	
        	// Members
        	// Getting Registration data which all SyncStatus = 1 for this user
        	$msql = "SELECT [AdmCountryCode]
				      ,[LayR1ListCode]
				      ,[LayR2ListCode]
				      ,[LayR3ListCode]
				      ,[LayR4ListCode]
				      ,[HHID]
				      ,[HHMemID]
				      ,[MemName]
				      ,[MemSex]
				      ,[HHRelation]
				      ,[EntryBy]
				      ,[EntryDate]
				  FROM [dbo].[RegNHHMem]
				  WHERE [EntryBy] = :user_id
				  ";
        	//AND [SyncStatus]=1
        	
        	$this->query($msql);
        	$this->bind(':user_id', $staff_code);
        	$members = $this->resultset();
        	 
        	if($members != false){
        		
        		foreach ($members as $key => $value){
        			$data['members'][$key]['AdmCountryCode'] 	= $value['AdmCountryCode'];
        			$data['members'][$key]['DistrictName'] 		= $value['LayR1ListCode'];
        			$data['members'][$key]['UpazillaName'] 		= $value['LayR2ListCode'];
        			$data['members'][$key]['UnitName'] 			= $value['LayR3ListCode'];
        			$data['members'][$key]['VillageName'] 		= $value['LayR4ListCode'];
        			$data['members'][$key]['HHID'] 				= $value['HHID'];
        			$data['members'][$key]['HHMemID'] 			= $value['HHMemID'];
        			$data['members'][$key]['MemName'] 			= $value['MemName'];
        			$data['members'][$key]['MemSex'] 			= $value['MemSex'];
        			$data['members'][$key]['HHRelation'] 		= $value['HHRelation'];
        			$data['members'][$key]['EntryBy'] 			= $value['EntryBy'];
        			$data['members'][$key]['EntryDate'] 		= $value['EntryDate'];
        			 
        		}
        	} // end registration
        	
        	
			
        } else {
			return false;
		}
        
        return $data;
        
    } // End function is_valid_user
	    
	    
} // End class PCIModel
    
?>
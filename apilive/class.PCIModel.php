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
class PCIModel extends DBEngine {
    /*     * *****************************End of Reg hh Liberia************************************** */
    /*     * ******************************Start for  Mliea***************************** */

    public function add_registration_data($C_CODE, $DistrictName, $UpazillaName, $UnitName, $VillageName, $RegistrationID, $RegDate, $PersonName, $SEX, $HHSize, $Latitude, $Longitude, $AGLand, $VStatus, $MStatus, $EntryBy, $EntryDate, $VSLAGroup, $MembersData) {


        $checkSQL = "SELECT COUNT(*) AS total FROM [dbo].[RegNHHTable] WHERE [AdmCountryCode]='$C_CODE' AND [LayR1ListCode]='$DistrictName' AND [LayR2ListCode]='$UpazillaName' AND [LayR3ListCode]='$UnitName' AND [LayR4ListCode]='$VillageName' AND [HHID]='$RegistrationID'";

        $this->query($checkSQL);
        $is_exists = $this->single();

        if ($is_exists['total']) {

            //return "yes";
            // deleting data from Registration table
            $delH_sql = "DELETE FROM [dbo].[RegNHHTable] WHERE [AdmCountryCode]='$C_CODE' AND [LayR1ListCode]='$DistrictName' AND [LayR2ListCode]='$UpazillaName' AND [LayR3ListCode]='$UnitName' AND [LayR4ListCode]='$VillageName' AND [HHID]='$RegistrationID'";
            $this->query($delH_sql);
            $this->execute();

            // deleting data from Members table
            $delM_sql = "DELETE FROM [dbo].[RegNHHMem] WHERE [AdmCountryCode]='$C_CODE' AND [LayR1ListCode]='$DistrictName' AND [LayR2ListCode]='$UpazillaName' AND [LayR3ListCode]='$UnitName' AND [LayR4ListCode]='$VillageName' AND [HHID]='$RegistrationID'";
            $this->query($delM_sql);
            $this->execute();
        }


        if (!isset($HHSize) || $HHSize == "")
            $HHSize = 0;
        if (!isset($AGLand) || $AGLand == "")
            $AGLand = 0;

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
			,[VSLAGroup]
		) VALUES( '$C_CODE', '$DistrictName', '$UpazillaName', '$UnitName', '$VillageName', '$RegistrationID', '$RegDate', '$PersonName', '$SEX', $HHSize, '$Latitude', '$Longitude', $AGLand, '$VStatus', '$MStatus', '$EntryBy', '$EntryDate', '$VSLAGroup' )";

        $this->query($sql);
        $this->execute();

        //$last_id = mysql_insert_id(); // need to check why this is missing '$this->lastInsertId()'
        //$members = '{"mem_unit":"01","mem_village":"01","mem_district":"01","mem_name":"tmem","entry_by":"0001","entry_date":"05-29-2015 10:53:52","mem_sex":"F","mem_c_code":"0002","mem_hhID":"00001","mem_relation":"Grand Daughter","mem_upazilla":"01","mem_id":"0000102"}]';

        $jData = json_decode($MembersData);

        //print_r( $jData );

        if ($jData) {
            foreach ($jData as $key => $value) {

                $mem_c_code = $value->mem_c_code;
                $mem_district = $value->mem_district;
                $mem_upazilla = $value->mem_upazilla;
                $mem_unit = $value->mem_unit;
                $mem_village = $value->mem_village;
                $mem_hhID = $value->mem_hhID;
                $mem_id = $value->mem_id;
                $mem_name = $value->mem_name;
                $mem_sex = $value->mem_sex;
                $mem_relation = $value->mem_relation;
                $entry_by = $value->entry_by;
                $entry_date = $value->entry_date;

                $mem_lmp_date = $value->mem_lmp_date;
                $mem_child_dob = $value->mem_child_dob;
                $mem_elderly = $value->mem_elderly;
                $mem_disabled = $value->mem_disabled;
                $mem_age = $value->mem_age;


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
				,[EntryDate]
				,[LMPDate]
		        ,[ChildDOB]
		        ,[Elderly]
		        ,[Disabled]
		        ,[MemAge]
				)
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
				,'$entry_date'
				,'$mem_lmp_date'
				,'$mem_child_dob'
				,'$mem_elderly'
				,'$mem_disabled'
				,'$mem_age'

				)";

                $this->query($sqlM);
                $this->execute();
            }
        }


//		return $last_id; 	// need to check why the last insert ID is missing.
        return true;
    }

    public function is_down_load_programName($user, $pass) {
        $data = array();
        $data["countries"] = array();


        $data["srv_table_report"] = array();
        $data["adm_program_master"] = array();
        $data["countrie_no"] = array();


        $sql = '	SELECT DISTINCT
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

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];

            // gettingNUMBER OF COUNTRY ASSIGNE
            $c_no_sql = 'SELECT COUNT (*) AS CountryNo FROM [dbo].[StaffAssignCountry] WHERE
							[StfCode]=:user_id
						AND [StatusAssign]=1';

            $this->query($c_no_sql);
            $this->bind(':user_id', $staff_code);

            $countrie_no = $this->resultset();

            if ($countrie_no != false) {
                foreach ($countrie_no as $key => $count) {
                    $data['countrie_no'][$key]['CountryNo'] = $count['CountryNo'];
                }
            }


            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            $adm_program_master_sql = "SELECT [AdmCountryProgram].AdmCountryCode,
                [AdmCountryProgram].[AdmProgCode]
					  ,[AdmCountryProgram].[AdmAwardCode]
			          ,[AdmCountryProgram].[AdmDonorCode]

					  ,  [ProgName]
					  ,[ProgShortName]

				  FROM [dbo]. [AdmCountryProgram]
				  inner join  [AdmProgramMaster]
				  on [AdmCountryProgram].[AdmProgCode]= [AdmProgramMaster].[AdmProgCode]
				  and [AdmCountryProgram].[AdmDonorCode]= [AdmProgramMaster].[AdmDonorCode]
				  and [AdmCountryProgram].[AdmAwardCode]= [AdmProgramMaster].[AdmAwardCode]

				  group by
				  [AdmCountryProgram].AdmCountryCode,
				  [AdmCountryProgram].AdmCountryCode,[AdmCountryProgram].[AdmProgCode]
				  	  ,[AdmCountryProgram].[AdmAwardCode]
			          ,[AdmCountryProgram].[AdmDonorCode]
					  ,[ProgName]
					  ,[ProgShortName] ";


            $this->query($adm_program_master_sql);
            //$this->bind(':user_id', $staff_code);
            $adm_program_master = $this->resultset();

            if ($adm_program_master != false) {

                foreach ($adm_program_master as $key => $value) {
                    $data['adm_program_master'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_program_master'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_program_master'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_program_master'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_program_master'][$key]['ProgName'] = $value['ProgName'];
                    $data['adm_program_master'][$key]['ProgShortName'] = $value['ProgShortName'];
                }
            }// end of adm_program_master table


            $adm_op_monthsql = "SELECT [AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[OpCode]
					  ,[OpMonthCode]
					  ,[MonthLabel]
					  ,convert(varchar,[StartDate],110) AS UsaStartDate
					  ,convert(varchar,[EndDate],110) AS UsaEndDate
					  ,[Status]
				  FROM [dbo].[AdmOpMonthTable] ";


            $this->query($adm_op_monthsql);
            $adm_op_month = $this->resultset();

            if ($adm_op_month != false) {

                foreach ($adm_op_month as $key => $value) {
                    $data['adm_op_month'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_op_month'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_op_month'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_op_month'][$key]['OpCode'] = $value['OpCode'];
                    $data['adm_op_month'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                    $data['adm_op_month'][$key]['MonthLabel'] = $value['MonthLabel'];
                    $data['adm_op_month'][$key]['UsaStartDate'] = $value['UsaStartDate'];
                    $data['adm_op_month'][$key]['UsaEndDate'] = $value['UsaEndDate'];
                    $data['adm_op_month'][$key]['Status'] = $value['Status'];
                }
            }// end of adm_op_month table
        } else {
            return false;
        }
        return $data;
    }

    public function is_down_load_enu_table($user, $pass) {
        $data = array();
        $data["countries"] = array();


        $data["srv_table_report"] = array();
        $data["adm_program_master"] = array();
        $data["countrie_no"] = array();


        $sql = '	SELECT DISTINCT
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

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];

            // gettingNUMBER OF COUNTRY ASSIGNE
            $c_no_sql = 'SELECT COUNT (*) AS CountryNo FROM [dbo].[StaffAssignCountry] WHERE
							[StfCode]=:user_id
						AND [StatusAssign]=1';

            $this->query($c_no_sql);
            $this->bind(':user_id', $staff_code);

            $countrie_no = $this->resultset();

            if ($countrie_no != false) {
                foreach ($countrie_no as $key => $count) {
                    $data['countrie_no'][$key]['CountryNo'] = $count['CountryNo'];
                }
            }


            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            //dtenutable

            $enu_tablesql = "SELECT [StfCode]
						,[AdmCountryCode]
						,[DTBasic]
						,[btnSave]
						,[EntryBy]
						,convert(varchar,[EntryDate],110) AS UsaEntryDate
					FROM [dbo].[DTEnuTable]";




            $this->query($enu_tablesql);
            $enu_table = $this->resultset();

            if ($enu_table != false) {

                foreach ($enu_table as $key => $value) {
                    $data['enu_table'][$key]['StfCode'] = $value['StfCode'];
                    $data['enu_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['enu_table'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['enu_table'][$key]['btnSave'] = $value['btnSave'];
                    $data['enu_table'][$key]['EntryBy'] = $value['EntryBy'];
                    $data['enu_table'][$key]['UsaEntryDate'] = $value['UsaEntryDate'];
                }
            }// end of enu_table table
        } else {
            return false;
        }
        return $data;
    }
	
	 public function is_down_load_adm_apk_version($user, $pass) {
        $data = array();
        


      
        $data["apk_version"] = array();


        $sql = '	SELECT DISTINCT
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

        if ($data['user'] != false) {

            // getting StuffCode from User's data
      


            

            //dtenutable

            $off_apk_sql = "SELECT [AppSerial]
      ,[Version]
      ,[VersionDate]
  FROM [dbo].[AdmMachineOfflineAPKVersion]";




            $this->query($off_apk_sql);
            $off_apk = $this->resultset();

            if ($off_apk != false) {

                foreach ($off_apk as $key => $value) {
                    $data['apk_version'][$key]['AppSerial'] = $value['AppSerial'];
                    $data['apk_version'][$key]['Version'] = $value['Version'];
                    $data['apk_version'][$key]['VersionDate'] = $value['VersionDate'];
                 
                }
            }// end of off_apk table
        } else {
            return false;
        }
        return $data;
    }

    public function is_down_load_service_center_name($user, $pass, $country_code, $donor_code, $award_code, $program_code, $opMothCode, $distFlag) {

        $data = array();
        $data["countries"] = array();
        $data["dob_service_center"] = array();


        $sql = '	SELECT DISTINCT
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

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];

            // gettingNUMBER OF COUNTRY ASSIGNE
            $c_no_sql = 'SELECT COUNT (*) AS CountryNo FROM [dbo].[StaffAssignCountry] WHERE
							[StfCode]=:user_id
						AND [StatusAssign]=1';

            $this->query($c_no_sql);
            $this->bind(':user_id', $staff_code);

            $countrie_no = $this->resultset();

            if ($countrie_no != false) {
                foreach ($countrie_no as $key => $count) {
                    $data['countrie_no'][$key]['CountryNo'] = $count['CountryNo'];
                }
            }


            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            $service_center_sql = "SELECT SrvCenterTable.SrvCenterName AS SrvCenterName
							, StaffSrvCenterAccess.SrvCenterCode AS SrvCenterCode
							, StaffSrvCenterAccess.AdmCountryCode AS AdmCountryCode
                            FROM StaffSrvCenterAccess 
                            INNER JOIN 
                            SrvCenterTable ON StaffSrvCenterAccess.AdmCountryCode = 
                            SrvCenterTable.AdmCountryCode AND StaffSrvCenterAccess.SrvCenterCode = SrvCenterTable.SrvCenterCode 
                            LEFT JOIN 
                            SrvTableReport on 
                            SrvTableReport.AdmCountryCode = SrvCenterTable.AdmCountryCode and 
                            SrvTableReport.AdmAwardCode = :award_code and 
                            SrvTableReport.AdmDonorCode = :donor_code and 
                            SrvTableReport.SrvCenterCode = SrvCenterTable.SrvCenterCode and 
                            SrvTableReport.OpCode = 2 and 
                            SrvTableReport.opMonthCode= :opMothCode and 
                            SrvTableReport.progCode = :program_code and 
                            SrvTableReport.DistFlag = :distFlag
                            WHERE (StaffSrvCenterAccess.StfCode = :user_id) 
                            AND (StaffSrvCenterAccess.AdmCountryCode = :country_id) 
                            AND (StaffSrvCenterAccess.btnNew = '1' OR 
                            StaffSrvCenterAccess.btnSave ='1' OR
                             StaffSrvCenterAccess.btnDel = '1' OR 
                             StaffSrvCenterAccess.btnPepr = '1' OR 
                             StaffSrvCenterAccess.btnAprv = '1' OR 
                             StaffSrvCenterAccess.btnRevw = '1' OR 
                             StaffSrvCenterAccess.btnVrfy = '1' OR 
                             StaffSrvCenterAccess.btnDTran = '1') 
                            and PreparedBy is null
                            ORDER BY SrvCenterTable.SrvCenterName";
            $this->query($service_center_sql);
            $this->bind(':user_id', $staff_code);
            $this->bind(':country_id', $country_code);
            $this->bind(':donor_code', $donor_code);
            $this->bind(':award_code', $award_code);
            $this->bind(':program_code', $program_code);
            $this->bind(':opMothCode', $opMothCode);
            $this->bind(':distFlag', $distFlag);
            $servicecenter = $this->resultset();

            if ($servicecenter != false) {
                foreach ($servicecenter as $key => $value) {

                    $data['dob_service_center'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['dob_service_center'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                    $data['dob_service_center'][$key]['SrvCenterName'] = $value['SrvCenterName'];
                }
            } // end SERVICE CENTER
        } else {
            return false;
        }
        return $data;
    }

    /** get fdp name */
    public function is_down_load_fdp_name($user, $pass) {
        $data = array();
        $data["countries"] = array();

        $data["countries"] = array();
        $data["staff_fdp_access"] = array();

        $sql = 'SELECT DISTINCT
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
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass ';
        $this->query($sql);
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];

            // gettingNUMBER OF COUNTRY ASSIGNE
            $c_no_sql = 'SELECT COUNT (*) AS CountryNo FROM [dbo].[StaffAssignCountry] WHERE
							[StfCode]=:user_id
						AND [StatusAssign]=1';

            $this->query($c_no_sql);
            $this->bind(':user_id', $staff_code);

            $countrie_no = $this->resultset();

            if ($countrie_no != false) {
                foreach ($countrie_no as $key => $count) {
                    $data['countrie_no'][$key]['CountryNo'] = $count['CountryNo'];
                }
            }

            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            // Getting village fdp access
            // FDP Access Point


            $staff_fdp_access_sql = "SELECT  [FDPMaster].[AdmCountryCode]  AS [AdmCountryCode]
											,[FDPMaster].[FDPCode]  AS [FDPCode]
											,[FDPMaster].[FDPName] AS[FDPName]

													FROM [StaffFDPAccess]
												inner join [FDPMaster]
												ON [FDPMaster].AdmCountryCode=[StaffFDPAccess].AdmCountryCode
												AND [FDPMaster].FDPCode=[StaffFDPAccess].FDPCode
												WHERE btnNew=1
												AND   btnSave=1
												AND btnDel=1
												AND [StaffFDPAccess].StfCode=:user_id 					 ";

            $this->query($staff_fdp_access_sql);
            $this->bind(':user_id', $staff_code);
            $staff_fdp_access = $this->resultset();

            if ($staff_fdp_access != false) {

                foreach ($staff_fdp_access as $key => $value) {
                    $data['staff_fdp_access'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_fdp_access'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['staff_fdp_access'][$key]['FDPName'] = $value['FDPName'];
                    //	$data['staff_fdp_access'][$key]['btnSave']   = $value['btnSave'];
                    //$data['staff_fdp_access'][$key]['btnDel']   = $value['btnDel'];*/
                }
            }
        } else {
            return false;
        }
        return $data;
    }

    /**  end of get fdp name */
    public function is_down_load_village_name($user, $pass, $operation_mode) {
        $data = array();
        $data["countries"] = array();
        //$data["valid_dates"] = array();
        $data["village"] = array();

        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];

            // gettingNUMBER OF COUNTRY ASSIGNE
            $c_no_sql = 'SELECT COUNT (*) AS CountryNo FROM [dbo].[StaffAssignCountry] WHERE
							[StfCode]=:user_id
						AND [StatusAssign]=1';

            $this->query($c_no_sql);
            $this->bind(':user_id', $staff_code);

            $countrie_no = $this->resultset();

            if ($countrie_no != false) {
                foreach ($countrie_no as $key => $count) {
                    $data['countrie_no'][$key]['CountryNo'] = $count['CountryNo'];
                }
            }


            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            // Getting Registration data which all SyncStatus = 1 for this user
            /*  $villagesql = "			SELECT [GeoLayRName]
              , [GeoLayR4List].[AdmCountryCode],[GeoLayR4List].[AdmCountryCode]	+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode] AS LayRCode
              ,[LayR4ListName]

              FROM [dbo].[GeoLayR4List]
              left join [dbo].[GeoLayRMaster]
              on [GeoLayR4List].AdmCountryCode=[GeoLayRMaster].AdmCountryCode
              AND [GeoLayRMaster].GeoLayRCode=4
              where	[GeoLayR4List].AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in
              (Select AdmCountryCode+[LayRListCode] from [dbo].[StaffGeoInfoAccess]  where StfCode=:user_id
              and (btnNew=1 or btnSave=1 or btnDel=1))


              "; */

            $villagesql = " SELECT [GeoLayRMaster].[GeoLayRName] AS GeoLayRName
			, v.[AdmCountryCode],v.[AdmCountryCode]	+v.[LayR1ListCode]+v.[LayR2ListCode]+v.[LayR3ListCode]+v.[LayR4ListCode] AS LayRCode
							,isnull( adres.RegNAddLookup,'')+' '+[LayR4ListName] AS  LayR4ListName

						FROM [dbo].[GeoLayR4List] as v
						left join [dbo].[GeoLayRMaster]
						on v.AdmCountryCode=[GeoLayRMaster].AdmCountryCode
            AND [GeoLayRMaster].GeoLayRCode=4

							left join [dbo].[LUP_RegNAddLookup] As adres
							on
							adres.AdmCountryCode= v.AdmCountryCode
                            and adres.[LayR1ListCode]=v.[LayR1ListCode]
                            and adres.[LayR2ListCode]= v.[LayR2ListCode]
                            and adres.[LayR3ListCode]= v.[LayR3ListCode]
                            and adres.[LayR4ListCode]= v.[LayR4ListCode]
					where	v.AdmCountryCode+v.LayR1ListCode+v.LayR2ListCode+v.LayR3ListCode+v.LayR4ListCode in
            (Select AdmCountryCode+[LayRListCode] from [dbo].[StaffGeoInfoAccess]  where StfCode=:user_id
            and (btnNew=1 or btnSave=1 or btnDel=1))
          --  order by [LayR4ListName]  ASC
            ";


            $this->query($villagesql);
            $this->bind(':user_id', $staff_code);
            $village = $this->resultset();

            if ($village != false) {
                foreach ($village as $key => $value) {

                    $data['village'][$key]['GeoLayRName'] = $value['GeoLayRName'];
                    $data['village'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['village'][$key]['LayRCode'] = $value['LayRCode'];
                    $data['village'][$key]['LayR4ListName'] = $value['LayR4ListName'];
                }
            } // end village
        } else {
            return false;
        }
        return $data;
    }

    /* EXEQUTE QUERY  FUNCTION */

    public function query_exe($sql_string) {
        $this->query($sql_string);
        $this->execute();
        return true;
    }

    /* END OF EXEQUTE QUERY  FUNCTION */
	
			# Training N activity 
		  public function down_load_training_n_activity($user, $pass, $operation_mode) {
			  
			  		
        $data = array();
        $data["T_A_master"] = array();
        $data["T_A_category"] = array();
        $data["T_A_eventTopic"] = array();
        $data["T_A_group"] = array();
        $data["T_A_partOrgN"] = array();
        $data["T_A_posParticipants"] = array();
        $data["T_A_subGroup"] = array();
        $data["T_A_topicChild"] = array();
        $data["T_A_topicMaster"] = array();
        $data["LUP_TAParticipantCat"] = array();


        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
		
		//echo($sql);
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];

 

            $T_A_master_sql = "";
            $T_A_category_sql = "";
            $T_A_eventTopic_sql = "";
            $T_A_group_sql = "";
            $T_A_partOrgN_sql = "";
            $T_A_posParticipants_sql = "";
            $T_A_subGroup_sql = "";
            $T_A_topicChild_sql = "";
            $T_A_topicMaster_sql = "";
            $LUP_TAParticipantCat_sql = "";
           

            switch ($operation_mode) {
                case 5: // for traning Activity  select 2 village
				$T_A_master_sql = "SELECT [AdmCountryCode]
											,[EventCode]
											,[EventName]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[TAGroup]
											,[TASubGroup]
											,[OrgNCode]
											,convert(varchar,StartDate,110) AS [StartDate]
											,convert(varchar,EndDate,110) AS [EndDate]
											,[VenueName]
											,[VenueAddress]
											,[Active]											
											,ISNULL([TotalDays],'') as TotalDays
											,ISNULL([HoursPerDay],'') as HoursPerDay
									FROM [dbo].[TAMaster]";
				$T_A_category_sql = "SELECT [AdmCountryCode]
									,[TACatCode]
									,[TACatName]
									,[SrcBen]
								
									FROM [dbo].[TACategory]";
									
					$T_A_eventTopic_sql = "SELECT [AdmCountryCode]
      ,[EventCode]
      ,[TopicMasterCode]
      ,[TopicChildCode]     
  FROM [dbo].[TAEventTopic]";
					$T_A_group_sql = "SELECT [AdmCountryCode]
      ,[TAGroup]
      ,[TAGroupTitle] 
  FROM [dbo].[TAGroup]";
					$T_A_partOrgN_sql = "SELECT [AdmCountryCode]
      ,[PartOrgNCode]
      ,[PartOrgNName]
      ,[SrcBen]
     
  FROM [dbo].[TAPartOrgN]";
					$T_A_posParticipants_sql = "SELECT [AdmCountryCode]
      ,[PosCode]
      ,[PosTitle]    
  FROM [dbo].[TAPosParticipants]";
					$T_A_subGroup_sql = "SELECT [AdmCountryCode]
      ,[TAGroup]
      ,[TASubGroup]
      ,[TASubTitle]  
  FROM [dbo].[TASubGroup]";
				$T_A_topicChild_sql = "SELECT [TopicMasterCode]
      ,[TopicChildCode]
      ,[TopicSubTitle]      
  FROM [dbo].[TATopicChild]";
					$T_A_topicMaster_sql = "SELECT [TopicMasterCode]
      ,[TopicTitle]     
  FROM [dbo].[TATopicMaster]";
  $LUP_TAParticipantCat_sql = "SELECT [AdmCountryCode]
      ,[TAGroup]
      ,[PartCatCode]
      ,[PartCatTitle]
    
  FROM [dbo].[LUP_TAParticipantCat]";
					//$T_A_category_sql = "";
                    break;

                case 1: // Registration
                case 2: // for DISTRIBUTION
                case 3: // for SERVICE
                case 4: // for other 
				
				$T_A_master_sql = "SELECT [AdmCountryCode],[EventCode]
											,[EventName]	,[AdmDonorCode]
											,[AdmAwardCode] ,[TAGroup]
											,[TASubGroup]	,[OrgNCode]
											,[StartDate] ,[EndDate]
											,[VenueName]			,[VenueAddress]
											,[Active]											
											,[TotalDays]									,[HoursPerDay]
									FROM [dbo].[TAMaster]
									where [AdmCountryCode] ='000'";
				$T_A_category_sql = "SELECT [AdmCountryCode],[TACatCode],[TACatName]		,[SrcBen]
										FROM [dbo].[TACategory]
										where [AdmCountryCode] ='000'";
				
				$T_A_eventTopic_sql = "SELECT [AdmCountryCode]      ,[EventCode]      ,[TopicMasterCode],[TopicChildCode]     
  FROM [dbo].[TAEventTopic] 
  where [AdmCountryCode] ='000'";
  			$T_A_group_sql = "SELECT [AdmCountryCode]      ,[TAGroup]      ,[TAGroupTitle] 
  FROM [dbo].[TAGroup]
  where [AdmCountryCode] ='000'";
  
  					$T_A_partOrgN_sql = "SELECT [AdmCountryCode]      ,[PartOrgNCode]      ,[PartOrgNName]      ,[SrcBen]     
  FROM [dbo].[TAPartOrgN]
  where [AdmCountryCode] ='000'  ";
  	$T_A_posParticipants_sql = "SELECT [AdmCountryCode]      ,[PosCode]      ,[PosTitle]    
  FROM [dbo].[TAPosParticipants]
    where [AdmCountryCode] ='000'";
	
	$T_A_subGroup_sql = "SELECT [AdmCountryCode]      ,[TAGroup]      ,[TASubGroup]      ,[TASubTitle]  
  FROM [dbo].[TASubGroup]
  where [AdmCountryCode] ='000'";
  
  
  $T_A_topicChild_sql = "SELECT [TopicMasterCode]      ,[TopicChildCode]      ,[TopicSubTitle] FROM [dbo].[TATopicChild]
   where [TopicMasterCode] ='000'";	

   $T_A_topicMaster_sql = "SELECT [TopicMasterCode]      ,[TopicTitle]     
  FROM [dbo].[TATopicMaster]
   where [TopicMasterCode] ='000'";
     $LUP_TAParticipantCat_sql = "SELECT [AdmCountryCode]
      ,[TAGroup]
      ,[PartCatCode]
      ,[PartCatTitle]
    
  FROM [dbo].[LUP_TAParticipantCat]
  where [AdmCountryCode] ='0000'";
                    break;
            }

				
           


            $this->query($T_A_master_sql);
            $T_A_master = $this->resultset();
            if ($T_A_master != false) {

                foreach ($T_A_master as $key => $value) {
                    $data['T_A_master'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_master'][$key]['EventCode'] = $value['EventCode'];
                    $data['T_A_master'][$key]['EventName'] = $value['EventName'];
                    $data['T_A_master'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['T_A_master'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['T_A_master'][$key]['TAGroup'] = $value['TAGroup'];
                    $data['T_A_master'][$key]['TASubGroup'] = $value['TASubGroup'];
                    $data['T_A_master'][$key]['OrgNCode'] = $value['OrgNCode'];
                    $data['T_A_master'][$key]['StartDate'] = $value['StartDate'];
                    $data['T_A_master'][$key]['EndDate'] = $value['EndDate'];
                    $data['T_A_master'][$key]['VenueName'] = $value['VenueName'];
                    $data['T_A_master'][$key]['VenueAddress'] = $value['VenueAddress'];
                    $data['T_A_master'][$key]['Active'] = $value['Active'];
                    $data['T_A_master'][$key]['TotalDays'] = $value['TotalDays'];
                    $data['T_A_master'][$key]['HoursPerDay'] = $value['HoursPerDay'];
                 
                }
            }// end of D_T_master table

	
			$this->query($T_A_category_sql);
            $T_A_category = $this->resultset();
            if ($T_A_category != false) {

                foreach ($T_A_category as $key => $value) {
                    $data['T_A_category'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_category'][$key]['TACatCode'] = $value['TACatCode'];
                    $data['T_A_category'][$key]['TACatName'] = $value['TACatName'];
                    $data['T_A_category'][$key]['SrcBen'] = $value['SrcBen'];    
                   
                 
                }
            }// end of T_A_category table
            
				$this->query($T_A_eventTopic_sql);
            $T_A_eventTopic = $this->resultset();
            if ($T_A_eventTopic != false) {
 
                foreach ($T_A_eventTopic as $key => $value) {
                    $data['T_A_eventTopic'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_eventTopic'][$key]['EventCode'] = $value['EventCode'];
                    $data['T_A_eventTopic'][$key]['TopicMasterCode'] = $value['TopicMasterCode'];
                    $data['T_A_eventTopic'][$key]['TopicChildCode'] = $value['TopicChildCode'];    
                   
                 
                }
            }// end of T_A_eventTopic table
			
			$this->query($T_A_group_sql);
            $T_A_group = $this->resultset();
            if ($T_A_group != false) {

                foreach ($T_A_group as $key => $value) {
                    $data['T_A_group'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_group'][$key]['TAGroup'] = $value['TAGroup'];
                    $data['T_A_group'][$key]['TAGroupTitle'] = $value['TAGroupTitle'];                    
                 
                }
            }// end of T_A_group table
			
			
						
			$this->query($T_A_partOrgN_sql);
            $T_A_partOrgN = $this->resultset();
            if ($T_A_partOrgN != false) {

                foreach ($T_A_partOrgN as $key => $value) {
                    $data['T_A_partOrgN'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_partOrgN'][$key]['PartOrgNCode'] = $value['PartOrgNCode'];
                    $data['T_A_partOrgN'][$key]['PartOrgNName'] = $value['PartOrgNName'];                    
                    $data['T_A_partOrgN'][$key]['SrcBen'] = $value['SrcBen'];                    
                 
                }
            }// end of T_A_partOrgN table
			
			
			$this->query($T_A_posParticipants_sql);
            $T_A_posParticipants = $this->resultset();
            if ($T_A_posParticipants != false) {
               foreach ($T_A_posParticipants as $key => $value) {
                    $data['T_A_posParticipants'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_posParticipants'][$key]['PosCode'] = $value['PosCode'];
                    $data['T_A_posParticipants'][$key]['PosTitle'] = $value['PosTitle'];                    
                                  
                 
                }
            }// end of T_A_posParticipants table
			
			
			
			$this->query($T_A_subGroup_sql);
            $T_A_subGroup = $this->resultset();
            if ($T_A_subGroup != false) {
               foreach ($T_A_subGroup as $key => $value) {
                    $data['T_A_subGroup'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['T_A_subGroup'][$key]['TAGroup'] = $value['TAGroup'];
                    $data['T_A_subGroup'][$key]['TASubGroup'] = $value['TASubGroup'];                    
                    $data['T_A_subGroup'][$key]['TASubTitle'] = $value['TASubTitle'];                    
                                  
                 
                }
            }// end of T_A_subGroup table
			
						
			$this->query($T_A_topicChild_sql);
            $T_A_topicChild = $this->resultset();
            if ($T_A_topicChild != false) {
               foreach ($T_A_topicChild as $key => $value) {
                    $data['T_A_topicChild'][$key]['TopicMasterCode'] = $value['TopicMasterCode'];
                    $data['T_A_topicChild'][$key]['TopicChildCode'] = $value['TopicChildCode'];
                    $data['T_A_topicChild'][$key]['TopicSubTitle'] = $value['TopicSubTitle'];             
                    
                }
            }// end of T_A_topicChild table
			
			
			$this->query($T_A_topicMaster_sql);
            $T_A_topicMaster = $this->resultset();
            if ($T_A_topicMaster != false) {
               foreach ($T_A_topicMaster as $key => $value) {
                    $data['T_A_topicMaster'][$key]['TopicMasterCode'] = $value['TopicMasterCode'];
                    $data['T_A_topicMaster'][$key]['TopicTitle'] = $value['TopicTitle'];                            
                    
                }
            }// end of T_A_topicChild table
			
					$this->query($LUP_TAParticipantCat_sql);
            $LUP_TAParticipantCat = $this->resultset();
            if ($LUP_TAParticipantCat != false) {
               foreach ($LUP_TAParticipantCat as $key => $value) {
                    $data['LUP_TAParticipantCat'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['LUP_TAParticipantCat'][$key]['TAGroup'] = $value['TAGroup'];                            
                    $data['LUP_TAParticipantCat'][$key]['PartCatCode'] = $value['PartCatCode'];                            
                    $data['LUP_TAParticipantCat'][$key]['PartCatTitle'] = $value['PartCatTitle'];                            
                    
                }
            }// end of LUP_TAParticipantCat_sql table
			
        } else {
            return false;
        }
        return $data;
    }

		# end of Training N activity
   

    /**
     * Dynamic
     */
    public function is_down_load_dynamic_table($user, $pass, $operation_mode) {
        $data = array();
        $data["D_T_answer"] = array();
        $data["D_T_basic"] = array();
        $data["D_T_category"] = array();
        $data["D_T_CountryProgram"] = array();
        $data["D_T_GeoListLevel"] = array();
        $data["D_T_QResMode"] = array();
        $data["D_T_QTable"] = array();
        $data["D_T_ResponseTable"] = array();
        $data["D_T_TableDefinition"] = array();
        $data["D_T_TableListCategory"] = array();
        $data["D_T_LUP"] = array();
		$data["DTA_Skip_Table"] = array();

        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];



            $D_T_answer_sql = "";
            $D_T_basic_sql = "";

            $D_T_category_sql = "";
            $D_T_CountryProgram_sql = "";
            $D_T_GeoListLevel_sql = "";
            $D_T_QResMode_sql = "";
            $D_T_QTable_sql = "";
            $D_T_ResponseTable_sql = "";

            $D_T_TableDefinition_sql = "";
            $D_T_TableListCategory_sql = "";
            $D_T_LUP_sql = "";
			$DTA_Skip_Table_sql = "";

            switch ($operation_mode) {
                case 4: // for registration  select 2 village


                    $D_T_answer_sql = "SELECT [DTBasic]
                                                        ,[DTQCode]
                                                        ,[DTACode]
                                                        ,[DTALabel]
                                                        ,[DTAValue]
                                                        ,[DTSeq]
                                                        ,[DTAShort]
                                                        ,[DTScoreCode]
                                                        ,[DTSkipDTQCode]
                                                        ,[DTACompareCode]
                                                        ,[ShowHide]
                                                        ,[MaxValue]
                                                        ,[MinValue]
                                                        ,[DataType]
                                                        ,[MarkOnGrid]
                                                    FROM [dbo].[DTATable]";

                    $D_T_basic_sql = "SELECT [DTBasic]
                                                  ,[DTTitle]
                                                 ,[DTSubTitle]
                                                 ,[DTDescription]
                                                 ,[DTAutoScroll]
                                                 ,[DTAutoScrollText]
                                                 ,[DTActive]
                                                 ,[DTCategory]
                                                 ,[DTGeoListLevel]
                                                 ,[DTOPMode]
												 ,[DTShortName]
                                               FROM [dbo].[DTBasic]";

                    $D_T_category_sql = " SELECT [DTCategory]
                                                 ,[CategoryName]
                                                 ,[Frequency]
                                                 FROM [dbo].[DTCategory]";

                    $D_T_CountryProgram_sql = "SELECT [AdmCountryCode]
                                                  ,[AdmDonorCode]
                                                  ,[AdmAwardCode]
                                                  ,[AdmProgCode]
                                                  ,[ProgActivityCode]
                                                  ,[ProgActivityTitle]
                                                  ,[DTBasic]
                                                  ,[RefIdentifier]
                                                  ,[Status]
                                                  ,[RptFrequency]
                                              FROM [dbo].[DTCountryProgram]";
                    $D_T_GeoListLevel_sql = " SELECT [GeoLevel]
                                                         ,[GeoLevelName]
                                                         ,[ListUDFName]
                                                       FROM [dbo].[DTGeoListLevel]";
                    $D_T_QResMode_sql = "SELECT [QResMode]
                                                               ,[QResLupText]
                                                               ,[DataType]
                                                               ,[LookUpUDFName]
                                                               ,[ResponseValueControl]
                                                           FROM [dbo].[DTQResMode]";
                    $D_T_QTable_sql = "  SELECT [DTBasic]
                                                     ,[DTQCode]
                                                     ,[QText]
                                                     ,[QResMode]
                                                     ,[QSeq]
                                                     ,[AllowNull]
                                                      ,[LUPTableName]
                                                       FROM [dbo].[DTQTable]";
                    $D_T_ResponseTable_sql = " SELECT [DTBasic]
                                                  ,[AdmCountryCode]
                                                  ,[AdmDonorCode]
                                                  ,[AdmAwardCode]
                                                  ,[AdmProgCode]
                                                  ,[DTEnuID]
                                                  ,[DTQCode]
                                                  ,[DTACode]
                                                  ,[DTRSeq]
                                                  ,[DTAValue]
                                                  ,[ProgActivityCode]
                                                  ,[DTTimeString]
                                                  ,[OpMode]
                                                  ,[OpMonthCode]
                                                  ,[DataType]
                                                 FROM [dbo].[DTResponseTable]												 
												 where (dtbasic + dtenuid + convert(nvarchar, Dtrseq)) in  (
 select dtbasic + dtenuid + convert(nvarchar, max(Dtrseq))  from DTResponseTable group by DTBasic, DTEnuID having dtenuid = '" . $staff_code. "' )";
                    $D_T_TableDefinition_sql = "SELECT [TableName]
                                                           ,[FieldName]
                                                           ,[FieldDefinition]
                                                           ,[FieldShortName]
                                                           ,[ValueUDF]
                                                           ,[LUPTableName]
                                                           ,[AdminOnly]
                                                       FROM [dbo].[DTTableDefinition]";
                    $D_T_TableListCategory_sql = "SELECT [TableName]
                                                           ,[TableGroupCode]
                                                           ,[UseAdminOnly]
                                                           ,[UseReport]
                                                           ,[UseTransaction]
                                                           ,[UseLUP]
                                                       FROM [dbo].[DTTableListCategory]";
                    $D_T_LUP_sql = "SELECT [AdmCountryCode]
                                           ,[TableName]
                                           ,[ListCode]
                                           ,[ListName]
                                           FROM [dbo].[DTLUP]";
										   
					$DTA_Skip_Table_sql = "SELECT [DTBasic]
											,[DTQCode]
											,[SkipCode]
											,[DTACodeCombN]
											,[DTSkipDTQCode]
										FROM [dbo].[DTASkipTable]";

                    break;

                case 1: // for Registration
                case 2: // for DISTRIBUTION
                case 3: // for SERVICE
                case 5: // for Training And Activity

                    $D_T_answer_sql = "SELECT [DTBasic]
                                                        ,[DTQCode]
                                                        ,[DTACode]
                                                        ,[DTALabel]
                                                        ,[DTAValue]
                                                        ,[DTSeq]
                                                        ,[DTAShort]
                                                        ,[DTScoreCode]
                                                        ,[DTSkipDTQCode]
                                                        ,[DTACompareCode]
                                                        ,[ShowHide]
                                                        ,[MaxValue]
                                                        ,[MinValue]
                                                        ,[DataType]
                                                        ,[MarkOnGrid]
                                                    FROM [dbo].[DTATable]
                                                     WHERE [EntryBy]='0'";
                    $D_T_basic_sql = "SELECT [DTBasic]
                                                  ,[DTTitle]
                                                 ,[DTSubTitle]
                                                 ,[DTDescription]
                                                 ,[DTAutoScroll]
                                                 ,[DTAutoScrollText]
                                                 ,[DTActive]
                                                 ,[DTCategory]
                                                 ,[DTGeoListLevel]
                                                 ,[DTOPMode]
												 ,[DTShortName]
                                               FROM [dbo].[DTBasic]
                                                WHERE [EntryBy]='0'";

                    $D_T_category_sql = " SELECT [DTCategory]
                                                 ,[CategoryName]
                                                 ,[Frequency]
                                                 FROM [dbo].[DTCategory]
                                                  WHERE [EntryBy]='0'";

                    $D_T_CountryProgram_sql = "SELECT [AdmCountryCode]
                                                  ,[AdmDonorCode]
                                                  ,[AdmAwardCode]
                                                  ,[AdmProgCode]
                                                  ,[ProgActivityCode]
                                                  ,[ProgActivityTitle]
                                                  ,[DTBasic]
                                                  ,[RefIdentifier]
                                                  ,[Status]
                                                  ,[RptFrequency]
                                              FROM [dbo].[DTCountryProgram]
                                               WHERE [EntryBy]='0'";

                    $D_T_GeoListLevel_sql = " SELECT [GeoLevel]
                                                         ,[GeoLevelName]
                                                         ,[ListUDFName]
                                                       FROM [dbo].[DTGeoListLevel]
                                                        WHERE [EntryBy]='0'";

                    $D_T_QResMode_sql = "SELECT [QResMode]
                                                               ,[QResLupText]
                                                               ,[DataType]
                                                               ,[LookUpUDFName]
                                                               ,[ResponseValueControl]
                                                           FROM [dbo].[DTQResMode]
                                                            WHERE [EntryBy]='0'";

                    $D_T_QTable_sql = "  SELECT [DTBasic]
                                                     ,[DTQCode]
                                                     ,[QText]
                                                     ,[QResMode]
                                                     ,[QSeq]
                                                     ,[AllowNull]
                                                     ,[LUPTableName]
                                                       FROM [dbo].[DTQTable]
                                                        WHERE [EntryBy]='0'";

                    $D_T_ResponseTable_sql = " SELECT [DTBasic]
                                                  ,[AdmCountryCode]
                                                  ,[AdmDonorCode]
                                                  ,[AdmAwardCode]
                                                  ,[AdmProgCode]
                                                  ,[DTEnuID]
                                                  ,[DTQCode]
                                                  ,[DTACode]
                                                  ,[DTRSeq]
                                                  ,[DTAValue]
                                                  ,[ProgActivityCode]
                                                  ,[DTTimeString]
                                                  ,[OpMode]
                                                  ,[OpMonthCode]
                                                  ,[DataType]
                                                 FROM [dbo].[DTResponseTable]
                                                  WHERE [EntryBy]='0'";

                    $D_T_TableDefinition_sql = "SELECT [TableName]
                                                           ,[FieldName]
                                                           ,[FieldDefinition]
                                                           ,[FieldShortName]
                                                           ,[ValueUDF]
                                                           ,[LUPTableName]
                                                           ,[AdminOnly]
                                                       FROM [dbo].[DTTableDefinition]
                                                        WHERE [EntryBy]='0'";

                    $D_T_TableListCategory_sql = "SELECT [TableName]
                                                           ,[TableGroupCode]
                                                           ,[UseAdminOnly]
                                                           ,[UseReport]
                                                           ,[UseTransaction]
                                                           ,[UseLUP]
                                                       FROM [dbo].[DTTableListCategory]
                                                        WHERE [EntryBy]='0'";
                    $D_T_LUP_sql = "SELECT [AdmCountryCode]
                                           ,[TableName]
                                           ,[ListCode]
                                           ,[ListName]
                                           FROM [dbo].[DTLUP]
                                           WHERE [EntryBy]='0'";
										   
					$DTA_Skip_Table_sql = "SELECT [DTBasic]
											,[DTQCode]
											,[SkipCode]
											,[DTACodeCombN]
											,[DTSkipDTQCode]
										FROM [dbo].[DTASkipTable]
										WHERE [DTBasic]='0'
										";

                    break;
            }


            //	echo($reg_m_assign_prog_srvsql);


            $this->query($D_T_answer_sql);
            $D_T_answer = $this->resultset();
            if ($D_T_answer != false) {

                foreach ($D_T_answer as $key => $value) {
                    $data['D_T_answer'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['D_T_answer'][$key]['DTQCode'] = $value['DTQCode'];
                    $data['D_T_answer'][$key]['DTACode'] = $value['DTACode'];
                    $data['D_T_answer'][$key]['DTALabel'] = $value['DTALabel'];
                    $data['D_T_answer'][$key]['DTAValue'] = $value['DTAValue'];
                    $data['D_T_answer'][$key]['DTSeq'] = $value['DTSeq'];
                    $data['D_T_answer'][$key]['DTAShort'] = $value['DTAShort'];
                    $data['D_T_answer'][$key]['DTScoreCode'] = $value['DTScoreCode'];
                    $data['D_T_answer'][$key]['DTSkipDTQCode'] = $value['DTSkipDTQCode'];
                    $data['D_T_answer'][$key]['DTACompareCode'] = $value['DTACompareCode'];
                    $data['D_T_answer'][$key]['ShowHide'] = $value['ShowHide'];
                    $data['D_T_answer'][$key]['MaxValue'] = $value['MaxValue'];
                    $data['D_T_answer'][$key]['MinValue'] = $value['MinValue'];
                    $data['D_T_answer'][$key]['DataType'] = $value['DataType'];
                    $data['D_T_answer'][$key]['MarkOnGrid'] = $value['MarkOnGrid'];
                }
            }// end of D_T_answer table

            $this->query($D_T_basic_sql);
            $D_T_basic = $this->resultset();
            if ($D_T_basic != false) {

                foreach ($D_T_basic as $key => $value) {
                    $data['D_T_basic'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['D_T_basic'][$key]['DTTitle'] = $value['DTTitle'];
                    $data['D_T_basic'][$key]['DTSubTitle'] = $value['DTSubTitle'];
                    $data['D_T_basic'][$key]['DTDescription'] = $value['DTDescription'];
                    $data['D_T_basic'][$key]['DTAutoScroll'] = $value['DTAutoScroll'];
                    $data['D_T_basic'][$key]['DTAutoScrollText'] = $value['DTAutoScrollText'];
                    $data['D_T_basic'][$key]['DTActive'] = $value['DTActive'];
                    $data['D_T_basic'][$key]['DTCategory'] = $value['DTCategory'];
                    $data['D_T_basic'][$key]['DTGeoListLevel'] = $value['DTGeoListLevel'];
                    $data['D_T_basic'][$key]['DTOPMode'] = $value['DTOPMode'];
                    $data['D_T_basic'][$key]['DTShortName'] = $value['DTShortName'];					
                }
            }// end of D_T_basic table


            $this->query($D_T_category_sql);
            $D_T_category = $this->resultset();
            if ($D_T_category != false) {

                foreach ($D_T_category as $key => $value) {
                    $data['D_T_category'][$key]['DTCategory'] = $value['DTCategory'];
                    $data['D_T_category'][$key]['CategoryName'] = $value['CategoryName'];
                    $data['D_T_category'][$key]['Frequency'] = $value['Frequency'];
                }
            }// end of D_T_category table


            $this->query($D_T_CountryProgram_sql);
            $D_T_CountryProgram = $this->resultset();
            if ($D_T_CountryProgram != false) {

                foreach ($D_T_CountryProgram as $key => $value) {
                    $data['D_T_CountryProgram'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['D_T_CountryProgram'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['D_T_CountryProgram'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['D_T_CountryProgram'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['D_T_CountryProgram'][$key]['ProgActivityCode'] = $value['ProgActivityCode'];
                    $data['D_T_CountryProgram'][$key]['ProgActivityTitle'] = $value['ProgActivityTitle'];
                    $data['D_T_CountryProgram'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['D_T_CountryProgram'][$key]['RefIdentifier'] = $value['RefIdentifier'];
                    $data['D_T_CountryProgram'][$key]['Status'] = $value['Status'];
                    $data['D_T_CountryProgram'][$key]['RptFrequency'] = $value['RptFrequency'];
                }
            }// end of D_T_CountryProgram table


            $this->query($D_T_GeoListLevel_sql);
            $D_T_GeoListLevel = $this->resultset();
            if ($D_T_GeoListLevel != false) {

                foreach ($D_T_GeoListLevel as $key => $value) {
                    $data['D_T_GeoListLevel'][$key]['GeoLevel'] = $value['GeoLevel'];
                    $data['D_T_GeoListLevel'][$key]['GeoLevelName'] = $value['GeoLevelName'];
                    $data['D_T_GeoListLevel'][$key]['ListUDFName'] = $value['ListUDFName'];
                }
            }// end of D_T_GeoListLevel table

            $this->query($D_T_QResMode_sql);
            $D_T_QResMode = $this->resultset();
            if ($D_T_QResMode != false) {

                foreach ($D_T_QResMode as $key => $value) {
                    $data['D_T_QResMode'][$key]['QResMode'] = $value['QResMode'];
                    $data['D_T_QResMode'][$key]['QResLupText'] = $value['QResLupText'];
                    $data['D_T_QResMode'][$key]['DataType'] = $value['DataType'];
                    $data['D_T_QResMode'][$key]['LookUpUDFName'] = $value['LookUpUDFName'];
                    $data['D_T_QResMode'][$key]['ResponseValueControl'] = $value['ResponseValueControl'];
                }
            }// end of D_T_QResMode table

            $this->query($D_T_QTable_sql);
            $D_T_QTable = $this->resultset();
            if ($D_T_QTable != false) {

                foreach ($D_T_QTable as $key => $value) {
                    $data['D_T_QTable'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['D_T_QTable'][$key]['DTQCode'] = $value['DTQCode'];
                    $data['D_T_QTable'][$key]['QText'] = $value['QText'];
                    $data['D_T_QTable'][$key]['QResMode'] = $value['QResMode'];
                    $data['D_T_QTable'][$key]['QSeq'] = $value['QSeq'];
                    $data['D_T_QTable'][$key]['AllowNull'] = $value['AllowNull'];
                    $data['D_T_QTable'][$key]['LUPTableName'] = $value['LUPTableName'];
                }
            }// end of D_T_QTable table


            $this->query($D_T_ResponseTable_sql);
            $D_T_ResponseTable = $this->resultset();
            if ($D_T_ResponseTable != false) {

                foreach ($D_T_ResponseTable as $key => $value) {
                    $data['D_T_ResponseTable'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['D_T_ResponseTable'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['D_T_ResponseTable'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['D_T_ResponseTable'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['D_T_ResponseTable'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['D_T_ResponseTable'][$key]['DTEnuID'] = $value['DTEnuID'];
                    $data['D_T_ResponseTable'][$key]['DTQCode'] = $value['DTQCode'];
                    $data['D_T_ResponseTable'][$key]['DTACode'] = $value['DTACode'];
                    $data['D_T_ResponseTable'][$key]['DTRSeq'] = $value['DTRSeq'];
                    $data['D_T_ResponseTable'][$key]['DTAValue'] = $value['DTAValue'];
                    $data['D_T_ResponseTable'][$key]['ProgActivityCode'] = $value['ProgActivityCode'];
                    $data['D_T_ResponseTable'][$key]['DTTimeString'] = $value['DTTimeString'];
                    $data['D_T_ResponseTable'][$key]['OpMode'] = $value['OpMode'];
                    $data['D_T_ResponseTable'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                    $data['D_T_ResponseTable'][$key]['DataType'] = $value['DataType'];
                }
            }// end of D_T_ResponseTable table

            $this->query($D_T_TableDefinition_sql);
            $D_T_TableDefinition = $this->resultset();
            if ($D_T_TableDefinition != false) {

                foreach ($D_T_TableDefinition as $key => $value) {
                    $data['D_T_TableDefinition'][$key]['TableName'] = $value['TableName'];
                    $data['D_T_TableDefinition'][$key]['FieldName'] = $value['FieldName'];
                    $data['D_T_TableDefinition'][$key]['FieldDefinition'] = $value['FieldDefinition'];
                    $data['D_T_TableDefinition'][$key]['FieldShortName'] = $value['FieldShortName'];
                    $data['D_T_TableDefinition'][$key]['ValueUDF'] = $value['ValueUDF'];
                    $data['D_T_TableDefinition'][$key]['LUPTableName'] = $value['LUPTableName'];
                    $data['D_T_TableDefinition'][$key]['AdminOnly'] = $value['AdminOnly'];
                }
            }// end of D_T_TableDefinition table


            $this->query($D_T_TableListCategory_sql);
            $D_T_TableListCategory = $this->resultset();
            if ($D_T_TableListCategory != false) {

                foreach ($D_T_TableListCategory as $key => $value) {
                    $data['D_T_TableListCategory'][$key]['TableName'] = $value['TableName'];
                    $data['D_T_TableListCategory'][$key]['TableGroupCode'] = $value['TableGroupCode'];
                    $data['D_T_TableListCategory'][$key]['UseAdminOnly'] = $value['UseAdminOnly'];
                    $data['D_T_TableListCategory'][$key]['UseReport'] = $value['UseReport'];
                    $data['D_T_TableListCategory'][$key]['UseTransaction'] = $value['UseTransaction'];
                    $data['D_T_TableListCategory'][$key]['UseLUP'] = $value['UseLUP'];
                }
            }// end of D_T_TableListCategory table


            $this->query($D_T_LUP_sql);
            $D_T_LUP = $this->resultset();
            if ($D_T_LUP != false) {

                foreach ($D_T_LUP as $key => $value) {
                    $data['D_T_LUP'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['D_T_LUP'][$key]['TableName'] = $value['TableName'];
                    $data['D_T_LUP'][$key]['ListCode'] = $value['ListCode'];
                    $data['D_T_LUP'][$key]['ListName'] = $value['ListName'];
                }
            }// end of D_T_LUP table
			
			// DTA_Skip_Table_sql
			$this->query($DTA_Skip_Table_sql);
            $DTA_Skip_Table = $this->resultset();
            if ($DTA_Skip_Table != false) {

                foreach ($DTA_Skip_Table as $key => $value) {
                    $data['DTA_Skip_Table'][$key]['DTBasic'] = $value['DTBasic'];
                    $data['DTA_Skip_Table'][$key]['DTQCode'] = $value['DTQCode'];
                    $data['DTA_Skip_Table'][$key]['SkipCode'] = $value['SkipCode'];
                    $data['DTA_Skip_Table'][$key]['DTACodeCombN'] = $value['DTACodeCombN'];
                    $data['DTA_Skip_Table'][$key]['DTSkipDTQCode'] = $value['DTSkipDTQCode'];	

                }
            }// end of DTA_Skip_Table table
        } else {
            return false;
        }
        return $data;
    }

    /**
     * End Dynamic
     */

    /**
     * @param $user
     * @param $pass
     * @param $lay_r_code_j
     * @param $operation_mode
     * @return array|bool
     */
    // Download RegNAssignProgramService Data & Sub Assigne Class
    public function is_down_load_reg_assn_prog($user, $pass, $lay_r_code_j, $operation_mode) {
        $data = array();
        $data["reg_m_assign_prog_srv"] = array();

        $data["regn_pw"] = array();
        $data["regn_lm"] = array();
        $data["regn_cu2"] = array();
        $data["regn_ca2"] = array();
        $data["reg_n_agr"] = array();
        $data["reg_n_ct"] = array();
        $data["reg_n_ffa"] = array();
        $data["reg_n_we"] = array();

        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // decode the json array
            $jData = json_decode($lay_r_code_j);
            $isAddressCodeAdded = false;


            $s = "";
            $countryCode = "";
            //echo($operation_mode);
            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the value of selected

                        $s .= $value->selectedLayR4Code . ',';
                        // is address code addded
                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 			echo($s);
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 3 : // fro sERVICE cENTER  2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;
					case 5:
					foreach ($jData as $key => $value) {
                        // set the value of selected

                        $s .= $value->selectedLayR4Code . ',';
                        
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 	
					break ;
            }


            if ($jData) {

                $reg_m_assign_prog_srvsql = "";
                $regn_pw_sql = "";
                // $reg_n_agr_sql = "";
                $regn_lm_sql = "";
                $regn_cu2_sql = "";
                $regn_ca2_sql = "";
                $reg_n_agr_sql = "";
                $reg_n_ct_sql = "";
                $reg_n_ffa_sql = "";
                $reg_n_we_sql = "";
                switch ($operation_mode) {
                    case 1: // for registration  select 2 village
                        // getting data from regNassign_progSrv table
                        // for some reson address code is not implemente  completely
                        if ($isAddressCodeAdded) {
                            $reg_m_assign_prog_srvsql = "SELECT [AdmCountryCode]
								,[LayR1ListCode]
								,[LayR2ListCode]
								,[LayR3ListCode]
								,[LayR4ListCode]
								,[AdmDonorCode]
								,[AdmAwardCode]
								,[HHID]
								,[MemID]
								,[ProgCode]
								,[SrvCode]
								,convert(varchar,RegNDate,110) AS [RegNDate]
								,[GRDCode]
								,convert(varchar,GRDDate,110) AS [GRDDate]
								, ISNULL(convert(varchar,[SrvMin],110) ,'') AS [SrvMin]
                                , ISNULL(convert(varchar,[SrvMax],110) ,'') AS [SrvMax]
								FROM [dbo].[RegNAssignProgSrv]
						
								-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where  AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . "))";


                            // getting data from regnPW table StfCode

                            $regn_pw_sql = "SELECT
									[AdmCountryCode],
									[LayR1ListCode] ,
									[LayR2ListCode] ,
									[LayR3ListCode] ,
									[LayR4ListCode] ,
									[HHID] ,
									[MemID],
									convert(varchar,RegNDate,110) AS [RegNDate],
									convert(varchar,LMPDate,110) AS[LMPDate],
									[AdmProgCode],
									[AdmSrvCode],
									[GRDCode],
									[PWGRDDate]
								
									from [RegN_PW]
								where

								AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")  )";

                            $regn_lm_sql = "SELECT
									[AdmCountryCode],
									[LayR1ListCode] ,
									[LayR2ListCode] ,
									[LayR3ListCode] ,
									[LayR4ListCode] ,
									[HHID] ,
									[MemID],
									convert(varchar,RegNDate,110) AS [RegNDate],
									convert(varchar,LMDOB,110) AS [LMDOB],
									[AdmProgCode],
									[AdmSrvCode],
									[GRDCode],
									[LMGRDDate]
									,[ChildName]
                                    ,[ChildSex]
									
									from [RegN_LM]
								where

								AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")
                     )";

                            $regn_cu2_sql = "SELECT
									[AdmCountryCode],
									[LayR1ListCode] ,
									[LayR2ListCode] ,
									[LayR3ListCode] ,
									[LayR4ListCode] ,
									[HHID] ,
									[MemID],
									convert(varchar,RegNDate,110) AS[RegNDate],
									convert(varchar,CU2DOB,110) AS [CU2DOB],
									[AdmProgCode],
									[AdmSrvCode],
									[GRDCode],
									[CU2GRDDate]
									,[ChildName]
                                    ,[ChildSex]
									--,[EntryBy]
									--,	[EntryDate]
									from [RegN_Cu2]
								where

								AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")
                     )";

                            //added by MR
                            // getting data from regnCu2 table StfCode

                            $regn_ca2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,CA2DOB,110) AS [CA2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CA2GRDDate]
							,[ChildName]
                            ,[ChildSex]
							--,[EntryBy]
							--,[EntryDate]
							from [RegN_Ca2]
						 where

						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")
                     )";


                            $reg_n_ct_sql = "SELECT [AdmCountryCode]
							,[LayR1ListCode]
							,[LayR2ListCode]
							,[LayR3ListCode]
							,[LayR4ListCode]
							,[HHID]
							,[MemID]
							,[C11_CT_PR]
							,[C21_CT_PR]
							,[C31_CT_PR]
							,[C32_CT_PR]
							,[C33_CT_PR]
							,[C34_CT_PR]
							,[C35_CT_PR]
							,[C36_CT_PR]
							,[C37_CT_PR]
							,[C38_CT_PR]
							--,[EntryBy]
							--,[EntryDate]
						FROM [dbo].[RegN_CT]
						WHERE
						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")
                     )";

                            $reg_n_agr_sql = "SELECT [AdmCountryCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[HHID]
									  ,[MemID]
									  ,convert(varchar,RegNDate,110) AS [RegNDate]
									  ,[ElderlyYN]
									  --,[EntryBy]
									  --,[EntryDate
									  ,ISNULL([LandSize],0) AS [LandSize]
									  ,ISNULL([DependOnGanyu],'') AS DependOnGanyu
									  ,ISNULL([Willingness],'') AS Willingness
									  ,ISNULL([WinterCultivation],'') AS WinterCultivation
									  ,ISNULL([VulnerableHH],'') AS VulnerableHH
									  ,ISNULL([PlantingValueChainCrop],'') AS PlantingValueChainCrop
									  			       ,ISNULL([AGOINVC],'') AS  [AGOINVC]
                        ,ISNULL([AGONASFAM],'') AS  [AGONASFAM]
                        ,ISNULL([AGOCU],'') AS  [AGOCU]
                        ,ISNULL([AGOOther],'') AS  [AGOOther]
                          ,ISNULL([LSGoat],0) AS  [LSGoat]
                        ,ISNULL([LSChicken],0) AS  [LSChicken]
                        ,ISNULL([LSOther],0) AS  [LSOther]
                        ,ISNULL([LSPigeon],0) AS  [LSPigeon]
								  FROM [dbo].[RegN_AGR]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")
                     )";


                            $reg_n_ffa_sql = "SELECT [AdmCountryCode]
                ,[LayR1ListCode]
                        ,[LayR2ListCode]
                        ,[LayR3ListCode]
                        ,[LayR4ListCode]
                        ,[HHID]
                        ,[MemID]
                        ,ISNULL([OrphanedChildren],'') AS [OrphanedChildren]
                        ,[ChildHeaded]
                        ,[ElderlyHeaded]
                        ,[ChronicallyIll]
                        ,[FemaleHeaded]
                        ,[CropFailure]
                        ,[ChildrenRecSuppFeedN]
                        ,[Willingness]

                    FROM [dbo].[RegN_FFA]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (   SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode  in(" . $s . ")
                     )";
					 
					    $reg_n_we_sql = "SELECT [AdmCountryCode]
      ,[LayR1ListCode]
      ,[LayR2ListCode]
      ,[LayR3ListCode]
      ,[LayR4ListCode]
      ,[HHID]
      ,[MemID]
      ,[RegNDate]
      ,[WealthRanking]
      ,[MemberExtGroup]
     
  FROM [dbo].[RegN_WE]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in  (" . $s . ")";
					 
					 
                        } else {
                            $reg_m_assign_prog_srvsql = "SELECT [AdmCountryCode]
								,[LayR1ListCode]
								,[LayR2ListCode]
								,[LayR3ListCode]
								,[LayR4ListCode]
								,[AdmDonorCode]
								,[AdmAwardCode]
								,[HHID]
								,[MemID]
								,[ProgCode]
								,[SrvCode]
								,convert(varchar,RegNDate,110) AS [RegNDate]
								,[GRDCode]
								,convert(varchar,GRDDate,110) AS [GRDDate]
								, ISNULL(convert(varchar,[SrvMin],110) ,'') AS [SrvMin]
                                , ISNULL(convert(varchar,[SrvMax],110) ,'') AS [SrvMax]
								FROM [dbo].[RegNAssignProgSrv]
								--  where [AdmCountryCode]=(Select AdmCountryCode from [dbo].[StaffAssignCountry] where --StfCode=:user_id and [StatusAssign]=1)
								--And
								-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where  AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";


                            // getting data from regnPW table StfCode

                            $regn_pw_sql = "SELECT
									[AdmCountryCode],
									[LayR1ListCode] ,
									[LayR2ListCode] ,
									[LayR3ListCode] ,
									[LayR4ListCode] ,
									[HHID] ,
									[MemID],
									convert(varchar,RegNDate,110) AS [RegNDate],
									convert(varchar,LMPDate,110) AS[LMPDate],
									[AdmProgCode],
									[AdmSrvCode],
									[GRDCode],
									[PWGRDDate]
								
									from [RegN_PW]
								where

								AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";

                            $regn_lm_sql = "SELECT
									[AdmCountryCode],
									[LayR1ListCode] ,
									[LayR2ListCode] ,
									[LayR3ListCode] ,
									[LayR4ListCode] ,
									[HHID] ,
									[MemID],
									convert(varchar,RegNDate,110) AS [RegNDate],
									convert(varchar,LMDOB,110) AS [LMDOB],
									[AdmProgCode],
									[AdmSrvCode],
									[GRDCode],
									[LMGRDDate]
									,[ChildName]
                                    ,[ChildSex]
								
									from [RegN_LM]
								where

								AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";

                            $regn_cu2_sql = "SELECT
									[AdmCountryCode],
									[LayR1ListCode] ,
									[LayR2ListCode] ,
									[LayR3ListCode] ,
									[LayR4ListCode] ,
									[HHID] ,
									[MemID],
									convert(varchar,RegNDate,110) AS[RegNDate],
									convert(varchar,CU2DOB,110) AS [CU2DOB],
									[AdmProgCode],
									[AdmSrvCode],
									[GRDCode],
									[CU2GRDDate]
									,[ChildName]
                                    ,[ChildSex]
									
									from [RegN_Cu2]
								where

								AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";

                            //added by MR
                            // getting data from regnCu2 table StfCode

                            $regn_ca2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,CA2DOB,110) AS [CA2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CA2GRDDate]
							,[ChildName]
                            ,[ChildSex]
							--,[EntryBy]
							--,[EntryDate]
							from [RegN_Ca2]
						 where

						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";


                            $reg_n_ct_sql = "SELECT [AdmCountryCode]
							,[LayR1ListCode]
							,[LayR2ListCode]
							,[LayR3ListCode]
							,[LayR4ListCode]
							,[HHID]
							,[MemID]
							,[C11_CT_PR]
							,[C21_CT_PR]
							,[C31_CT_PR]
							,[C32_CT_PR]
							,[C33_CT_PR]
							,[C34_CT_PR]
							,[C35_CT_PR]
							,[C36_CT_PR]
							,[C37_CT_PR]
							,[C38_CT_PR]
							--,[EntryBy]
							--,[EntryDate]
						FROM [dbo].[RegN_CT]
						WHERE
						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";

                            $reg_n_agr_sql = "SELECT [AdmCountryCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[HHID]
									  ,[MemID]
									  ,convert(varchar,RegNDate,110) AS [RegNDate]
									  ,[ElderlyYN]
									 
									  ,ISNULL([LandSize],0) AS [LandSize]
									  ,ISNULL([DependOnGanyu],'') AS DependOnGanyu
									  ,ISNULL([Willingness],'') AS Willingness
									  ,ISNULL([WinterCultivation],'') AS WinterCultivation
									  ,ISNULL([VulnerableHH],'') AS VulnerableHH
									  ,ISNULL([PlantingValueChainCrop],'') AS PlantingValueChainCrop
									       ,ISNULL([AGOINVC],'') AS  [AGOINVC]
                        ,ISNULL([AGONASFAM],'') AS  [AGONASFAM]
                        ,ISNULL([AGOCU],'') AS  [AGOCU]
                        ,ISNULL([AGOOther],'') AS  [AGOOther]
                        ,ISNULL([LSGoat],0) AS  [LSGoat]
                        ,ISNULL([LSChicken],0) AS  [LSChicken]
                        ,ISNULL([LSOther],0) AS  [LSOther]
                        ,ISNULL([LSPigeon],0) AS  [LSPigeon]

								  FROM [dbo].[RegN_AGR]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";

                            $reg_n_ffa_sql = "SELECT [AdmCountryCode]
                ,[LayR1ListCode]
                        ,[LayR2ListCode]
                        ,[LayR3ListCode]
                        ,[LayR4ListCode]
                        ,[HHID]
                        ,[MemID]
                        ,ISNULL([OrphanedChildren],'') AS [OrphanedChildren]
                        ,[ChildHeaded]
                        ,[ElderlyHeaded]
                        ,[ChronicallyIll]
                        ,[FemaleHeaded]
                        ,[CropFailure]
                        ,[ChildrenRecSuppFeedN]
                        ,[Willingness]

                    FROM [dbo].[RegN_FFA]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";
						 	    $reg_n_we_sql = "SELECT [AdmCountryCode]
      ,[LayR1ListCode]
      ,[LayR2ListCode]
      ,[LayR3ListCode]
      ,[LayR4ListCode]
      ,[HHID]
      ,[MemID]
      ,[RegNDate]
      ,[WealthRanking]
      ,[MemberExtGroup]
     
  FROM [dbo].[RegN_WE]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";
                        }

                        break;
                    case 2: // for distribution
                    case 4: // for Other
                        $reg_m_assign_prog_srvsql = "SELECT [AdmCountryCode]
					  ,[LayR1ListCode]
					  ,[LayR2ListCode]
					  ,[LayR3ListCode]
					  ,[LayR4ListCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[HHID]
					  ,[MemID]
					  ,[ProgCode]
					  ,[SrvCode]
					  ,convert(varchar,RegNDate,110) AS [RegNDate]
					  ,[GRDCode]
					  ,convert(varchar,GRDDate,110) AS [GRDDate]
					  , ISNULL(convert(varchar,[SrvMin],110) ,'') AS [SrvMin]
                      , ISNULL(convert(varchar,[SrvMax],110) ,'') AS [SrvMax]
					  FROM [dbo].[RegNAssignProgSrv]
					--  where [AdmCountryCode]=(Select AdmCountryCode from [dbo].[StaffAssignCountry] where --StfCode=:user_id and [StatusAssign]=1)
				  --And
					  -- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
						where  AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        // getting data from regnPW table StfCode

                        $regn_pw_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,LMPDate,110) AS[LMPDate],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[PWGRDDate]

							from [RegN_PW]
						 where

						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";

                        $regn_lm_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,LMDOB,110) AS [LMDOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[LMGRDDate]
							,[ChildName]
                            ,[ChildSex]
							--,[EntryBy]
							--,[EntryDate]
							from [RegN_LM]
						 where

						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";

                        $regn_cu2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS[RegNDate],
							convert(varchar,CU2DOB,110) AS [CU2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CU2GRDDate]
							--,[EntryBy]
							--,	[EntryDate]
							from [RegN_Cu2]
						 where

						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";

                        //added by MR
                        // getting data from regnCu2 table StfCode

                        $regn_ca2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,CA2DOB,110) AS [CA2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CA2GRDDate]
							--,[EntryBy]
							--,[EntryDate]
							from [RegN_Ca2]
						 where

						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        $reg_n_ct_sql = "SELECT [AdmCountryCode]
							,[LayR1ListCode]
							,[LayR2ListCode]
							,[LayR3ListCode]
							,[LayR4ListCode]
							,[HHID]
							,[MemID]
							,[C11_CT_PR]
							,[C21_CT_PR]
							,[C31_CT_PR]
							,[C32_CT_PR]
							,[C33_CT_PR]
							,[C34_CT_PR]
							,[C35_CT_PR]
							,[C36_CT_PR]
							,[C37_CT_PR]
							,[C38_CT_PR]
							--,[EntryBy]
							--,[EntryDate]
						FROM [dbo].[RegN_CT]
						WHERE
						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";

                        $reg_n_agr_sql = "SELECT [AdmCountryCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[HHID]
									  ,[MemID]
									  ,convert(varchar,RegNDate,110) AS [RegNDate]
									  ,[ElderlyYN]
									  --,[EntryBy]
									  --,[EntryDate
									  ,ISNULL([LandSize],0) AS [LandSize]
									  ,ISNULL([DependOnGanyu],'') AS DependOnGanyu
									  ,ISNULL([Willingness],'') AS Willingness
									  ,ISNULL([WinterCultivation],'') AS WinterCultivation
									  ,ISNULL([VulnerableHH],'') AS VulnerableHH
									  ,ISNULL([PlantingValueChainCrop],'') AS PlantingValueChainCrop
									       ,ISNULL([AGOINVC],'') AS  [AGOINVC]
                        ,ISNULL([AGONASFAM],'') AS  [AGONASFAM]
                        ,ISNULL([AGOCU],'') AS  [AGOCU]
                        ,ISNULL([AGOOther],'') AS  [AGOOther]
                          ,ISNULL([LSGoat],0) AS  [LSGoat]
                        ,ISNULL([LSChicken],0) AS  [LSChicken]
                        ,ISNULL([LSOther],0) AS  [LSOther]
                        ,ISNULL([LSPigeon],0) AS  [LSPigeon]

								  FROM [dbo].[RegN_AGR]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        $reg_n_ffa_sql = "SELECT [AdmCountryCode]
                ,[LayR1ListCode]
                        ,[LayR2ListCode]
                        ,[LayR3ListCode]
                        ,[LayR4ListCode]
                        ,[HHID]
                        ,[MemID]
                        ,ISNULL([OrphanedChildren],'') AS [OrphanedChildren]
                        ,[ChildHeaded]
                        ,[ElderlyHeaded]
                        ,[ChronicallyIll]
                        ,[FemaleHeaded]
                        ,[CropFailure]
                        ,[ChildrenRecSuppFeedN]
                        ,[Willingness]

                    FROM [dbo].[RegN_FFA]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";
						 
						            $reg_n_we_sql = "SELECT [AdmCountryCode]
      ,[LayR1ListCode]
      ,[LayR2ListCode]
      ,[LayR3ListCode]
      ,[LayR4ListCode]
      ,[HHID]
      ,[MemID]
      ,[RegNDate]
      ,[WealthRanking]
      ,[MemberExtGroup]
     
  FROM [dbo].[RegN_WE]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";
						 
						 
                        break;


                    case 3: // for sERVICE

                        $reg_m_assign_prog_srvsql = "SELECT [AdmCountryCode]
										,[LayR1ListCode]
										,[LayR2ListCode]
										,[LayR3ListCode]
										,[LayR4ListCode]
										,[AdmDonorCode]
										,[AdmAwardCode]
										,[HHID]
										,[MemID]
										,[ProgCode]
										,[SrvCode]
										,convert(varchar,RegNDate,110) AS [RegNDate]
										,[GRDCode]
										,convert(varchar,GRDDate,110) AS [GRDDate]
										, ISNULL(convert(varchar,[SrvMin],110) ,'') AS [SrvMin]
                                        , ISNULL(convert(varchar,[SrvMax],110) ,'') AS [SrvMax]
										FROM [dbo].[RegNAssignProgSrv]
										where
										[AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]+[MemID] in(SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE AdmCountryCode+[GrpCode]
					IN(SELECT AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";


                        // getting data from regnPW table StfCode

                        $regn_pw_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,LMPDate,110) AS[LMPDate],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[PWGRDDate]

							from [RegN_PW]
							 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE  AdmCountryCode+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";
                        //FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";

                        $regn_lm_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,LMDOB,110) AS [LMDOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[LMGRDDate]
							,[ChildName]
                            ,[ChildSex]

							from [RegN_LM]
							 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";
                        //	FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";

                        $regn_cu2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS[RegNDate],
							convert(varchar,CU2DOB,110) AS [CU2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CU2GRDDate]
							,[ChildName]
                            ,[ChildSex]

							from [RegN_Cu2]
							 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";
                        //		FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";
                        //added by MR
                        // getting data from regnCu2 table StfCode

                        $regn_ca2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,CA2DOB,110) AS [CA2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CA2GRDDate]
							,[ChildName]
                            ,[ChildSex]

							from [RegN_Ca2]
							 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";
                        //	FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";


                        $reg_n_ct_sql = "SELECT [AdmCountryCode]
							,[LayR1ListCode]
							,[LayR2ListCode]
							,[LayR3ListCode]
							,[LayR4ListCode]
							,[HHID]
							,[MemID]
							,[C11_CT_PR]
							,[C21_CT_PR]
							,[C31_CT_PR]
							,[C32_CT_PR]
							,[C33_CT_PR]
							,[C34_CT_PR]
							,[C35_CT_PR]
							,[C36_CT_PR]
							,[C37_CT_PR]
							,[C38_CT_PR]

						FROM [dbo].[RegN_CT]
							 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";
                        //	FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";

                        $reg_n_agr_sql = "SELECT [AdmCountryCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[HHID]
									  ,[MemID]
									  ,convert(varchar,RegNDate,110) AS [RegNDate]
									  ,[ElderlyYN]

									  ,ISNULL([LandSize],0) AS [LandSize]
									  ,ISNULL([DependOnGanyu],'') AS DependOnGanyu
									  ,ISNULL([Willingness],'') AS Willingness
									  ,ISNULL([WinterCultivation],'') AS WinterCultivation
									  ,ISNULL([VulnerableHH],'') AS VulnerableHH
									  ,ISNULL([PlantingValueChainCrop],'') AS PlantingValueChainCrop

                        ,ISNULL([AGOINVC],'') AS  [AGOINVC]
                        ,ISNULL([AGONASFAM],'') AS  [AGONASFAM]
                        ,ISNULL([AGOCU],'') AS  [AGOCU]
                        ,ISNULL([AGOOther],'') AS  [AGOOther]
                        ,ISNULL([LSGoat],0) AS  [LSGoat]
                        ,ISNULL([LSChicken],0) AS  [LSChicken]
                        ,ISNULL([LSOther],0) AS  [LSOther]
                        ,ISNULL([LSPigeon],0) AS  [LSPigeon]

								  FROM [dbo].[RegN_AGR]

							 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))";
                        //		FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";


                        $reg_n_ffa_sql = "SELECT [AdmCountryCode]
                ,[LayR1ListCode]
                        ,[LayR2ListCode]
                        ,[LayR3ListCode]
                        ,[LayR4ListCode]
                        ,[HHID]
                        ,[MemID]
                        ,ISNULL([OrphanedChildren],'') AS [OrphanedChildren]
                        ,[ChildHeaded]
                        ,[ElderlyHeaded]
                        ,[ChronicallyIll]
                        ,[FemaleHeaded]
                        ,[CropFailure]
                        ,[ChildrenRecSuppFeedN]
                        ,[Willingness]

                    FROM [dbo].[RegN_FFA]
		 where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))	";
					
					            $reg_n_we_sql = "SELECT [AdmCountryCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RegNDate]
									,[WealthRanking]
									,[MemberExtGroup]
									
								FROM [dbo].[RegN_WE]
										where
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode +[HHID]+[MemID]
						 in (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]+[MemID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+[GrpCode]
					IN(SELECT  AdmCountryCode+GrpCode
					FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))	";


                        break;
						case 5: // training & activity 
						$reg_m_assign_prog_srvsql = "SELECT [AdmCountryCode]
								,[LayR1ListCode]
								,[LayR2ListCode]
								,[LayR3ListCode]
								,[LayR4ListCode]
								,[AdmDonorCode]
								,[AdmAwardCode]
								,[HHID]
								,[MemID]
								,[ProgCode]
								,[SrvCode]
								,convert(varchar,RegNDate,110) AS [RegNDate]
								,[GRDCode]
								,convert(varchar,GRDDate,110) AS [GRDDate]
								, ISNULL(convert(varchar,[SrvMin],110) ,'') AS [SrvMin]
                                , ISNULL(convert(varchar,[SrvMax],110) ,'') AS [SrvMax]
								FROM [dbo].[RegNAssignProgSrv]
						
								-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where  AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s.")";
									// getting data from regnPW table StfCode

                        $regn_pw_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,LMPDate,110) AS[LMPDate],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[PWGRDDate]

							from [RegN_PW]
							 where
						  AdmCountryCode='0000'";

                        $regn_lm_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,LMDOB,110) AS [LMDOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[LMGRDDate]
							,[ChildName]
                            ,[ChildSex]

							from [RegN_LM]
							 where
						 AdmCountryCode='0000'";
                        //	FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") )) )";

                        $regn_cu2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS[RegNDate],
							convert(varchar,CU2DOB,110) AS [CU2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CU2GRDDate]
							,[ChildName]
                            ,[ChildSex]

							from [RegN_Cu2]
							 where
						 AdmCountryCode='0000'";
                        //added by MR
                        // getting data from regnCu2 table StfCode

                        $regn_ca2_sql = "SELECT
							[AdmCountryCode],
							[LayR1ListCode] ,
							[LayR2ListCode] ,
							[LayR3ListCode] ,
							[LayR4ListCode] ,
							[HHID] ,
							[MemID],
							convert(varchar,RegNDate,110) AS [RegNDate],
							convert(varchar,CA2DOB,110) AS [CA2DOB],
							[AdmProgCode],
							[AdmSrvCode],
							[GRDCode],
							[CA2GRDDate]
							,[ChildName]
                            ,[ChildSex]

							from [RegN_Ca2]
							 where
					 AdmCountryCode='0000'";


                        $reg_n_ct_sql = "SELECT [AdmCountryCode]
							,[LayR1ListCode]
							,[LayR2ListCode]
							,[LayR3ListCode]
							,[LayR4ListCode]
							,[HHID]
							,[MemID]
							,[C11_CT_PR]
							,[C21_CT_PR]
							,[C31_CT_PR]
							,[C32_CT_PR]
							,[C33_CT_PR]
							,[C34_CT_PR]
							,[C35_CT_PR]
							,[C36_CT_PR]
							,[C37_CT_PR]
							,[C38_CT_PR]

						FROM [dbo].[RegN_CT]
							 where
						 AdmCountryCode='0000'";

                        $reg_n_agr_sql = "SELECT [AdmCountryCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[HHID]
									  ,[MemID]
									  ,convert(varchar,RegNDate,110) AS [RegNDate]
									  ,[ElderlyYN]

									  ,ISNULL([LandSize],0) AS [LandSize]
									  ,ISNULL([DependOnGanyu],'') AS DependOnGanyu
									  ,ISNULL([Willingness],'') AS Willingness
									  ,ISNULL([WinterCultivation],'') AS WinterCultivation
									  ,ISNULL([VulnerableHH],'') AS VulnerableHH
									  ,ISNULL([PlantingValueChainCrop],'') AS PlantingValueChainCrop

                        ,ISNULL([AGOINVC],'') AS  [AGOINVC]
                        ,ISNULL([AGONASFAM],'') AS  [AGONASFAM]
                        ,ISNULL([AGOCU],'') AS  [AGOCU]
                        ,ISNULL([AGOOther],'') AS  [AGOOther]
                        ,ISNULL([LSGoat],0) AS  [LSGoat]
                        ,ISNULL([LSChicken],0) AS  [LSChicken]
                        ,ISNULL([LSOther],0) AS  [LSOther]
                        ,ISNULL([LSPigeon],0) AS  [LSPigeon]

								  FROM [dbo].[RegN_AGR]

							 where
						 AdmCountryCode='0000'";


                        $reg_n_ffa_sql = "SELECT [AdmCountryCode]
                ,[LayR1ListCode]
                        ,[LayR2ListCode]
                        ,[LayR3ListCode]
                        ,[LayR4ListCode]
                        ,[HHID]
                        ,[MemID]
                        ,ISNULL([OrphanedChildren],'') AS [OrphanedChildren]
                        ,[ChildHeaded]
                        ,[ElderlyHeaded]
                        ,[ChronicallyIll]
                        ,[FemaleHeaded]
                        ,[CropFailure]
                        ,[ChildrenRecSuppFeedN]
                        ,[Willingness]

                    FROM [dbo].[RegN_FFA]
		 where
			 AdmCountryCode='0000'	";
					
					            $reg_n_we_sql = "SELECT [AdmCountryCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RegNDate]
									,[WealthRanking]
									,[MemberExtGroup]
									
								FROM [dbo].[RegN_WE]
										where
						 AdmCountryCode='0000'	";

						break ;
                }


                	//echo($reg_m_assign_prog_srvsql);


                $this->query($reg_m_assign_prog_srvsql);

                
                $reg_m_assign_prog_srv = $this->resultset();

                if ($reg_m_assign_prog_srv != false) {

                    foreach ($reg_m_assign_prog_srv as $key => $value) {
                        $data['reg_m_assign_prog_srv'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_m_assign_prog_srv'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_m_assign_prog_srv'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_m_assign_prog_srv'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_m_assign_prog_srv'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_m_assign_prog_srv'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['reg_m_assign_prog_srv'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['reg_m_assign_prog_srv'][$key]['HHID'] = $value['HHID'];
                        $data['reg_m_assign_prog_srv'][$key]['MemID'] = $value['MemID'];
                        $data['reg_m_assign_prog_srv'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['reg_m_assign_prog_srv'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['reg_m_assign_prog_srv'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['reg_m_assign_prog_srv'][$key]['GRDCode'] = $value['GRDCode'];
                        $data['reg_m_assign_prog_srv'][$key]['GRDDate'] = $value['GRDDate'];
                        $data['reg_m_assign_prog_srv'][$key]['SrvMin'] = $value['SrvMin'];
                        $data['reg_m_assign_prog_srv'][$key]['SrvMax'] = $value['SrvMax'];
                    }
                }// end of regNassign_progSrv table


                $this->query($regn_pw_sql);
                $regn_pw = $this->resultset();

                if ($regn_pw != false) {

                    foreach ($regn_pw as $key => $value) {

                        $data['regn_pw'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['regn_pw'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['regn_pw'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['regn_pw'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['regn_pw'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['regn_pw'][$key]['HHID'] = $value['HHID'];
                        $data['regn_pw'][$key]['MemID'] = $value['MemID'];
                        $data['regn_pw'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['regn_pw'][$key]['LMPDate'] = $value['LMPDate'];
                        $data['regn_pw'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                        $data['regn_pw'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                        $data['regn_pw'][$key]['GRDCode'] = $value['GRDCode'];
                        $data['regn_pw'][$key]['PWGRDDate'] = $value['PWGRDDate'];
                    }
                }// end of regn_pw table
               


                $this->query($regn_lm_sql);               
                $regn_lm = $this->resultset();

                if ($regn_lm != false) {

                    foreach ($regn_lm as $key => $value) {
                        $data['regn_lm'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['regn_lm'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['regn_lm'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['regn_lm'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['regn_lm'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['regn_lm'][$key]['HHID'] = $value['HHID'];
                        $data['regn_lm'][$key]['MemID'] = $value['MemID'];
                        $data['regn_lm'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['regn_lm'][$key]['LMDOB'] = $value['LMDOB'];
                        $data['regn_lm'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                        $data['regn_lm'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                        $data['regn_lm'][$key]['GRDCode'] = $value['GRDCode'];
                        $data['regn_lm'][$key]['LMGRDDate'] = $value['LMGRDDate'];
                        $data['regn_lm'][$key]['ChildName'] = $value['ChildName'];
                        $data['regn_lm'][$key]['ChildSex'] = $value['ChildSex'];
                    }
                }// end of regn_lm table
                //regncu2 table


                $this->query($regn_cu2_sql);

                $regn_cu2 = $this->resultset();

                if ($regn_cu2 != false) {

                    foreach ($regn_cu2 as $key => $value) {

                        $data['regn_cu2'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['regn_cu2'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['regn_cu2'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['regn_cu2'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['regn_cu2'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['regn_cu2'][$key]['HHID'] = $value['HHID'];
                        $data['regn_cu2'][$key]['MemID'] = $value['MemID'];
                        $data['regn_cu2'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['regn_cu2'][$key]['CU2DOB'] = $value['CU2DOB'];
                        $data['regn_cu2'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                        $data['regn_cu2'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                        $data['regn_cu2'][$key]['GRDCode'] = $value['GRDCode'];
                        $data['regn_cu2'][$key]['CU2GRDDate'] = $value['CU2GRDDate'];
                        $data['regn_cu2'][$key]['ChildName'] = $value['ChildName'];
                        $data['regn_cu2'][$key]['ChildSex'] = $value['ChildSex'];
                    }
                }// end of regn_cu2 table


                $this->query($regn_ca2_sql);
                //$this->bind(':user_id', $staff_code);
                $regn_ca2 = $this->resultset();

                if ($regn_ca2 != false) {
                    foreach ($regn_ca2 as $key => $value) {

                        $data['regn_ca2'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['regn_ca2'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['regn_ca2'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['regn_ca2'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['regn_ca2'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['regn_ca2'][$key]['HHID'] = $value['HHID'];
                        $data['regn_ca2'][$key]['MemID'] = $value['MemID'];
                        $data['regn_ca2'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['regn_ca2'][$key]['CA2DOB'] = $value['CA2DOB'];
                        $data['regn_ca2'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                        $data['regn_ca2'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                        $data['regn_ca2'][$key]['GRDCode'] = $value['GRDCode'];
                        $data['regn_ca2'][$key]['CA2GRDDate'] = $value['CA2GRDDate'];
                        $data['regn_ca2'][$key]['ChildName'] = $value['ChildName'];
                        $data['regn_ca2'][$key]['ChildSex'] = $value['ChildSex'];
                    }
                }// end of regn_cu2 table
               

                $this->query($reg_n_agr_sql);
                $reg_n_agr = $this->resultset();
                if ($reg_n_agr != false) {

                    foreach ($reg_n_agr as $key => $value) {
                        $data['reg_n_agr'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_n_agr'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_n_agr'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_n_agr'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_n_agr'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_n_agr'][$key]['HHID'] = $value['HHID'];
                        $data['reg_n_agr'][$key]['MemID'] = $value['MemID'];
                        $data['reg_n_agr'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['reg_n_agr'][$key]['ElderlyYN'] = $value['ElderlyYN'];
                        $data['reg_n_agr'][$key]['LandSize'] = $value['LandSize'];
                        $data['reg_n_agr'][$key]['DependOnGanyu'] = $value['DependOnGanyu'];
                        $data['reg_n_agr'][$key]['Willingness'] = $value['Willingness'];
                        $data['reg_n_agr'][$key]['WinterCultivation'] = $value['WinterCultivation'];
                        $data['reg_n_agr'][$key]['VulnerableHH'] = $value['VulnerableHH'];
                        $data['reg_n_agr'][$key]['PlantingValueChainCrop'] = $value['PlantingValueChainCrop'];
                        $data['reg_n_agr'][$key]['AGOINVC'] = $value['AGOINVC'];
                        $data['reg_n_agr'][$key]['AGONASFAM'] = $value['AGONASFAM'];
                        $data['reg_n_agr'][$key]['AGOCU'] = $value['AGOCU'];
                        $data['reg_n_agr'][$key]['AGOOther'] = $value['AGOOther'];
                        $data['reg_n_agr'][$key]['LSGoat'] = $value['LSGoat'];
                        $data['reg_n_agr'][$key]['LSChicken'] = $value['LSChicken'];
                        $data['reg_n_agr'][$key]['LSPigeon'] = $value['LSPigeon'];
                        $data['reg_n_agr'][$key]['LSOther'] = $value['LSOther'];
                    }
                }// end of RegNAgr table


                $this->query($reg_n_ct_sql);

                $reg_n_ct = $this->resultset();

                if ($reg_n_ct != false) {

                    foreach ($reg_n_ct as $key => $value) {
                        $data['reg_n_ct'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_n_ct'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_n_ct'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_n_ct'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_n_ct'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_n_ct'][$key]['HHID'] = $value['HHID'];
                        $data['reg_n_ct'][$key]['MemID'] = $value['MemID'];
                        $data['reg_n_ct'][$key]['C11_CT_PR'] = $value['C11_CT_PR'];
                        $data['reg_n_ct'][$key]['C21_CT_PR'] = $value['C21_CT_PR'];
                        $data['reg_n_ct'][$key]['C31_CT_PR'] = $value['C31_CT_PR'];
                        $data['reg_n_ct'][$key]['C32_CT_PR'] = $value['C32_CT_PR'];
                        $data['reg_n_ct'][$key]['C33_CT_PR'] = $value['C33_CT_PR'];
                        $data['reg_n_ct'][$key]['C34_CT_PR'] = $value['C34_CT_PR'];
                        $data['reg_n_ct'][$key]['C35_CT_PR'] = $value['C35_CT_PR'];
                        $data['reg_n_ct'][$key]['C36_CT_PR'] = $value['C36_CT_PR'];
                        $data['reg_n_ct'][$key]['C37_CT_PR'] = $value['C37_CT_PR'];
                        $data['reg_n_ct'][$key]['C38_CT_PR'] = $value['C38_CT_PR'];
                    }
                }// end of RegNCTtable


                $this->query($reg_n_ffa_sql);
                $reg_n_ffa = $this->resultset();
                if ($reg_n_ffa != false) {

                    foreach ($reg_n_ffa as $key => $value) {
                        $data['reg_n_ffa'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_n_ffa'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_n_ffa'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_n_ffa'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_n_ffa'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_n_ffa'][$key]['HHID'] = $value['HHID'];
                        $data['reg_n_ffa'][$key]['MemID'] = $value['MemID'];
                        $data['reg_n_ffa'][$key]['OrphanedChildren'] = $value['OrphanedChildren'];
                        $data['reg_n_ffa'][$key]['ChildHeaded'] = $value['ChildHeaded'];
                        $data['reg_n_ffa'][$key]['ElderlyHeaded'] = $value['ElderlyHeaded'];
                        $data['reg_n_ffa'][$key]['ChronicallyIll'] = $value['ChronicallyIll'];
                        $data['reg_n_ffa'][$key]['FemaleHeaded'] = $value['FemaleHeaded'];
                        $data['reg_n_ffa'][$key]['CropFailure'] = $value['CropFailure'];
                        $data['reg_n_ffa'][$key]['ChildrenRecSuppFeedN'] = $value['ChildrenRecSuppFeedN'];
                        $data['reg_n_ffa'][$key]['Willingness'] = $value['Willingness'];
                    }
                }// end of RegNffaTable
				
				$this->query($reg_n_we_sql);
                $reg_n_we = $this->resultset();
                if ($reg_n_we != false) {

                    foreach ($reg_n_we as $key => $value) {
                        $data['reg_n_we'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_n_we'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_n_we'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_n_we'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_n_we'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_n_we'][$key]['HHID'] = $value['HHID'];
                        $data['reg_n_we'][$key]['MemID'] = $value['MemID'];
                        $data['reg_n_we'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['reg_n_we'][$key]['WealthRanking'] = $value['WealthRanking'];
                        $data['reg_n_we'][$key]['MemberExtGroup'] = $value['MemberExtGroup'];
                        
                    }
                }// end of RegNWeTable
				
            }
        } else {
            return false;
        }
        return $data;
    }

    // service & service Extend data
    public function is_down_load_service_data($user, $pass, $lay_r_code_j, $operation_mode) {
        $data = array();

        $data["service_table"] = array();
        $data["service_exe_table"] = array();
        $data["service_specific_table"] = array();


        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // decode the json array
            $jData = json_decode($lay_r_code_j);


            $s = "";
            $countryCode = "";
            $isAddressCodeAdded = false;

            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';

                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 			echo($s);
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 3 : // fro service  2 Service Center code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list							echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;
            }


            if ($jData) {


                $service_table_sql = "";
                $service_exe_table_sql = "";
                $service_specific_table_sql = "";

                switch ($operation_mode) {
                    case 1: // fro registration 2 village select query


                        if ($isAddressCodeAdded) {
                            $service_table_sql = "SELECT  [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
									            ,RegNMemProgGrp.GrpCode As GrpCode

									             ,[DistFlag]

							FROM[SrvTable]
							LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
						-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
							WHERE
							[SrvTable].AdmCountryCode+[SrvTable].LayR1ListCode+[SrvTable].LayR2ListCode+[SrvTable].LayR3ListCode+[SrvTable].LayR4ListCode in (  SELECT   [AdmCountryCode]
                    + [LayR1ListCode] + [LayR2ListCode] + [LayR3ListCode]
                    + [LayR4ListCode] from [RegNHHTable] where AdmCountryCode + LayR1ListCode + LayR2ListCode + LayR3ListCode + LayR4ListCode + [RegNAddLookupCode] in(" . $s . ") ) ";
                        } else {
                            $service_table_sql = "SELECT  [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
									            ,RegNMemProgGrp.GrpCode As GrpCode

									             ,[DistFlag]

							FROM[SrvTable]
							LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
						-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
							WHERE
							[SrvTable].AdmCountryCode+[SrvTable].LayR1ListCode+[SrvTable].LayR2ListCode+[SrvTable].LayR3ListCode+[SrvTable].LayR4ListCode in (" . $s . ") ";
                        }


                        // getting data from [SrvExtendedTable]

                        $service_exe_table_sql = "SELECT [AdmCountryCode]
													,[AdmDonorCode]
													,[AdmAwardCode]
													,[LayR1ListCode]
													,[LayR2ListCode]
													,[LayR3ListCode]
													,[LayR4ListCode]
													,[HHID]
													,[MemID]
													,[ProgCode]
													,[SrvCode]
													,[OpCode]
													,[OpMonthCode]
													,[VOItmSpec]
													,[VOItmUnit]
													,[VORefNumber]
													,[VOItmCost]
											FROM [dbo].[SrvExtendedTable]
						-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";


                        $service_specific_table_sql = "SELECT [AdmCountryCode]
                                                     ,[AdmDonorCode]
                                                     ,[AdmAwardCode]
                                                     ,[LayR1ListCode]
                                                     ,[LayR2ListCode]
                                                     ,[LayR3ListCode]
                                                     ,[LayR4ListCode]
                                                     ,[HHID]
                                                     ,[MemID]
                                                     ,[ProgCode]
                                                     ,[SrvCode]
                                                     ,[OpCode]
                                                     ,[OpMonthCode]
                                                     ,[SrvCenterCode]
                                                     ,[FDPCode]
                                                     ,[SrvStatus]
                                                     ,[BabyStatus]
                                                     ,[GMPAttendace]
                                                     ,[WeightStatus]
                                                     ,[NutAttendance]
                                                     ,[VitA_Under5]
                                                     ,[Exclusive_CurrentlyBF]
                                                     ,[DateCompFeeding]
                                                     ,[CMAMRef]
                                                     ,[CMAMAdd]
                                                     ,[ANCVisit]
                                                     ,[PNCVisit_2D]
                                                     ,[PNCVisit_1W]
                                                     ,[PNCVisit_6W]
                                                     ,[DeliveryStaff_1]
                                                     ,[DeliveryStaff_2]
                                                     ,[DeliveryStaff_3]
                                                     ,[HomeSupport24H_1d]
                                                     ,[HomeSupport24H_2d]
                                                     ,[HomeSupport24H_3d]
                                                     ,[HomeSupport24H_8d]
                                                     ,[HomeSupport24H_14d]
                                                     ,[HomeSupport24H_21d]
                                                     ,[HomeSupport24H_30d]
                                                     ,[HomeSupport24H_60d]
                                                     ,[HomeSupport24H_90d]
                                                     ,[HomeSupport48H_1d]
                                                     ,[HomeSupport48H_3d]
                                                     ,[HomeSupport48H_8d]
                                                     ,[HomeSupport48H_30d]
                                                     ,[HomeSupport48H_60d]
                                                     ,[HomeSupport48H_90d]
                                                     ,[Maternal_Bleeding]
                                                     ,[Maternal_Seizure]
                                                     ,[Maternal_Infection]
                                                     ,[Maternal_ProlongedLabor]
                                                     ,[Maternal_ObstructedLabor]
                                                     ,[Maternal_PPRM]
                                                     ,[NBorn_Asphyxia]
                                                     ,[NBorn_Sepsis]
                                                     ,[NBorn_Hypothermia]
                                                     ,[NBorn_Hyperthermia]
                                                     ,[NBorn_NoSuckling]
                                                     ,[NBorn_Jaundice]
                                                     ,[Child_Diarrhea]
                                                     ,[Child_Pneumonia]
                                                     ,[Child_Fever]
                                                     ,[Child_CerebralPalsy]
                                                     ,[Immu_Polio]
                                                     ,[Immu_BCG]
                                                     ,[Immu_Measles]
                                                     ,[Immu_DPT_HIB]
                                                     ,[Immu_Lotta]
                                                     ,[Immu_Other]
                                                     ,[FPCounsel_MaleCondom]
                                                     ,[FPCounsel_FemaleCondom]
                                                     ,[FPCounsel_Pill]
                                                     ,[FPCounsel_Depo]
                                                     ,[FPCounsel_LongParmanent]
                                                     ,[FPCounsel_NoMethod]
                                                     ,[CropCode]
                                                     ,[LoanSource]
                                                     ,[LoanAMT]
                                                     ,[AnimalCode]
                                                     ,[LeadCode]
                                                    -- ,[EntryBy]
                                                    -- ,[EntryDate]
                                                  FROM [dbo].[SrvSpecific]
                -- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";
                        break;
                    case 2: // for distribution fdp
                        $service_table_sql = "SELECT  DISTINCT [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
												,RegNMemProgGrp.GrpCode As GrpCode
												,[DistFlag]

										FROM [dbo].[SrvTable]


										LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
										where
										[SrvTable].[OpMonthCode] in(Select SrvOpMonthCode from DistNPlanbasic

											where DisOpMonthCode in (Select top 1  OpMonthCode from AdmOpMonthTable
													where Status='A' and OpCode='3' and admcountryCode =" . $countryCode . " )
																	and admcountryCode+FDPCode in(" . $s . ")
																		)
												and [SrvTable].[AdmCountryCode]+FDPCode in(" . $s . ")";


                        $service_exe_table_sql = "SELECT [AdmCountryCode]
														,[AdmDonorCode]
														,[AdmAwardCode]
														,[LayR1ListCode]
														,[LayR2ListCode]
														,[LayR3ListCode]
														,[LayR4ListCode]
														,[HHID]
														,[MemID]
														,[ProgCode]
														,[SrvCode]
														,[OpCode]
														,[OpMonthCode]
														,[VOItmSpec]
														,[VOItmUnit]
														,[VORefNumber]
														,[VOItmCost]
													FROM [dbo].[SrvExtendedTable]

											where [AdmCountryCode]+[LayR1ListCode]
													+[LayR2ListCode]
													+[LayR3ListCode]
													+[LayR4ListCode]
													+[HHID]
													+[MemID] in(
													SELECT  DISTINCT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode] +[LayR4ListCode]+[HHID]+[MemID]
														      FROM [dbo].[SrvTable]
														        where
														        [SrvTable].[OpMonthCode] in(Select SrvOpMonthCode from DistNPlanbasic
												                                                  where DisOpMonthCode in (Select OpMonthCode from AdmOpMonthTable
												                                                                            where Status='A' and OpCode='3' and admcountryCode =" . $countryCode . ")

													                    and admcountryCode+FDPCode in(" . $s . ")
																		)
								                                and [AdmCountryCode]+FDPCode in(" . $s . "))";


                        $service_specific_table_sql = "SELECT [AdmCountryCode]
                                                     ,[AdmDonorCode]
                                                     ,[AdmAwardCode]
                                                     ,[LayR1ListCode]
                                                     ,[LayR2ListCode]
                                                     ,[LayR3ListCode]
                                                     ,[LayR4ListCode]
                                                     ,[HHID]
                                                     ,[MemID]
                                                     ,[ProgCode]
                                                     ,[SrvCode]
                                                     ,[OpCode]
                                                     ,[OpMonthCode]
                                                     ,[SrvCenterCode]
                                                     ,[FDPCode]
                                                     ,[SrvStatus]
                                                     ,[BabyStatus]
                                                     ,[GMPAttendace]
                                                     ,[WeightStatus]
                                                     ,[NutAttendance]
                                                     ,[VitA_Under5]
                                                     ,[Exclusive_CurrentlyBF]
                                                     ,[DateCompFeeding]
                                                     ,[CMAMRef]
                                                     ,[CMAMAdd]
                                                     ,[ANCVisit]
                                                     ,[PNCVisit_2D]
                                                     ,[PNCVisit_1W]
                                                     ,[PNCVisit_6W]
                                                     ,[DeliveryStaff_1]
                                                     ,[DeliveryStaff_2]
                                                     ,[DeliveryStaff_3]
                                                     ,[HomeSupport24H_1d]
                                                     ,[HomeSupport24H_2d]
                                                     ,[HomeSupport24H_3d]
                                                     ,[HomeSupport24H_8d]
                                                     ,[HomeSupport24H_14d]
                                                     ,[HomeSupport24H_21d]
                                                     ,[HomeSupport24H_30d]
                                                     ,[HomeSupport24H_60d]
                                                     ,[HomeSupport24H_90d]
                                                     ,[HomeSupport48H_1d]
                                                     ,[HomeSupport48H_3d]
                                                     ,[HomeSupport48H_8d]
                                                     ,[HomeSupport48H_30d]
                                                     ,[HomeSupport48H_60d]
                                                     ,[HomeSupport48H_90d]
                                                     ,[Maternal_Bleeding]
                                                     ,[Maternal_Seizure]
                                                     ,[Maternal_Infection]
                                                     ,[Maternal_ProlongedLabor]
                                                     ,[Maternal_ObstructedLabor]
                                                     ,[Maternal_PPRM]
                                                     ,[NBorn_Asphyxia]
                                                     ,[NBorn_Sepsis]
                                                     ,[NBorn_Hypothermia]
                                                     ,[NBorn_Hyperthermia]
                                                     ,[NBorn_NoSuckling]
                                                     ,[NBorn_Jaundice]
                                                     ,[Child_Diarrhea]
                                                     ,[Child_Pneumonia]
                                                     ,[Child_Fever]
                                                     ,[Child_CerebralPalsy]
                                                     ,[Immu_Polio]
                                                     ,[Immu_BCG]
                                                     ,[Immu_Measles]
                                                     ,[Immu_DPT_HIB]
                                                     ,[Immu_Lotta]
                                                     ,[Immu_Other]
                                                     ,[FPCounsel_MaleCondom]
                                                     ,[FPCounsel_FemaleCondom]
                                                     ,[FPCounsel_Pill]
                                                     ,[FPCounsel_Depo]
                                                     ,[FPCounsel_LongParmanent]
                                                     ,[FPCounsel_NoMethod]
                                                     ,[CropCode]
                                                     ,[LoanSource]
                                                     ,[LoanAMT]
                                                     ,[AnimalCode]
                                                     ,[LeadCode]
                                                    -- ,[EntryBy]
                                                    -- ,[EntryDate]
                                                  FROM [dbo].[SrvSpecific]

											where [AdmCountryCode]+[LayR1ListCode]
													+[LayR2ListCode]
													+[LayR3ListCode]
													+[LayR4ListCode]
													+[HHID]
													+[MemID] in(
													SELECT  DISTINCT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode] +[LayR4ListCode]+[HHID]+[MemID]
														      FROM [dbo].[SrvTable]
														        where
														        [SrvTable].[OpMonthCode] in(Select SrvOpMonthCode from DistNPlanbasic
												                                                  where DisOpMonthCode in (Select OpMonthCode from AdmOpMonthTable
												                                                                            where Status='A' and OpCode='3' and admcountryCode =" . $countryCode . ")

													                    and admcountryCode+FDPCode in(" . $s . ")
																		)
								                                and [AdmCountryCode]+FDPCode in(" . $s . "))";

                        break;


                    case 3: // for Service ServiceCenter
                        $service_table_sql = "SELECT [SrvTable].[AdmCountryCode]
											,[SrvTable].[AdmDonorCode]
											,[SrvTable].[AdmAwardCode]
											,[SrvTable].[LayR1ListCode]
											,[SrvTable].[LayR2ListCode]
											,[SrvTable].[LayR3ListCode]
											,[SrvTable].[LayR4ListCode]
											,[SrvTable].[HHID]
											,[SrvTable].[MemID]
											,[SrvTable].[ProgCode]
											,[SrvTable].[SrvCode]
											,[OpCode]
											,[OpMonthCode]
											,[SrvSL]
											,[SrvCenterCode]
											,convert(varchar,[SrvDT],110) AS [SrvDT]
											,[SrvStatus]
											,[DistStatus]
											,[DistDT]
											,FDPCode
											,isnull([WD],0) As WD
											,RegNMemProgGrp.GrpCode As GrpCode
											,[DistFlag]

										FROM [dbo].[SrvTable]
										LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
										where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
												where [AdmCountryCode]=" . $countryCode . "  and [OpCode]=2  and  [Status]='A'
													ORDER BY OpMonthCode DESC)

										and [SrvStatus]='O'
										and [SrvTable].[AdmCountryCode]+[SrvCenterCode] IN (" . $s . ")";


                        $service_exe_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[HHID]
											,[MemID]
											,[ProgCode]
											,[SrvCode]
											,[OpCode]
											,[OpMonthCode]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[VOItmCost]
											FROM [dbo].[SrvExtendedTable]
										where[AdmCountryCode]+[AdmDonorCode]	+[AdmAwardCode]	+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
											+[HHID]	+[MemID]+[ProgCode]	+[SrvCode] in(	SELECT [AdmCountryCode]
														+[AdmDonorCode]
														+[AdmAwardCode]
														+[LayR1ListCode]
														+[LayR2ListCode]
														+[LayR3ListCode]
														+[LayR4ListCode]
														+[HHID]
														+[MemID]
														+[ProgCode]
														+[SrvCode]
														FROM [dbo].[SrvTable]
														where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
																			where [AdmCountryCode]=" . $countryCode . "  and [OpCode]=2  and  [Status]='A'
																			ORDER BY OpMonthCode DESC)

															and [SrvStatus]='O'
															and [AdmCountryCode]+[SrvCenterCode] IN (" . $s . ")	)	";

                        $service_specific_table_sql = "SELECT [AdmCountryCode]
                                                     ,[AdmDonorCode]
                                                     ,[AdmAwardCode]
                                                     ,[LayR1ListCode]
                                                     ,[LayR2ListCode]
                                                     ,[LayR3ListCode]
                                                     ,[LayR4ListCode]
                                                     ,[HHID]
                                                     ,[MemID]
                                                     ,[ProgCode]
                                                     ,[SrvCode]
                                                     ,[OpCode]
                                                     ,[OpMonthCode]
                                                     ,[SrvCenterCode]
                                                     ,[FDPCode]
                                                     ,[SrvStatus]
                                                     ,[BabyStatus]
                                                     ,[GMPAttendace]
                                                     ,[WeightStatus]
                                                     ,[NutAttendance]
                                                     ,[VitA_Under5]
                                                     ,[Exclusive_CurrentlyBF]
                                                     ,[DateCompFeeding]
                                                     ,[CMAMRef]
                                                     ,[CMAMAdd]
                                                     ,[ANCVisit]
                                                     ,[PNCVisit_2D]
                                                     ,[PNCVisit_1W]
                                                     ,[PNCVisit_6W]
                                                     ,[DeliveryStaff_1]
                                                     ,[DeliveryStaff_2]
                                                     ,[DeliveryStaff_3]
                                                     ,[HomeSupport24H_1d]
                                                     ,[HomeSupport24H_2d]
                                                     ,[HomeSupport24H_3d]
                                                     ,[HomeSupport24H_8d]
                                                     ,[HomeSupport24H_14d]
                                                     ,[HomeSupport24H_21d]
                                                     ,[HomeSupport24H_30d]
                                                     ,[HomeSupport24H_60d]
                                                     ,[HomeSupport24H_90d]
                                                     ,[HomeSupport48H_1d]
                                                     ,[HomeSupport48H_3d]
                                                     ,[HomeSupport48H_8d]
                                                     ,[HomeSupport48H_30d]
                                                     ,[HomeSupport48H_60d]
                                                     ,[HomeSupport48H_90d]
                                                     ,[Maternal_Bleeding]
                                                     ,[Maternal_Seizure]
                                                     ,[Maternal_Infection]
                                                     ,[Maternal_ProlongedLabor]
                                                     ,[Maternal_ObstructedLabor]
                                                     ,[Maternal_PPRM]
                                                     ,[NBorn_Asphyxia]
                                                     ,[NBorn_Sepsis]
                                                     ,[NBorn_Hypothermia]
                                                     ,[NBorn_Hyperthermia]
                                                     ,[NBorn_NoSuckling]
                                                     ,[NBorn_Jaundice]
                                                     ,[Child_Diarrhea]
                                                     ,[Child_Pneumonia]
                                                     ,[Child_Fever]
                                                     ,[Child_CerebralPalsy]
                                                     ,[Immu_Polio]
                                                     ,[Immu_BCG]
                                                     ,[Immu_Measles]
                                                     ,[Immu_DPT_HIB]
                                                     ,[Immu_Lotta]
                                                     ,[Immu_Other]
                                                     ,[FPCounsel_MaleCondom]
                                                     ,[FPCounsel_FemaleCondom]
                                                     ,[FPCounsel_Pill]
                                                     ,[FPCounsel_Depo]
                                                     ,[FPCounsel_LongParmanent]
                                                     ,[FPCounsel_NoMethod]
                                                     ,[CropCode]
                                                     ,[LoanSource]
                                                     ,[LoanAMT]
                                                     ,[AnimalCode]
                                                     ,[LeadCode]
                                                    -- ,[EntryBy]
                                                    -- ,[EntryDate]
                                                  FROM [dbo].[SrvSpecific]
										where[AdmCountryCode]+[AdmDonorCode]	+[AdmAwardCode]	+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
											+[HHID]	+[MemID]+[ProgCode]	+[SrvCode] in(	SELECT [AdmCountryCode]
														+[AdmDonorCode]
														+[AdmAwardCode]
														+[LayR1ListCode]
														+[LayR2ListCode]
														+[LayR3ListCode]
														+[LayR4ListCode]
														+[HHID]
														+[MemID]
														+[ProgCode]
														+[SrvCode]
														FROM [dbo].[SrvTable]
														where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
																			where [AdmCountryCode]=" . $countryCode . "  and [OpCode]=2  and  [Status]='A'
																			ORDER BY OpMonthCode DESC)

															and [SrvStatus]='O'
															and [AdmCountryCode]+[SrvCenterCode] IN (" . $s . ")	)	";

                        break;


                    case 4: // for dynamic
                    case 5: // for training N activity 
                        $service_table_sql = "SELECT  DISTINCT [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
												,RegNMemProgGrp.GrpCode As GrpCode
												,[DistFlag]

										FROM [dbo].[SrvTable]


										where
										[SrvTable].[OpMonthCode] in(0)";


                        $service_exe_table_sql = "SELECT [AdmCountryCode]
														,[AdmDonorCode]
														,[AdmAwardCode]
														,[LayR1ListCode]
														,[LayR2ListCode]
														,[LayR3ListCode]
														,[LayR4ListCode]
														,[HHID]
														,[MemID]
														,[ProgCode]
														,[SrvCode]
														,[OpCode]
														,[OpMonthCode]
														,[VOItmSpec]
														,[VOItmUnit]
														,[VORefNumber]
														,[VOItmCost]
													FROM [dbo].[SrvExtendedTable]

											where [AdmCountryCode]+[LayR1ListCode]
													+[LayR2ListCode]
													+[LayR3ListCode]
													+[LayR4ListCode]
													+[HHID]
													+[MemID] in(0)";


                        $service_specific_table_sql = "SELECT [AdmCountryCode]
                                                     ,[AdmDonorCode]
                                                     ,[AdmAwardCode]
                                                     ,[LayR1ListCode]
                                                     ,[LayR2ListCode]
                                                     ,[LayR3ListCode]
                                                     ,[LayR4ListCode]
                                                     ,[HHID]
                                                     ,[MemID]
                                                     ,[ProgCode]
                                                     ,[SrvCode]
                                                     ,[OpCode]
                                                     ,[OpMonthCode]
                                                     ,[SrvCenterCode]
                                                     ,[FDPCode]
                                                     ,[SrvStatus]
                                                     ,[BabyStatus]
                                                     ,[GMPAttendace]
                                                     ,[WeightStatus]
                                                     ,[NutAttendance]
                                                     ,[VitA_Under5]
                                                     ,[Exclusive_CurrentlyBF]
                                                     ,[DateCompFeeding]
                                                     ,[CMAMRef]
                                                     ,[CMAMAdd]
                                                     ,[ANCVisit]
                                                     ,[PNCVisit_2D]
                                                     ,[PNCVisit_1W]
                                                     ,[PNCVisit_6W]
                                                     ,[DeliveryStaff_1]
                                                     ,[DeliveryStaff_2]
                                                     ,[DeliveryStaff_3]
                                                     ,[HomeSupport24H_1d]
                                                     ,[HomeSupport24H_2d]
                                                     ,[HomeSupport24H_3d]
                                                     ,[HomeSupport24H_8d]
                                                     ,[HomeSupport24H_14d]
                                                     ,[HomeSupport24H_21d]
                                                     ,[HomeSupport24H_30d]
                                                     ,[HomeSupport24H_60d]
                                                     ,[HomeSupport24H_90d]
                                                     ,[HomeSupport48H_1d]
                                                     ,[HomeSupport48H_3d]
                                                     ,[HomeSupport48H_8d]
                                                     ,[HomeSupport48H_30d]
                                                     ,[HomeSupport48H_60d]
                                                     ,[HomeSupport48H_90d]
                                                     ,[Maternal_Bleeding]
                                                     ,[Maternal_Seizure]
                                                     ,[Maternal_Infection]
                                                     ,[Maternal_ProlongedLabor]
                                                     ,[Maternal_ObstructedLabor]
                                                     ,[Maternal_PPRM]
                                                     ,[NBorn_Asphyxia]
                                                     ,[NBorn_Sepsis]
                                                     ,[NBorn_Hypothermia]
                                                     ,[NBorn_Hyperthermia]
                                                     ,[NBorn_NoSuckling]
                                                     ,[NBorn_Jaundice]
                                                     ,[Child_Diarrhea]
                                                     ,[Child_Pneumonia]
                                                     ,[Child_Fever]
                                                     ,[Child_CerebralPalsy]
                                                     ,[Immu_Polio]
                                                     ,[Immu_BCG]
                                                     ,[Immu_Measles]
                                                     ,[Immu_DPT_HIB]
                                                     ,[Immu_Lotta]
                                                     ,[Immu_Other]
                                                     ,[FPCounsel_MaleCondom]
                                                     ,[FPCounsel_FemaleCondom]
                                                     ,[FPCounsel_Pill]
                                                     ,[FPCounsel_Depo]
                                                     ,[FPCounsel_LongParmanent]
                                                     ,[FPCounsel_NoMethod]
                                                     ,[CropCode]
                                                     ,[LoanSource]
                                                     ,[LoanAMT]
                                                     ,[AnimalCode]
                                                     ,[LeadCode]

                                                  FROM [dbo].[SrvSpecific]

											where [AdmCountryCode]+[LayR1ListCode]
													+[LayR2ListCode]
													+[LayR3ListCode]
													+[LayR4ListCode]
													+[HHID]
													+[MemID] in(0)";

                        break;
                }
                //service_table
                // getting data from SrvTable table


                $this->query($service_table_sql);

                $service_table = $this->resultset();

                if ($service_table != false) {

                    foreach ($service_table as $key => $value) {
                        $data['service_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['service_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['service_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['service_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['service_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['service_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['service_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['service_table'][$key]['HHID'] = $value['HHID'];
                        $data['service_table'][$key]['MemID'] = $value['MemID'];
                        $data['service_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['service_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['service_table'][$key]['OpCode'] = $value['OpCode'];
                        $data['service_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['service_table'][$key]['SrvSL'] = $value['SrvSL'];
                        $data['service_table'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                        $data['service_table'][$key]['SrvDT'] = $value['SrvDT'];
                        $data['service_table'][$key]['SrvStatus'] = $value['SrvStatus'];
                        $data['service_table'][$key]['DistStatus'] = $value['DistStatus'];
                        $data['service_table'][$key]['DistDT'] = $value['DistDT'];
                        $data['service_table'][$key]['FDPCode'] = $value['FDPCode'];
                        $data['service_table'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['service_table'][$key]['WD'] = $value['WD'];
                        $data['service_table'][$key]['DistFlag'] = $value['DistFlag'];
                    }
                }// end of service_table table
                // service Extended table


                $this->query($service_exe_table_sql);
                //$this->bind(':user_id', $staff_code);
                $service_exe_table = $this->resultset();

                if ($service_exe_table != false) {

                    foreach ($service_exe_table as $key => $value) {
                        $data['service_exe_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['service_exe_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['service_exe_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['service_exe_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['service_exe_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['service_exe_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['service_exe_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['service_exe_table'][$key]['HHID'] = $value['HHID'];
                        $data['service_exe_table'][$key]['MemID'] = $value['MemID'];
                        $data['service_exe_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['service_exe_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['service_exe_table'][$key]['OpCode'] = $value['OpCode'];
                        $data['service_exe_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['service_exe_table'][$key]['VOItmSpec'] = $value['VOItmSpec'];
                        $data['service_exe_table'][$key]['VOItmUnit'] = $value['VOItmUnit'];
                        $data['service_exe_table'][$key]['VORefNumber'] = $value['VORefNumber'];
                        $data['service_exe_table'][$key]['VOItmCost'] = $value['VOItmCost'];
                    }
                }// end of service_table table
                //srevice specific table

                $this->query($service_specific_table_sql);
                //$this->bind(':user_id', $staff_code);
                $service_specific_tables = $this->resultset();

                if ($service_specific_tables != false) {

                    foreach ($service_specific_tables as $key => $value) {
                        $data['service_specific_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['service_specific_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['service_specific_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['service_specific_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['service_specific_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['service_specific_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['service_specific_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['service_specific_table'][$key]['HHID'] = $value['HHID'];
                        $data['service_specific_table'][$key]['MemID'] = $value['MemID'];
                        $data['service_specific_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['service_specific_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['service_specific_table'][$key]['OpCode'] = $value['OpCode'];
                        $data['service_specific_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['service_specific_table'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                        $data['service_specific_table'][$key]['FDPCode'] = $value['FDPCode'];
                        $data['service_specific_table'][$key]['SrvStatus'] = $value['SrvStatus'];
                        $data['service_specific_table'][$key]['GMPAttendace'] = $value['BabyStatus'];

                        $data['service_specific_table'][$key]['WeightStatus'] = $value['WeightStatus'];
                        $data['service_specific_table'][$key]['NutAttendance'] = $value['NutAttendance'];
                        $data['service_specific_table'][$key]['VitA_Under5'] = $value['VitA_Under5'];
                        $data['service_specific_table'][$key]['Exclusive_CurrentlyBF'] = $value['Exclusive_CurrentlyBF'];
                        $data['service_specific_table'][$key]['DateCompFeeding'] = $value['DateCompFeeding'];
                        $data['service_specific_table'][$key]['CMAMRef'] = $value['CMAMRef'];
                        $data['service_specific_table'][$key]['CMAMAdd'] = $value['CMAMAdd'];
                        $data['service_specific_table'][$key]['ANCVisit'] = $value['ANCVisit'];
                        $data['service_specific_table'][$key]['PNCVisit_2D'] = $value['PNCVisit_2D'];
                        $data['service_specific_table'][$key]['PNCVisit_1W'] = $value['PNCVisit_1W'];
                        $data['service_specific_table'][$key]['PNCVisit_6W'] = $value['PNCVisit_6W'];
                        $data['service_specific_table'][$key]['DeliveryStaff_1'] = $value['DeliveryStaff_1'];
                        $data['service_specific_table'][$key]['DeliveryStaff_2'] = $value['DeliveryStaff_2'];
                        $data['service_specific_table'][$key]['DeliveryStaff_3'] = $value['DeliveryStaff_3'];
                        $data['service_specific_table'][$key]['HomeSupport24H_1d'] = $value['HomeSupport24H_1d'];
                        $data['service_specific_table'][$key]['HomeSupport24H_2d'] = $value['HomeSupport24H_2d'];
                        $data['service_specific_table'][$key]['HomeSupport24H_3d'] = $value['HomeSupport24H_3d'];
                        $data['service_specific_table'][$key]['HomeSupport24H_8d'] = $value['HomeSupport24H_8d'];
                        $data['service_specific_table'][$key]['HomeSupport24H_14d'] = $value['HomeSupport24H_14d'];

                        $data['service_specific_table'][$key]['HomeSupport24H_21d'] = $value['HomeSupport24H_21d'];
                        $data['service_specific_table'][$key]['HomeSupport24H_30d'] = $value['HomeSupport24H_30d'];
                        $data['service_specific_table'][$key]['HomeSupport24H_60d'] = $value['HomeSupport24H_60d'];

                        $data['service_specific_table'][$key]['HomeSupport24H_90d'] = $value['HomeSupport24H_90d'];
                        $data['service_specific_table'][$key]['HomeSupport48H_1d'] = $value['HomeSupport48H_1d'];

                        $data['service_specific_table'][$key]['HomeSupport48H_3d'] = $value['HomeSupport48H_3d'];
                        $data['service_specific_table'][$key]['HomeSupport48H_8d'] = $value['HomeSupport48H_8d'];

                        $data['service_specific_table'][$key]['HomeSupport48H_30d'] = $value['HomeSupport48H_30d'];
                        $data['service_specific_table'][$key]['HomeSupport48H_60d'] = $value['HomeSupport48H_60d'];

                        $data['service_specific_table'][$key]['HomeSupport48H_90d'] = $value['HomeSupport48H_90d'];
                        $data['service_specific_table'][$key]['Maternal_Bleeding'] = $value['Maternal_Bleeding'];
                        $data['service_specific_table'][$key]['Maternal_Seizure'] = $value['Maternal_Seizure'];
                        $data['service_specific_table'][$key]['Maternal_Infection'] = $value['Maternal_Infection'];
                        $data['service_specific_table'][$key]['Maternal_ProlongedLabor'] = $value['Maternal_ProlongedLabor'];

                        $data['service_specific_table'][$key]['Maternal_ObstructedLabor'] = $value['Maternal_ObstructedLabor'];
                        $data['service_specific_table'][$key]['Maternal_PPRM'] = $value['Maternal_PPRM'];
                        $data['service_specific_table'][$key]['NBorn_Asphyxia'] = $value['NBorn_Asphyxia'];
                        $data['service_specific_table'][$key]['NBorn_Sepsis'] = $value['NBorn_Sepsis'];
                        $data['service_specific_table'][$key]['NBorn_Hypothermia'] = $value['NBorn_Hypothermia'];
                        $data['service_specific_table'][$key]['NBorn_Hyperthermia'] = $value['NBorn_Hyperthermia'];
                        $data['service_specific_table'][$key]['NBorn_NoSuckling'] = $value['NBorn_NoSuckling'];
                        $data['service_specific_table'][$key]['NBorn_Jaundice'] = $value['NBorn_Jaundice'];
                        $data['service_specific_table'][$key]['Child_Diarrhea'] = $value['Child_Diarrhea'];

                        $data['service_specific_table'][$key]['Child_Pneumonia'] = $value['Child_Pneumonia'];
                        $data['service_specific_table'][$key]['Child_Fever'] = $value['Child_Fever'];
                        $data['service_specific_table'][$key]['Child_CerebralPalsy'] = $value['Child_CerebralPalsy'];
                        $data['service_specific_table'][$key]['Immu_Polio'] = $value['Immu_Polio'];
                        $data['service_specific_table'][$key]['Immu_BCG'] = $value['Immu_BCG'];

                        $data['service_specific_table'][$key]['Immu_Measles'] = $value['Immu_Measles'];
                        $data['service_specific_table'][$key]['Immu_DPT_HIB'] = $value['Immu_DPT_HIB'];
                        $data['service_specific_table'][$key]['Immu_Lotta'] = $value['Immu_Lotta'];
                        $data['service_specific_table'][$key]['Immu_Other'] = $value['Immu_Other'];
                        $data['service_specific_table'][$key]['FPCounsel_MaleCondom'] = $value['FPCounsel_MaleCondom'];
                        $data['service_specific_table'][$key]['FPCounsel_FemaleCondom'] = $value['FPCounsel_FemaleCondom'];
                        $data['service_specific_table'][$key]['FPCounsel_Pill'] = $value['FPCounsel_Pill'];
                        $data['service_specific_table'][$key]['FPCounsel_Depo'] = $value['FPCounsel_Depo'];
                        $data['service_specific_table'][$key]['FPCounsel_LongParmanent'] = $value['FPCounsel_LongParmanent'];
                        $data['service_specific_table'][$key]['FPCounsel_NoMethod'] = $value['FPCounsel_NoMethod'];
                        $data['service_specific_table'][$key]['CropCode'] = $value['CropCode'];
                        $data['service_specific_table'][$key]['LoanSource'] = $value['LoanSource'];
                        $data['service_specific_table'][$key]['LoanAMT'] = $value['LoanAMT'];
                        $data['service_specific_table'][$key]['AnimalCode'] = $value['AnimalCode'];
                        $data['service_specific_table'][$key]['LeadCode'] = $value['LeadCode'];
                    }
                }// end of service_table table
            }// end of if
        } else {
            return false;
        }
        return $data;
    }

    // memgrp code data


    public function is_down_load_reg_mem_grp_data($user, $pass, $lay_r_code_j, $operation_mode) {
        $data = array();

        $data["reg_n_mem_prog_grp"] = array();
        $data["gps_location_content"] = array();


        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();


        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // decode the json array
            $jData = json_decode($lay_r_code_j);


            $s = "";
            $countryCode = "";
            $isAddressCodeAdded = false;

            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';

                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list					echo($s);
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 3 : // fro service  2 Service Center code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list							echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;
            }


            if ($jData) {


                $reg_n_mem_prog_grp_sql = "";


                switch ($operation_mode) {
                    case 1: // fro registration 2 village select query
                        if ($isAddressCodeAdded) {
                            $reg_n_mem_prog_grp_sql = "SELECT [AdmCountryCode]
											  ,[LayR1ListCode]
											  ,[LayR2ListCode]
											  ,[LayR3ListCode]
											  ,[LayR4ListCode]
											  ,[AdmDonorCode]
											  ,[AdmAwardCode]
											  ,[HHID]
											  ,[MemID]
											  ,[ProgCode]
											  ,[SrvCode]
											  ,[GrpCode]
											  ,[Active]
                                                                                          ,[GrpLayR1ListCode]
                                                                                          ,[GrpLayR2ListCode]
,[GrpLayR3ListCode]
										  FROM [dbo].[RegNMemProgGrp]
										   WHERE AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode  in (" . $s . ") ";
                        } else {
                            $reg_n_mem_prog_grp_sql = "SELECT [AdmCountryCode]
											  ,[LayR1ListCode]
											  ,[LayR2ListCode]
											  ,[LayR3ListCode]
											  ,[LayR4ListCode]
											  ,[AdmDonorCode]
											  ,[AdmAwardCode]
											  ,[HHID]
											  ,[MemID]
											  ,[ProgCode]
											  ,[SrvCode]
											  ,[GrpCode]
											  ,[Active]
,[GrpLayR1ListCode]
                                                                                          ,[GrpLayR2ListCode]
,[GrpLayR3ListCode]
										  FROM [dbo].[RegNMemProgGrp]
										   WHERE AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ") ";
                        }


                        break;
                    // no data for distribution mode
                    case 2: // for distribution Mode
						 $reg_n_mem_prog_grp_sql = "SELECT [AdmCountryCode]
											  ,[LayR1ListCode]
											  ,[LayR2ListCode]
											  ,[LayR3ListCode]
											  ,[LayR4ListCode]
											  ,[AdmDonorCode]
											  ,[AdmAwardCode]
											  ,[HHID]
											  ,[MemID]
											  ,[ProgCode]
											  ,[SrvCode]
											  ,[GrpCode]
											  ,[Active]
                                                                                          ,[GrpLayR1ListCode]
                                                                                          ,[GrpLayR2ListCode]
                                                                                         ,[GrpLayR3ListCode]

										  FROM [dbo].[RegNMemProgGrp]
										   WHERE AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        break;
					 
					   case 3: // for Service ServiceCenter
                        $reg_n_mem_prog_grp_sql = "SELECT [AdmCountryCode]
											  ,[LayR1ListCode]
											  ,[LayR2ListCode]
											  ,[LayR3ListCode]
											  ,[LayR4ListCode]
											  ,[AdmDonorCode]
											  ,[AdmAwardCode]
											  ,[HHID]
											  ,[MemID]
											  ,[ProgCode]
											  ,[SrvCode]
											  ,[GrpCode]
											  ,[Active]
											  ,[GrpLayR1ListCode]
                                                                                          ,[GrpLayR2ListCode]
                                                                                         ,[GrpLayR3ListCode]

										FROM [dbo].[RegNMemProgGrp]
										   WHERE
										   [Active]='Y'
										   and [GrpCode] in(Select [GrpCode] from [dbo].[CommunityGroup] where [AdmCountryCode]+[SrvCenterCode]in(" . $s . "))";


                        break;
						
					   case 4: // for Dynamic Mode 
					   case 5: // for Training N Activity Mode 
                        $reg_n_mem_prog_grp_sql = "SELECT [AdmCountryCode]
											  ,[LayR1ListCode]
											  ,[LayR2ListCode]
											  ,[LayR3ListCode]
											  ,[LayR4ListCode]
											  ,[AdmDonorCode]
											  ,[AdmAwardCode]
											  ,[HHID]
											  ,[MemID]
											  ,[ProgCode]
											  ,[SrvCode]
											  ,[GrpCode]
											  ,[Active]
                                                                                          ,[GrpLayR1ListCode]
                                                                                          ,[GrpLayR2ListCode]
                                                                                         ,[GrpLayR3ListCode]

										  FROM [dbo].[RegNMemProgGrp]
										   WHERE AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        break;
                   
                }
              


                $this->query($reg_n_mem_prog_grp_sql);
                $reg_n_mem_prog_grp = $this->resultset();

                if ($reg_n_mem_prog_grp != false) {


                    foreach ($reg_n_mem_prog_grp as $key => $value) {

                        $data['reg_n_mem_prog_grp'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_n_mem_prog_grp'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['reg_n_mem_prog_grp'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['reg_n_mem_prog_grp'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_n_mem_prog_grp'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_n_mem_prog_grp'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_n_mem_prog_grp'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_n_mem_prog_grp'][$key]['HHID'] = $value['HHID'];
                        $data['reg_n_mem_prog_grp'][$key]['MemID'] = $value['MemID'];
                        $data['reg_n_mem_prog_grp'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['reg_n_mem_prog_grp'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['reg_n_mem_prog_grp'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['reg_n_mem_prog_grp'][$key]['Active'] = $value['Active'];
                        $data['reg_n_mem_prog_grp'][$key]['GrpLayR1ListCode'] = $value['GrpLayR1ListCode'];
                        $data['reg_n_mem_prog_grp'][$key]['GrpLayR2ListCode'] = $value['GrpLayR2ListCode'];
                        $data['reg_n_mem_prog_grp'][$key]['GrpLayR3ListCode'] = $value['GrpLayR3ListCode'];
                    }
                }// end of RegNMemProgGrp

                $gps_location_content_sql = "SELECT [AdmCountryCode]
                                                ,[GrpCode]
                                                ,[SubGrpCode]
                                                ,[LocationCode]
                                                ,[ContentCode]
                                               -- ,convert(varchar(max), [ImageFile]) as ImageFile
                                                , [GrpCode] as ImageFileString
                                                ,[Remarks]
                                            FROM [dbo].[GPSLocationContent]";


                $this->query($gps_location_content_sql);
                $gps_location_content = $this->resultset();

                if ($gps_location_content != false) {


                    foreach ($gps_location_content as $key => $value) {

                        $data['gps_location_content'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['gps_location_content'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['gps_location_content'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                        $data['gps_location_content'][$key]['LocationCode'] = $value['LocationCode'];
                        $data['gps_location_content'][$key]['ContentCode'] = $value['ContentCode'];
                        $data['gps_location_content'][$key]['ImageFileString'] = $value['ImageFileString'];
                        $data['gps_location_content'][$key]['Remarks'] = $value['Remarks'];
                    }// end of for each
                }// end of RegNMemProgGrp
            }// end of if
        } else {
            return false;
        }
        return $data;
    }

    // reg member data
    public function is_down_load_reg_member($user, $pass, $lay_r_code_j, $operation_mode) {
        $data = array();
        $data["members"] = array();

        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // decode the json array
            $jData = json_decode($lay_r_code_j);


            $s = "";
            $countryCode = "";
            $isAddressCodeAdded = false;

            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 			echo($s);
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 3 : // fro Service Center  2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;
					case 5 : // fro Service Center  2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                   
                  
                    break;
            }
            if ($jData) {
                $msql = "";
                switch ($operation_mode) {
                    case 1:    // for  registration
                        if ($isAddressCodeAdded) {
                            $msql = "SELECT [RegNHHMem].[AdmCountryCode]
						,[RegNHHMem].[LayR1ListCode]
						,[RegNHHMem].[LayR2ListCode]
						,[RegNHHMem].[LayR3ListCode]
						,[RegNHHMem].[LayR4ListCode]
						,[RegNHHMem].[HHID]
						,[HHMemID]
						,[MemName]
						,[MemSex]
						,[HHRelation]
						,[RegNHHMem].[EntryBy]
						,[RegNHHMem].[EntryDate]
						,[LMPDate]
						,[ChildDOB]
						,[Elderly]
						,[Disabled]
						,[MemAge]
						,[RegNDate]
						,[BirthYear]
						,[MaritalStatus]
						,[ContactNo]
						,[MemOtherID]
						,ISNULL([MemName_First],'') AS [MemName_First]
						,ISNULL([MemName_Middle],'') AS [MemName_Middle]
						, ISNULL([MemName_Last],'') AS [MemName_Last]
						--  , convert(varchar(max), [Photo]) as Photo
						,ISNULL(Type_ID,'') AS [Type_ID]
						,ISNULL([ID_NO],'') AS ID_NO
						,ISNULL([V_BSCMemName1_First],'') AS V_BSCMemName1_First
						,ISNULL([V_BSCMemName1_Middle],'') AS V_BSCMemName1_Middle
						,ISNULL([V_BSCMemName1_Last],'') AS V_BSCMemName1_Last
						,ISNULL([V_BSCMem1_TitlePosition],'') AS V_BSCMem1_TitlePosition
						,ISNULL([V_BSCMemName2_First],'') AS V_BSCMemName2_First
						,ISNULL([V_BSCMemName2_Middle],'') AS V_BSCMemName2_Middle
						,ISNULL([V_BSCMemName2_Last],'') AS V_BSCMemName2_Last
						,ISNULL([V_BSCMem2_TitlePosition],'') AS V_BSCMem2_TitlePosition
						,ISNULL([Proxy_Designation],'') AS Proxy_Designation
						,ISNULL([Proxy_Name_First],'') AS Proxy_Name_First
						,ISNULL([Proxy_Name_Middle],'') AS Proxy_Name_Middle
						,ISNULL([Proxy_Name_Last],'') AS Proxy_Name_Last
						,ISNULL([Proxy_BirthYear],'') AS Proxy_BirthYear
						--, convert(varchar(max), [Proxy_Photo])  as ProxyPhoto
						,ISNULL([Proxy_Type_ID],'') AS Proxy_Type_ID
						,ISNULL([Proxy_ID_NO],'') AS Proxy_ID_NO
						,ISNULL([P_BSCMemName1_First],'') AS P_BSCMemName1_First
						,ISNULL([P_BSCMemName1_Middle],'') AS P_BSCMemName1_Middle
						,ISNULL([P_BSCMemName1_Last],'') AS P_BSCMemName1_Last
						,ISNULL([P_BSCMem1_TitlePosition],'') AS P_BSCMem1_TitlePosition
						,ISNULL([P_BSCMemName2_First],'') AS P_BSCMemName2_First
						,ISNULL([P_BSCMemName2_Middle],'') AS P_BSCMemName2_Middle
						,ISNULL([P_BSCMemName2_Last],'') AS P_BSCMemName2_Last
						,ISNULL([P_BSCMem2_TitlePosition],'') AS P_BSCMem2_TitlePosition
						,ISNULL(GrpCode,'') AS GrpCode
						,[MemTypeFlag]

									FROM [dbo].[RegNHHMem]
									LEFT JOIN  [dbo].[RegNMemProgGrp] ON
									[RegNHHMem].AdmCountryCode= [RegNMemProgGrp].AdmCountryCode
									AND [RegNHHMem].LayR1ListCode= [RegNMemProgGrp].LayR1ListCode
									AND [RegNHHMem].LayR2ListCode= [RegNMemProgGrp].LayR2ListCode
									AND [RegNHHMem].LayR3ListCode= [RegNMemProgGrp].LayR3ListCode
									AND [RegNHHMem].LayR4ListCode= [RegNMemProgGrp].LayR4ListCode
									AND [RegNHHMem].HHID= [RegNMemProgGrp].HHID
									AND [RegNHHMem].HHMemID= [RegNMemProgGrp].[MemID]

					where

									[RegNHHMem].AdmCountryCode+[RegNHHMem].LayR1ListCode+[RegNHHMem].LayR2ListCode+[RegNHHMem].LayR3ListCode+[RegNHHMem].LayR4ListCode in (SELECT   [AdmCountryCode]
							  +[LayR1ListCode]							  +[LayR2ListCode]							  +[LayR3ListCode]
							  +[LayR4ListCode] from [RegNHHTable] where AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ") )";
                        } else {
                            $msql = "SELECT [RegNHHMem].[AdmCountryCode]
						,[RegNHHMem].[LayR1ListCode]
						,[RegNHHMem].[LayR2ListCode]
						,[RegNHHMem].[LayR3ListCode]
						,[RegNHHMem].[LayR4ListCode]
						,[RegNHHMem].[HHID]
						,[HHMemID]
						,[MemName]
						,[MemSex]
						,[HHRelation]
						,[RegNHHMem].[EntryBy]
						,[RegNHHMem].[EntryDate]
						,[LMPDate]
						,[ChildDOB]
						,[Elderly]
						,[Disabled]
						,[MemAge]
						,[RegNDate]
						,[BirthYear]
						,[MaritalStatus]
						,[ContactNo]
						,[MemOtherID]
						,ISNULL([MemName_First],'') AS [MemName_First]
						,ISNULL([MemName_Middle],'') AS [MemName_Middle]
						, ISNULL([MemName_Last],'') AS [MemName_Last]
						--  , convert(varchar(max), [Photo]) as Photo
						,ISNULL(Type_ID,'') AS [Type_ID]
						,ISNULL([ID_NO],'') AS ID_NO
						,ISNULL([V_BSCMemName1_First],'') AS V_BSCMemName1_First
						,ISNULL([V_BSCMemName1_Middle],'') AS V_BSCMemName1_Middle
						,ISNULL([V_BSCMemName1_Last],'') AS V_BSCMemName1_Last
						,ISNULL([V_BSCMem1_TitlePosition],'') AS V_BSCMem1_TitlePosition
						,ISNULL([V_BSCMemName2_First],'') AS V_BSCMemName2_First
						,ISNULL([V_BSCMemName2_Middle],'') AS V_BSCMemName2_Middle
						,ISNULL([V_BSCMemName2_Last],'') AS V_BSCMemName2_Last
						,ISNULL([V_BSCMem2_TitlePosition],'') AS V_BSCMem2_TitlePosition
						,ISNULL([Proxy_Designation],'') AS Proxy_Designation
						,ISNULL([Proxy_Name_First],'') AS Proxy_Name_First
						,ISNULL([Proxy_Name_Middle],'') AS Proxy_Name_Middle
						,ISNULL([Proxy_Name_Last],'') AS Proxy_Name_Last
						,ISNULL([Proxy_BirthYear],'') AS Proxy_BirthYear
						--, convert(varchar(max), [Proxy_Photo])  as ProxyPhoto
						,ISNULL([Proxy_Type_ID],'') AS Proxy_Type_ID
						,ISNULL([Proxy_ID_NO],'') AS Proxy_ID_NO
						,ISNULL([P_BSCMemName1_First],'') AS P_BSCMemName1_First
						,ISNULL([P_BSCMemName1_Middle],'') AS P_BSCMemName1_Middle
						,ISNULL([P_BSCMemName1_Last],'') AS P_BSCMemName1_Last
						,ISNULL([P_BSCMem1_TitlePosition],'') AS P_BSCMem1_TitlePosition
						,ISNULL([P_BSCMemName2_First],'') AS P_BSCMemName2_First
						,ISNULL([P_BSCMemName2_Middle],'') AS P_BSCMemName2_Middle
						,ISNULL([P_BSCMemName2_Last],'') AS P_BSCMemName2_Last
						,ISNULL([P_BSCMem2_TitlePosition],'') AS P_BSCMem2_TitlePosition
						,ISNULL(GrpCode,'') AS GrpCode
						,[MemTypeFlag]

									FROM [dbo].[RegNHHMem]
									LEFT JOIN  [dbo].[RegNMemProgGrp] ON
									[RegNHHMem].AdmCountryCode= [RegNMemProgGrp].AdmCountryCode
									AND [RegNHHMem].LayR1ListCode= [RegNMemProgGrp].LayR1ListCode
									AND [RegNHHMem].LayR2ListCode= [RegNMemProgGrp].LayR2ListCode
									AND [RegNHHMem].LayR3ListCode= [RegNMemProgGrp].LayR3ListCode
									AND [RegNHHMem].LayR4ListCode= [RegNMemProgGrp].LayR4ListCode
									AND [RegNHHMem].HHID= [RegNMemProgGrp].HHID
									AND [RegNHHMem].HHMemID= [RegNMemProgGrp].[MemID]

					where

									[RegNHHMem].AdmCountryCode+[RegNHHMem].LayR1ListCode+[RegNHHMem].LayR2ListCode+[RegNHHMem].LayR3ListCode+[RegNHHMem].LayR4ListCode in (" . $s . ")
					";
                        }
                        break;
                    case 2:
                        $msql = "SELECT DISTINCT [RegNHHMem].[AdmCountryCode]
							,[RegNHHMem].[LayR1ListCode]
							,[RegNHHMem].[LayR2ListCode]
							,[RegNHHMem].[LayR3ListCode]
							,[RegNHHMem].[LayR4ListCode]
							,[RegNHHMem].[HHID]
							,[HHMemID]
							,[MemName]
							,[MemSex]
							,[HHRelation]
							,[RegNHHMem].[EntryBy]
							,[RegNHHMem].[EntryDate]
							,[LMPDate]
							,[ChildDOB]
							,[Elderly]
							,[Disabled]
							,[MemAge]
							,[RegNDate]
							,[BirthYear]
							,[MaritalStatus]
							,[ContactNo]
							,[MemOtherID]
							,ISNULL([MemName_First],'') AS [MemName_First]
							,ISNULL([MemName_Middle],'') AS [MemName_Middle]
							, ISNULL([MemName_Last],'') AS [MemName_Last]
							--  , convert(varchar(max), [Photo]) as Photo
							,ISNULL(Type_ID,'') AS [Type_ID]
							,ISNULL([ID_NO],'') AS ID_NO
							,ISNULL([V_BSCMemName1_First],'') AS V_BSCMemName1_First
							,ISNULL([V_BSCMemName1_Middle],'') AS V_BSCMemName1_Middle
							,ISNULL([V_BSCMemName1_Last],'') AS V_BSCMemName1_Last
							,ISNULL([V_BSCMem1_TitlePosition],'') AS V_BSCMem1_TitlePosition
							,ISNULL([V_BSCMemName2_First],'') AS V_BSCMemName2_First
							,ISNULL([V_BSCMemName2_Middle],'') AS V_BSCMemName2_Middle
							,ISNULL([V_BSCMemName2_Last],'') AS V_BSCMemName2_Last
							,ISNULL([V_BSCMem2_TitlePosition],'') AS V_BSCMem2_TitlePosition
							,ISNULL([Proxy_Designation],'') AS Proxy_Designation
							,ISNULL([Proxy_Name_First],'') AS Proxy_Name_First
							,ISNULL([Proxy_Name_Middle],'') AS Proxy_Name_Middle
							,ISNULL([Proxy_Name_Last],'') AS Proxy_Name_Last
							,ISNULL([Proxy_BirthYear],'') AS Proxy_BirthYear
							--  , convert(varchar(max), [Proxy_Photo])  as ProxyPhoto
							,ISNULL([Proxy_Type_ID],'') AS Proxy_Type_ID
							,ISNULL([Proxy_ID_NO],'') AS Proxy_ID_NO
							,ISNULL([P_BSCMemName1_First],'') AS P_BSCMemName1_First
							,ISNULL([P_BSCMemName1_Middle],'') AS P_BSCMemName1_Middle
							,ISNULL([P_BSCMemName1_Last],'') AS P_BSCMemName1_Last
							,ISNULL([P_BSCMem1_TitlePosition],'') AS P_BSCMem1_TitlePosition
							,ISNULL([P_BSCMemName2_First],'') AS P_BSCMemName2_First
							,ISNULL([P_BSCMemName2_Middle],'') AS P_BSCMemName2_Middle
							,ISNULL([P_BSCMemName2_Last],'') AS P_BSCMemName2_Last
							,ISNULL([P_BSCMem2_TitlePosition],'') AS P_BSCMem2_TitlePosition
							,ISNULL(GrpCode,'') AS GrpCode
							,[MemTypeFlag]

	FROM [dbo].[RegNHHMem]
	LEFT JOIN  [dbo].[RegNMemProgGrp] ON
	[RegNHHMem].AdmCountryCode= [RegNMemProgGrp].AdmCountryCode
	AND [RegNHHMem].LayR1ListCode= [RegNMemProgGrp].LayR1ListCode
	AND [RegNHHMem].LayR2ListCode= [RegNMemProgGrp].LayR2ListCode
	AND [RegNHHMem].LayR3ListCode= [RegNMemProgGrp].LayR3ListCode
	AND [RegNHHMem].LayR4ListCode= [RegNMemProgGrp].LayR4ListCode
	AND [RegNHHMem].HHID= [RegNMemProgGrp].HHID
	AND [RegNHHMem].HHMemID= [RegNMemProgGrp].[MemID]

	where [RegNHHMem].[AdmCountryCode]+[RegNHHMem].[LayR1ListCode]+[RegNHHMem].[LayR2ListCode]+[RegNHHMem].[LayR3ListCode]+[RegNHHMem].[LayR4ListCode]+[RegNHHMem].[HHID]+[RegNHHMem].[HHMemID] in (
					(SELECT  DISTINCT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]+[MemID]
						FROM [dbo].[SrvTable]
						where[SrvTable].[OpMonthCode] in(
								Select SrvOpMonthCode from DistNPlanbasic
								where DisOpMonthCode in (
										Select OpMonthCode
										from AdmOpMonthTable
										where Status='A'
										and OpCode='3'
										and admcountryCode ='" . $countryCode . "'
										)
								and admcountryCode +FDPCode in (" . $s . ")
								)
						and [AdmCountryCode]+FDPCode in(" . $s . ")
						)

				)";

                        break;


                    case 3:    // for  Service Center
                        $msql = "SELECT  [RegNHHMem].[AdmCountryCode]
				,[RegNHHMem].[LayR1ListCode]
				,[RegNHHMem].[LayR2ListCode]
				,[RegNHHMem].[LayR3ListCode]
				,[RegNHHMem].[LayR4ListCode]
				,[RegNHHMem].[HHID]
				,[HHMemID]
				,[MemName]
				,[MemSex]
				,[HHRelation]
				,[RegNHHMem].[EntryBy]
				,[RegNHHMem].[EntryDate]
				,[LMPDate]
				,[ChildDOB]
				,[Elderly]
				,[Disabled]
				,[MemAge]
				,[RegNDate]
				,[BirthYear]
				,[MaritalStatus]
				,[ContactNo]
				,[MemOtherID]
				,[MemName_First]
				,[MemName_Middle]
				,[MemName_Last]
				--  , convert(varchar(max), [Photo]) as Photo
				,ISNULL(Type_ID,'') AS [Type_ID]
				,ISNULL([ID_NO],'') AS ID_NO
				,ISNULL([V_BSCMemName1_First],'') AS V_BSCMemName1_First
				,ISNULL([V_BSCMemName1_Middle],'') AS V_BSCMemName1_Middle
				,ISNULL([V_BSCMemName1_Last],'') AS V_BSCMemName1_Last
				,ISNULL([V_BSCMem1_TitlePosition],'') AS V_BSCMem1_TitlePosition
				,ISNULL([V_BSCMemName2_First],'') AS V_BSCMemName2_First
				,ISNULL([V_BSCMemName2_Middle],'') AS V_BSCMemName2_Middle
				,ISNULL([V_BSCMemName2_Last],'') AS V_BSCMemName2_Last
				,ISNULL([V_BSCMem2_TitlePosition],'') AS V_BSCMem2_TitlePosition
				,ISNULL([Proxy_Designation],'') AS Proxy_Designation
				,ISNULL([Proxy_Name_First],'') AS Proxy_Name_First
				,ISNULL([Proxy_Name_Middle],'') AS Proxy_Name_Middle
				,ISNULL([Proxy_Name_Last],'') AS Proxy_Name_Last
				,ISNULL([Proxy_BirthYear],'') AS Proxy_BirthYear
				--  , convert(varchar(max), [Proxy_Photo])  as ProxyPhoto
				,ISNULL([Proxy_Type_ID],'') AS Proxy_Type_ID
				,ISNULL([Proxy_ID_NO],'') AS Proxy_ID_NO
				,ISNULL([P_BSCMemName1_First],'') AS P_BSCMemName1_First
				,ISNULL([P_BSCMemName1_Middle],'') AS P_BSCMemName1_Middle
				,ISNULL([P_BSCMemName1_Last],'') AS P_BSCMemName1_Last
				,ISNULL([P_BSCMem1_TitlePosition],'') AS P_BSCMem1_TitlePosition
				,ISNULL([P_BSCMemName2_First],'') AS P_BSCMemName2_First
				,ISNULL([P_BSCMemName2_Middle],'') AS P_BSCMemName2_Middle
				,ISNULL([P_BSCMemName2_Last],'') AS P_BSCMemName2_Last
				,ISNULL([P_BSCMem2_TitlePosition],'') AS P_BSCMem2_TitlePosition

				,ISNULL(GrpCode,'') AS GrpCode
				,[MemTypeFlag]

				FROM [dbo].[RegNHHMem]
				LEFT JOIN  [dbo].[RegNMemProgGrp] ON
				[RegNHHMem].AdmCountryCode= [RegNMemProgGrp].AdmCountryCode
				AND [RegNHHMem].LayR1ListCode= [RegNMemProgGrp].LayR1ListCode
				AND [RegNHHMem].LayR2ListCode= [RegNMemProgGrp].LayR2ListCode
				AND [RegNHHMem].LayR3ListCode= [RegNMemProgGrp].LayR3ListCode
				AND [RegNHHMem].LayR4ListCode= [RegNMemProgGrp].LayR4ListCode
				AND [RegNHHMem].HHID= [RegNMemProgGrp].HHID
				AND [RegNHHMem].HHMemID= [RegNMemProgGrp].[MemID]


				where
				--GET MEMBER DATA BASE ON   [RegNMemProgGrp] TABLE

				[RegNHHMem].AdmCountryCode+[RegNHHMem].LayR1ListCode+[RegNHHMem].LayR2ListCode+[RegNHHMem].LayR3ListCode+[RegNHHMem].LayR4ListCode+[RegNHHMem].[HHID]+[RegNHHMem].[HHMemID] in (
					SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]+[MemID]
							FROM [dbo].[RegNMemProgGrp]
							WHERE [AdmCountryCode]+ [AdmDonorCode]+[AdmAwardCode]+[ProgCode]+[GrpCode]IN(
									SELECT Distinct AdmCountryCode+AdmDonorCode+AdmAwardCode+AdmProgCode+GrpCode
									FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ")
							)

				)
				--GET MEMBER DATA BASE ON   [SrvTable] TABLE
				OR [RegNHHMem].AdmCountryCode+[RegNHHMem].LayR1ListCode+[RegNHHMem].LayR2ListCode+[RegNHHMem].LayR3ListCode+[RegNHHMem].LayR4ListCode+[RegNHHMem].[HHID]+[RegNHHMem].[HHMemID] in(
					SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]+[MemID]
					FROM [dbo].[SrvTable]
					where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
										where [AdmCountryCode]='" . $countryCode . "'  and [OpCode]=2  and  [Status]='A'
										ORDER BY OpMonthCode DESC)
										and [SrvStatus]='O'
										and [AdmCountryCode]+[SrvCenterCode] IN (" . $s . "))";
                        break;




                    case 4:
                        $msql = "SELECT DISTINCT [RegNHHMem].[AdmCountryCode]
							,[RegNHHMem].[LayR1ListCode]
							,[RegNHHMem].[LayR2ListCode]
							,[RegNHHMem].[LayR3ListCode]
							,[RegNHHMem].[LayR4ListCode]
							,[RegNHHMem].[HHID]
							,[HHMemID]
							,[MemName]
							,[MemSex]
							,[HHRelation]
							,[RegNHHMem].[EntryBy]
							,[RegNHHMem].[EntryDate]
							,[LMPDate]
							,[ChildDOB]
							,[Elderly]
							,[Disabled]
							,[MemAge]
							,[RegNDate]
							,[BirthYear]
							,[MaritalStatus]
							,[ContactNo]
							,[MemOtherID]
							,ISNULL([MemName_First],'') AS [MemName_First]
							,ISNULL([MemName_Middle],'') AS [MemName_Middle]
							, ISNULL([MemName_Last],'') AS [MemName_Last]
							--  , convert(varchar(max), [Photo]) as Photo
							,ISNULL(Type_ID,'') AS [Type_ID]
							,ISNULL([ID_NO],'') AS ID_NO
							,ISNULL([V_BSCMemName1_First],'') AS V_BSCMemName1_First
							,ISNULL([V_BSCMemName1_Middle],'') AS V_BSCMemName1_Middle
							,ISNULL([V_BSCMemName1_Last],'') AS V_BSCMemName1_Last
							,ISNULL([V_BSCMem1_TitlePosition],'') AS V_BSCMem1_TitlePosition
							,ISNULL([V_BSCMemName2_First],'') AS V_BSCMemName2_First
							,ISNULL([V_BSCMemName2_Middle],'') AS V_BSCMemName2_Middle
							,ISNULL([V_BSCMemName2_Last],'') AS V_BSCMemName2_Last
							,ISNULL([V_BSCMem2_TitlePosition],'') AS V_BSCMem2_TitlePosition
							,ISNULL([Proxy_Designation],'') AS Proxy_Designation
							,ISNULL([Proxy_Name_First],'') AS Proxy_Name_First
							,ISNULL([Proxy_Name_Middle],'') AS Proxy_Name_Middle
							,ISNULL([Proxy_Name_Last],'') AS Proxy_Name_Last
							,ISNULL([Proxy_BirthYear],'') AS Proxy_BirthYear
							--  , convert(varchar(max), [Proxy_Photo])  as ProxyPhoto
							,ISNULL([Proxy_Type_ID],'') AS Proxy_Type_ID
							,ISNULL([Proxy_ID_NO],'') AS Proxy_ID_NO
							,ISNULL([P_BSCMemName1_First],'') AS P_BSCMemName1_First
							,ISNULL([P_BSCMemName1_Middle],'') AS P_BSCMemName1_Middle
							,ISNULL([P_BSCMemName1_Last],'') AS P_BSCMemName1_Last
							,ISNULL([P_BSCMem1_TitlePosition],'') AS P_BSCMem1_TitlePosition
							,ISNULL([P_BSCMemName2_First],'') AS P_BSCMemName2_First
							,ISNULL([P_BSCMemName2_Middle],'') AS P_BSCMemName2_Middle
							,ISNULL([P_BSCMemName2_Last],'') AS P_BSCMemName2_Last
							,ISNULL([P_BSCMem2_TitlePosition],'') AS P_BSCMem2_TitlePosition
							,ISNULL(GrpCode,'') AS GrpCode
							,[MemTypeFlag]

	FROM [dbo].[RegNHHMem]


	where [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]+[HHMemID] in (0)";

                        break;
						case 5: 
						  $msql = "SELECT [RegNHHMem].[AdmCountryCode]
						,[RegNHHMem].[LayR1ListCode]
						,[RegNHHMem].[LayR2ListCode]
						,[RegNHHMem].[LayR3ListCode]
						,[RegNHHMem].[LayR4ListCode]
						,[RegNHHMem].[HHID]
						,[HHMemID]
						,[MemName]
						,[MemSex]
						,[HHRelation]
						,[RegNHHMem].[EntryBy]
						,[RegNHHMem].[EntryDate]
						,[LMPDate]
						,[ChildDOB]
						,[Elderly]
						,[Disabled]
						,[MemAge]
						,[RegNHHMem].[RegNDate]
						,[BirthYear]
						,[MaritalStatus]
						,[ContactNo]
						,[MemOtherID]
						,ISNULL([MemName_First],'') AS [MemName_First]
						,ISNULL([MemName_Middle],'') AS [MemName_Middle]
						, ISNULL([MemName_Last],'') AS [MemName_Last]
						--  , convert(varchar(max), [Photo]) as Photo
						,ISNULL(Type_ID,'') AS [Type_ID]
						,ISNULL([ID_NO],'') AS ID_NO
						,ISNULL([V_BSCMemName1_First],'') AS V_BSCMemName1_First
						,ISNULL([V_BSCMemName1_Middle],'') AS V_BSCMemName1_Middle
						,ISNULL([V_BSCMemName1_Last],'') AS V_BSCMemName1_Last
						,ISNULL([V_BSCMem1_TitlePosition],'') AS V_BSCMem1_TitlePosition
						,ISNULL([V_BSCMemName2_First],'') AS V_BSCMemName2_First
						,ISNULL([V_BSCMemName2_Middle],'') AS V_BSCMemName2_Middle
						,ISNULL([V_BSCMemName2_Last],'') AS V_BSCMemName2_Last
						,ISNULL([V_BSCMem2_TitlePosition],'') AS V_BSCMem2_TitlePosition
						,ISNULL([Proxy_Designation],'') AS Proxy_Designation
						,ISNULL([Proxy_Name_First],'') AS Proxy_Name_First
						,ISNULL([Proxy_Name_Middle],'') AS Proxy_Name_Middle
						,ISNULL([Proxy_Name_Last],'') AS Proxy_Name_Last
						,ISNULL([Proxy_BirthYear],'') AS Proxy_BirthYear
						--, convert(varchar(max), [Proxy_Photo])  as ProxyPhoto
						,ISNULL([Proxy_Type_ID],'') AS Proxy_Type_ID
						,ISNULL([Proxy_ID_NO],'') AS Proxy_ID_NO
						,ISNULL([P_BSCMemName1_First],'') AS P_BSCMemName1_First
						,ISNULL([P_BSCMemName1_Middle],'') AS P_BSCMemName1_Middle
						,ISNULL([P_BSCMemName1_Last],'') AS P_BSCMemName1_Last
						,ISNULL([P_BSCMem1_TitlePosition],'') AS P_BSCMem1_TitlePosition
						,ISNULL([P_BSCMemName2_First],'') AS P_BSCMemName2_First
						,ISNULL([P_BSCMemName2_Middle],'') AS P_BSCMemName2_Middle
						,ISNULL([P_BSCMemName2_Last],'') AS P_BSCMemName2_Last
						,ISNULL([P_BSCMem2_TitlePosition],'') AS P_BSCMem2_TitlePosition
						,ISNULL('000','') AS GrpCode
						,[MemTypeFlag]

									FROM [dbo].[RegNHHMem]
									inner JOIN  [dbo].[RegNAssignProgSrv]ON
									[RegNHHMem].AdmCountryCode= [RegNAssignProgSrv].AdmCountryCode
									AND [RegNHHMem].LayR1ListCode= [RegNAssignProgSrv].LayR1ListCode
									AND [RegNHHMem].LayR2ListCode= [RegNAssignProgSrv].LayR2ListCode
									AND [RegNHHMem].LayR3ListCode= [RegNAssignProgSrv].LayR3ListCode
									AND [RegNHHMem].LayR4ListCode= [RegNAssignProgSrv].LayR4ListCode
									AND [RegNHHMem].HHID= [RegNAssignProgSrv].HHID
									AND [RegNHHMem].HHMemID= [RegNAssignProgSrv].[MemID]

					where

									[RegNAssignProgSrv].AdmCountryCode+[RegNAssignProgSrv].LayR1ListCode+[RegNAssignProgSrv].LayR2ListCode+[RegNAssignProgSrv].LayR3ListCode+[RegNAssignProgSrv].LayR4ListCode in (" . $s . ")";
						break;
                }




                $this->query($msql);
                $members = $this->resultset();
                if ($members != false) {

                    foreach ($members as $key => $value) {
                        $data['members'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['members'][$key]['DistrictName'] = $value['LayR1ListCode'];
                        $data['members'][$key]['UpazillaName'] = $value['LayR2ListCode'];
                        $data['members'][$key]['UnitName'] = $value['LayR3ListCode'];
                        $data['members'][$key]['VillageName'] = $value['LayR4ListCode'];
                        $data['members'][$key]['HHID'] = $value['HHID'];
                        $data['members'][$key]['HHMemID'] = $value['HHMemID'];
                        $data['members'][$key]['MemName'] = $value['MemName'];
                        $data['members'][$key]['MemSex'] = $value['MemSex'];
                        $data['members'][$key]['HHRelation'] = $value['HHRelation'];
                        $data['members'][$key]['EntryBy'] = $value['EntryBy'];
                        $data['members'][$key]['EntryDate'] = $value['EntryDate'];
                        $data['members'][$key]['LMPDate'] = $value['LMPDate'];
                        $data['members'][$key]['ChildDOB'] = $value['ChildDOB'];
                        $data['members'][$key]['Elderly'] = $value['Elderly'];
                        $data['members'][$key]['Disabled'] = $value['Disabled'];
                        $data['members'][$key]['MemAge'] = $value['MemAge'];
                        $data['members'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['members'][$key]['BirthYear'] = $value['BirthYear'];
                        $data['members'][$key]['MaritalStatus'] = $value['MaritalStatus'];
                        $data['members'][$key]['ContactNo'] = $value['ContactNo'];
                        $data['members'][$key]['MemOtherID'] = $value['MemOtherID'];
                        $data['members'][$key]['MemName_First'] = $value['MemName_First'];
                        $data['members'][$key]['MemName_Middle'] = $value['MemName_Middle'];
                        $data['members'][$key]['MemName_Last'] = $value['MemName_Last'];
                        $data['members'][$key]['Photo'] = 'na'; //$value['Photo'];
                        $data['members'][$key]['Type_ID'] = $value['Type_ID'];
                        $data['members'][$key]['TypedID_No'] = $value['ID_NO'];
                        $data['members'][$key]['V_BSCMemName1_First'] = $value['V_BSCMemName1_First'];
                        $data['members'][$key]['V_BSCMemName1_Middle'] = $value['V_BSCMemName1_Middle'];
                        $data['members'][$key]['V_BSCMemName1_Last'] = $value['V_BSCMemName1_Last'];
                        $data['members'][$key]['V_BSCMem1_TitlePosition'] = $value['V_BSCMem1_TitlePosition'];
                        $data['members'][$key]['V_BSCMemName2_First'] = $value['V_BSCMemName2_First'];
                        $data['members'][$key]['V_BSCMemName2_Middle'] = $value['V_BSCMemName2_Middle'];
                        $data['members'][$key]['V_BSCMemName2_Last'] = $value['V_BSCMemName2_Last'];
                        $data['members'][$key]['V_BSCMem2_TitlePosition'] = $value['V_BSCMem2_TitlePosition'];
                        $data['members'][$key]['Proxy_Designation'] = $value['Proxy_Designation'];
                        $data['members'][$key]['Proxy_Name_First'] = $value['Proxy_Name_First'];
                        $data['members'][$key]['Proxy_Name_Middle'] = $value['Proxy_Name_Middle'];
                        $data['members'][$key]['Proxy_Name_Last'] = $value['Proxy_Name_Last'];
                        $data['members'][$key]['Proxy_BirthYear'] = $value['Proxy_BirthYear'];
                        $data['members'][$key]['Proxy_Photo'] = 'Na'; //$value['ProxyPhoto'];
                        $data['members'][$key]['Proxy_Type_ID'] = $value['Proxy_Type_ID'];
                        $data['members'][$key]['Proxy_ID_NO'] = $value['Proxy_ID_NO'];
                        $data['members'][$key]['P_BSCMemName1_First'] = $value['P_BSCMemName1_First'];
                        $data['members'][$key]['P_BSCMemName1_Middle'] = $value['P_BSCMemName1_Middle'];
                        $data['members'][$key]['P_BSCMemName1_Last'] = $value['P_BSCMemName1_Last'];
                        $data['members'][$key]['P_BSCMem1_TitlePosition'] = $value['P_BSCMem1_TitlePosition'];
                        $data['members'][$key]['P_BSCMemName2_First'] = $value['P_BSCMemName2_First'];
                        $data['members'][$key]['P_BSCMemName2_Middle'] = $value['P_BSCMemName2_Middle'];
                        $data['members'][$key]['P_BSCMemName2_Last'] = $value['P_BSCMemName2_Last'];
                        $data['members'][$key]['P_BSCMem2_TitlePosition'] = $value['P_BSCMem2_TitlePosition'];
                        $data['members'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['members'][$key]['MemTypeFlag'] = $value['MemTypeFlag'];
                    }
                } // end MEMBER
            }


            // Members
            // Getting Registration data which all SyncStatus = 1 for this user
        } else {
            return false;
        }
        return $data;
    }

    // todo: use the is_down_load_reg_houseHold to sprite json


    public function is_down_load_reg_house_hold($user, $pass, $lay_r_code_j, $operation_mode) {
        $data = array();
        $data["countries"] = array();

        $data["registration"] = array();

        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // decode the json array
            $jData = json_decode($lay_r_code_j);


            $s = "";
            $isAddressCodeAdded = false;

            $countryCode = "";
            //echo($operation_mode);
            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list
                    //   echo($s);
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;


                case 3 : // fro sERVICE CNTERN 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;
					
					  case 5 : // fro sERVICE CNTERN 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                  
                    
                    break;
            }


            if ($jData) {
                $rsql = "";
                switch ($operation_mode) {
                    case 1: // for registration
                        // if slected layer is Address code
                        if ($isAddressCodeAdded) {

                            $rsql = "SELECT   [AdmCountryCode]
							  ,[LayR1ListCode]
							  ,[LayR2ListCode]
							  ,[LayR3ListCode]
							  ,[LayR4ListCode]
							  ,[HHID]
							  , convert(varchar,[RegNDate],110) AS [RegNDate]
							  ,[HHHeadName]
							  ,ISNULL([HHHeadSex], '')as[HHHeadSex]
							  , ISNULL([HHSize], 0)as[HHSize]
                              , ISNULL([GPSLat], '')as[GPSLat]
							  ,ISNULL([GPSLong], '')as [GPSLong]
							  ,ISNULL([AGLand], '')as [AGLand]
							  ,ISNULL([VStatus], '')as [VStatus]
							  ,ISNULL([MStatus], '')as [MStatus]
							  ,[EntryBy]
							  ,[EntryDate]
							  ,ISNULL([VSLAGroup], '') as [VSLAGroup]
							  ,ISNULL([GPSLongSwap], '') as [GPSLongSwap]
							  ,ISNULL([RegNAddLookupCode], '') as  [RegNAddLookupCode]
							   ,ISNULL([LTp2Hectres],'') as [LTp2Hectres]
							   ,ISNULL([LT3mFoodStock],'')as [LT3mFoodStock]
							   ,ISNULL([NoMajorCommonLiveStock],'')as [NoMajorCommonLiveStock]
							   ,ISNULL([ReceiveNoFormalWages],'') as [ReceiveNoFormalWages]
							   ,ISNULL([NoIGA],'') as [NoIGA]
							   ,ISNULL([RelyPiecework],'') as [RelyPiecework]
							  ,ISNULL([HHHeadCat], '') as [HHHeadCat]
							  ,ISNULL([LT2yrsM], 0) as [LT2yrsM]
							  ,ISNULL([LT2yrsF], 0) as [LT2yrsF]
							  ,ISNULL([M2to5yrs], 0) as [M2to5yrs]
							  ,ISNULL([F2to5yrs], 0) as [F2to5yrs]
							  ,ISNULL([M6to12yrs], 0) as [M6to12yrs]
							  ,ISNULL([F6to12yrs], 0) as [F6to12yrs]
							  ,ISNULL([M13to17yrs], 0) as [M13to17yrs]
							  ,ISNULL([F13to17yrs], 0) as [F13to17yrs]
							  ,ISNULL([Orphn_LT18yrsM], 0) as [Orphn_LT18yrsM]
							  ,ISNULL([Orphn_LT18yrsF], 0) as [Orphn_LT18yrsF]
							  ,ISNULL([Adlt_18to59M], 0) as [Adlt_18to59M]
							  ,ISNULL([Adlt_18to59F], 0) as [Adlt_18to59F]
							  ,ISNULL([Eld_60pM], 0) as [Eld_60pM]
							  ,ISNULL([Eld_60pF], 0) as [Eld_60pF]
							  ,ISNULL([PLW], '') as [PLW]
							  ,ISNULL([ChronicallyIll], 0) as [ChronicallyIll]
							  ,ISNULL([LivingDeceasedContractEbola], 0) as [LivingDeceasedContractEbola]
							  ,ISNULL([ExtraChildBecauseEbola], 0) as [ExtraChildBecauseEbola]
							  ,ISNULL([ExtraElderlyPersonBecauseEbola], 0) as [ExtraElderlyPersonBecauseEbola]
							  ,ISNULL([ExtraChronicallyIllDisabledPersonBecauseEbola], 0) as [ExtraChronicallyIllDisabledPersonBecauseEbola]
							  ,ISNULL([BRFCntCattle], 0) as [BRFCntCattle]
							  ,ISNULL([BRFValCattle], 0) as [BRFValCattle]
							  ,ISNULL([AFTCntCattle], 0) as [AFTCntCattle]
							  ,ISNULL([AFTValCattle], 0) as [AFTValCattle]
							  ,ISNULL([BRFCntSheepGoats], 0) as [BRFCntSheepGoats]
							  ,ISNULL([BRFValSheepGoats], 0) as [BRFValSheepGoats]
							  ,ISNULL([AFTCntSheepGoats], 0) as [AFTCntSheepGoats]
							  ,ISNULL([AFTValSheepGoats], 0) as [AFTValSheepGoats]
							  ,ISNULL([BRFCntPoultry], 0) as [BRFCntPoultry]
							  ,ISNULL([BRFValPoultry], 0) as [BRFValPoultry]
							  ,ISNULL([AFTCntPoultry], 0) as [AFTCntPoultry]
							  ,ISNULL([AFTValPoultry], 0) as [AFTValPoultry]
							  ,ISNULL([BRFCntOther], 0) as [BRFCntOther]
							  ,ISNULL([BRFValOther], 0) as [BRFValOther]
							  ,ISNULL([AFTCntOther], 0) as [AFTCntOther]
							  ,ISNULL([AFTValOther], 0) as [AFTValOther]
							  ,ISNULL([BRFAcreCultivable], 0) as [BRFAcreCultivable]
							  ,ISNULL([BRFValCultivable], 0) as [BRFValCultivable]
							  ,ISNULL([AFTAcreCultivable], 0) as [AFTAcreCultivable]
							  ,ISNULL([AFTValCultivable], 0) as [AFTValCultivable]
							  ,ISNULL([BRFAcreNonCultivable], 0) as [BRFAcreNonCultivable]
							  ,ISNULL([BRFValNonCultivable], 0) as [BRFValNonCultivable]
							  ,ISNULL([AFTAcreNonCultivable], 0) as [AFTAcreNonCultivable]
							  ,ISNULL([AFTValNonCultivable], 0) as [AFTValNonCultivable]
							  ,ISNULL([BRFAcreOrchards], 0) as [BRFAcreOrchards]
							  ,ISNULL([BRFValOrchards], 0) as [BRFValOrchards]
							  ,ISNULL([AFTAcreOrchards], 0) as [AFTAcreOrchards]
							  ,ISNULL([AFTValOrchards], 0) as [AFTValOrchards]
							  ,ISNULL([BRFValCrop], 0) as [BRFValCrop]
							  ,ISNULL([AFTValCrop], 0) as [AFTValCrop]
							  ,ISNULL([BRFValLivestock], 0) as [BRFValLivestock]
							  ,ISNULL([AFTValLivestock], 0) as [AFTValLivestock]
							  ,ISNULL([BRFValSmallBusiness], 0) as [BRFValSmallBusiness]
							  ,ISNULL([AFTValSmallBusiness], 0) as [AFTValSmallBusiness]
							  ,ISNULL([BRFValEmployment], 0) as [BRFValEmployment]
							  ,ISNULL([AFTValEmployment], 0) as [AFTValEmployment]
							  ,ISNULL([BRFValRemittances], 0) as [BRFValRemittances]
							  ,ISNULL([AFTValRemittances], 0) as [AFTValRemittances]
							  ,ISNULL([BRFCntWageEnr], 0) as [BRFCntWageEnr]
							  ,ISNULL([AFTCntWageEnr], 0) as [AFTCntWageEnr]
							  ,ISNULL([WRank], '') as [WRank]

								FROM [dbo].[RegNHHTable]
							where

						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";
                        } else {

                            $rsql = "SELECT   [AdmCountryCode]
							  ,[LayR1ListCode]
							  ,[LayR2ListCode]
							  ,[LayR3ListCode]
							  ,[LayR4ListCode]
							  ,[HHID]
							  , convert(varchar,[RegNDate],110) AS [RegNDate]
							  ,[HHHeadName]
							  ,ISNULL([HHHeadSex], '')as[HHHeadSex]
							  , ISNULL([HHSize], 0)as[HHSize]
                              , ISNULL([GPSLat], '')as[GPSLat]
							  ,ISNULL([GPSLong], '')as [GPSLong]
							  ,ISNULL([AGLand], '')as [AGLand]
							  ,ISNULL([VStatus], '')as [VStatus]
							  ,ISNULL([MStatus], '')as [MStatus]
							  ,[EntryBy]
							  ,[EntryDate]
							  ,ISNULL([VSLAGroup], '') as [VSLAGroup]
							  ,ISNULL([GPSLongSwap], '') as [GPSLongSwap]
							  ,ISNULL([RegNAddLookupCode], '') as  [RegNAddLookupCode]
							  ,ISNULL([LTp2Hectres],'') as [LTp2Hectres]
							   ,ISNULL([LT3mFoodStock],'')as [LT3mFoodStock]
							   ,ISNULL([NoMajorCommonLiveStock],'')as [NoMajorCommonLiveStock]
							   ,ISNULL([ReceiveNoFormalWages],'') as [ReceiveNoFormalWages]
							   ,ISNULL([NoIGA],'') as [NoIGA]
							   ,ISNULL([RelyPiecework],'') as [RelyPiecework]
							  ,ISNULL([HHHeadCat], '') as [HHHeadCat]
							  ,ISNULL([LT2yrsM], 0) as [LT2yrsM]
							  ,ISNULL([LT2yrsF], 0) as [LT2yrsF]
							  ,ISNULL([M2to5yrs], 0) as [M2to5yrs]
							  ,ISNULL([F2to5yrs], 0) as [F2to5yrs]
							  ,ISNULL([M6to12yrs], 0) as [M6to12yrs]
							  ,ISNULL([F6to12yrs], 0) as [F6to12yrs]
							  ,ISNULL([M13to17yrs], 0) as [M13to17yrs]
							  ,ISNULL([F13to17yrs], 0) as [F13to17yrs]
							  ,ISNULL([Orphn_LT18yrsM], 0) as [Orphn_LT18yrsM]
							  ,ISNULL([Orphn_LT18yrsF], 0) as [Orphn_LT18yrsF]
							  ,ISNULL([Adlt_18to59M], 0) as [Adlt_18to59M]
							  ,ISNULL([Adlt_18to59F], 0) as [Adlt_18to59F]
							  ,ISNULL([Eld_60pM], 0) as [Eld_60pM]
							  ,ISNULL([Eld_60pF], 0) as [Eld_60pF]
							  ,ISNULL([PLW], '') as [PLW]
							  ,ISNULL([ChronicallyIll], 0) as [ChronicallyIll]
							  ,ISNULL([LivingDeceasedContractEbola], 0) as [LivingDeceasedContractEbola]
							  ,ISNULL([ExtraChildBecauseEbola], 0) as [ExtraChildBecauseEbola]
							  ,ISNULL([ExtraElderlyPersonBecauseEbola], 0) as [ExtraElderlyPersonBecauseEbola]
							  ,ISNULL([ExtraChronicallyIllDisabledPersonBecauseEbola], 0) as [ExtraChronicallyIllDisabledPersonBecauseEbola]
							  ,ISNULL([BRFCntCattle], 0) as [BRFCntCattle]
							  ,ISNULL([BRFValCattle], 0) as [BRFValCattle]
							  ,ISNULL([AFTCntCattle], 0) as [AFTCntCattle]
							  ,ISNULL([AFTValCattle], 0) as [AFTValCattle]
							  ,ISNULL([BRFCntSheepGoats], 0) as [BRFCntSheepGoats]
							  ,ISNULL([BRFValSheepGoats], 0) as [BRFValSheepGoats]
							  ,ISNULL([AFTCntSheepGoats], 0) as [AFTCntSheepGoats]
							  ,ISNULL([AFTValSheepGoats], 0) as [AFTValSheepGoats]
							  ,ISNULL([BRFCntPoultry], 0) as [BRFCntPoultry]
							  ,ISNULL([BRFValPoultry], 0) as [BRFValPoultry]
							  ,ISNULL([AFTCntPoultry], 0) as [AFTCntPoultry]
							  ,ISNULL([AFTValPoultry], 0) as [AFTValPoultry]
							  ,ISNULL([BRFCntOther], 0) as [BRFCntOther]
							  ,ISNULL([BRFValOther], 0) as [BRFValOther]
							  ,ISNULL([AFTCntOther], 0) as [AFTCntOther]
							  ,ISNULL([AFTValOther], 0) as [AFTValOther]
							  ,ISNULL([BRFAcreCultivable], 0) as [BRFAcreCultivable]
							  ,ISNULL([BRFValCultivable], 0) as [BRFValCultivable]
							  ,ISNULL([AFTAcreCultivable], 0) as [AFTAcreCultivable]
							  ,ISNULL([AFTValCultivable], 0) as [AFTValCultivable]
							  ,ISNULL([BRFAcreNonCultivable], 0) as [BRFAcreNonCultivable]
							  ,ISNULL([BRFValNonCultivable], 0) as [BRFValNonCultivable]
							  ,ISNULL([AFTAcreNonCultivable], 0) as [AFTAcreNonCultivable]
							  ,ISNULL([AFTValNonCultivable], 0) as [AFTValNonCultivable]
							  ,ISNULL([BRFAcreOrchards], 0) as [BRFAcreOrchards]
							  ,ISNULL([BRFValOrchards], 0) as [BRFValOrchards]
							  ,ISNULL([AFTAcreOrchards], 0) as [AFTAcreOrchards]
							  ,ISNULL([AFTValOrchards], 0) as [AFTValOrchards]
							  ,ISNULL([BRFValCrop], 0) as [BRFValCrop]
							  ,ISNULL([AFTValCrop], 0) as [AFTValCrop]
							  ,ISNULL([BRFValLivestock], 0) as [BRFValLivestock]
							  ,ISNULL([AFTValLivestock], 0) as [AFTValLivestock]
							  ,ISNULL([BRFValSmallBusiness], 0) as [BRFValSmallBusiness]
							  ,ISNULL([AFTValSmallBusiness], 0) as [AFTValSmallBusiness]
							  ,ISNULL([BRFValEmployment], 0) as [BRFValEmployment]
							  ,ISNULL([AFTValEmployment], 0) as [AFTValEmployment]
							  ,ISNULL([BRFValRemittances], 0) as [BRFValRemittances]
							  ,ISNULL([AFTValRemittances], 0) as [AFTValRemittances]
							  ,ISNULL([BRFCntWageEnr], 0) as [BRFCntWageEnr]
							  ,ISNULL([AFTCntWageEnr], 0) as [AFTCntWageEnr]
							  ,ISNULL([WRank], '') as [WRank]

								FROM [dbo].[RegNHHTable]
							where

						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";
                        }
                        break;
                    case 2:
                        $rsql = "SELECT   [AdmCountryCode]
							  ,[LayR1ListCode]
							  ,[LayR2ListCode]
							  ,[LayR3ListCode]
							  ,[LayR4ListCode]
							  ,[HHID]
							  ,convert(varchar,[RegNDate],110) AS [RegNDate]
							  ,[HHHeadName]
							  ,ISNULL([HHHeadSex], '')as[HHHeadSex]
							  , ISNULL([HHSize], 0)as[HHSize]
                              , ISNULL([GPSLat], '')as[GPSLat]
							  ,ISNULL([GPSLong], '')as [GPSLong]
							  ,ISNULL([AGLand], '')as [AGLand]
							  ,ISNULL([VStatus], '')as [VStatus]
							  ,ISNULL([MStatus], '')as [MStatus]
							  ,[EntryBy]
							  ,[EntryDate]
							  ,ISNULL([VSLAGroup], '') as [VSLAGroup]
							  ,ISNULL([GPSLongSwap], '') as [GPSLongSwap]
							  ,ISNULL([RegNAddLookupCode], '') as  [RegNAddLookupCode]
							  ,ISNULL([LTp2Hectres],'') as [LTp2Hectres]
							   ,ISNULL([LT3mFoodStock],'')as [LT3mFoodStock]
							   ,ISNULL([NoMajorCommonLiveStock],'')as [NoMajorCommonLiveStock]
							   ,ISNULL([ReceiveNoFormalWages],'') as [ReceiveNoFormalWages]
							   ,ISNULL([NoIGA],'') as [NoIGA]
							   ,ISNULL([RelyPiecework],'') as [RelyPiecework]
							  ,ISNULL([HHHeadCat], '') as [HHHeadCat]
							  ,ISNULL([LT2yrsM], 0) as [LT2yrsM]
							  ,ISNULL([LT2yrsF], 0) as [LT2yrsF]
							  ,ISNULL([M2to5yrs], 0) as [M2to5yrs]
							  ,ISNULL([F2to5yrs], 0) as [F2to5yrs]
							  ,ISNULL([M6to12yrs], 0) as [M6to12yrs]
							  ,ISNULL([F6to12yrs], 0) as [F6to12yrs]
							  ,ISNULL([M13to17yrs], 0) as [M13to17yrs]
							  ,ISNULL([F13to17yrs], 0) as [F13to17yrs]
							  ,ISNULL([Orphn_LT18yrsM], 0) as [Orphn_LT18yrsM]
							  ,ISNULL([Orphn_LT18yrsF], 0) as [Orphn_LT18yrsF]
							  ,ISNULL([Adlt_18to59M], 0) as [Adlt_18to59M]
							  ,ISNULL([Adlt_18to59F], 0) as [Adlt_18to59F]
							  ,ISNULL([Eld_60pM], 0) as [Eld_60pM]
							  ,ISNULL([Eld_60pF], 0) as [Eld_60pF]
							  ,ISNULL([PLW], '') as [PLW]
							  ,ISNULL([ChronicallyIll], 0) as [ChronicallyIll]
							  ,ISNULL([LivingDeceasedContractEbola], 0) as [LivingDeceasedContractEbola]
							  ,ISNULL([ExtraChildBecauseEbola], 0) as [ExtraChildBecauseEbola]
							  ,ISNULL([ExtraElderlyPersonBecauseEbola], 0) as [ExtraElderlyPersonBecauseEbola]
							  ,ISNULL([ExtraChronicallyIllDisabledPersonBecauseEbola], 0) as [ExtraChronicallyIllDisabledPersonBecauseEbola]
							  ,ISNULL([BRFCntCattle], 0) as [BRFCntCattle]
							  ,ISNULL([BRFValCattle], 0) as [BRFValCattle]
							  ,ISNULL([AFTCntCattle], 0) as [AFTCntCattle]
							  ,ISNULL([AFTValCattle], 0) as [AFTValCattle]
							  ,ISNULL([BRFCntSheepGoats], 0) as [BRFCntSheepGoats]
							  ,ISNULL([BRFValSheepGoats], 0) as [BRFValSheepGoats]
							  ,ISNULL([AFTCntSheepGoats], 0) as [AFTCntSheepGoats]
							  ,ISNULL([AFTValSheepGoats], 0) as [AFTValSheepGoats]
							  ,ISNULL([BRFCntPoultry], 0) as [BRFCntPoultry]
							  ,ISNULL([BRFValPoultry], 0) as [BRFValPoultry]
							  ,ISNULL([AFTCntPoultry], 0) as [AFTCntPoultry]
							  ,ISNULL([AFTValPoultry], 0) as [AFTValPoultry]
							  ,ISNULL([BRFCntOther], 0) as [BRFCntOther]
							  ,ISNULL([BRFValOther], 0) as [BRFValOther]
							  ,ISNULL([AFTCntOther], 0) as [AFTCntOther]
							  ,ISNULL([AFTValOther], 0) as [AFTValOther]
							  ,ISNULL([BRFAcreCultivable], 0) as [BRFAcreCultivable]
							  ,ISNULL([BRFValCultivable], 0) as [BRFValCultivable]
							  ,ISNULL([AFTAcreCultivable], 0) as [AFTAcreCultivable]
							  ,ISNULL([AFTValCultivable], 0) as [AFTValCultivable]
							  ,ISNULL([BRFAcreNonCultivable], 0) as [BRFAcreNonCultivable]
							  ,ISNULL([BRFValNonCultivable], 0) as [BRFValNonCultivable]
							  ,ISNULL([AFTAcreNonCultivable], 0) as [AFTAcreNonCultivable]
							  ,ISNULL([AFTValNonCultivable], 0) as [AFTValNonCultivable]
							  ,ISNULL([BRFAcreOrchards], 0) as [BRFAcreOrchards]
							  ,ISNULL([BRFValOrchards], 0) as [BRFValOrchards]
							  ,ISNULL([AFTAcreOrchards], 0) as [AFTAcreOrchards]
							  ,ISNULL([AFTValOrchards], 0) as [AFTValOrchards]
							  ,ISNULL([BRFValCrop], 0) as [BRFValCrop]
							  ,ISNULL([AFTValCrop], 0) as [AFTValCrop]
							  ,ISNULL([BRFValLivestock], 0) as [BRFValLivestock]
							  ,ISNULL([AFTValLivestock], 0) as [AFTValLivestock]
							  ,ISNULL([BRFValSmallBusiness], 0) as [BRFValSmallBusiness]
							  ,ISNULL([AFTValSmallBusiness], 0) as [AFTValSmallBusiness]
							  ,ISNULL([BRFValEmployment], 0) as [BRFValEmployment]
							  ,ISNULL([AFTValEmployment], 0) as [AFTValEmployment]
							  ,ISNULL([BRFValRemittances], 0) as [BRFValRemittances]
							  ,ISNULL([AFTValRemittances], 0) as [AFTValRemittances]
							  ,ISNULL([BRFCntWageEnr], 0) as [BRFCntWageEnr]
							  ,ISNULL([AFTCntWageEnr], 0) as [AFTCntWageEnr]
							  ,ISNULL([WRank], '') as [WRank]

								FROM [dbo].[RegNHHTable]
								where [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]
								 in (
									(SELECT  DISTINCT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]

											FROM [dbo].[SrvTable]
											where
											[SrvTable].[OpMonthCode] in(Select SrvOpMonthCode from DistNPlanbasic

												where DisOpMonthCode in
															(Select OpMonthCode
																	from AdmOpMonthTable
																	where Status='A'
																	and OpCode='3'
																	and admcountryCode =" . $countryCode . ")

												and admcountryCode +FDPCode in (" . $s . ")
												)
											and [AdmCountryCode]+FDPCode in(" . $s . ") )

									)";
                        break;


                    case 3:
                        //echo("Case 3");
                        $rsql = "SELECT   [AdmCountryCode]
							  ,[LayR1ListCode]
							  ,[LayR2ListCode]
							  ,[LayR3ListCode]
							  ,[LayR4ListCode]
							  ,[HHID]
							  ,convert(varchar,[RegNDate],110) AS [RegNDate]
							  ,[HHHeadName]
							  ,ISNULL([HHHeadSex], '')as[HHHeadSex]
							  , ISNULL([HHSize], 0)as[HHSize]
                              , ISNULL([GPSLat], '')as[GPSLat]
							  ,ISNULL([GPSLong], '')as [GPSLong]
							  ,ISNULL([AGLand], '')as [AGLand]
							  ,ISNULL([VStatus], '')as [VStatus]
							  ,ISNULL([MStatus], '')as [MStatus]
							  ,[EntryBy]
							  ,[EntryDate]
							  ,ISNULL([VSLAGroup], '') as [VSLAGroup]
							  ,ISNULL([GPSLongSwap], '') as [GPSLongSwap]
							  ,ISNULL([RegNAddLookupCode], '') as  [RegNAddLookupCode]
							  ,ISNULL([LTp2Hectres],'') as [LTp2Hectres]
							   ,ISNULL([LT3mFoodStock],'')as [LT3mFoodStock]
							   ,ISNULL([NoMajorCommonLiveStock],'')as [NoMajorCommonLiveStock]
							   ,ISNULL([ReceiveNoFormalWages],'') as [ReceiveNoFormalWages]
							   ,ISNULL([NoIGA],'') as [NoIGA]
							   ,ISNULL([RelyPiecework],'') as [RelyPiecework]
							  ,ISNULL([HHHeadCat], '') as [HHHeadCat]
							  ,ISNULL([LT2yrsM], 0) as [LT2yrsM]
							  ,ISNULL([LT2yrsF], 0) as [LT2yrsF]
							  ,ISNULL([M2to5yrs], 0) as [M2to5yrs]
							  ,ISNULL([F2to5yrs], 0) as [F2to5yrs]
							  ,ISNULL([M6to12yrs], 0) as [M6to12yrs]
							  ,ISNULL([F6to12yrs], 0) as [F6to12yrs]
							  ,ISNULL([M13to17yrs], 0) as [M13to17yrs]
							  ,ISNULL([F13to17yrs], 0) as [F13to17yrs]
							  ,ISNULL([Orphn_LT18yrsM], 0) as [Orphn_LT18yrsM]
							  ,ISNULL([Orphn_LT18yrsF], 0) as [Orphn_LT18yrsF]
							  ,ISNULL([Adlt_18to59M], 0) as [Adlt_18to59M]
							  ,ISNULL([Adlt_18to59F], 0) as [Adlt_18to59F]
							  ,ISNULL([Eld_60pM], 0) as [Eld_60pM]
							  ,ISNULL([Eld_60pF], 0) as [Eld_60pF]
							  ,ISNULL([PLW], '') as [PLW]
							  ,ISNULL([ChronicallyIll], 0) as [ChronicallyIll]
							  ,ISNULL([LivingDeceasedContractEbola], 0) as [LivingDeceasedContractEbola]
							  ,ISNULL([ExtraChildBecauseEbola], 0) as [ExtraChildBecauseEbola]
							  ,ISNULL([ExtraElderlyPersonBecauseEbola], 0) as [ExtraElderlyPersonBecauseEbola]
							  ,ISNULL([ExtraChronicallyIllDisabledPersonBecauseEbola], 0) as [ExtraChronicallyIllDisabledPersonBecauseEbola]
							  ,ISNULL([BRFCntCattle], 0) as [BRFCntCattle]
							  ,ISNULL([BRFValCattle], 0) as [BRFValCattle]
							  ,ISNULL([AFTCntCattle], 0) as [AFTCntCattle]
							  ,ISNULL([AFTValCattle], 0) as [AFTValCattle]
							  ,ISNULL([BRFCntSheepGoats], 0) as [BRFCntSheepGoats]
							  ,ISNULL([BRFValSheepGoats], 0) as [BRFValSheepGoats]
							  ,ISNULL([AFTCntSheepGoats], 0) as [AFTCntSheepGoats]
							  ,ISNULL([AFTValSheepGoats], 0) as [AFTValSheepGoats]
							  ,ISNULL([BRFCntPoultry], 0) as [BRFCntPoultry]
							  ,ISNULL([BRFValPoultry], 0) as [BRFValPoultry]
							  ,ISNULL([AFTCntPoultry], 0) as [AFTCntPoultry]
							  ,ISNULL([AFTValPoultry], 0) as [AFTValPoultry]
							  ,ISNULL([BRFCntOther], 0) as [BRFCntOther]
							  ,ISNULL([BRFValOther], 0) as [BRFValOther]
							  ,ISNULL([AFTCntOther], 0) as [AFTCntOther]
							  ,ISNULL([AFTValOther], 0) as [AFTValOther]
							  ,ISNULL([BRFAcreCultivable], 0) as [BRFAcreCultivable]
							  ,ISNULL([BRFValCultivable], 0) as [BRFValCultivable]
							  ,ISNULL([AFTAcreCultivable], 0) as [AFTAcreCultivable]
							  ,ISNULL([AFTValCultivable], 0) as [AFTValCultivable]
							  ,ISNULL([BRFAcreNonCultivable], 0) as [BRFAcreNonCultivable]
							  ,ISNULL([BRFValNonCultivable], 0) as [BRFValNonCultivable]
							  ,ISNULL([AFTAcreNonCultivable], 0) as [AFTAcreNonCultivable]
							  ,ISNULL([AFTValNonCultivable], 0) as [AFTValNonCultivable]
							  ,ISNULL([BRFAcreOrchards], 0) as [BRFAcreOrchards]
							  ,ISNULL([BRFValOrchards], 0) as [BRFValOrchards]
							  ,ISNULL([AFTAcreOrchards], 0) as [AFTAcreOrchards]
							  ,ISNULL([AFTValOrchards], 0) as [AFTValOrchards]
							  ,ISNULL([BRFValCrop], 0) as [BRFValCrop]
							  ,ISNULL([AFTValCrop], 0) as [AFTValCrop]
							  ,ISNULL([BRFValLivestock], 0) as [BRFValLivestock]
							  ,ISNULL([AFTValLivestock], 0) as [AFTValLivestock]
							  ,ISNULL([BRFValSmallBusiness], 0) as [BRFValSmallBusiness]
							  ,ISNULL([AFTValSmallBusiness], 0) as [AFTValSmallBusiness]
							  ,ISNULL([BRFValEmployment], 0) as [BRFValEmployment]
							  ,ISNULL([AFTValEmployment], 0) as [AFTValEmployment]
							  ,ISNULL([BRFValRemittances], 0) as [BRFValRemittances]
							  ,ISNULL([AFTValRemittances], 0) as [AFTValRemittances]
							  ,ISNULL([BRFCntWageEnr], 0) as [BRFCntWageEnr]
							  ,ISNULL([AFTCntWageEnr], 0) as [AFTCntWageEnr]
							  ,ISNULL([WRank], '') as [WRank]

								FROM [dbo].[RegNHHTable]
								where [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]

								IN (SELECT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
															+[HHID]FROM [dbo].[RegNMemProgGrp]
													WHERE [AdmCountryCode]+ [AdmDonorCode]+[AdmAwardCode]+[ProgCode]+[GrpCode]
															IN(SELECT Distinct AdmCountryCode+AdmDonorCode+AdmAwardCode+AdmProgCode+GrpCode
													FROM [dbo].[CommunityGroup] WHERE AdmCountryCode+SrvCenterCode IN (" . $s . ") ))

					OR  			[AdmCountryCode]
										+[LayR1ListCode]
										+[LayR2ListCode]
										+[LayR3ListCode]
										+[LayR4ListCode]
										+[HHID]  IN (

					SELECT [AdmCountryCode]	+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]

										FROM [dbo].[SrvTable]
										where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
												where [AdmCountryCode]='" . $countryCode . "' and [OpCode]=2  and  [Status]='A'
													ORDER BY OpMonthCode DESC)

										and [SrvStatus]='O'
										and [AdmCountryCode]+[SrvCenterCode] IN (" . $s . "))

								";
                        //echo($rsql);
                        break;
                    case 4:
                        $rsql = "SELECT   [AdmCountryCode]
							  ,[LayR1ListCode]
							  ,[LayR2ListCode]
							  ,[LayR3ListCode]
							  ,[LayR4ListCode]
							  ,[HHID]
							  ,convert(varchar,[RegNDate],110) AS [RegNDate]
							  ,[HHHeadName]
							  ,ISNULL([HHHeadSex], '')as[HHHeadSex]
							  , ISNULL([HHSize], 0)as[HHSize]
                              , ISNULL([GPSLat], '')as[GPSLat]
							  ,ISNULL([GPSLong], '')as [GPSLong]
							  ,ISNULL([AGLand], '')as [AGLand]
							  ,ISNULL([VStatus], '')as [VStatus]
							  ,ISNULL([MStatus], '')as [MStatus]
							  ,[EntryBy]
							  ,[EntryDate]
							  ,ISNULL([VSLAGroup], '') as [VSLAGroup]
							  ,ISNULL([GPSLongSwap], '') as [GPSLongSwap]
							  ,ISNULL([RegNAddLookupCode], '') as  [RegNAddLookupCode]
							  ,ISNULL([LTp2Hectres],'') as [LTp2Hectres]
							   ,ISNULL([LT3mFoodStock],'')as [LT3mFoodStock]
							   ,ISNULL([NoMajorCommonLiveStock],'')as [NoMajorCommonLiveStock]
							   ,ISNULL([ReceiveNoFormalWages],'') as [ReceiveNoFormalWages]
							   ,ISNULL([NoIGA],'') as [NoIGA]
							   ,ISNULL([RelyPiecework],'') as [RelyPiecework]
							  ,ISNULL([HHHeadCat], '') as [HHHeadCat]
							  ,ISNULL([LT2yrsM], 0) as [LT2yrsM]
							  ,ISNULL([LT2yrsF], 0) as [LT2yrsF]
							  ,ISNULL([M2to5yrs], 0) as [M2to5yrs]
							  ,ISNULL([F2to5yrs], 0) as [F2to5yrs]
							  ,ISNULL([M6to12yrs], 0) as [M6to12yrs]
							  ,ISNULL([F6to12yrs], 0) as [F6to12yrs]
							  ,ISNULL([M13to17yrs], 0) as [M13to17yrs]
							  ,ISNULL([F13to17yrs], 0) as [F13to17yrs]
							  ,ISNULL([Orphn_LT18yrsM], 0) as [Orphn_LT18yrsM]
							  ,ISNULL([Orphn_LT18yrsF], 0) as [Orphn_LT18yrsF]
							  ,ISNULL([Adlt_18to59M], 0) as [Adlt_18to59M]
							  ,ISNULL([Adlt_18to59F], 0) as [Adlt_18to59F]
							  ,ISNULL([Eld_60pM], 0) as [Eld_60pM]
							  ,ISNULL([Eld_60pF], 0) as [Eld_60pF]
							  ,ISNULL([PLW], '') as [PLW]
							  ,ISNULL([ChronicallyIll], 0) as [ChronicallyIll]
							  ,ISNULL([LivingDeceasedContractEbola], 0) as [LivingDeceasedContractEbola]
							  ,ISNULL([ExtraChildBecauseEbola], 0) as [ExtraChildBecauseEbola]
							  ,ISNULL([ExtraElderlyPersonBecauseEbola], 0) as [ExtraElderlyPersonBecauseEbola]
							  ,ISNULL([ExtraChronicallyIllDisabledPersonBecauseEbola], 0) as [ExtraChronicallyIllDisabledPersonBecauseEbola]
							  ,ISNULL([BRFCntCattle], 0) as [BRFCntCattle]
							  ,ISNULL([BRFValCattle], 0) as [BRFValCattle]
							  ,ISNULL([AFTCntCattle], 0) as [AFTCntCattle]
							  ,ISNULL([AFTValCattle], 0) as [AFTValCattle]
							  ,ISNULL([BRFCntSheepGoats], 0) as [BRFCntSheepGoats]
							  ,ISNULL([BRFValSheepGoats], 0) as [BRFValSheepGoats]
							  ,ISNULL([AFTCntSheepGoats], 0) as [AFTCntSheepGoats]
							  ,ISNULL([AFTValSheepGoats], 0) as [AFTValSheepGoats]
							  ,ISNULL([BRFCntPoultry], 0) as [BRFCntPoultry]
							  ,ISNULL([BRFValPoultry], 0) as [BRFValPoultry]
							  ,ISNULL([AFTCntPoultry], 0) as [AFTCntPoultry]
							  ,ISNULL([AFTValPoultry], 0) as [AFTValPoultry]
							  ,ISNULL([BRFCntOther], 0) as [BRFCntOther]
							  ,ISNULL([BRFValOther], 0) as [BRFValOther]
							  ,ISNULL([AFTCntOther], 0) as [AFTCntOther]
							  ,ISNULL([AFTValOther], 0) as [AFTValOther]
							  ,ISNULL([BRFAcreCultivable], 0) as [BRFAcreCultivable]
							  ,ISNULL([BRFValCultivable], 0) as [BRFValCultivable]
							  ,ISNULL([AFTAcreCultivable], 0) as [AFTAcreCultivable]
							  ,ISNULL([AFTValCultivable], 0) as [AFTValCultivable]
							  ,ISNULL([BRFAcreNonCultivable], 0) as [BRFAcreNonCultivable]
							  ,ISNULL([BRFValNonCultivable], 0) as [BRFValNonCultivable]
							  ,ISNULL([AFTAcreNonCultivable], 0) as [AFTAcreNonCultivable]
							  ,ISNULL([AFTValNonCultivable], 0) as [AFTValNonCultivable]
							  ,ISNULL([BRFAcreOrchards], 0) as [BRFAcreOrchards]
							  ,ISNULL([BRFValOrchards], 0) as [BRFValOrchards]
							  ,ISNULL([AFTAcreOrchards], 0) as [AFTAcreOrchards]
							  ,ISNULL([AFTValOrchards], 0) as [AFTValOrchards]
							  ,ISNULL([BRFValCrop], 0) as [BRFValCrop]
							  ,ISNULL([AFTValCrop], 0) as [AFTValCrop]
							  ,ISNULL([BRFValLivestock], 0) as [BRFValLivestock]
							  ,ISNULL([AFTValLivestock], 0) as [AFTValLivestock]
							  ,ISNULL([BRFValSmallBusiness], 0) as [BRFValSmallBusiness]
							  ,ISNULL([AFTValSmallBusiness], 0) as [AFTValSmallBusiness]
							  ,ISNULL([BRFValEmployment], 0) as [BRFValEmployment]
							  ,ISNULL([AFTValEmployment], 0) as [AFTValEmployment]
							  ,ISNULL([BRFValRemittances], 0) as [BRFValRemittances]
							  ,ISNULL([AFTValRemittances], 0) as [AFTValRemittances]
							  ,ISNULL([BRFCntWageEnr], 0) as [BRFCntWageEnr]
							  ,ISNULL([AFTCntWageEnr], 0) as [AFTCntWageEnr]
							  ,ISNULL([WRank], '') as [WRank]

								FROM [dbo].[RegNHHTable]
								where [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]+[HHID]
								 in (0)";
                        break;
						case 5: 
						 $rsql = "SELECT   [RegNHHTable].[AdmCountryCode]
							  ,[RegNHHTable].[LayR1ListCode]
							  ,[RegNHHTable].[LayR2ListCode]
							  ,[RegNHHTable].[LayR3ListCode]
							  ,[RegNHHTable].[LayR4ListCode]
							  ,[RegNHHTable].[HHID]
							  , convert(varchar,[RegNHHTable].RegNDate,110) AS [RegNDate]
							  ,[HHHeadName]
							  ,ISNULL([HHHeadSex], '')as[HHHeadSex]
							  , ISNULL([HHSize], 0)as[HHSize]
                              , ISNULL([GPSLat], '')as[GPSLat]
							  ,ISNULL([GPSLong], '')as [GPSLong]
							  ,ISNULL([AGLand], '')as [AGLand]
							  ,ISNULL([VStatus], '')as [VStatus]
							  ,ISNULL([MStatus], '')as [MStatus]
							  ,[RegNHHTable].[EntryBy]
							  ,[RegNHHTable].[EntryDate]
							  ,ISNULL([VSLAGroup], '') as [VSLAGroup]
							  ,ISNULL([GPSLongSwap], '') as [GPSLongSwap]
							  ,ISNULL([RegNAddLookupCode], '') as  [RegNAddLookupCode]
							   ,ISNULL([LTp2Hectres],'') as [LTp2Hectres]
							   ,ISNULL([LT3mFoodStock],'')as [LT3mFoodStock]
							   ,ISNULL([NoMajorCommonLiveStock],'')as [NoMajorCommonLiveStock]
							   ,ISNULL([ReceiveNoFormalWages],'') as [ReceiveNoFormalWages]
							   ,ISNULL([NoIGA],'') as [NoIGA]
							   ,ISNULL([RelyPiecework],'') as [RelyPiecework]
							  ,ISNULL([HHHeadCat], '') as [HHHeadCat]
							  ,ISNULL([LT2yrsM], 0) as [LT2yrsM]
							  ,ISNULL([LT2yrsF], 0) as [LT2yrsF]
							  ,ISNULL([M2to5yrs], 0) as [M2to5yrs]
							  ,ISNULL([F2to5yrs], 0) as [F2to5yrs]
							  ,ISNULL([M6to12yrs], 0) as [M6to12yrs]
							  ,ISNULL([F6to12yrs], 0) as [F6to12yrs]
							  ,ISNULL([M13to17yrs], 0) as [M13to17yrs]
							  ,ISNULL([F13to17yrs], 0) as [F13to17yrs]
							  ,ISNULL([Orphn_LT18yrsM], 0) as [Orphn_LT18yrsM]
							  ,ISNULL([Orphn_LT18yrsF], 0) as [Orphn_LT18yrsF]
							  ,ISNULL([Adlt_18to59M], 0) as [Adlt_18to59M]
							  ,ISNULL([Adlt_18to59F], 0) as [Adlt_18to59F]
							  ,ISNULL([Eld_60pM], 0) as [Eld_60pM]
							  ,ISNULL([Eld_60pF], 0) as [Eld_60pF]
							  ,ISNULL([PLW], '') as [PLW]
							  ,ISNULL([ChronicallyIll], 0) as [ChronicallyIll]
							  ,ISNULL([LivingDeceasedContractEbola], 0) as [LivingDeceasedContractEbola]
							  ,ISNULL([ExtraChildBecauseEbola], 0) as [ExtraChildBecauseEbola]
							  ,ISNULL([ExtraElderlyPersonBecauseEbola], 0) as [ExtraElderlyPersonBecauseEbola]
							  ,ISNULL([ExtraChronicallyIllDisabledPersonBecauseEbola], 0) as [ExtraChronicallyIllDisabledPersonBecauseEbola]
							  ,ISNULL([BRFCntCattle], 0) as [BRFCntCattle]
							  ,ISNULL([BRFValCattle], 0) as [BRFValCattle]
							  ,ISNULL([AFTCntCattle], 0) as [AFTCntCattle]
							  ,ISNULL([AFTValCattle], 0) as [AFTValCattle]
							  ,ISNULL([BRFCntSheepGoats], 0) as [BRFCntSheepGoats]
							  ,ISNULL([BRFValSheepGoats], 0) as [BRFValSheepGoats]
							  ,ISNULL([AFTCntSheepGoats], 0) as [AFTCntSheepGoats]
							  ,ISNULL([AFTValSheepGoats], 0) as [AFTValSheepGoats]
							  ,ISNULL([BRFCntPoultry], 0) as [BRFCntPoultry]
							  ,ISNULL([BRFValPoultry], 0) as [BRFValPoultry]
							  ,ISNULL([AFTCntPoultry], 0) as [AFTCntPoultry]
							  ,ISNULL([AFTValPoultry], 0) as [AFTValPoultry]
							  ,ISNULL([BRFCntOther], 0) as [BRFCntOther]
							  ,ISNULL([BRFValOther], 0) as [BRFValOther]
							  ,ISNULL([AFTCntOther], 0) as [AFTCntOther]
							  ,ISNULL([AFTValOther], 0) as [AFTValOther]
							  ,ISNULL([BRFAcreCultivable], 0) as [BRFAcreCultivable]
							  ,ISNULL([BRFValCultivable], 0) as [BRFValCultivable]
							  ,ISNULL([AFTAcreCultivable], 0) as [AFTAcreCultivable]
							  ,ISNULL([AFTValCultivable], 0) as [AFTValCultivable]
							  ,ISNULL([BRFAcreNonCultivable], 0) as [BRFAcreNonCultivable]
							  ,ISNULL([BRFValNonCultivable], 0) as [BRFValNonCultivable]
							  ,ISNULL([AFTAcreNonCultivable], 0) as [AFTAcreNonCultivable]
							  ,ISNULL([AFTValNonCultivable], 0) as [AFTValNonCultivable]
							  ,ISNULL([BRFAcreOrchards], 0) as [BRFAcreOrchards]
							  ,ISNULL([BRFValOrchards], 0) as [BRFValOrchards]
							  ,ISNULL([AFTAcreOrchards], 0) as [AFTAcreOrchards]
							  ,ISNULL([AFTValOrchards], 0) as [AFTValOrchards]
							  ,ISNULL([BRFValCrop], 0) as [BRFValCrop]
							  ,ISNULL([AFTValCrop], 0) as [AFTValCrop]
							  ,ISNULL([BRFValLivestock], 0) as [BRFValLivestock]
							  ,ISNULL([AFTValLivestock], 0) as [AFTValLivestock]
							  ,ISNULL([BRFValSmallBusiness], 0) as [BRFValSmallBusiness]
							  ,ISNULL([AFTValSmallBusiness], 0) as [AFTValSmallBusiness]
							  ,ISNULL([BRFValEmployment], 0) as [BRFValEmployment]
							  ,ISNULL([AFTValEmployment], 0) as [AFTValEmployment]
							  ,ISNULL([BRFValRemittances], 0) as [BRFValRemittances]
							  ,ISNULL([AFTValRemittances], 0) as [AFTValRemittances]
							  ,ISNULL([BRFCntWageEnr], 0) as [BRFCntWageEnr]
							  ,ISNULL([AFTCntWageEnr], 0) as [AFTCntWageEnr]
							  ,ISNULL([WRank], '') as [WRank]

								FROM [dbo].[RegNHHTable]
								
									inner JOIN  [dbo].[RegNAssignProgSrv]ON
									[RegNHHTable].AdmCountryCode= [RegNAssignProgSrv].AdmCountryCode
									AND [RegNHHTable].LayR1ListCode= [RegNAssignProgSrv].LayR1ListCode
									AND [RegNHHTable].LayR2ListCode= [RegNAssignProgSrv].LayR2ListCode
									AND [RegNHHTable].LayR3ListCode= [RegNAssignProgSrv].LayR3ListCode
									AND [RegNHHTable].LayR4ListCode= [RegNAssignProgSrv].LayR4ListCode
									AND [RegNHHTable].HHID= [RegNAssignProgSrv].HHID
									

					where

									[RegNAssignProgSrv].AdmCountryCode+[RegNAssignProgSrv].LayR1ListCode+[RegNAssignProgSrv].LayR2ListCode+[RegNAssignProgSrv].LayR3ListCode+[RegNAssignProgSrv].LayR4ListCode in (" . $s . ")";
						
						break;
                }


                $this->query($rsql);
                $registration = $this->resultset();

                if ($registration != false) {
                    foreach ($registration as $key => $value) {
                        $data['registration'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['registration'][$key]['DistrictName'] = $value['LayR1ListCode'];
                        $data['registration'][$key]['UpazillaName'] = $value['LayR2ListCode'];
                        $data['registration'][$key]['UnitName'] = $value['LayR3ListCode'];
                        $data['registration'][$key]['VillageName'] = $value['LayR4ListCode'];
                        $data['registration'][$key]['RegistrationID'] = $value['HHID'];
                        $data['registration'][$key]['RegNDate'] = $value['RegNDate'];
                        $data['registration'][$key]['PersonName'] = $value['HHHeadName'];
                        $data['registration'][$key]['SEX'] = $value['HHHeadSex'];
                        $data['registration'][$key]['HHSize'] = $value['HHSize'];
                        $data['registration'][$key]['Latitude'] = $value['GPSLat'];
                        $data['registration'][$key]['Longitude'] = $value['GPSLong'];
                        $data['registration'][$key]['AGLand'] = $value['AGLand'];
                        $data['registration'][$key]['VStatus'] = $value['VStatus'];
                        $data['registration'][$key]['MStatus'] = $value['MStatus'];
                        $data['registration'][$key]['EntryBy'] = $value['EntryBy'];
                        $data['registration'][$key]['EntryDate'] = $value['EntryDate'];
                        $data['registration'][$key]['VSLAGroup'] = $value['VSLAGroup'];
                        $data['registration'][$key]['GPSLongSwap'] = $value['GPSLongSwap'];
                        $data['registration'][$key]['RegNAddLookupCode'] = $value['RegNAddLookupCode'];

                        $data['registration'][$key]['LTp2Hectres'] = $value['LTp2Hectres'];
                        $data['registration'][$key]['LT3mFoodStock'] = $value['LT3mFoodStock'];
                        $data['registration'][$key]['NoMajorCommonLiveStock'] = $value['NoMajorCommonLiveStock'];
                        $data['registration'][$key]['ReceiveNoFormalWages'] = $value['ReceiveNoFormalWages'];
                        $data['registration'][$key]['NoIGA'] = $value['NoIGA'];
                        $data['registration'][$key]['RelyPiecework'] = $value['RelyPiecework'];

                        $data['registration'][$key]['HHHeadCat'] = $value['HHHeadCat'];
                        $data['registration'][$key]['LT2yrsM'] = $value['LT2yrsM'];
                        $data['registration'][$key]['LT2yrsF'] = $value['LT2yrsF'];
                        $data['registration'][$key]['M2to5yrs'] = $value['M2to5yrs'];
                        $data['registration'][$key]['F2to5yrs'] = $value['F2to5yrs'];
                        $data['registration'][$key]['M6to12yrs'] = $value['M6to12yrs'];
                        $data['registration'][$key]['F6to12yrs'] = $value['F6to12yrs'];
                        $data['registration'][$key]['M13to17yrs'] = $value['M13to17yrs'];
                        $data['registration'][$key]['F13to17yrs'] = $value['F13to17yrs'];
                        $data['registration'][$key]['Orphn_LT18yrsM'] = $value['Orphn_LT18yrsM'];
                        $data['registration'][$key]['Orphn_LT18yrsF'] = $value['Orphn_LT18yrsF'];
                        $data['registration'][$key]['Adlt_18to59M'] = $value['Adlt_18to59M'];
                        $data['registration'][$key]['Adlt_18to59F'] = $value['Adlt_18to59F'];
                        $data['registration'][$key]['Eld_60pM'] = $value['Eld_60pM'];
                        $data['registration'][$key]['Eld_60pF'] = $value['Eld_60pF'];
                        $data['registration'][$key]['PLW'] = $value['PLW'];
                        $data['registration'][$key]['ChronicallyIll'] = $value['ChronicallyIll'];
                        $data['registration'][$key]['LivingDeceasedContractEbola'] = $value['LivingDeceasedContractEbola'];
                        $data['registration'][$key]['ExtraChildBecauseEbola'] = $value['ExtraChildBecauseEbola'];
                        $data['registration'][$key]['ExtraElderlyPersonBecauseEbola'] = $value['ExtraElderlyPersonBecauseEbola'];
                        $data['registration'][$key]['ExtraChronicallyIllDisabledPersonBecauseEbola'] = $value['ExtraChronicallyIllDisabledPersonBecauseEbola'];
                        $data['registration'][$key]['BRFCntCattle'] = $value['BRFCntCattle'];
                        $data['registration'][$key]['BRFValCattle'] = $value['BRFValCattle'];
                        $data['registration'][$key]['AFTCntCattle'] = $value['AFTCntCattle'];
                        $data['registration'][$key]['AFTValCattle'] = $value['AFTValCattle'];
                        $data['registration'][$key]['BRFCntSheepGoats'] = $value['BRFCntSheepGoats'];
                        $data['registration'][$key]['BRFValSheepGoats'] = $value['BRFValSheepGoats'];
                        $data['registration'][$key]['AFTCntSheepGoats'] = $value['AFTCntSheepGoats'];
                        $data['registration'][$key]['AFTValSheepGoats'] = $value['AFTValSheepGoats'];
                        $data['registration'][$key]['BRFCntPoultry'] = $value['BRFCntPoultry'];
                        $data['registration'][$key]['BRFValPoultry'] = $value['BRFValPoultry'];
                        $data['registration'][$key]['AFTCntPoultry'] = $value['AFTCntPoultry'];
                        $data['registration'][$key]['AFTValPoultry'] = $value['AFTValPoultry'];
                        $data['registration'][$key]['BRFCntOther'] = $value['BRFCntOther'];
                        $data['registration'][$key]['BRFValOther'] = $value['BRFValOther'];
                        $data['registration'][$key]['AFTCntOther'] = $value['AFTCntOther'];
                        $data['registration'][$key]['AFTValOther'] = $value['AFTValOther'];
                        $data['registration'][$key]['BRFAcreCultivable'] = $value['BRFAcreCultivable'];
                        $data['registration'][$key]['BRFValCultivable'] = $value['BRFValCultivable'];
                        $data['registration'][$key]['AFTAcreCultivable'] = $value['AFTAcreCultivable'];
                        $data['registration'][$key]['AFTValCultivable'] = $value['AFTValCultivable'];
                        $data['registration'][$key]['BRFAcreNonCultivable'] = $value['BRFAcreNonCultivable'];
                        $data['registration'][$key]['BRFValNonCultivable'] = $value['BRFValNonCultivable'];
                        $data['registration'][$key]['AFTAcreNonCultivable'] = $value['AFTAcreNonCultivable'];
                        $data['registration'][$key]['AFTValNonCultivable'] = $value['AFTValNonCultivable'];
                        $data['registration'][$key]['BRFAcreOrchards'] = $value['BRFAcreOrchards'];
                        $data['registration'][$key]['BRFValOrchards'] = $value['BRFValOrchards'];
                        $data['registration'][$key]['AFTAcreOrchards'] = $value['AFTAcreOrchards'];
                        $data['registration'][$key]['AFTValOrchards'] = $value['AFTValOrchards'];
                        $data['registration'][$key]['BRFValCrop'] = $value['BRFValCrop'];
                        $data['registration'][$key]['AFTValCrop'] = $value['AFTValCrop'];
                        $data['registration'][$key]['BRFValLivestock'] = $value['BRFValLivestock'];
                        $data['registration'][$key]['AFTValLivestock'] = $value['AFTValLivestock'];
                        $data['registration'][$key]['BRFValSmallBusiness'] = $value['BRFValSmallBusiness'];
                        $data['registration'][$key]['AFTValSmallBusiness'] = $value['AFTValSmallBusiness'];
                        $data['registration'][$key]['BRFValEmployment'] = $value['BRFValEmployment'];
                        $data['registration'][$key]['AFTValEmployment'] = $value['AFTValEmployment'];
                        $data['registration'][$key]['BRFValRemittances'] = $value['BRFValRemittances'];
                        $data['registration'][$key]['AFTValRemittances'] = $value['AFTValRemittances'];
                        $data['registration'][$key]['BRFCntWageEnr'] = $value['BRFCntWageEnr'];
                        $data['registration'][$key]['AFTCntWageEnr'] = $value['AFTCntWageEnr'];
                        $data['registration'][$key]['WRank'] = $value['WRank'];
                    } // end of for each
                } // end of if data set exits
                // end registration
            }// end of if (josn) exite
        } else {
            return false;
        }
        return $data;
    }

    // get_reference data

    public function get_reference_data($user, $pass, $lay_r_code_j, $operation_mode) {
        //public function get_reference_data( $user, $pass)
        $data = array();
        // reference table
        $data["countries"] = array();
        $data["valid_dates"] = array();
        $data["layer_labels"] = array();
        $data['district'] = array();
        $data['upazilla'] = array();
        $data['unit'] = array();
        $data['village'] = array();
        $data['relation'] = array();
        $data["gps_group"] = array();
        $data["gps_subgroup"] = array();
        $data["gps_location"] = array();
        $data["adm_countryaward"] = array();
        $data["adm_donor"] = array();
        $data["adm_program_master"] = array();
        $data["adm_service_master"] = array();
        $data["adm_op_month"] = array();
        $data["adm_country_program"] = array();
        $data["dob_service_center"] = array();
        $data["card_print_reason"] = array();
        $data["staff_access_info"] = array();
        $data["lb_reg_hh_category"] = array();


        $data["reg_lup_graduation"] = array();
        $data["report_template"] = array();
        $data["staff_fdp_access"] = array();
        $data["fdp_master"] = array();
        $data["fdp_master"] = array();
        $data["distribution_table"] = array();
        $data["distribution_ext_table"] = array();

        $data["lup_srv_option_list"] = array();
        $data["service_table"] = array();
        $data["service_exe_table"] = array();


        // new added

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

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            // getting Layer Label data
            $lsql = '
        				SELECT [AdmCountryCode]
					      ,[GeoLayRCode]
					      ,[GeoLayRName]
					  	FROM [dbo].[GeoLayRMaster]';

            $this->query($lsql);

            $labels = $this->resultset();

            if ($labels != false) {
                foreach ($labels as $key => $label) {
                    $data['layer_labels'][$key]['AdmCountryCode'] = $label['AdmCountryCode'];
                    $data['layer_labels'][$key]['GeoLayRCode'] = $label['GeoLayRCode'];
                    $data['layer_labels'][$key]['GeoLayRName'] = $label['GeoLayRName'];
                }
            }


            // getting valid registration dates
            $date_sql = "
        			SELECT [AdmCountryCode]
				      ,[StartDate]
				      ,[EndDate]
				  FROM [dbo].[AdmOpMonthTable]
				  WHERE [OpCode]=1 AND [Status]='A'";

            $this->query($date_sql);

            $valid_dates = $this->resultset();

            if ($valid_dates != false) {
                foreach ($valid_dates as $key => $date) {
                    $data['valid_dates'][$key]['AdmCountryCode'] = $date['AdmCountryCode'];
                    $data['valid_dates'][$key]['StartDate'] = $date['StartDate'];
                    $data['valid_dates'][$key]['EndDate'] = $date['EndDate'];
                }
            }


            // getting all permitted Lay1, Lay2, Lay3 and Lay4 from StaffGeoInfoAccess
            $psql = '
        			SELECT DISTINCT sa.[LayRListCode] AS list_code
                	FROM [dbo].[StaffMaster] AS u
					JOIN [dbo].[StaffGeoInfoAccess] AS sa
						ON u.[StfCode] = sa.[StfCode]
               		WHERE u.[StfCode] = :user_id and( btnNew =1 or btnSave=1 or btnDel=1)';


            $this->query($psql);
            $this->bind(':user_id', $staff_code);

            $permission = $this->resultset();

            // e.g. 01010101, 01010102, 01010201
            $district = "";
            $upazilla = "";
            $unit = "";
            $village = "";
            $village1 = "";
            $upazilla1 = "";
            $unit1 = "";
            if ($permission != false) {

                //code by Mr
                /* fetching village code , district, upazilla,union for only the
                  user access village name,upazilla,union,district; */
                foreach ($permission as $key => $value) {
                    $v1 = $value['list_code'];
                    if (strpos($village1, $v1) === false)
                        $village1 .= "'" . $v1 . "',";
                    $t1 = substr($value['list_code'], 0, 4);
                    if (strpos($upazilla1, $t1) === false)
                        $upazilla1 .= "'" . $t1 . "',";
                    $u1 = substr($value['list_code'], 0, 6);
                    if (strpos($unit1, $u1) === false)
                        $unit1 .= "'" . $u1 . "',";
                }
                $village1 = substr($village1, 0, -1);
                $upazilla1 = substr($upazilla1, 0, -1);
                $unit1 = substr($unit1, 0, -1);

                //end mr code

                foreach ($permission as $key => $value) {

                    $d = substr($value['list_code'], 0, 2);
                    if (strpos($district, $d) === false)
                        $district .= "'" . $d . "',";

                    $t = substr($value['list_code'], 2, 2);
                    if (strpos($upazilla, $t) === false)
                        $upazilla .= "'" . $t . "',";

                    $u = substr($value['list_code'], 4, 2);
                    if (strpos($unit, $u) === false)
                        $unit .= "'" . $u . "',";

                    $v = substr($value['list_code'], 6, 2);
                    if (strpos($village, $v) === false)
                        $village .= "'" . $v . "',";
                } // end permission

                $district = substr($district, 0, -1);
                $upazilla = substr($upazilla, 0, -1);
                $unit = substr($unit, 0, -1);
                $village = substr($village, 0, -1);


                // District
                if ($district != false) {
                    $dsql = "SELECT DISTINCT [AdmCountryCode],[GeoLayRCode],[LayRListCode], [LayRListName]
        					FROM [dbo].[GeoLayR1List]
        					WHERE
							[AdmCountryCode] IN (Select AdmCountryCode from [dbo].[StaffAssignCountry] where StfCode=:user_id and [StatusAssign]=1)
							AND
							[LayRListCode] IN ($district)

        			";

                    $this->query($dsql);
                    $this->bind(':user_id', $staff_code);
                    $district = $this->resultset();

                    foreach ($district as $key => $value) {
                        $data['district'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['district'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['district'][$key]['LayRListCode'] = $value['LayRListCode'];
                        $data['district'][$key]['LayRListName'] = $value['LayRListName'];
                    }
                } // end district
                // Upazilla
                if ($upazilla != false) {

                    //facthing only access upazilla name
                    $tsql = "SELECT DISTINCT [AdmCountryCode],[GeoLayRCode],[LayR1ListCode],[LayR2ListCode], [LayR2ListName]
        					FROM [dbo].[GeoLayR2List]
        					WHERE [AdmCountryCode] IN (Select AdmCountryCode from [dbo].[StaffAssignCountry] where StfCode=:user_id and [StatusAssign]=1)
							AND
							[LayR1ListCode]+[LayR2ListCode] IN ($upazilla1)

        			";

                    $this->query($tsql);
                    $this->bind(':user_id', $staff_code);
                    $upazilla = $this->resultset();

                    foreach ($upazilla as $key => $value) {
                        $data['upazilla'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['upazilla'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['upazilla'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['upazilla'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['upazilla'][$key]['LayR2ListName'] = $value['LayR2ListName'];
                    }
                } // end upazilla
                // Unit
                if ($unit != false) {


                    //facthing only accss unit
                    $usql = "SELECT DISTINCT [AdmCountryCode],[GeoLayRCode],[LayR1ListCode],[LayR2ListCode], [LayR3ListCode], [LayR3ListName]
        					FROM [dbo].[GeoLayR3List]
        					WHERE [AdmCountryCode] IN (Select AdmCountryCode from [dbo].[StaffAssignCountry] where StfCode=:user_id and [StatusAssign]=1)
							AND
							[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode] IN ($unit1)

        			";

                    $this->query($usql);
                    $this->bind(':user_id', $staff_code);
                    $unit = $this->resultset();

                    foreach ($unit as $key => $value) {
                        $data['unit'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['unit'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['unit'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['unit'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['unit'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['unit'][$key]['LayR3ListName'] = $value['LayR3ListName'];
                    }
                } // end unit
                // Village
                if ($village != false) {

                    //fatching only access village name
                    $vsql = "SELECT DISTINCT vill.[AdmCountryCode]
									,vill.[GeoLayRCode]
									,vill.[LayR1ListCode]
									,vill.[LayR2ListCode]
									,vill.[LayR3ListCode]
									,vill.[LayR4ListCode]
									,vill.[LayR4ListName]
									,vill.[HHCount]
        					FROM [dbo].[StaffAssignCountry] AS stff
							JOIN [dbo].[GeoLayR4List] AS vill  ON stff.[AdmCountryCode]=vill.[AdmCountryCode]
        					WHERE [LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode] IN ($village1)
							AND  stff.[StfCode]= :user_id AND stff.[StatusAssign]=1
        			";

                    $this->query($vsql);
                    $this->bind(':user_id', $staff_code);    // IF CRASH THAN COMMENTS THIS LINE
                    $village = $this->resultset();

                    foreach ($village as $key => $value) {
                        $data['village'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['village'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['village'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['village'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['village'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['village'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['village'][$key]['LayR4ListName'] = $value['LayR4ListName'];
                        $data['village'][$key]['HHCount'] = $value['HHCount'];
                    }
                } // end village
            }

            // getting data from Relation table
            $relsql = "SELECT [HHRelationCode],[RelationName] FROM [dbo].[LUP_RegNHHRelation]";

            $this->query($relsql);
            $relation = $this->resultset();

            if ($relation != false) {
                foreach ($relation as $key => $value) {
                    $data['relation'][$key]['HHRelationCode'] = $value['HHRelationCode'];
                    $data['relation'][$key]['RelationName'] = $value['RelationName'];
                }
            } // end Relation
            //added by pop
            // getting data from GPSGroupTable table

            $gps_groupsql = "SELECT [GrpCode]
				      ,[GrpName]
				      ,[Description]

				  FROM [dbo].[GPSGroupTable]
				 -- WHERE [EntryBy] = :user_id
				  ";
            //AND [SyncStatus]=1

            $this->query($gps_groupsql);
            //$this->bind(':user_id', $staff_code);
            $gps_group = $this->resultset();

            if ($gps_group != false) {

                foreach ($gps_group as $key => $value) {
                    $data['gps_group'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_group'][$key]['GrpName'] = $value['GrpName'];
                    $data['gps_group'][$key]['Description'] = $value['Description'];
                }
            }// end of gps_group table
            //added by pop
            // getting data from GPSSubGroupTable table

            $gps_subgroupsql = "SELECT [GrpCode]
					  ,[SubGrpCode]
				      ,[SubGrpName]
				      ,[Description]

				  FROM [dbo].[GPSSubGroupTable]
				 -- WHERE [EntryBy] = :user_id
				  ";


            $this->query($gps_subgroupsql);
            //$this->bind(':user_id', $staff_code);
            $gps_subgroup = $this->resultset();

            if ($gps_subgroup != false) {

                foreach ($gps_subgroup as $key => $value) {
                    $data['gps_subgroup'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_subgroup'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_subgroup'][$key]['SubGrpName'] = $value['SubGrpName'];
                    $data['gps_subgroup'][$key]['Description'] = $value['Description'];
                }
            }// end of gps_subgroup table
            //added by pop
            // getting data from GPSLocationTable table

            $gps_locationsql = "SELECT [AdmCountryCode]
			          ,[GrpCode]
					  ,[SubGrpCode]
					  ,[LocationCode]
				      ,[LocationName]
				      ,[Long]
					  ,[Latd]

				  FROM [dbo].[GPSLocationTable]";


            $this->query($gps_locationsql);
            //$this->bind(':user_id', $staff_code);
            $gps_location = $this->resultset();

            if ($gps_location != false) {

                foreach ($gps_location as $key => $value) {
                    $data['gps_location'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['gps_location'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_location'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_location'][$key]['LocationCode'] = $value['LocationCode'];
                    $data['gps_location'][$key]['LocationName'] = $value['LocationName'];
                    $data['gps_location'][$key]['Long'] = $value['Long'];
                    $data['gps_location'][$key]['Latd'] = $value['Latd'];
                }
            }// end of gps_location table
            
            // getting data from Adm_CountryAward table
            $adm_countryawardsql = "SELECT stff.[AdmCountryCode]
							,award.[AdmDonorCode]
							,award.[AdmAwardCode]
							,award.[AwardRefNumber]
							,award.[AwardStartDate]
							,award.[AwardEndDate]
							,award.[AwardShortName]
							,award.[AwardStatus]
						FROM [dbo].[StaffAssignCountry] AS stff
						JOIN [dbo].[AdmCountryAward] AS award  	ON award.[AdmCountryCode]=stff.[AdmCountryCode]
						WHERE stff.[StfCode]= :user_id AND stff.[StatusAssign]=1";


            $this->query($adm_countryawardsql);
            $this->bind(':user_id', $staff_code);
            $adm_countryaward = $this->resultset();

            if ($adm_countryaward != false) {

                foreach ($adm_countryaward as $key => $value) {
                    $data['adm_countryaward'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_countryaward'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_countryaward'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_countryaward'][$key]['AwardRefNumber'] = $value['AwardRefNumber'];
                    $data['adm_countryaward'][$key]['AwardStartDate'] = $value['AwardStartDate'];
                    $data['adm_countryaward'][$key]['AwardEndDate'] = $value['AwardEndDate'];
                    $data['adm_countryaward'][$key]['AwardShortName'] = $value['AwardShortName'];
                    $data['adm_countryaward'][$key]['AwardStatus'] = $value['AwardStatus'];
                    //	$data['adm_countryaward'][$key]['EntryDate'] 		= $value['EntryDate'];// no need
                }
            }// end of adm_countryaward table
            


  
            $adm_donorsql = "SELECT [AdmDonorCode]
					  ,[AdmDonorName]
				  FROM [dbo].[AdmDonor]			
				  ";


            $this->query($adm_donorsql);          
            $adm_donor = $this->resultset();

            if ($adm_donor != false) {

                foreach ($adm_donor as $key => $value) {
                    $data['adm_donor'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_donor'][$key]['AdmDonorName'] = $value['AdmDonorName'];
                }
            }// end of adm_donor table
            //adm_program_master
            //added by pop
            // getting data from AdmProgramMaster table

            $adm_program_master_sql = "SELECT [AdmProgCode]
					  ,[AdmAwardCode]
			          ,[AdmDonorCode]
					  ,[ProgName]
					  ,[ProgShortName]
					  ,[MultipleSrv]

				  FROM [dbo].[AdmProgramMaster]

				  ";


            $this->query($adm_program_master_sql);
            //$this->bind(':user_id', $staff_code);
            $adm_program_master = $this->resultset();

            if ($adm_program_master != false) {

                foreach ($adm_program_master as $key => $value) {
                    $data['adm_program_master'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_program_master'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_program_master'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_program_master'][$key]['ProgName'] = $value['ProgName'];
                    $data['adm_program_master'][$key]['ProgShortName'] = $value['ProgShortName'];
                    $data['adm_program_master'][$key]['MultipleSrv'] = $value['MultipleSrv'];
                }
            }// end of adm_program_master table
            //adm_service_master
            // getting data from AdmServiceMaster table

            $adm_service_mastersql = "SELECT [AdmProgCode]
					  ,[AdmSrvCode]
					  ,[AdmSrvName]
					  ,[AdmSrvShortName]

				  FROM [dbo].[AdmServiceMaster]
				--  WHERE [EntryBy] = :user_id
				  ";


            $this->query($adm_service_mastersql);
            //$this->bind(':user_id', $staff_code);
            $adm_service_master = $this->resultset();

            if ($adm_service_master != false) {

                foreach ($adm_service_master as $key => $value) {
                    $data['adm_service_master'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_service_master'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['adm_service_master'][$key]['AdmSrvName'] = $value['AdmSrvName'];
                    $data['adm_service_master'][$key]['AdmSrvShortName'] = $value['AdmSrvShortName'];
                }
            }// end of adm_service_master table
            // getting data from AdmOpMonthTable table

            $adm_op_monthsql = "SELECT [AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[OpCode]
					  ,[OpMonthCode]
					  ,[MonthLabel]
					  ,[StartDate]
					  ,[EndDate]
					  ,convert(varchar,[StartDate],110) AS UsaStartDate
					  ,convert(varchar,[EndDate],110) AS UsaEndDate
					  ,[Status]
				  FROM [dbo].[AdmOpMonthTable] ";


            $this->query($adm_op_monthsql);
            //$this->bind(':user_id', $staff_code);
            $adm_op_month = $this->resultset();

            if ($adm_op_month != false) {

                foreach ($adm_op_month as $key => $value) {
                    $data['adm_op_month'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_op_month'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_op_month'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_op_month'][$key]['OpCode'] = $value['OpCode'];
                    $data['adm_op_month'][$key]['OpMonthCode'] = $value['OpMonthCode'];

                    $data['adm_op_month'][$key]['MonthLabel'] = $value['MonthLabel'];
                    $data['adm_op_month'][$key]['StartDate'] = $value['StartDate'];
                    $data['adm_op_month'][$key]['EndDate'] = $value['EndDate'];
                    $data['adm_op_month'][$key]['UsaStartDate'] = $value['UsaStartDate'];
                    $data['adm_op_month'][$key]['UsaEndDate'] = $value['UsaEndDate'];
                    $data['adm_op_month'][$key]['Status'] = $value['Status'];
                }
            }// end of adm_op_month table
            //AdmCountryProgram
            //added by pop
            // getting data from AdmCountryProgram table

            $adm_country_programsql = "SELECT [AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[AdmProgCode]
					  ,[AdmSrvCode]
					  ,[ProgFlag]
					  ,[FoodFlag]
					  ,[NFoodFlag]
					  ,[CashFlag]
					  ,[VOFlag]
					  ,[DefaultFoodDays]
					  ,[DefaultNFoodDays]
					  ,[DefaultCashDays]
					  ,[DefaultVODays]
					  ,[SrvSpecific]
					  FROM [dbo].[AdmCountryProgram]

				  ";


            $this->query($adm_country_programsql);
            $adm_country_program = $this->resultset();

            if ($adm_country_program != false) {

                foreach ($adm_country_program as $key => $value) {
                    $data['adm_country_program'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_country_program'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_country_program'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_country_program'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_country_program'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['adm_country_program'][$key]['ProgFlag'] = $value['ProgFlag'];
                    $data['adm_country_program'][$key]['FoodFlag'] = $value['FoodFlag'];
                    $data['adm_country_program'][$key]['NFoodFlag'] = $value['NFoodFlag'];
                    $data['adm_country_program'][$key]['CashFlag'] = $value['CashFlag'];
                    $data['adm_country_program'][$key]['VOFlag'] = $value['VOFlag'];
                    $data['adm_country_program'][$key]['DefaultFoodDays'] = $value['DefaultFoodDays'];
                    $data['adm_country_program'][$key]['DefaultNFoodDays'] = $value['DefaultNFoodDays'];
                    $data['adm_country_program'][$key]['DefaultCashDays'] = $value['DefaultCashDays'];
                    $data['adm_country_program'][$key]['DefaultVODays'] = $value['DefaultVODays'];
                    $data['adm_country_program'][$key]['SrvSpecific'] = $value['SrvSpecific'];
                }
            }// end of AdmCountryProgram table


            /** @modify:2015-10-20 */
            $dob_service_centersql = " select	  SrvCenterTable.AdmCountryCode ,
								SrvCenterTable.SrvCenterName,SrvCenterTable.SrvCenterCode
								, SrvCenterTable.FDPCode  from SrvCenterTable



				 ";


            $this->query($dob_service_centersql);

            $dob_service_center = $this->resultset();

            if ($dob_service_center != false) {

                foreach ($dob_service_center as $key => $value) {
                    $data['dob_service_center'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    //$data['dob_service_center'][$key]['SrvCenterCode'] 		= $value['SrvCenterCode'];
                    $data['dob_service_center'][$key]['SrvCenterName'] = $value['SrvCenterName'];
                    $data['dob_service_center'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                    //$data['dob_service_center'][$key]['SrvCenterAddress'] 				= $value['SrvCenterAddress'];
                    //	$data['dob_service_center'][$key]['SrvCenterCatCode'] 				= $value['SrvCenterCatCode'];
                    $data['dob_service_center'][$key]['FDPCode'] = $value['FDPCode'];
                }
            }// end of SrvCenterTable table
            //StaffGeoInfoAccessTable
            // getting data from StaffGeoInfoAccess table StfCode

            $staff_access_info_sql = "SELECT [StfCode]
					  ,[AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[LayRListCode]
					  ,[btnNew]
					  ,[btnSave]
					  ,[btnDel]
					  ,[btnPepr]
					  ,[btnAprv]
					  ,[btnRevw]
					  ,[btnVrfy]
					  ,[btnDTran]
					  FROM [dbo].[StaffGeoInfoAccess]
				  WHERE [StfCode] = :user_id and ( btnNew =1 OR btnSave=1 OR btnDel=1 )
				  ";


            $this->query($staff_access_info_sql);
            $this->bind(':user_id', $staff_code);
            $staff_access_info = $this->resultset();

            if ($staff_access_info != false) {

                foreach ($staff_access_info as $key => $value) {
                    $data['staff_access_info'][$key]['StfCode'] = $value['StfCode'];
                    $data['staff_access_info'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_access_info'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['staff_access_info'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['staff_access_info'][$key]['LayRListCode'] = $value['LayRListCode'];
                    $data['staff_access_info'][$key]['btnNew'] = $value['btnNew'];
                    $data['staff_access_info'][$key]['btnSave'] = $value['btnSave'];
                    $data['staff_access_info'][$key]['btnDel'] = $value['btnDel'];
                    $data['staff_access_info'][$key]['btnPepr'] = $value['btnPepr'];
                    $data['staff_access_info'][$key]['btnAprv'] = $value['btnAprv'];
                    $data['staff_access_info'][$key]['btnRevw'] = $value['btnRevw'];
                    $data['staff_access_info'][$key]['btnVrfy'] = $value['btnVrfy'];
                    $data['staff_access_info'][$key]['btnDTran'] = $value['btnDTran'];
                }
            }// end of StaffGeoInfoAccess table


            $lb_reg_hh_categorysql = "SELECT [AdmCountryCode]
			   ,[HHHeadCatCode]
			   ,[CatName]
			   FROM [dbo].[LUP_RegNHHHeadCategory] ";


            $this->query($lb_reg_hh_categorysql);

            $lb_reg_hh_category = $this->resultset();

            if ($lb_reg_hh_category != false) {

                foreach ($lb_reg_hh_category as $key => $value) {
                    $data['lb_reg_hh_category'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lb_reg_hh_category'][$key]['HHHeadCatCode'] = $value['HHHeadCatCode'];
                    $data['lb_reg_hh_category'][$key]['CatName'] = $value['CatName'];
                }
            }// end of LUP_RegNHHHeadCategory table
            ///---2015-09-29
            /** added by pop @2015-09-29
             * $data["reg_lup_graduation"]  = array();
             * RegNLUP_Graduation
             * added by pop
             * getting data from RegNLUP_Graduation table
             */
            $reg_lup_graduationsql = "SELECT [AdmProgCode]
									,[AdmSrvCode]
									,[GRDCode]
									,[GRDTitle]
									,[DefaultCatActive]
									,[DefaultCatExit]

								FROM  [dbo].[RegNLUP_Graduation]";


            $this->query($reg_lup_graduationsql);

            $reg_lup_graduation = $this->resultset();

            if ($reg_lup_graduation != false) {

                foreach ($reg_lup_graduation as $key => $value) {
                    $data['reg_lup_graduation'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['reg_lup_graduation'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['reg_lup_graduation'][$key]['GRDCode'] = $value['GRDCode'];
                    $data['reg_lup_graduation'][$key]['GRDTitle'] = $value['GRDTitle'];
                    $data['reg_lup_graduation'][$key]['DefaultCatActive'] = $value['DefaultCatActive'];
                    $data['reg_lup_graduation'][$key]['DefaultCatExit'] = $value['DefaultCatExit'];
                }
            }// end of RegNLUP_Graduation table

            /**   */
            /* date: 2016-01-27 */
            $report_templateSql = "SELECT [AdmCountryCode], [RptLabel]
									,RptGroup+RptCode as RptgNcode
									FROM  [dbo].[RptTemplateTable]
									 WHERE (RptGroup = '01') AND (RptGenericCode = '1')
									AND (AdmReportIsActive = 'A')
									AND (AdmCountryCode = (select AdmCountryCode
									from dbo.StaffAssignCountry where StfCode= :user_id and StatusAssign=1))
									ORDER BY RptLabel					";

            $this->query($report_templateSql);
            $this->bind(':user_id', $staff_code);

            $report_template = $this->resultset();

            if ($report_template != false) {

                foreach ($report_template as $key => $value) {
                    $data['report_template'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['report_template'][$key]['RptLabel'] = $value['RptLabel'];
                    $data['report_template'][$key]['Code'] = $value['RptgNcode'];
                }
            }// end of [dbo].[RptTemplateTable]table

            /* date: 2015-11-05 */
            $card_print_reasonSql = "SELECT [ReasonCode]
									,[ReasonTitle]
									FROM  [dbo].[LUP_RegNCardPrintReason]";


            $this->query($card_print_reasonSql);

            $card_print_reason = $this->resultset();

            if ($card_print_reason != false) {

                foreach ($card_print_reason as $key => $value) {
                    $data['card_print_reason'][$key]['ReasonCode'] = $value['ReasonCode'];
                    $data['card_print_reason'][$key]['ReasonTitle'] = $value['ReasonTitle'];
                }
            }// end of [dbo].[LUP_RegNCardPrintReason] table
            // FDP Access Point

            $staff_fdp_access_sql = "SELECT [StfCode]
							  ,[AdmCountryCode]
							  ,[FDPCode]
							  ,[btnNew]
							  ,[btnSave]
							  ,[btnDel]
						  FROM [dbo].[StaffFDPAccess]
						 where StfCode= :user_id ";

            $this->query($staff_fdp_access_sql);
            $this->bind(':user_id', $staff_code);
            $staff_fdp_access = $this->resultset();

            if ($staff_fdp_access != false) {

                foreach ($staff_fdp_access as $key => $value) {
                    $data['staff_fdp_access'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_fdp_access'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['staff_fdp_access'][$key]['btnNew'] = $value['btnNew'];
                    $data['staff_fdp_access'][$key]['btnSave'] = $value['btnSave'];
                    $data['staff_fdp_access'][$key]['btnDel'] = $value['btnDel'];
                }
            }// end of RegNMemCardPrintTable table
            // fdp master

            $fdp_master_sql = "SELECT [AdmCountryCode]
									  ,[FDPCode]
									  ,[FDPName]
									  ,[FDPCatCode]
									  ,[WHCode]
									  ,[LayR1Code]
									  ,[LayR2Code]
								  FROM [GPath_Android_Test].[dbo].[FDPMaster]
					 ";

            $this->query($fdp_master_sql);
            // $this->bind(':user_id', $staff_code);
            $fdp_master = $this->resultset();

            if ($fdp_master != false) {

                foreach ($fdp_master as $key => $value) {
                    $data['fdp_master'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['fdp_master'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['fdp_master'][$key]['FDPName'] = $value['FDPName'];
                    $data['fdp_master'][$key]['FDPCatCode'] = $value['FDPCatCode'];
                    $data['fdp_master'][$key]['WHCode'] = $value['WHCode'];
                    $data['fdp_master'][$key]['LayR1Code'] = $value['LayR1Code'];
                    $data['fdp_master'][$key]['LayR2Code'] = $value['LayR2Code'];
                }
            }// end of fdp_master table
            //2016-02-27
            $reg_lup_graduationsql = "SELECT [AdmProgCode]
									,[AdmSrvCode]
									,[GRDCode]
									,[GRDTitle]
									,[DefaultCatActive]
									,[DefaultCatExit]

								FROM  [dbo].[RegNLUP_Graduation]";


            $this->query($reg_lup_graduationsql);

            $reg_lup_graduation = $this->resultset();

            if ($reg_lup_graduation != false) {

                foreach ($reg_lup_graduation as $key => $value) {
                    $data['reg_lup_graduation'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['reg_lup_graduation'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['reg_lup_graduation'][$key]['GRDCode'] = $value['GRDCode'];
                    $data['reg_lup_graduation'][$key]['GRDTitle'] = $value['GRDTitle'];
                    $data['reg_lup_graduation'][$key]['DefaultCatActive'] = $value['DefaultCatActive'];
                    $data['reg_lup_graduation'][$key]['DefaultCatExit'] = $value['DefaultCatExit'];
                }
            }// end of RegNLUP_Graduation table

            /**   */
            /* date: 2016-01-27 */
            $report_templateSql = "SELECT [AdmCountryCode], [RptLabel]
									,RptGroup+RptCode as RptgNcode
									FROM  [dbo].[RptTemplateTable]
									 WHERE (RptGroup = '01') AND (RptGenericCode = '1')
									AND (AdmReportIsActive = 'A')
									AND (AdmCountryCode = (select AdmCountryCode
									from dbo.StaffAssignCountry where StfCode= :user_id and StatusAssign=1))
									ORDER BY RptLabel					";

            $this->query($report_templateSql);
            $this->bind(':user_id', $staff_code);

            $report_template = $this->resultset();

            if ($report_template != false) {

                foreach ($report_template as $key => $value) {
                    $data['report_template'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['report_template'][$key]['RptLabel'] = $value['RptLabel'];
                    $data['report_template'][$key]['RptgNcode'] = $value['RptgNcode'];
                }
            }// end of [dbo].[RptTemplateTable]table

            /* date: 2015-11-05 */
            $card_print_reasonSql = "SELECT [ReasonCode]
									,[ReasonTitle]
									FROM  [dbo].[LUP_RegNCardPrintReason]";


            $this->query($card_print_reasonSql);

            $card_print_reason = $this->resultset();

            if ($card_print_reason != false) {

                foreach ($card_print_reason as $key => $value) {
                    $data['card_print_reason'][$key]['ReasonCode'] = $value['ReasonCode'];
                    $data['card_print_reason'][$key]['ReasonTitle'] = $value['ReasonTitle'];
                }
            }// end of [dbo].[LUP_RegNCardPrintReason] table
            // FDP Access Point

            $staff_fdp_access_sql = "SELECT [StfCode]
							  ,[AdmCountryCode]
							  ,[FDPCode]
							  ,[btnNew]
							  ,[btnSave]
							  ,[btnDel]
						  FROM [dbo].[StaffFDPAccess]
						 where StfCode= :user_id ";

            $this->query($staff_fdp_access_sql);
            $this->bind(':user_id', $staff_code);
            $staff_fdp_access = $this->resultset();

            if ($staff_fdp_access != false) {

                foreach ($staff_fdp_access as $key => $value) {
                    $data['staff_fdp_access'][$key]['StfCode'] = $value['StfCode'];
                    $data['staff_fdp_access'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_fdp_access'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['staff_fdp_access'][$key]['btnNew'] = $value['btnNew'];
                    $data['staff_fdp_access'][$key]['btnSave'] = $value['btnSave'];
                    $data['staff_fdp_access'][$key]['btnDel'] = $value['btnDel'];
                }
            }// end of RegNMemCardPrintTable table
            // food distribution point fdp master

            $fdp_master_sql = "SELECT [AdmCountryCode]
									  ,[FDPCode]
									  ,[FDPName]
									  ,[FDPCatCode]
									  ,[WHCode]
									  ,[LayR1Code]
									  ,[LayR2Code]
								  FROM [dbo].[FDPMaster]	 ";

            $this->query($fdp_master_sql);
            // $this->bind(':user_id', $staff_code);
            $fdp_master = $this->resultset();

            if ($fdp_master != false) {

                foreach ($fdp_master as $key => $value) {
                    $data['fdp_master'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['fdp_master'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['fdp_master'][$key]['FDPName'] = $value['FDPName'];
                    $data['fdp_master'][$key]['FDPCatCode'] = $value['FDPCatCode'];
                    $data['fdp_master'][$key]['WHCode'] = $value['WHCode'];
                    $data['fdp_master'][$key]['LayR1Code'] = $value['LayR1Code'];
                    $data['fdp_master'][$key]['LayR2Code'] = $value['LayR2Code'];
                }
            }// end of fdp_master table
            // dbo.lupsrv_option


            $lup_srv_option_list_sql = "SELECT [AdmCountryCode]
								  ,[ProgCode]
								  ,[SrvCode]
								  ,[LUPOptionCode]
								  ,[LUPOptionName]
							  FROM [dbo].[LUP_SrvOptionList]
					 ";

            $this->query($lup_srv_option_list_sql);
            // $this->bind(':user_id', $staff_code);
            $lup_srv_option_list = $this->resultset();

            if ($lup_srv_option_list != false) {

                foreach ($lup_srv_option_list as $key => $value) {
                    $data['lup_srv_option_list'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_srv_option_list'][$key]['ProgCode'] = $value['ProgCode'];
                    $data['lup_srv_option_list'][$key]['SrvCode'] = $value['SrvCode'];
                    $data['lup_srv_option_list'][$key]['LUPOptionCode'] = $value['LUPOptionCode'];
                    $data['lup_srv_option_list'][$key]['LUPOptionName'] = $value['LUPOptionName'];
                }
            }// end of lup_srv_option_list table
            // decode the json array
            $jData = json_decode($lay_r_code_j);


            $s = "";
            $countryCode = "";
            $isAddressCodeAdded = false;
            //echo($operation_mode);
            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';


                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 			echo($s);
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 3 : // fro Service   2 Service center code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;
            }


            if ($jData) {
                $service_table_sql = "";
                $service_exe_table_sql = "";
                $reg_mem_card_request_sql = "";
                switch ($operation_mode) {

                    case 1:
                        if ($isAddressCodeAdded) {
                            $service_table_sql = "SELECT  DISTINCT [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
												,RegNMemProgGrp.GrpCode As GrpCode
												,[DistFlag]
							FROM[SrvTable]
							LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
						-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
							WHERE
							[SrvTable].AdmCountryCode+[SrvTable].LayR1ListCode+[SrvTable].LayR2ListCode+[SrvTable].LayR3ListCode+[SrvTable].LayR4ListCode in (      SELECT   [AdmCountryCode]
                    +[LayR1ListCode]							  +[LayR2ListCode]							  +[LayR3ListCode]
                    +[LayR4ListCode] from [RegNHHTable] where AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")) ";


                            // getting data from [SrvExtendedTable]


                            $service_exe_table_sql = "SELECT [AdmCountryCode]
													,[AdmDonorCode]
													,[AdmAwardCode]
													,[LayR1ListCode]
													,[LayR2ListCode]
													,[LayR3ListCode]
													,[LayR4ListCode]
													,[HHID]
													,[MemID]
													,[ProgCode]
													,[SrvCode]
													,[OpCode]
													,[OpMonthCode]
													,[VOItmSpec]
													,[VOItmUnit]
													,[VORefNumber]
													,[VOItmCost]
											FROM [dbo].[SrvExtendedTable]
									-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
											where

										AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (      SELECT   [AdmCountryCode]
                    +[LayR1ListCode]							  +[LayR2ListCode]							  +[LayR3ListCode]
                    +[LayR4ListCode] from [RegNHHTable] where AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode+[RegNAddLookupCode] in (" . $s . "))";


                            /** added by pop @2015-11-10
                             */
                            $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
					-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (      SELECT   [AdmCountryCode]
                    +[LayR1ListCode]							  +[LayR2ListCode]							  +[LayR3ListCode]
                    +[LayR4ListCode] from [RegNHHTable] where AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . "))";
                        } else {
                            $service_table_sql = "SELECT  DISTINCT [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
												,RegNMemProgGrp.GrpCode As GrpCode
												,[DistFlag]
							FROM[SrvTable]
							LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
						-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
							WHERE
							[SrvTable].AdmCountryCode+[SrvTable].LayR1ListCode+[SrvTable].LayR2ListCode+[SrvTable].LayR3ListCode+[SrvTable].LayR4ListCode in (" . $s . ") ";


                            // getting data from [SrvExtendedTable]


                            $service_exe_table_sql = "SELECT [AdmCountryCode]
													,[AdmDonorCode]
													,[AdmAwardCode]
													,[LayR1ListCode]
													,[LayR2ListCode]
													,[LayR3ListCode]
													,[LayR4ListCode]
													,[HHID]
													,[MemID]
													,[ProgCode]
													,[SrvCode]
													,[OpCode]
													,[OpMonthCode]
													,[VOItmSpec]
													,[VOItmUnit]
													,[VORefNumber]
													,[VOItmCost]
											FROM [dbo].[SrvExtendedTable]
									-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
											where

										AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";


                            /** added by pop @2015-11-10
                             */
                            $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
					-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";
                        }

                        break;
                    case 2:        // for distribution


                        $service_table_sql = "SELECT  DISTINCT [SrvTable].[AdmCountryCode]
												,[SrvTable].[AdmDonorCode]
												,[SrvTable].[AdmAwardCode]
												,[SrvTable].[LayR1ListCode]
												,[SrvTable].[LayR2ListCode]
												,[SrvTable].[LayR3ListCode]
												,[SrvTable].[LayR4ListCode]
												,[SrvTable].[HHID]
												,[SrvTable].[MemID]
												,[SrvTable].[ProgCode]
												,[SrvTable].[SrvCode]
												,[OpCode]
												,[OpMonthCode]
												,[SrvSL]
												,[SrvCenterCode]
												,convert(varchar,[SrvDT],110) AS [SrvDT]
												,[SrvStatus]
												,[DistStatus]
												,[DistDT]
												,FDPCode
												,isnull([WD],0) As WD
												,RegNMemProgGrp.GrpCode As GrpCode
                                                ,[DistFlag]
										FROM [dbo].[SrvTable]


										LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
										where
										[SrvTable].[OpMonthCode] in(Select SrvOpMonthCode from DistNPlanbasic

											where DisOpMonthCode in (Select OpMonthCode from AdmOpMonthTable
													where Status='A' and OpCode='3' and admcountryCode =" . $countryCode . " )
																	and admcountryCode+FDPCode in(" . $s . ")
																		)
												and [SrvTable].[AdmCountryCode]+FDPCode in(" . $s . ")";


                        $service_exe_table_sql = "SELECT [AdmCountryCode]
														,[AdmDonorCode]
														,[AdmAwardCode]
														,[LayR1ListCode]
														,[LayR2ListCode]
														,[LayR3ListCode]
														,[LayR4ListCode]
														,[HHID]
														,[MemID]
														,[ProgCode]
														,[SrvCode]
														,[OpCode]
														,[OpMonthCode]
														,[VOItmSpec]
														,[VOItmUnit]
														,[VORefNumber]
														,[VOItmCost]
													FROM [dbo].[SrvExtendedTable]

											where [AdmCountryCode]+[LayR1ListCode]
													+[LayR2ListCode]
													+[LayR3ListCode]
													+[LayR4ListCode]
													+[HHID]
													+[MemID] in(
													SELECT  DISTINCT [AdmCountryCode]+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode] +[LayR4ListCode]+[HHID]+[MemID]

														FROM [dbo].[SrvTable]

														where

														[SrvTable].[OpMonthCode] in(Select SrvOpMonthCode from DistNPlanbasic

												where DisOpMonthCode in (Select OpMonthCode from AdmOpMonthTable
												where Status='A' and OpCode='3' and admcountryCode =" . $countryCode . ")

													and admcountryCode+FDPCode in(" . $s . ")
																		)
								and [AdmCountryCode]+FDPCode in(" . $s . "))";


                        $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
					-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        break;


                    case 3:        // for Service mode


                        $service_table_sql = "SELECT [SrvTable].[AdmCountryCode]
											,[SrvTable].[AdmDonorCode]
											,[SrvTable].[AdmAwardCode]
											,[SrvTable].[LayR1ListCode]
											,[SrvTable].[LayR2ListCode]
											,[SrvTable].[LayR3ListCode]
											,[SrvTable].[LayR4ListCode]
											,[SrvTable].[HHID]
											,[SrvTable].[MemID]
											,[SrvTable].[ProgCode]
											,[SrvTable].[SrvCode]
											,[OpCode]
											,[OpMonthCode]
											,[SrvSL]
											,[SrvCenterCode]
											,convert(varchar,[SrvDT],110) AS [SrvDT]
											,[SrvStatus]
											,[DistStatus]
											,[DistDT]
											,FDPCode
											,isnull([WD],0) As WD
											,RegNMemProgGrp.GrpCode As GrpCode
                                            ,[DistFlag]
										FROM [dbo].[SrvTable]
										LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
										where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
												where [AdmCountryCode]=" . $countryCode . "  and [OpCode]=2  and  [Status]='A'
													ORDER BY OpMonthCode DESC)

										and [SrvStatus]='O'
										and [SrvTable].[AdmCountryCode]+[SrvCenterCode] IN (" . $s . ")";


                        $service_exe_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[HHID]
											,[MemID]
											,[ProgCode]
											,[SrvCode]
											,[OpCode]
											,[OpMonthCode]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[VOItmCost]
											FROM [dbo].[SrvExtendedTable]
										where[AdmCountryCode]+[AdmDonorCode]	+[AdmAwardCode]	+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
											+[HHID]	+[MemID]+[ProgCode]	+[SrvCode] in(	SELECT [AdmCountryCode]
														+[AdmDonorCode]		+[AdmAwardCode]
														+[LayR1ListCode]	+[LayR2ListCode]
														+[LayR3ListCode]	+[LayR4ListCode]
														+[HHID]														+[MemID]
														+[ProgCode]														+[SrvCode]
														FROM [dbo].[SrvTable]
														where[OpMonthCode]in( SELECT TOP 1  [OpMonthCode]     FROM [dbo].[AdmOpMonthTable]
																			where [AdmCountryCode]=" . $countryCode . "  and [OpCode]=2  and  [Status]='A'
																			ORDER BY OpMonthCode DESC)

															and [SrvStatus]='O'
															and [AdmCountryCode]+[SrvCenterCode] IN (" . $s . ")";


                        $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
					-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        break;
						case 4:        // for dynamic mode
						case 5:        // for training and Activity mode


                        $service_table_sql = "SELECT [SrvTable].[AdmCountryCode]
											,[SrvTable].[AdmDonorCode]
											,[SrvTable].[AdmAwardCode]
											,[SrvTable].[LayR1ListCode]
											,[SrvTable].[LayR2ListCode]
											,[SrvTable].[LayR3ListCode]
											,[SrvTable].[LayR4ListCode]
											,[SrvTable].[HHID]
											,[SrvTable].[MemID]
											,[SrvTable].[ProgCode]
											,[SrvTable].[SrvCode]
											,[OpCode]
											,[OpMonthCode]
											,[SrvSL]
											,[SrvCenterCode]
											,convert(varchar,[SrvDT],110) AS [SrvDT]
											,[SrvStatus]
											,[DistStatus]
											,[DistDT]
											,FDPCode
											,isnull([WD],0) As WD
											,RegNMemProgGrp.GrpCode As GrpCode
                                            ,[DistFlag]
										FROM [dbo].[SrvTable]
										LEFT JOIN RegNMemProgGrp
							ON [SrvTable].[AdmCountryCode]=RegNMemProgGrp.[AdmCountryCode]
												AND [SrvTable].[AdmDonorCode]=RegNMemProgGrp.[AdmDonorCode]
												AND [SrvTable].[AdmAwardCode]=  RegNMemProgGrp.[AdmAwardCode]
												AND [SrvTable].[LayR1ListCode]=RegNMemProgGrp.[LayR1ListCode]
												AND [SrvTable].[LayR2ListCode]=RegNMemProgGrp.[LayR2ListCode]
												AND [SrvTable].[LayR3ListCode]= RegNMemProgGrp.[LayR3ListCode]
												AND [SrvTable].[LayR4ListCode]=RegNMemProgGrp.[LayR4ListCode]
												AND [SrvTable].[HHID]=RegNMemProgGrp.[HHID]
												AND [SrvTable].[MemID]=RegNMemProgGrp.[MemID]
												AND [SrvTable].[ProgCode]=RegNMemProgGrp.[ProgCode]
												AND [SrvTable].[SrvCode]  =RegNMemProgGrp.[SrvCode]
										where[OpMonthCode]in(0)";


                        $service_exe_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[HHID]
											,[MemID]
											,[ProgCode]
											,[SrvCode]
											,[OpCode]
											,[OpMonthCode]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[VOItmCost]
											FROM [dbo].[SrvExtendedTable]
										where[AdmCountryCode]+[AdmDonorCode]	+[AdmAwardCode]	+[LayR1ListCode]+[LayR2ListCode]+[LayR3ListCode]+[LayR4ListCode]
											+[HHID]	+[MemID]+[ProgCode]	+[SrvCode] in(0)";


                        $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
					-- FRIST CONDITION IS CHECK THE TAHT IS THAT STAFF HAS ACCESS PERMISSION IN THAT VILLAGE
									where

							AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        break;
                }


                $this->query($service_table_sql);

                $service_table = $this->resultset();

                if ($service_table != false) {

                    foreach ($service_table as $key => $value) {
                        $data['service_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['service_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['service_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['service_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['service_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['service_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['service_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['service_table'][$key]['HHID'] = $value['HHID'];
                        $data['service_table'][$key]['MemID'] = $value['MemID'];
                        $data['service_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['service_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['service_table'][$key]['OpCode'] = $value['OpCode'];
                        $data['service_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['service_table'][$key]['SrvSL'] = $value['SrvSL'];
                        $data['service_table'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                        $data['service_table'][$key]['SrvDT'] = $value['SrvDT'];
                        $data['service_table'][$key]['SrvStatus'] = $value['SrvStatus'];
                        $data['service_table'][$key]['DistStatus'] = $value['DistStatus'];
                        $data['service_table'][$key]['DistDT'] = $value['DistDT'];
                        $data['service_table'][$key]['FDPCode'] = $value['FDPCode'];
                        $data['service_table'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['service_table'][$key]['WD'] = $value['WD'];
                        $data['service_table'][$key]['DistFlag'] = $value['DistFlag'];
                    }
                }// end of service_table table
                // service Extended table


                $this->query($service_exe_table_sql);
                //$this->bind(':user_id', $staff_code);
                $service_exe_table = $this->resultset();

                if ($service_exe_table != false) {

                    foreach ($service_exe_table as $key => $value) {
                        $data['service_exe_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['service_exe_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['service_exe_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['service_exe_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['service_exe_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['service_exe_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['service_exe_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['service_exe_table'][$key]['HHID'] = $value['HHID'];
                        $data['service_exe_table'][$key]['MemID'] = $value['MemID'];
                        $data['service_exe_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['service_exe_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['service_exe_table'][$key]['OpCode'] = $value['OpCode'];
                        $data['service_exe_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['service_exe_table'][$key]['VOItmSpec'] = $value['VOItmSpec'];
                        $data['service_exe_table'][$key]['VOItmUnit'] = $value['VOItmUnit'];
                        $data['service_exe_table'][$key]['VORefNumber'] = $value['VORefNumber'];
                        $data['service_exe_table'][$key]['VOItmCost'] = $value['VOItmCost'];
                    }
                }// end of service_table table


                $this->query($reg_mem_card_request_sql);
                // $this->bind(':user_id', $staff_code);
                $reg_mem_card_request = $this->resultset();

                if ($reg_mem_card_request != false) {

                    foreach ($reg_mem_card_request as $key => $value) {
                        $data['reg_mem_card_request'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_mem_card_request'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['reg_mem_card_request'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['reg_mem_card_request'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_mem_card_request'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_mem_card_request'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_mem_card_request'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_mem_card_request'][$key]['HHID'] = $value['HHID'];
                        $data['reg_mem_card_request'][$key]['MemID'] = $value['MemID'];
                        $data['reg_mem_card_request'][$key]['RptGroup'] = $value['RptGroup'];
                        $data['reg_mem_card_request'][$key]['RptCode'] = $value['RptCode'];
                        $data['reg_mem_card_request'][$key]['RequestSL'] = $value['RequestSL'];
                        $data['reg_mem_card_request'][$key]['ReasonCode'] = $value['ReasonCode'];
                        $data['reg_mem_card_request'][$key]['RequestDate'] = $value['RequestDate'];
                        $data['reg_mem_card_request'][$key]['PrintDate'] = $value['PrintDate'];
                        $data['reg_mem_card_request'][$key]['PrintBy'] = $value['PrintBy'];
                        $data['reg_mem_card_request'][$key]['DeliveryDate'] = $value['DeliveryDate'];
                        $data['reg_mem_card_request'][$key]['DeliveredBy'] = $value['DeliveredBy'];
                        $data['reg_mem_card_request'][$key]['DelStatus'] = $value['DelStatus'];
                        $data['reg_mem_card_request'][$key]['EntryBy'] = $value['EntryBy'];
                        $data['reg_mem_card_request'][$key]['EntryDate'] = $value['EntryDate'];
                    }// end of the for each
                }// end of RegNMemCardPrintTable table
            }
        } else {
            return false;
        }

        return $data;
    }

// End function is_valid_user

    public function is_valid_user($user, $pass, $lay_r_code_j, $operation_mode) {
        $data = array();
        // reference table
        $data["countries"] = array();
        $data["valid_dates"] = array();
        $data["layer_labels"] = array();
        $data['district'] = array();
        $data['upazilla'] = array();
        $data['unit'] = array();
        $data['village'] = array();
        $data['relation'] = array();
        $data["gps_group"] = array();
        $data["gps_subgroup"] = array();
        $data["gps_location"] = array();
        $data["adm_countryaward"] = array();
        $data["adm_award"] = array();
        $data["adm_donor"] = array();
        $data["adm_program_master"] = array();
        $data["adm_service_master"] = array();
        $data["adm_op_month"] = array();
        $data["adm_country_program"] = array();
        $data["dob_service_center"] = array();
        $data["card_print_reason"] = array();
        $data["staff_access_info"] = array();
        $data["lb_reg_hh_category"] = array();


        $data["service_table"] = array();


        $data["reg_lup_graduation"] = array();
        $data["geo_layR4_list"] = array();
        $data["report_template"] = array();

        $data["reg_mem_card_request_sql"] = array();
        $data["staff_fdp_access"] = array();
        $data["fdp_master"] = array();
        $data["distribution_table"] = array();
        $data["distribution_ext_table"] = array();
        $data["distbasic_table"] = array();

        $data["lup_srv_option_list"] = array();
        $data["vo_itm_table"] = array();
        $data["vo_itm_meas_table"] = array();
        $data["vo_country_prog_itm"] = array();
        $data["community_group"] = array();
        $data["lup_community_animal"] = array();
        $data["lup_prog_group_crop"] = array();
        $data["lup_community_loan_source"] = array();
        $data["lup_community_fund_source"] = array();
        $data["lup_community_irrigation_system"] = array();
        $data["lup_community__lead_position"] = array();
        $data["community_group_category"] = array();
        $data["lup_reg_n_add_lookup"] = array();
        $data["org_n_code"] = array();
        $data["prog_org_n_role"] = array();
        $data["gps_lup_list"] = array();
        $data["community_grp_detail"] = array();
        $data["staff_srv_center_access"] = array();

        // new added
		

        $sql = "
				SELECT DISTINCT
						s.[StfCode],
						sa.[AdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
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
        ";
        $sql_other = "SELECT DISTINCT
						s.[StfCode],
						s.[OrigAdmCountryCode],
                        REPLACE(s.[UsrLogInName],' ','') [UsrLogInName],
                        s.[UsrLogInPW],
                        s.[StfName],
                        s.[UsrEmail],
                        s.[StfStatus],
                        s.[EntryBy],
                        s.[EntryDate]
                FROM [dbo].[StaffMaster] AS s 
                WHERE [UsrLogInName] = :user AND [UsrLogInPW] = :pass";

        if ($operation_mode == "4") {
            $this->query($sql_other);
        } else {
            $this->query($sql);
        }
        $this->bind(':user', $user);
        $this->bind(':pass', $pass);

        $data['user'] = $this->single();

        if ($data['user'] != false) {

            // getting StuffCode from User's data
            $staff_code = $data['user']["StfCode"];


            // getting country list
            $csql = '
        			SELECT sc.[AdmCountryCode], c.[AdmCountryName]
  					FROM [dbo].[StaffAssignCountry] AS sc
  					JOIN [dbo].[AdmCountry] AS c
        				ON c.[AdmCountryCode]=sc.[AdmCountryCode]
  					WHERE sc.[StfCode]= :user_id AND sc.[StatusAssign]=1';

            $this->query($csql);
            $this->bind(':user_id', $staff_code);

            $countries = $this->resultset();

            if ($countries != false) {
                foreach ($countries as $key => $country) {
                    $data['countries'][$key]['AdmCountryCode'] = $country['AdmCountryCode'];
                    $data['countries'][$key]['AdmCountryName'] = $country['AdmCountryName'];
                }
            }


            // getting Layer Label data
            $lsql = '
        				SELECT [AdmCountryCode]
					      ,[GeoLayRCode]
					      ,[GeoLayRName]
					  	FROM [dbo].[GeoLayRMaster]';

            $this->query($lsql);

            $labels = $this->resultset();

            if ($labels != false) {
                foreach ($labels as $key => $label) {
                    $data['layer_labels'][$key]['AdmCountryCode'] = $label['AdmCountryCode'];
                    $data['layer_labels'][$key]['GeoLayRCode'] = $label['GeoLayRCode'];
                    $data['layer_labels'][$key]['GeoLayRName'] = $label['GeoLayRName'];
                }
            }


            // getting valid registration dates
            $date_sql = "
        			SELECT [AdmCountryCode]
				      ,[StartDate]
				      ,[EndDate]
				  FROM [dbo].[AdmOpMonthTable]
				  WHERE [OpCode]=1 AND [Status]='A'";

            $this->query($date_sql);

            $valid_dates = $this->resultset();

            if ($valid_dates != false) {
                foreach ($valid_dates as $key => $date) {
                    $data['valid_dates'][$key]['AdmCountryCode'] = $date['AdmCountryCode'];
                    $data['valid_dates'][$key]['StartDate'] = $date['StartDate'];
                    $data['valid_dates'][$key]['EndDate'] = $date['EndDate'];
                }
            }


            // getting all permitted Lay1, Lay2, Lay3 and Lay4 from StaffGeoInfoAccess
            $psql = '
        			SELECT DISTINCT sa.[LayRListCode] AS list_code
                	FROM [dbo].[StaffMaster] AS u
					JOIN [dbo].[StaffGeoInfoAccess] AS sa
						ON u.[StfCode] = sa.[StfCode]
               		WHERE u.[StfCode] = :user_id and( btnNew =1 or btnSave=1 or btnDel=1)';


            $this->query($psql);
            $this->bind(':user_id', $staff_code);

            $permission = $this->resultset();

            // e.g. 01010101, 01010102, 01010201
            $district = "";
            $upazilla = "";
            $unit = "";
            $village = "";
            $village1 = "";
            $upazilla1 = "";
            $unit1 = "";
            if ($permission != false) {

                //code by Mr
                /* fetching village code , district, upazilla,union for only the
                  user access village name,upazilla,union,district; */
                foreach ($permission as $key => $value) {
                    $v1 = $value['list_code'];
                    if (strpos($village1, $v1) === false)
                        $village1 .= "'" . $v1 . "',";
                    $t1 = substr($value['list_code'], 0, 4);
                    if (strpos($upazilla1, $t1) === false)
                        $upazilla1 .= "'" . $t1 . "',";
                    $u1 = substr($value['list_code'], 0, 6);
                    if (strpos($unit1, $u1) === false)
                        $unit1 .= "'" . $u1 . "',";
                }
                $village1 = substr($village1, 0, -1);
                $upazilla1 = substr($upazilla1, 0, -1);
                $unit1 = substr($unit1, 0, -1);

                //end mr code

                foreach ($permission as $key => $value) {

                    $d = substr($value['list_code'], 0, 2);
                    if (strpos($district, $d) === false)
                        $district .= "'" . $d . "',";

                    $t = substr($value['list_code'], 2, 2);
                    if (strpos($upazilla, $t) === false)
                        $upazilla .= "'" . $t . "',";

                    $u = substr($value['list_code'], 4, 2);
                    if (strpos($unit, $u) === false)
                        $unit .= "'" . $u . "',";

                    $v = substr($value['list_code'], 6, 2);
                    if (strpos($village, $v) === false)
                        $village .= "'" . $v . "',";
                } // end permission

                $district = substr($district, 0, -1);
                $upazilla = substr($upazilla, 0, -1);
                $unit = substr($unit, 0, -1);
                $village = substr($village, 0, -1);
                //echo("district");
                //echo($district);
                // District
                if ($district != false) {
                    $dsql = "SELECT DISTINCT [AdmCountryCode],[GeoLayRCode],[LayRListCode], [LayRListName]
        					FROM [dbo].[GeoLayR1List]
        					WHERE
							[AdmCountryCode] IN (Select AdmCountryCode from [dbo].[StaffAssignCountry] where StfCode=:user_id and [StatusAssign]=1)
							"; /* ;AND
                      [LayRListCode] IN (" . $district . ")

                      "; */

                    $this->query($dsql);
                    $this->bind(':user_id', $staff_code);
                    $district = $this->resultset();

                    foreach ($district as $key => $value) {
                        $data['district'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['district'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['district'][$key]['LayRListCode'] = $value['LayRListCode'];
                        $data['district'][$key]['LayRListName'] = $value['LayRListName'];
                    }
                } // end district
                // Upazilla
                if ($upazilla != false) {

                    //facthing only access upazilla name
                    $tsql = "SELECT DISTINCT [AdmCountryCode],[GeoLayRCode],[LayR1ListCode],[LayR2ListCode], [LayR2ListName]
        					FROM [dbo].[GeoLayR2List]
        					WHERE [AdmCountryCode]IN (Select AdmCountryCode from [dbo].[StaffAssignCountry] where StfCode=:user_id and [StatusAssign]=1)
							"; /* AND
                      [LayR1ListCode]+[LayR2ListCode] IN (" . $upazilla1 . ")

                      " */;

                    $this->query($tsql);
                    $this->bind(':user_id', $staff_code);
                    $upazilla = $this->resultset();

                    foreach ($upazilla as $key => $value) {
                        $data['upazilla'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['upazilla'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['upazilla'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['upazilla'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['upazilla'][$key]['LayR2ListName'] = $value['LayR2ListName'];
                    }
                } // end upazilla
                // Unit
                if ($unit != false) {


                    //facthing only accss unit
                    $usql = "SELECT DISTINCT [AdmCountryCode],[GeoLayRCode],[LayR1ListCode],[LayR2ListCode], [LayR3ListCode], [LayR3ListName]
        					FROM [dbo].[GeoLayR3List]
        					WHERE [AdmCountryCode] IN (Select AdmCountryCode from [dbo].[StaffAssignCountry] where StfCode=:user_id and [StatusAssign]=1)
							";

                    $this->query($usql);
                    $this->bind(':user_id', $staff_code);
                    $unit = $this->resultset();

                    foreach ($unit as $key => $value) {
                        $data['unit'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['unit'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['unit'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['unit'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['unit'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['unit'][$key]['LayR3ListName'] = $value['LayR3ListName'];
                    }
                } // end unit
                // Villate
                if ($village != false) {

                    $vsql = "SELECT DISTINCT vill.[AdmCountryCode]
									,vill.[GeoLayRCode]
									,vill.[LayR1ListCode]
									,vill.[LayR2ListCode]
									,vill.[LayR3ListCode]
									,vill.[LayR4ListCode]
									,vill.[LayR4ListName]
									,vill.[HHCount]
        					FROM [dbo].[StaffAssignCountry] AS stff
							JOIN [dbo].[GeoLayR4List] AS vill  ON stff.[AdmCountryCode]=vill.[AdmCountryCode]
        					WHERE  stff.[StfCode]= :user_id AND stff.[StatusAssign]=1
        			";

                    $this->query($vsql);
                    $this->bind(':user_id', $staff_code);    // IF CRASH THAN COMMENTS THIS LINE
                    $village = $this->resultset();

                    foreach ($village as $key => $value) {
                        $data['village'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['village'][$key]['GeoLayRCode'] = $value['GeoLayRCode'];
                        $data['village'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['village'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['village'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['village'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['village'][$key]['LayR4ListName'] = $value['LayR4ListName'];
                        $data['village'][$key]['HHCount'] = $value['HHCount'];
                    }
                } // end village
            }

            // getting data from Relation table
            $relsql = "SELECT [HHRelationCode],[RelationName] FROM [dbo].[LUP_RegNHHRelation]";

            $this->query($relsql);
            $relation = $this->resultset();

            if ($relation != false) {
                foreach ($relation as $key => $value) {
                    $data['relation'][$key]['HHRelationCode'] = $value['HHRelationCode'];
                    $data['relation'][$key]['RelationName'] = $value['RelationName'];
                }
            } // end Relation
            //added by pop
            // getting data from GPSGroupTable table

            $gps_groupsql = "SELECT [GrpCode]
				      ,[GrpName]
				      ,[Description]

				  FROM [dbo].[GPSGroupTable]
				 -- WHERE [EntryBy] = :user_id
				  ";
            //AND [SyncStatus]=1

            $this->query($gps_groupsql);
            //$this->bind(':user_id', $staff_code);
            $gps_group = $this->resultset();

            if ($gps_group != false) {

                foreach ($gps_group as $key => $value) {
                    $data['gps_group'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_group'][$key]['GrpName'] = $value['GrpName'];
                    $data['gps_group'][$key]['Description'] = $value['Description'];
                }
            }// end of gps_group table
            //added by pop
            // getting data from GPSSubGroupTable table

            $gps_subgroupsql = "SELECT [GrpCode]
					  ,[SubGrpCode]
				      ,[SubGrpName]
				      ,[Description]

				  FROM [dbo].[GPSSubGroupTable]
				 -- WHERE [EntryBy] = :user_id
				  ";


            $this->query($gps_subgroupsql);
            //$this->bind(':user_id', $staff_code);
            $gps_subgroup = $this->resultset();

            if ($gps_subgroup != false) {

                foreach ($gps_subgroup as $key => $value) {
                    $data['gps_subgroup'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_subgroup'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_subgroup'][$key]['SubGrpName'] = $value['SubGrpName'];
                    $data['gps_subgroup'][$key]['Description'] = $value['Description'];
                }
            }// end of gps_subgroup table
            //added by pop
            // getting data from GPSLocationTable table

            $gps_location_sql = "SELECT [AdmCountryCode]
			          ,[GrpCode]
					  ,[SubGrpCode]
					  ,[LocationCode]
				      ,[LocationName]
				        ,isnull([Long],'')As [Long]
					  ,isnull([Latd],'')AS [Latd]

				  FROM [dbo].[GPSLocationTable]

				  ";


            $this->query($gps_location_sql);
            //	$this->bind(':user_id', $staff_code);
            $gps_location = $this->resultset();

            if ($gps_location != false) {

                foreach ($gps_location as $key => $value) {
                    $data['gps_location'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['gps_location'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_location'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_location'][$key]['LocationCode'] = $value['LocationCode'];
                    $data['gps_location'][$key]['LocationName'] = $value['LocationName'];
                    $data['gps_location'][$key]['Long'] = $value['Long'];
                    $data['gps_location'][$key]['Latd'] = $value['Latd'];
                }
            }// end of gps_location table
            //adm_countryaward
            //added by pop
            // getting data from Adm_CountryAward table

            $adm_countryawardsql = "SELECT stff.[AdmCountryCode]
							,award.[AdmDonorCode]
							,award.[AdmAwardCode]
							,award.[AwardRefNumber]
							,award.[AwardStartDate]
							,award.[AwardEndDate]
							,award.[AwardShortName]
							,award.[AwardStatus]
						FROM [dbo].[StaffAssignCountry] AS stff
						JOIN [dbo].[AdmCountryAward] AS award  	ON award.[AdmCountryCode]=stff.[AdmCountryCode]
						WHERE stff.[StfCode]= :user_id AND stff.[StatusAssign]=1";


            $this->query($adm_countryawardsql);
            $this->bind(':user_id', $staff_code);
            $adm_countryaward = $this->resultset();

            if ($adm_countryaward != false) {

                foreach ($adm_countryaward as $key => $value) {
                    $data['adm_countryaward'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_countryaward'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_countryaward'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_countryaward'][$key]['AwardRefNumber'] = $value['AwardRefNumber'];
                    $data['adm_countryaward'][$key]['AwardStartDate'] = $value['AwardStartDate'];
                    $data['adm_countryaward'][$key]['AwardEndDate'] = $value['AwardEndDate'];
                    $data['adm_countryaward'][$key]['AwardShortName'] = $value['AwardShortName'];
                    $data['adm_countryaward'][$key]['AwardStatus'] = $value['AwardStatus'];
                    //	$data['adm_countryaward'][$key]['EntryDate'] 		= $value['EntryDate'];// no need
                }
            }// end of adm_countryaward table
           
		   $adm_awardsql = "SELECT [AwardCode]
      ,[AdmDonorCode]
      ,[AwardName]
      ,[AwardShort]
      
  FROM [dbo].[AdmAward]";


            $this->query($adm_awardsql);            
            $adm_award = $this->resultset();

            if ($adm_award != false) {

                foreach ($adm_award as $key => $value) {
                    $data['adm_award'][$key]['AwardCode'] = $value['AwardCode'];
                    $data['adm_award'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_award'][$key]['AwardName'] = $value['AwardName'];
                    $data['adm_award'][$key]['AwardShort'] = $value['AwardShort'];
                    
                }
            }// end of adm_award table
            // getting data from AdmDonor table

            $adm_donorsql = "SELECT [AdmDonorCode]
					  ,[AdmDonorName]

				  FROM [dbo].[AdmDonor]
				--  WHERE [EntryBy] = :user_id
				  ";


            $this->query($adm_donorsql);
            //	$this->bind(':user_id', $staff_code);
            $adm_donor = $this->resultset();

            if ($adm_donor != false) {

                foreach ($adm_donor as $key => $value) {
                    $data['adm_donor'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_donor'][$key]['AdmDonorName'] = $value['AdmDonorName'];
                }
            }// end of adm_donor table




            $adm_program_mastersql = "SELECT [AdmProgCode]
					  ,[AdmAwardCode]
			          ,[AdmDonorCode]
					  ,[ProgName]
					  ,[ProgShortName]
					  ,[MultipleSrv]


				  FROM [dbo].[AdmProgramMaster]

				  ";


            $this->query($adm_program_mastersql);
            //$this->bind(':user_id', $staff_code);
            $adm_program_master = $this->resultset();

            if ($adm_program_master != false) {

                foreach ($adm_program_master as $key => $value) {
                    $data['adm_program_master'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_program_master'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_program_master'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_program_master'][$key]['ProgName'] = $value['ProgName'];
                    $data['adm_program_master'][$key]['ProgShortName'] = $value['ProgShortName'];
                    $data['adm_program_master'][$key]['MultipleSrv'] = $value['MultipleSrv'];
                }
            }// end of adm_program_master table
            //adm_service_master
            //added by pop
            // getting data from AdmServiceMaster table

            $adm_service_mastersql = "SELECT [AdmProgCode]
					  ,[AdmSrvCode]
					  ,[AdmSrvName]
					  ,[AdmSrvShortName]

				  FROM [dbo].[AdmServiceMaster]

				  ";


            $this->query($adm_service_mastersql);
            $adm_service_master = $this->resultset();

            if ($adm_service_master != false) {

                foreach ($adm_service_master as $key => $value) {
                    $data['adm_service_master'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_service_master'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['adm_service_master'][$key]['AdmSrvName'] = $value['AdmSrvName'];
                    $data['adm_service_master'][$key]['AdmSrvShortName'] = $value['AdmSrvShortName'];
                }
            }// end of adm_service_master table
            //adm_op_month
            //added by pop
            // getting data from AdmOpMonthTable table

            $adm_op_monthsql = "SELECT [AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[OpCode]
					  ,[OpMonthCode]
					  ,[MonthLabel]
					  ,[StartDate]
					  ,[EndDate]
					  ,convert(varchar,[StartDate],110) AS UsaStartDate
					  ,convert(varchar,[EndDate],110) AS UsaEndDate
					  ,[Status]


				  FROM [dbo].[AdmOpMonthTable]
			--	  WHERE [EntryBy] = :user_id
				  ";


            $this->query($adm_op_monthsql);

            $adm_op_month = $this->resultset();

            if ($adm_op_month != false) {

                foreach ($adm_op_month as $key => $value) {
                    $data['adm_op_month'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_op_month'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_op_month'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_op_month'][$key]['OpCode'] = $value['OpCode'];
                    $data['adm_op_month'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                    $data['adm_op_month'][$key]['MonthLabel'] = $value['MonthLabel'];
                    $data['adm_op_month'][$key]['StartDate'] = $value['StartDate'];
                    $data['adm_op_month'][$key]['EndDate'] = $value['EndDate'];
                    $data['adm_op_month'][$key]['UsaStartDate'] = $value['UsaStartDate'];
                    $data['adm_op_month'][$key]['UsaEndDate'] = $value['UsaEndDate'];
                    $data['adm_op_month'][$key]['Status'] = $value['Status'];
                }
            }// end of adm_op_month table
            //AdmCountryProgram
            //added by pop
            // getting data from AdmCountryProgram table

            $adm_country_programsql = "SELECT [AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[AdmProgCode]
					  ,[AdmSrvCode]
					  ,[ProgFlag]
					  ,[FoodFlag]
					  ,[NFoodFlag]
					  ,[CashFlag]
					  ,[VOFlag]
					  ,[DefaultFoodDays]
					  ,[DefaultNFoodDays]
					  ,[DefaultCashDays]
					  ,[DefaultVODays]
					  ,[SrvSpecific]
					  FROM [dbo].[AdmCountryProgram]

				  ";


            $this->query($adm_country_programsql);
            $adm_country_program = $this->resultset();

            if ($adm_country_program != false) {

                foreach ($adm_country_program as $key => $value) {
                    $data['adm_country_program'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['adm_country_program'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['adm_country_program'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['adm_country_program'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['adm_country_program'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['adm_country_program'][$key]['ProgFlag'] = $value['ProgFlag'];
                    $data['adm_country_program'][$key]['FoodFlag'] = $value['FoodFlag'];
                    $data['adm_country_program'][$key]['NFoodFlag'] = $value['NFoodFlag'];
                    $data['adm_country_program'][$key]['CashFlag'] = $value['CashFlag'];
                    $data['adm_country_program'][$key]['VOFlag'] = $value['VOFlag'];
                    $data['adm_country_program'][$key]['DefaultFoodDays'] = $value['DefaultFoodDays'];
                    $data['adm_country_program'][$key]['DefaultNFoodDays'] = $value['DefaultNFoodDays'];
                    $data['adm_country_program'][$key]['DefaultCashDays'] = $value['DefaultCashDays'];
                    $data['adm_country_program'][$key]['DefaultVODays'] = $value['DefaultVODays'];
                    $data['adm_country_program'][$key]['SrvSpecific'] = $value['SrvSpecific'];
                }
            }// end of AdmCountryProgram table


            /** @modify:2015-10-20 */
            $dob_service_centersql = "
				 select	  SrvCenterTable.AdmCountryCode ,
								SrvCenterTable.SrvCenterName,SrvCenterTable.SrvCenterCode, SrvCenterTable.FDPCode  from SrvCenterTable

							ORDER BY SrvCenterTable.SrvCenterName


				 	";


            $this->query($dob_service_centersql);

            $dob_service_center = $this->resultset();

            if ($dob_service_center != false) {

                foreach ($dob_service_center as $key => $value) {
                    $data['dob_service_center'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];

                    $data['dob_service_center'][$key]['SrvCenterName'] = $value['SrvCenterName'];
                    $data['dob_service_center'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                    $data['dob_service_center'][$key]['FDPCode'] = $value['FDPCode'];
                }
            }// end of SrvCenterTable table
            //StaffGeoInfoAccessTable
            //added by pop
            // getting data from StaffGeoInfoAccess table StfCode

            $staff_access_info_sql = "SELECT [StfCode]
					  ,[AdmCountryCode]
					  ,[AdmDonorCode]
					  ,[AdmAwardCode]
					  ,[LayRListCode]
					  ,[btnNew]
					  ,[btnSave]
					  ,[btnDel]
					  ,[btnPepr]
					  ,[btnAprv]
					  ,[btnRevw]
					  ,[btnVrfy]
					  ,[btnDTran]
					  FROM [dbo].[StaffGeoInfoAccess]
				  WHERE [StfCode] = :user_id and ( btnNew =1 OR btnSave=1 OR btnDel=1 )
				  ";


            $this->query($staff_access_info_sql);
            $this->bind(':user_id', $staff_code);
            $staff_access_info = $this->resultset();

            if ($staff_access_info != false) {

                foreach ($staff_access_info as $key => $value) {
                    $data['staff_access_info'][$key]['StfCode'] = $value['StfCode'];
                    $data['staff_access_info'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_access_info'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['staff_access_info'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['staff_access_info'][$key]['LayRListCode'] = $value['LayRListCode'];
                    $data['staff_access_info'][$key]['btnNew'] = $value['btnNew'];
                    $data['staff_access_info'][$key]['btnSave'] = $value['btnSave'];
                    $data['staff_access_info'][$key]['btnDel'] = $value['btnDel'];
                    $data['staff_access_info'][$key]['btnPepr'] = $value['btnPepr'];
                    $data['staff_access_info'][$key]['btnAprv'] = $value['btnAprv'];
                    $data['staff_access_info'][$key]['btnRevw'] = $value['btnRevw'];
                    $data['staff_access_info'][$key]['btnVrfy'] = $value['btnVrfy'];
                    $data['staff_access_info'][$key]['btnDTran'] = $value['btnDTran'];
                }
            }// end of StaffGeoInfoAccess table



			$staff_srv_center_access_sql = "SELECT [StfCode]
											,[AdmCountryCode]
											,[SrvCenterCode]
											,[btnNew]
											,[btnSave]
											,[btnDel]										
																						
								FROM [dbo].[StaffSrvCenterAccess]
				  WHERE [StfCode] = :user_id and ( btnNew =1 OR btnSave=1 OR btnDel=1 )
				  ";


            $this->query($staff_srv_center_access_sql);
            $this->bind(':user_id', $staff_code);
            $staff_srv_center_access = $this->resultset();

            if ($staff_srv_center_access != false) {

                foreach ($staff_srv_center_access as $key => $value) {
                    $data['staff_srv_center_access'][$key]['StfCode'] = $value['StfCode'];
                    $data['staff_srv_center_access'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_srv_center_access'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];            
                    $data['staff_srv_center_access'][$key]['btnNew'] = $value['btnNew'];
                    $data['staff_srv_center_access'][$key]['btnSave'] = $value['btnSave'];
                    $data['staff_srv_center_access'][$key]['btnDel'] = $value['btnDel'];
         
                }
            }// end of StaffGeoInfoAccess table


            $lb_reg_hh_categorysql = "SELECT [AdmCountryCode]
			   ,[HHHeadCatCode]
			   ,[CatName]
			   FROM [dbo].[LUP_RegNHHHeadCategory] ";


            $this->query($lb_reg_hh_categorysql);

            $lb_reg_hh_category = $this->resultset();

            if ($lb_reg_hh_category != false) {

                foreach ($lb_reg_hh_category as $key => $value) {
                    $data['lb_reg_hh_category'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lb_reg_hh_category'][$key]['HHHeadCatCode'] = $value['HHHeadCatCode'];
                    $data['lb_reg_hh_category'][$key]['CatName'] = $value['CatName'];
                }
            }// end of LUP_RegNHHHeadCategory table


            $reg_lup_graduationsql = "SELECT [AdmProgCode]
									,[AdmSrvCode]
									,[GRDCode]
									,[GRDTitle]
									,[DefaultCatActive]
									,[DefaultCatExit]

								FROM  [dbo].[RegNLUP_Graduation]";


            $this->query($reg_lup_graduationsql);

            $reg_lup_graduation = $this->resultset();

            if ($reg_lup_graduation != false) {

                foreach ($reg_lup_graduation as $key => $value) {
                    $data['reg_lup_graduation'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['reg_lup_graduation'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['reg_lup_graduation'][$key]['GRDCode'] = $value['GRDCode'];
                    $data['reg_lup_graduation'][$key]['GRDTitle'] = $value['GRDTitle'];
                    $data['reg_lup_graduation'][$key]['DefaultCatActive'] = $value['DefaultCatActive'];
                    $data['reg_lup_graduation'][$key]['DefaultCatExit'] = $value['DefaultCatExit'];
                }
            }// end of RegNLUP_Graduation table

            /**   */
            /* date: 2016-01-27 */
            $report_templateSql = "SELECT [AdmCountryCode], [RptLabel]
									,RptGroup+RptCode as Code
									FROM  [dbo].[RptTemplateTable]
									 WHERE (RptGroup = '01') AND (RptGenericCode = '1')
									AND (AdmReportIsActive = 'A')
									AND (AdmCountryCode = (select AdmCountryCode
									from dbo.StaffAssignCountry where StfCode= :user_id and StatusAssign=1))
									ORDER BY RptLabel					";

            $this->query($report_templateSql);
            $this->bind(':user_id', $staff_code);

            $report_template = $this->resultset();

            if ($report_template != false) {

                foreach ($report_template as $key => $value) {
                    $data['report_template'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['report_template'][$key]['RptLabel'] = $value['RptLabel'];
                    $data['report_template'][$key]['Code'] = $value['Code'];
                }
            }// end of [dbo].[RptTemplateTable]table

            /* date: 2015-11-05 */
            $card_print_reasonSql = "SELECT [ReasonCode]
									,[ReasonTitle]
									FROM  [dbo].[LUP_RegNCardPrintReason]";


            $this->query($card_print_reasonSql);

            $card_print_reason = $this->resultset();

            if ($card_print_reason != false) {

                foreach ($card_print_reason as $key => $value) {
                    $data['card_print_reason'][$key]['ReasonCode'] = $value['ReasonCode'];
                    $data['card_print_reason'][$key]['ReasonTitle'] = $value['ReasonTitle'];
                }
            }// end of [dbo].[LUP_RegNCardPrintReason] table
            // decode the json array
            $jData = json_decode($lay_r_code_j);


            $s = "";
            $countryCode = "";
            $isAddressCodeAdded = false;
            //echo($operation_mode);
            switch ($operation_mode) {

                case 1:// for Registration 2 village Code will be selected
                    $i = 0;
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';


                        if ($i < 1) {
                            $isAdd = $value->selectedLayR4Code;
                            // echo(strlen($isAdd));
                            $isAddressCodeAdded = true;
                            ++$i;
                        }
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 			echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    break;
                case 2 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {
                        // set the valu of selected

                        $s .= $value->selectedLayR4Code . ',';
                        //echo($s);
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 3 : // fro distribution 2 fdp code is select
                    foreach ($jData as $key => $value) {

                        $s .= $value->selectedLayR4Code . ',';
                    }
                    // to re move the last comma
                    $s = substr($s, 0, -1);
                    // to show the list 					echo($s);
                    $countryCode = substr($s, 0, 4); // to get the country code
                    //echo($countryCode);
                    break;

                case 4 : // fro distribution 2 fdp code is select

                    $s = "0";

                    break;
            }


            if ($jData) {
                $reg_mem_card_request_sql = "";
                $distribution_table_sql = "";
                $distribution_ext_table_sql = "";
                $dist_basic_table_sql = "";
                $community_grp_detail_sql = "";
                $community_group_sql = "";
                switch ($operation_mode) {
                    case 1:        // for registration
                        if ($isAddressCodeAdded) {
                            /**
                             *
                             * getting data from RegNMemCardPrintTable table
                             */
                            $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (SELECT   [AdmCountryCode]
                    +[LayR1ListCode]							  +[LayR2ListCode]							  +[LayR3ListCode]
                    +[LayR4ListCode] from [RegNHHTable] where AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")

		)";


                            // start distribution_table


                            $distribution_table_sql = "SELECT [AdmCountryCode]
									  ,[AdmDonorCode]
									  ,[AdmAwardCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[ProgCode]
									  ,[SrvCode]
									  ,[OpMonthCode]
									  ,[FDPCode]
									  ,[ID]
									  ,[DistStatus]
									  ,[SrvOpMonthCode]
									  ,[DistFlag]
									  ,[WD]
									--  ,[EntryBy]
									--  ,[EntryDate]
								FROM [dbo].[DistTable]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";

                            // start distribution Extend_table


                            $distribution_ext_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[ProgCode]
											,[SrvCode]
											,[OpMonthCode]
											,[FDPCode]
											,[ID]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[SrvOpMonthCode]
											,[DistFlag]

										  FROM [dbo].[DistExtendedTable]
											where
						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                            $dist_basic_table_sql = "SELECT [AdmCountryCode]
                                                      ,[AdmDonorCode]
                                                      ,[AdmAwardCode]
                                                      ,[ProgCode]
                                                      ,[OpCode]
                                                      ,[SrvOpMonthCode]
                                                      ,[DisOpMonthCode]
                                                      ,[FDPCode]
                                                      ,[DistFlag]
                                                      ,[OrgCode]
                                                      ,[Distributor]
                                                      ,[DistributionDate]
                                                      ,[DeliveryDate]
                                                      ,[Status]
                                                      ,[PreparedBy]
                                                      ,[VerifiedBy]
                                                      ,[ApproveBy]
                                                      ,[EntryBy]
                                                      ,[EntryDate]
                                                  FROM [dbo].[DistNPlanBasic]

						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						where [AdmCountryCode]+ FDPCode IN (0)";
                        } else {
                            /**
                             *
                             * getting data from RegNMemCardPrintTable table
                             */
                            $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (" . $s . ")";


                            // start distribution_table


                            $distribution_table_sql = "SELECT [AdmCountryCode]
									  ,[AdmDonorCode]
									  ,[AdmAwardCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[ProgCode]
									  ,[SrvCode]
									  ,[OpMonthCode]
									  ,[FDPCode]
									  ,[ID]
									  ,[DistStatus]
									  ,[SrvOpMonthCode]
									  ,[DistFlag]
									  ,[WD]
									--  ,[EntryBy]
									--  ,[EntryDate]
								FROM [dbo].[DistTable]

								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";

                            // start distribution Extend_table


                            $distribution_ext_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[ProgCode]
											,[SrvCode]
											,[OpMonthCode]
											,[FDPCode]
											,[ID]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[SrvOpMonthCode]
											,[DistFlag]


										  FROM [dbo].[DistExtendedTable]
											where
						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                            $dist_basic_table_sql = "SELECT [AdmCountryCode]
                                                      ,[AdmDonorCode]
                                                      ,[AdmAwardCode]
                                                      ,[ProgCode]
                                                      ,[OpCode]
                                                      ,[SrvOpMonthCode]
                                                      ,[DisOpMonthCode]
                                                      ,[FDPCode]
                                                      ,[DistFlag]
                                                      ,[OrgCode]
                                                      ,[Distributor]
                                                      ,[DistributionDate]
                                                      ,[DeliveryDate]
                                                      ,[Status]
                                                      ,[PreparedBy]
                                                      ,[VerifiedBy]
                                                      ,[ApproveBy]
                                                      ,[EntryBy]
                                                      ,[EntryDate]
                                                  FROM [dbo].[DistNPlanBasic]

						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						where [AdmCountryCode]+ FDPCode IN (0)";
                        }

                        $community_grp_detail_sql = "SELECT [AdmCountryCode]
                                      ,[AdmDonorCode]
                                        ,[AdmAwardCode]
                                        ,[AdmProgCode]
                                        ,[GrpCode]
                                        ,[OrgCode]
                                        ,[StfCode]
                                        ,[LandSizeUnderIrrigation]
                                        ,[IrrigationSystemUsed]
                                        ,[FundSupport]
                                        ,[ActiveStatus]
                                        ,[RepName]
                                        ,[RepPhoneNumber]
                                        ,convert(varchar,[FormationDate],110) AS [FormationDate]
                                        ,[TypeOfGroup]
                                        ,[Status]
                                        ,[EntryBy]
                                        ,[EntryDate]
                                        ,[ProjectNo]
                                        ,[ProjectTitle]
	                                    ,LayR1Code
	                                    ,LayR2Code
	                                    ,LayR3Code
                      FROM [dbo].[CommunityGrpDetail]";

                        $community_group_sql = "SELECT [AdmCountryCode]
										  ,[AdmDonorCode]
										  ,[AdmAwardCode]
										  ,[AdmProgCode]
										  ,[GrpCode]
										  ,[GrpName]
										  ,[GrpCatCode]
										  ,[LayR1Code]
										  ,[LayR2Code]
										  ,[LayR3Code]
										  ,[SrvCenterCode]
									  FROM [dbo].[CommunityGroup]
									  ";
                        break;
                    case 2: // for distribution


                        /**
                         *
                         * getting data from RegNMemCardPrintTable table
                         * *** no data will dowelodload for reg_mem_card_request_sql
                         */
                        $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        // start distribution_table


                        $distribution_table_sql = "SELECT [AdmCountryCode]
									  ,[AdmDonorCode]
									  ,[AdmAwardCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[ProgCode]
									  ,[SrvCode]
									  ,[OpMonthCode]
									  ,[FDPCode]
									  ,[ID]
									  ,[DistStatus]
									  ,[SrvOpMonthCode]
									  ,[DistFlag]
									  ,[WD]
									--  ,[EntryBy]
									--  ,[EntryDate]
								FROM [dbo].[DistTable]
								where [AdmCountryCode]+ FDPCode IN (" . $s . ")

						and [OpMonthCode] in(Select OpMonthCode
						from AdmOpMonthTable where Status='A'
						and OpCode='3' and admcountryCode =" . $countryCode . ")
									";

                        // start distribution Extend_table


                        $distribution_ext_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[ProgCode]
											,[SrvCode]
											,[OpMonthCode]
											,[FDPCode]
											,[ID]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[EntryBy]
											,[EntryDate]
											,[SrvOpMonthCode]
											,[DistFlag]
										FROM [dbo].[DistExtendedTable]
								where [AdmCountryCode]+FDPCode in(" . $s . ")

							and [OpMonthCode] in(Select OpMonthCode from AdmOpMonthTable where Status='A' and OpCode='3'
							and admcountryCode =" . $countryCode . ")";


                        $dist_basic_table_sql = "SELECT [AdmCountryCode]
                                                      ,[AdmDonorCode]
                                                      ,[AdmAwardCode]
                                                      ,[ProgCode]
                                                      ,[OpCode]
                                                      ,[SrvOpMonthCode]
                                                      ,[DisOpMonthCode]
                                                      ,[FDPCode]
                                                      ,[DistFlag]
                                                      ,[OrgCode]
                                                      ,[Distributor]
                                                      ,[DistributionDate]
                                                      ,[DeliveryDate]
                                                      ,[Status]
                                                      ,[PreparedBy]
                                                      ,[VerifiedBy]
                                                      ,[ApproveBy]
                                                      ,[EntryBy]
                                                      ,[EntryDate]
                                                  FROM [dbo].[DistNPlanBasic]

						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						where [AdmCountryCode]+ FDPCode IN (" . $s . ")";

                        $community_grp_detail_sql = "SELECT [AdmCountryCode]
                                      ,[AdmDonorCode]
                                        ,[AdmAwardCode]
                                        ,[AdmProgCode]
                                        ,[GrpCode]
                                        ,[OrgCode]
                                        ,[StfCode]
                                        ,[LandSizeUnderIrrigation]
                                        ,[IrrigationSystemUsed]
                                        ,[FundSupport]
                                        ,[ActiveStatus]
                                        ,[RepName]
                                        ,[RepPhoneNumber]
                                        ,convert(varchar,[FormationDate],110) AS [FormationDate]
                                        ,[TypeOfGroup]
                                        ,[Status]
                                        ,[EntryBy]
                                        ,[EntryDate]
                                        ,[ProjectNo]
                                        ,[ProjectTitle]
	                                    ,LayR1Code
	                                    ,LayR2Code
	                                    ,LayR3Code
                      FROM [dbo].[CommunityGrpDetail]";

                        $community_group_sql = "SELECT [AdmCountryCode]
										  ,[AdmDonorCode]
										  ,[AdmAwardCode]
										  ,[AdmProgCode]
										  ,[GrpCode]
										  ,[GrpName]
										  ,[GrpCatCode]
										  ,[LayR1Code]
										  ,[LayR2Code]
										  ,[LayR3Code]
										  ,[SrvCenterCode]
									  FROM [dbo].[CommunityGroup]
									  ";
                        break;

                    case 3: // for service


                        /**
                         *
                         * getting data from RegNMemCardPrintTable table
                         * *** no data will dowelodload for reg_mem_card_request_sql & Distribution Data
                         */
                        $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        // start distribution_table


                        $distribution_table_sql = "SELECT [AdmCountryCode]
									  ,[AdmDonorCode]
									  ,[AdmAwardCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[ProgCode]
									  ,[SrvCode]
									  ,[OpMonthCode]
									  ,[FDPCode]
									  ,[ID]
									  ,[DistStatus]
									  ,[SrvOpMonthCode]
									  ,[DistFlag]
									  ,[WD]
									--  ,[EntryBy]
									--  ,[EntryDate]
								FROM [dbo].[DistTable]
								where [AdmCountryCode]+ FDPCode IN (0)
									";

                        // start distribution Extend_table


                        $distribution_ext_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[ProgCode]
											,[SrvCode]
											,[OpMonthCode]
											,[FDPCode]
											,[ID]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[EntryBy]
											,[EntryDate]
											,[SrvOpMonthCode]
											,[DistFlag]
										FROM [dbo].[DistExtendedTable]
								where [AdmCountryCode]+FDPCode in(0)";


                        $dist_basic_table_sql = "SELECT [AdmCountryCode]
                                                      ,[AdmDonorCode]
                                                      ,[AdmAwardCode]
                                                      ,[ProgCode]
                                                      ,[OpCode]
                                                      ,[SrvOpMonthCode]
                                                      ,[DisOpMonthCode]
                                                      ,[FDPCode]
                                                      ,[DistFlag]
                                                      ,[OrgCode]
                                                      ,[Distributor]
                                                      ,[DistributionDate]
                                                      ,[DeliveryDate]
                                                      ,[Status]
                                                      ,[PreparedBy]
                                                      ,[VerifiedBy]
                                                      ,[ApproveBy]
                                                      ,[EntryBy]
                                                      ,[EntryDate]
                                                  FROM [dbo].[DistNPlanBasic]

						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						where [AdmCountryCode]+ FDPCode IN (0)";

                        $community_grp_detail_sql = "SELECT [AdmCountryCode]
                                      ,[AdmDonorCode]
                                        ,[AdmAwardCode]
                                        ,[AdmProgCode]
                                        ,[GrpCode]
                                        ,[OrgCode]
                                        ,[StfCode]
                                        ,[LandSizeUnderIrrigation]
                                        ,[IrrigationSystemUsed]
                                        ,[FundSupport]
                                        ,[ActiveStatus]
                                        ,[RepName]
                                        ,[RepPhoneNumber]
                                        ,convert(varchar,[FormationDate],110) AS [FormationDate]
                                        ,[TypeOfGroup]
                                        ,[Status]
                                        ,[EntryBy]
                                        ,[EntryDate]
                                        ,[ProjectNo]
                                        ,[ProjectTitle]
	                                    ,LayR1Code
	                                    ,LayR2Code
	                                    ,LayR3Code
                      FROM [dbo].[CommunityGrpDetail]";

                    /*    $community_group_sql = "SELECT [AdmCountryCode]
										  ,[AdmDonorCode]
										  ,[AdmAwardCode]
										  ,[AdmProgCode]
										  ,[GrpCode]
										  ,[GrpName]
										  ,[GrpCatCode]
										  ,[LayR1Code]
										  ,[LayR2Code]
										  ,[LayR3Code]
										  ,[SrvCenterCode]
									  FROM [dbo].[CommunityGroup]
									  ";*/
                        break;

                    case 4: // for other or dynamic table
                    case 5: // for training N activity  


                        /**
                         *
                         * getting data from RegNMemCardPrintTable table
                         * *** no data will download for reg_mem_card_request_sql & Distribution Data
                         */
                        $reg_mem_card_request_sql = "SELECT [AdmCountryCode]
									,[AdmDonorCode]
									,[AdmAwardCode]
									,[LayR1ListCode]
									,[LayR2ListCode]
									,[LayR3ListCode]
									,[LayR4ListCode]
									,[HHID]
									,[MemID]
									,[RptGroup]
									,[RptCode]
									,[RequestSL]
									,[ReasonCode]
									,[RequestDate]
									,[PrintDate]
									,[PrintBy]
									,[DeliveryDate]
									,[DeliveredBy]
									,[DelStatus]
									,[EntryBy]
									,[EntryDate]

								FROM  [dbo].[RegNMemCardPrintTable]
								where
				 --  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						 AdmCountryCode+LayR1ListCode+LayR2ListCode+LayR3ListCode+LayR4ListCode in (0)";


                        // start distribution_table


                        $distribution_table_sql = "SELECT [AdmCountryCode]
									  ,[AdmDonorCode]
									  ,[AdmAwardCode]
									  ,[LayR1ListCode]
									  ,[LayR2ListCode]
									  ,[LayR3ListCode]
									  ,[LayR4ListCode]
									  ,[ProgCode]
									  ,[SrvCode]
									  ,[OpMonthCode]
									  ,[FDPCode]
									  ,[ID]
									  ,[DistStatus]
									  ,[SrvOpMonthCode]
									  ,[DistFlag]
									  ,[WD]
									--  ,[EntryBy]
									--  ,[EntryDate]
								FROM [dbo].[DistTable]
								where [AdmCountryCode]+ FDPCode IN (0)
									";

                        // start distribution Extend_table


                        $distribution_ext_table_sql = "SELECT [AdmCountryCode]
											,[AdmDonorCode]
											,[AdmAwardCode]
											,[LayR1ListCode]
											,[LayR2ListCode]
											,[LayR3ListCode]
											,[LayR4ListCode]
											,[ProgCode]
											,[SrvCode]
											,[OpMonthCode]
											,[FDPCode]
											,[ID]
											,[VOItmSpec]
											,[VOItmUnit]
											,[VORefNumber]
											,[EntryBy]
											,[EntryDate]
											,[SrvOpMonthCode]
											,[DistFlag]
										FROM [dbo].[DistExtendedTable]
								where [AdmCountryCode]+FDPCode in(0)";


                        $dist_basic_table_sql = "SELECT [AdmCountryCode]
                                                      ,[AdmDonorCode]
                                                      ,[AdmAwardCode]
                                                      ,[ProgCode]
                                                      ,[OpCode]
                                                      ,[SrvOpMonthCode]
                                                      ,[DisOpMonthCode]
                                                      ,[FDPCode]
                                                      ,[DistFlag]
                                                      ,[OrgCode]
                                                      ,[Distributor]
                                                      ,[DistributionDate]
                                                      ,[DeliveryDate]
                                                      ,[Status]
                                                      ,[PreparedBy]
                                                      ,[VerifiedBy]
                                                      ,[ApproveBy]
                                                      ,[EntryBy]
                                                      ,[EntryDate]
                                                  FROM [dbo].[DistNPlanBasic]

						--  CHECK COUNTRY IS ASSIGNED OR NOT & does he have access or not
						where [AdmCountryCode]+ FDPCode IN (0)";

                        $community_grp_detail_sql = "SELECT [AdmCountryCode]
                            ,[AdmDonorCode]
                            ,[AdmAwardCode]
                            ,[AdmProgCode]
                            ,[GrpCode]
                            ,[OrgCode]
                            ,[StfCode]
                            ,[LandSizeUnderIrrigation]
                            ,[IrrigationSystemUsed]
                            ,[FundSupport]
                            ,[ActiveStatus]
                            ,[RepName]
                            ,[RepPhoneNumber]
                            ,convert(varchar,[FormationDate],110) AS [FormationDate]
                            ,[TypeOfGroup]
                            ,[Status]
                            ,[EntryBy]
                            ,[EntryDate]
                            ,[ProjectNo]
                            ,[ProjectTitle]
                        	  ,LayR1Code
                        	  ,LayR2Code
                        	  ,LayR3Code
                        FROM [dbo].[CommunityGrpDetail]
                      
  ";

                        // CommunityGroup  table
                      /*  $community_group_sql = "SELECT [AdmCountryCode]
										  ,[AdmDonorCode]
										  ,[AdmAwardCode]
										  ,[AdmProgCode]
										  ,[GrpCode]
										  ,[GrpName]
										  ,[GrpCatCode]
										  ,[LayR1Code]
										  ,[LayR2Code]
										  ,[LayR3Code]
										  ,[SrvCenterCode]
									  FROM [dbo].[CommunityGroup]
									
									  ";*/

                        break;
                }

//echo($community_group_sql);
                //		 (Select AdmCountryCode+[LayRListCode] from [dbo].[StaffGeoInfoAccess]  where StfCode= :user_id
                //		 and (btnNew=1 or btnSave=1 or btnDel=1))								";


                $this->query($reg_mem_card_request_sql);

                $reg_mem_card_request = $this->resultset();

                if ($reg_mem_card_request != false) {

                    foreach ($reg_mem_card_request as $key => $value) {
                        $data['reg_mem_card_request'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['reg_mem_card_request'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['reg_mem_card_request'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['reg_mem_card_request'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['reg_mem_card_request'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['reg_mem_card_request'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['reg_mem_card_request'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['reg_mem_card_request'][$key]['HHID'] = $value['HHID'];
                        $data['reg_mem_card_request'][$key]['MemID'] = $value['MemID'];
                        $data['reg_mem_card_request'][$key]['RptGroup'] = $value['RptGroup'];
                        $data['reg_mem_card_request'][$key]['RptCode'] = $value['RptCode'];
                        $data['reg_mem_card_request'][$key]['RequestSL'] = $value['RequestSL'];
                        $data['reg_mem_card_request'][$key]['ReasonCode'] = $value['ReasonCode'];
                        $data['reg_mem_card_request'][$key]['RequestDate'] = $value['RequestDate'];
                        $data['reg_mem_card_request'][$key]['PrintDate'] = $value['PrintDate'];
                        $data['reg_mem_card_request'][$key]['PrintBy'] = $value['PrintBy'];
                        $data['reg_mem_card_request'][$key]['DeliveryDate'] = $value['DeliveryDate'];
                        $data['reg_mem_card_request'][$key]['DeliveredBy'] = $value['DeliveredBy'];
                        $data['reg_mem_card_request'][$key]['DelStatus'] = $value['DelStatus'];
                        $data['reg_mem_card_request'][$key]['EntryBy'] = $value['EntryBy'];
                        $data['reg_mem_card_request'][$key]['EntryDate'] = $value['EntryDate'];
                    }
                }// end of RegNMemCardPrintTable table
                //		 (Select AdmCountryCode+[LayRListCode] from [dbo].[StaffGeoInfoAccess]  where StfCode= :user_id
                //		 and (btnNew=1 or btnSave=1 or btnDel=1))
                //	 ";

                $this->query($distribution_table_sql);
                // $this->bind(':user_id', $staff_code);
                $distribution_table = $this->resultset();

                if ($distribution_table != false) {

                    foreach ($distribution_table as $key => $value) {
                        $data['distribution_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['distribution_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['distribution_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['distribution_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['distribution_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['distribution_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['distribution_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['distribution_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['distribution_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['distribution_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['distribution_table'][$key]['FDPCode'] = $value['FDPCode'];
                        $data['distribution_table'][$key]['ID'] = $value['ID'];
                        $data['distribution_table'][$key]['DistStatus'] = $value['DistStatus'];
                        $data['distribution_table'][$key]['SrvOpMonthCode'] = $value['SrvOpMonthCode'];
                        $data['distribution_table'][$key]['DistFlag'] = $value['DistFlag'];
                        $data['distribution_table'][$key]['WD'] = $value['WD'];
                    }
                }// end of distribution_table table
                // distribution Extended

                $this->query($distribution_ext_table_sql);
                $distribution_ext_table = $this->resultset();

                if ($distribution_ext_table != false) {

                    foreach ($distribution_ext_table as $key => $value) {
                        $data['distribution_ext_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['distribution_ext_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['distribution_ext_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['distribution_ext_table'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                        $data['distribution_ext_table'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                        $data['distribution_ext_table'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                        $data['distribution_ext_table'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                        $data['distribution_ext_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['distribution_ext_table'][$key]['SrvCode'] = $value['SrvCode'];
                        $data['distribution_ext_table'][$key]['OpMonthCode'] = $value['OpMonthCode'];
                        $data['distribution_ext_table'][$key]['FDPCode'] = $value['FDPCode'];
                        $data['distribution_ext_table'][$key]['ID'] = $value['ID'];
                        $data['distribution_ext_table'][$key]['VOItmSpec'] = $value['VOItmSpec'];
                        $data['distribution_ext_table'][$key]['VOItmUnit'] = $value['VOItmUnit'];
                        $data['distribution_ext_table'][$key]['VORefNumber'] = $value['VORefNumber'];
                        $data['distribution_ext_table'][$key]['SrvOpMonthCode'] = $value['SrvOpMonthCode'];
                        $data['distribution_ext_table'][$key]['DistFlag'] = $value['DistFlag'];
                    }
                }// end of distribution_table table
                // distribution Extended


                $this->query($dist_basic_table_sql);
                $dist_basic_table = $this->resultset();

                if ($dist_basic_table != false) {


                    foreach ($dist_basic_table as $key => $value) {
                        $data['distbasic_table'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['distbasic_table'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['distbasic_table'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['distbasic_table'][$key]['ProgCode'] = $value['ProgCode'];
                        $data['distbasic_table'][$key]['OpCode'] = $value['OpCode'];
                        $data['distbasic_table'][$key]['SrvOpMonthCode'] = $value['SrvOpMonthCode'];
                        $data['distbasic_table'][$key]['DisOpMonthCode'] = $value['DisOpMonthCode'];
                        $data['distbasic_table'][$key]['FDPCode'] = $value['FDPCode'];
                        $data['distbasic_table'][$key]['DistFlag'] = $value['DistFlag'];
                        $data['distbasic_table'][$key]['OrgCode'] = $value['OrgCode'];
                        $data['distbasic_table'][$key]['Distributor'] = $value['Distributor'];
                        $data['distbasic_table'][$key]['DistributionDate'] = $value['DistributionDate'];
                        $data['distbasic_table'][$key]['DeliveryDate'] = $value['DeliveryDate'];
                        $data['distbasic_table'][$key]['Status'] = $value['Status'];
                        $data['distbasic_table'][$key]['PreparedBy'] = $value['PreparedBy'];
                        $data['distbasic_table'][$key]['VerifiedBy'] = $value['VerifiedBy'];
                        $data['distbasic_table'][$key]['ApproveBy'] = $value['ApproveBy'];
                    }
                }// end of distribution_table table*/


                $this->query($community_grp_detail_sql);
                $community_grp_details = $this->resultset();

                if ($community_grp_details != false) {

                    foreach ($community_grp_details as $key => $value) {
                        $data['community_grp_detail'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['community_grp_detail'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['community_grp_detail'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['community_grp_detail'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                        $data['community_grp_detail'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['community_grp_detail'][$key]['OrgCode'] = $value['OrgCode'];
                        $data['community_grp_detail'][$key]['StfCode'] = $value['StfCode'];
                        $data['community_grp_detail'][$key]['LandSizeUnderIrrigation'] = $value['LandSizeUnderIrrigation'];
                        $data['community_grp_detail'][$key]['IrrigationSystemUsed'] = $value['IrrigationSystemUsed'];
                        $data['community_grp_detail'][$key]['FundSupport'] = $value['FundSupport'];
                        $data['community_grp_detail'][$key]['ActiveStatus'] = $value['ActiveStatus'];
                        $data['community_grp_detail'][$key]['RepName'] = $value['RepName'];
                        $data['community_grp_detail'][$key]['RepPhoneNumber'] = $value['RepPhoneNumber'];
                        $data['community_grp_detail'][$key]['FormationDate'] = $value['FormationDate'];
                        $data['community_grp_detail'][$key]['TypeOfGroup'] = $value['TypeOfGroup'];
                        $data['community_grp_detail'][$key]['Status'] = $value['Status'];
                        $data['community_grp_detail'][$key]['EntryBy'] = $value['EntryBy'];
                        $data['community_grp_detail'][$key]['EntryDate'] = $value['EntryDate'];
                        $data['community_grp_detail'][$key]['ProjectNo'] = $value['ProjectNo'];
                        $data['community_grp_detail'][$key]['ProjectTitle'] = $value['ProjectTitle'];
                        $data['community_grp_detail'][$key]['LayR1Code'] = $value['LayR1Code'];
                        $data['community_grp_detail'][$key]['LayR2Code'] = $value['LayR2Code'];
                        $data['community_grp_detail'][$key]['LayR3Code'] = $value['LayR3Code'];
                    }
                }// end of GPSSubGroupAttributes

               
            }// end of the jsonData
			
			# for all case the community group is needed 
			 // CommunityGroup  table 
                        $community_group_sql = "SELECT [AdmCountryCode]
										  ,[AdmDonorCode]
										  ,[AdmAwardCode]
										  ,[AdmProgCode]
										  ,[GrpCode]
										  ,[GrpName]
										  ,[GrpCatCode]
										  ,[LayR1Code]
										  ,[LayR2Code]
										  ,[LayR3Code]
										  ,[SrvCenterCode]
									  FROM [dbo].[CommunityGroup]
									
									  ";

			
			
			 $this->query($community_group_sql);
                $community_groups = $this->resultset();

                if ($community_groups != false) {

                    foreach ($community_groups as $key => $value) {
                        $data['community_group'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                        $data['community_group'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                        $data['community_group'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                        $data['community_group'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                        $data['community_group'][$key]['GrpCode'] = $value['GrpCode'];
                        $data['community_group'][$key]['GrpName'] = $value['GrpName'];
                        $data['community_group'][$key]['GrpCatCode'] = $value['GrpCatCode'];
                        $data['community_group'][$key]['LayR1Code'] = $value['LayR1Code'];
                        $data['community_group'][$key]['LayR2Code'] = $value['LayR2Code'];
                        $data['community_group'][$key]['LayR3Code'] = $value['LayR3Code'];
                        $data['community_group'][$key]['SrvCenterCode'] = $value['SrvCenterCode'];
                    }
                }// end of CommunityGroup
            // FDP Access Point

            $staff_fdp_access_sql = "SELECT [StfCode]
							  ,[AdmCountryCode]
							  ,[FDPCode]
							  ,[btnNew]
							  ,[btnSave]
							  ,[btnDel]
						  FROM [dbo].[StaffFDPAccess]
						 where StfCode= :user_id ";

            $this->query($staff_fdp_access_sql);
            $this->bind(':user_id', $staff_code);
            $staff_fdp_access = $this->resultset();

            if ($staff_fdp_access != false) {

                foreach ($staff_fdp_access as $key => $value) {
                    $data['staff_fdp_access'][$key]['StfCode'] = $value['StfCode'];
                    $data['staff_fdp_access'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['staff_fdp_access'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['staff_fdp_access'][$key]['btnNew'] = $value['btnNew'];
                    $data['staff_fdp_access'][$key]['btnSave'] = $value['btnSave'];
                    $data['staff_fdp_access'][$key]['btnDel'] = $value['btnDel'];
                }
            }// end of RegNMemCardPrintTable table
            // fdp master

            $fdp_master_sql = "SELECT [AdmCountryCode]
									  ,[FDPCode]
									  ,[FDPName]
									  ,[FDPCatCode]
									  ,[WHCode]
									  ,[LayR1Code]
									  ,[LayR2Code]
								  FROM [dbo].[FDPMaster]
					 ";

            $this->query($fdp_master_sql);
            // $this->bind(':user_id', $staff_code);
            $fdp_master = $this->resultset();

            if ($fdp_master != false) {

                foreach ($fdp_master as $key => $value) {
                    $data['fdp_master'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['fdp_master'][$key]['FDPCode'] = $value['FDPCode'];
                    $data['fdp_master'][$key]['FDPName'] = $value['FDPName'];
                    $data['fdp_master'][$key]['FDPCatCode'] = $value['FDPCatCode'];
                    $data['fdp_master'][$key]['WHCode'] = $value['WHCode'];
                    $data['fdp_master'][$key]['LayR1Code'] = $value['LayR1Code'];
                    $data['fdp_master'][$key]['LayR2Code'] = $value['LayR2Code'];
                }
            }// end of fdp_master table


            $lup_srv_option_list_sql = "SELECT [AdmCountryCode]
								  ,[ProgCode]
								  ,[SrvCode]
								  ,[LUPOptionCode]
								  ,[LUPOptionName]
							  FROM [dbo].[LUP_SrvOptionList]
					 ";

            $this->query($lup_srv_option_list_sql);
            // $this->bind(':user_id', $staff_code);
            $lup_srv_option_list = $this->resultset();

            if ($lup_srv_option_list != false) {

                foreach ($lup_srv_option_list as $key => $value) {
                    $data['lup_srv_option_list'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_srv_option_list'][$key]['ProgCode'] = $value['ProgCode'];
                    $data['lup_srv_option_list'][$key]['SrvCode'] = $value['SrvCode'];
                    $data['lup_srv_option_list'][$key]['LUPOptionCode'] = $value['LUPOptionCode'];
                    $data['lup_srv_option_list'][$key]['LUPOptionName'] = $value['LUPOptionName'];
                }
            }// end of lup_srv_option_list table
            // voucher item table
            $vo_itm_table_sql = "SELECT [CatCode]
												,[ItmCode]
												,[ItmName]

											FROM [dbo].[VOItmTable]
					 ";

            $this->query($vo_itm_table_sql);

            $vo_itm_table = $this->resultset();

            if ($vo_itm_table != false) {

                foreach ($vo_itm_table as $key => $value) {
                    $data['vo_itm_table'][$key]['CatCode'] = $value['CatCode'];
                    $data['vo_itm_table'][$key]['ItmCode'] = $value['ItmCode'];
                    $data['vo_itm_table'][$key]['ItmName'] = $value['ItmName'];
                }
            }// end of lup_srv_option_list table
            // voucher item meas table
            $vo_itm_meas_table_sql = "SELECT [MeasRCode]
											,[UnitMeas]
											,[MeasTitle]
										FROM [dbo].[VOItmMeas]
					 ";

            $this->query($vo_itm_meas_table_sql);
            $vo_itm_meas_table = $this->resultset();

            if ($vo_itm_meas_table != false) {

                foreach ($vo_itm_meas_table as $key => $value) {
                    $data['vo_itm_meas_table'][$key]['MeasRCode'] = $value['MeasRCode'];
                    $data['vo_itm_meas_table'][$key]['UnitMeas'] = $value['UnitMeas'];
                    $data['vo_itm_meas_table'][$key]['MeasTitle'] = $value['MeasTitle'];
                }
            }// end of voucher item meas table
            // vo_country_prog_itm  table
            $vo_country_prog_itm_sql = "SELECT [AdmCountryCode]
												,[AdmDonorCode]
												,[AdmAwardCode]
												,[AdmProgCode]
												,[AdmSrvCode]
												,[CatCode]
												,[ItmCode]
												,[MeasRCode]
												,[VOItmSpec]
												,[UnitCost]
												,[Active]
												,[Currency]
											FROM [dbo].[VOCountryProgItm]
					 ";

            $this->query($vo_country_prog_itm_sql);
            $vo_country_prog_itm = $this->resultset();

            if ($vo_country_prog_itm != false) {

                foreach ($vo_country_prog_itm as $key => $value) {
                    $data['vo_country_prog_itm'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['vo_country_prog_itm'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['vo_country_prog_itm'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['vo_country_prog_itm'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['vo_country_prog_itm'][$key]['AdmSrvCode'] = $value['AdmSrvCode'];
                    $data['vo_country_prog_itm'][$key]['CatCode'] = $value['CatCode'];
                    $data['vo_country_prog_itm'][$key]['ItmCode'] = $value['ItmCode'];
                    $data['vo_country_prog_itm'][$key]['MeasRCode'] = $value['MeasRCode'];
                    $data['vo_country_prog_itm'][$key]['VOItmSpec'] = $value['VOItmSpec'];
                    $data['vo_country_prog_itm'][$key]['UnitCost'] = $value['UnitCost'];
                    $data['vo_country_prog_itm'][$key]['Active'] = $value['Active'];
                    $data['vo_country_prog_itm'][$key]['Currency'] = $value['Currency'];
                }
            }// end of voucher item meas table
            // LUP_GPSTable  table
            $lup_gps_table_sql = "SELECT [GrpCode]
												,[SubGrpCode]
												,[AttributeCode]
												,[LookUpCode]
												,[LookUpName]
											FROM [dbo].[LUP_GPSTable]";

            $this->query($lup_gps_table_sql);
            $lup_gps_table = $this->resultset();

            if ($lup_gps_table != false) {

                foreach ($lup_gps_table as $key => $value) {
                    $data['lup_gps_table'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['lup_gps_table'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['lup_gps_table'][$key]['AttributeCode'] = $value['AttributeCode'];
                    $data['lup_gps_table'][$key]['LookUpCode'] = $value['LookUpCode'];
                    $data['lup_gps_table'][$key]['LookUpName'] = $value['LookUpName'];
                }
            }// end of LUP_GPS Table
            // GPSSubGroupAttributes  table
            $gps_sub_group_attributes_sql = "SELECT [GrpCode]
										,[SubGrpCode]
										,[AttributeCode]
										,[AttributeTitle]
										,[DataType]
										,[LookUp]		AS 	LookUpCode
									FROM [dbo].[GPSSubGroupAttributes]";

            $this->query($gps_sub_group_attributes_sql);
            $gps_sub_group_attributes = $this->resultset();

            if ($gps_sub_group_attributes != false) {

                foreach ($gps_sub_group_attributes as $key => $value) {
                    $data['gps_sub_group_attributes'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_sub_group_attributes'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_sub_group_attributes'][$key]['AttributeCode'] = $value['AttributeCode'];
                    $data['gps_sub_group_attributes'][$key]['AttributeTitle'] = $value['AttributeTitle'];
                    $data['gps_sub_group_attributes'][$key]['DataType'] = $value['DataType'];
                    $data['gps_sub_group_attributes'][$key]['LookUpCode'] = $value['LookUpCode'];
                }
            }// end of GPSSubGroupAttributes
            // GPSSubGroupAttributes  table
            $gps_location_attributes_sql = "SELECT [AdmCountryCode]
													,[GrpCode]
													,[SubGrpCode]
													,[LocationCode]
													,[AttributeCode]
													,[AttributeValue]
													,[AttPhoto]

												FROM [dbo].[GPSLocationAttributes]";

            $this->query($gps_location_attributes_sql);
            $gps_location_attributes = $this->resultset();

            if ($gps_location_attributes != false) {

                foreach ($gps_location_attributes as $key => $value) {
                    $data['gps_location_attributes'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['gps_location_attributes'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_location_attributes'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_location_attributes'][$key]['LocationCode'] = $value['LocationCode'];
                    $data['gps_location_attributes'][$key]['AttributeCode'] = $value['AttributeCode'];
                    $data['gps_location_attributes'][$key]['AttributeValue'] = $value['AttributeValue'];
                    $data['gps_location_attributes'][$key]['AttPhoto'] = $value['AttPhoto'];
                }
            }// end of GPSSubGroupAttributes
            // LUP_CommnityAnimalList  table
            $lup_community_animal_sql = "SELECT [AdmCountryCode]
											  ,[AdmDonorCode]
											  ,[AdmAwardCode]
											  ,[AdmProgCode]
											  ,[AnimalCode]
											  ,[AnimalType]

										  FROM [dbo].[LUP_CommnityAnimalList]";

            $this->query($lup_community_animal_sql);
            $lup_community_animals = $this->resultset();

            if ($lup_community_animals != false) {

                foreach ($lup_community_animals as $key => $value) {
                    $data['lup_community_animal'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_community_animal'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['lup_community_animal'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['lup_community_animal'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['lup_community_animal'][$key]['AnimalCode'] = $value['AnimalCode'];
                    $data['lup_community_animal'][$key]['AnimalType'] = $value['AnimalType'];
                }
            }// end of LUP_CommnityAnimalList
            // LUP_CommnityAnimalList  table
            $lup_prog_group_crop_sql = "SELECT [AdmCountryCode]
												  ,[AdmDonorCode]
												  ,[AdmAwardCode]
												  ,[AdmProgCode]
												  ,[CropCode]
												  ,[CropList]
												  ,[CropCatCode]

											  FROM [dbo].[LUP_ProgGroupCropList]";

            $this->query($lup_prog_group_crop_sql);
            $lup_prog_group_crops = $this->resultset();

            if ($lup_prog_group_crops != false) {

                foreach ($lup_prog_group_crops as $key => $value) {
                    $data['lup_prog_group_crop'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_prog_group_crop'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['lup_prog_group_crop'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['lup_prog_group_crop'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['lup_prog_group_crop'][$key]['CropCode'] = $value['CropCode'];
                    $data['lup_prog_group_crop'][$key]['CropList'] = $value['CropList'];
                    $data['lup_prog_group_crop'][$key]['CropCatCode'] = $value['CropCatCode'];
                }
            }// end of CommunityGroup
            // LUP_CommnityLoanSource  table
            $lup_community_loan_source_sql = "SELECT [AdmCountryCode]
												,[AdmDonorCode]
												,[AdmAwardCode]
												,[AdmProgCode]
												,[LoanCode]
												,[LoanSource]

											FROM [dbo].[LUP_CommnityLoanSource]";

            $this->query($lup_community_loan_source_sql);
            $lup_community_loan_sources = $this->resultset();

            if ($lup_community_loan_sources != false) {

                foreach ($lup_community_loan_sources as $key => $value) {
                    $data['lup_community_loan_source'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_community_loan_source'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['lup_community_loan_source'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['lup_community_loan_source'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['lup_community_loan_source'][$key]['LoanCode'] = $value['LoanCode'];
                    $data['lup_community_loan_source'][$key]['LoanSource'] = $value['LoanSource'];
                }
            }// end of LUP_CommnityLoanSource
            // LUP_CommunityLeadPosition  table
			
			 // LUP_CommnityLoanSource  table
            $lup_community_fund_source_sql = "SELECT [AdmCountryCode]
												,[AdmDonorCode]
												,[AdmAwardCode]
												,[AdmProgCode]
												,[FundCode]
      ,[FundSource]
   
  FROM [dbo].[LUP_CommnityFundSource]";

            $this->query($lup_community_fund_source_sql);
            $lup_community_fund_sources = $this->resultset();

            if ($lup_community_fund_sources != false) {

                foreach ($lup_community_fund_sources as $key => $value) {
                    $data['lup_community_fund_source'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_community_fund_source'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['lup_community_fund_source'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['lup_community_fund_source'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['lup_community_fund_source'][$key]['FundCode'] = $value['FundCode'];
                    $data['lup_community_fund_source'][$key]['FundSource'] = $value['FundSource'];
                }
            }// end of LUP_CommnityFundSource
            // lup_community_fund_source  table
			
			
			  $lup_community_irrigation_system_sql = "SELECT [AdmCountryCode]
												,[AdmDonorCode]
												,[AdmAwardCode]
												,[AdmProgCode]
											   ,[IrriSysCode]
      ,[IrriSysName]

  FROM [dbo].[LUP_CommunityIrrigationSystem]";

            $this->query($lup_community_irrigation_system_sql);
            $lup_community_irrigation_systems = $this->resultset();

            if ($lup_community_irrigation_systems != false) {

                foreach ($lup_community_irrigation_systems as $key => $value) {
                    $data['lup_community_irrigation_system'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_community_irrigation_system'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['lup_community_irrigation_system'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['lup_community_irrigation_system'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['lup_community_irrigation_system'][$key]['IrriSysCode'] = $value['IrriSysCode'];
                    $data['lup_community_irrigation_system'][$key]['IrriSysName'] = $value['IrriSysName'];
                }
            }// end of LUP_CommnityFundSource
            // lup_community_fund_source  table
            $lup_commnity_lead_position_sql = "SELECT [AdmCountryCode]
													  ,[AdmDonorCode]
													  ,[AdmAwardCode]
													  ,[AdmProgCode]
													  ,[LeadCode]
													  ,[LeadPosition]

												  FROM [dbo].[LUP_CommunityLeadPosition]";

            $this->query($lup_commnity_lead_position_sql);
            $lup_community__lead_positions = $this->resultset();

            if ($lup_community__lead_positions != false) {

                foreach ($lup_community__lead_positions as $key => $value) {
                    $data['lup_community__lead_position'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_community__lead_position'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['lup_community__lead_position'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['lup_community__lead_position'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['lup_community__lead_position'][$key]['LeadCode'] = $value['LeadCode'];
                    $data['lup_community__lead_position'][$key]['LeadPosition'] = $value['LeadPosition'];
                }
            }// end of LUP_CommunityLeadPosition
            // [CommunityGroupCategory]
            $community_group_category_sql = "SELECT [AdmCountryCode]
														  ,[AdmDonorCode]
														  ,[AdmAwardCode]
														  ,[AdmProgCode]
														  ,[GrpCatCode]
														  ,[GrpCatName]
														  ,[GrpCatShortName]
														   FROM [dbo].[CommunityGroupCategory]";

            $this->query($community_group_category_sql);
            $community_group_categoryes = $this->resultset();

            if ($community_group_categoryes != false) {

                foreach ($community_group_categoryes as $key => $value) {
                    $data['community_group_category'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['community_group_category'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['community_group_category'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['community_group_category'][$key]['AdmProgCode'] = $value['AdmProgCode'];
                    $data['community_group_category'][$key]['GrpCatCode'] = $value['GrpCatCode'];
                    $data['community_group_category'][$key]['GrpCatName'] = $value['GrpCatName'];
                    $data['community_group_category'][$key]['GrpCatShortName'] = $value['GrpCatShortName'];
                }
            }// end of LUP_CommunityLeadPosition
            // CommunityGroup  table
            $lup_reg_n_add_lookup_sql = "SELECT [AdmCountryCode]
     									,[RegNAddLookupCode]
     									,[RegNAddLookup]
     									,[LayR1ListCode]
     									,[LayR2ListCode]
     									,[LayR3ListCode]
     									,[LayR4ListCode]
  							FROM [dbo].[LUP_RegNAddLookup]";

            $this->query($lup_reg_n_add_lookup_sql);
            $lup_reg_n_add_lookups = $this->resultset();

            if ($lup_reg_n_add_lookups != false) {

                foreach ($lup_reg_n_add_lookups as $key => $value) {
                    $data['lup_reg_n_add_lookup'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['lup_reg_n_add_lookup'][$key]['RegNAddLookupCode'] = $value['RegNAddLookupCode'];
                    $data['lup_reg_n_add_lookup'][$key]['RegNAddLookup'] = $value['RegNAddLookup'];
                    $data['lup_reg_n_add_lookup'][$key]['LayR1ListCode'] = $value['LayR1ListCode'];
                    $data['lup_reg_n_add_lookup'][$key]['LayR2ListCode'] = $value['LayR2ListCode'];
                    $data['lup_reg_n_add_lookup'][$key]['LayR3ListCode'] = $value['LayR3ListCode'];
                    $data['lup_reg_n_add_lookup'][$key]['LayR4ListCode'] = $value['LayR4ListCode'];
                }
            }// end of GPSSubGroupAttributes


            $prog_org_n_role_sql = "SELECT [AdmCountryCode]
      ,[AdmDonorCode]
      ,[AdmAwardCode]
      ,[OrgNCode]
      ,[PrimeYN]
      ,[SubYN]
      ,[TechYN]
      ,[ImpYN]
      ,[LogYN]
      ,[OthYN]

  FROM [dbo].[ProgOrgNRole]";

            $this->query($prog_org_n_role_sql);
            $prog_org_n_roles = $this->resultset();

            if ($prog_org_n_roles != false) {

                foreach ($prog_org_n_roles as $key => $value) {
                    $data['prog_org_n_role'][$key]['AdmCountryCode'] = $value['AdmCountryCode'];
                    $data['prog_org_n_role'][$key]['AdmDonorCode'] = $value['AdmDonorCode'];
                    $data['prog_org_n_role'][$key]['AdmAwardCode'] = $value['AdmAwardCode'];
                    $data['prog_org_n_role'][$key]['OrgNCode'] = $value['OrgNCode'];
                    $data['prog_org_n_role'][$key]['PrimeYN'] = $value['PrimeYN'];
                    $data['prog_org_n_role'][$key]['SubYN'] = $value['SubYN'];
                    $data['prog_org_n_role'][$key]['TechYN'] = $value['TechYN'];
                    $data['prog_org_n_role'][$key]['LogYN'] = $value['LogYN'];
                    $data['prog_org_n_role'][$key]['ImpYN'] = $value['ImpYN'];
                    $data['prog_org_n_role'][$key]['OthYN'] = $value['OthYN'];
                }
            }// end of GPSSubGroupAttributes


            $org_n_code_sql = "SELECT [OrgNCode]
      ,[OrgNName]
      ,[OrgNShortName]

    FROM [dbo].[ProgOrgN]";

            $this->query($org_n_code_sql);
            $org_n_codes = $this->resultset();

            if ($org_n_codes != false) {

                foreach ($org_n_codes as $key => $value) {
                    $data['org_n_code'][$key]['OrgNCode'] = $value['OrgNCode'];
                    $data['org_n_code'][$key]['OrgNName'] = $value['OrgNName'];
                    $data['org_n_code'][$key]['OrgNShortName'] = $value['OrgNShortName'];
                }
            }// end of GPSSubGroupAttributes


            $staff_master_sql = "SELECT [StfCode]
      ,[OrigAdmCountryCode]
      ,[StfName]
      ,[OrgNCode]
      ,[OrgNDesgNCode]
      ,[StfStatus]
      ,[StfCategory]
      ,[UsrLogInName]
      ,[UsrLogInPW]
      ,[StfAdminRole]
  FROM [dbo].[StaffMaster]";

            $this->query($staff_master_sql);
            $staff_master = $this->resultset();

            if ($staff_master != false) {

                foreach ($staff_master as $key => $value) {
                    $data['staff_master'][$key]['StfCode'] = $value['StfCode'];
                    $data['staff_master'][$key]['OrigAdmCountryCode'] = $value['OrigAdmCountryCode'];
                    $data['staff_master'][$key]['StfName'] = $value['StfName'];
                    $data['staff_master'][$key]['OrgNCode'] = $value['OrgNCode'];
                    $data['staff_master'][$key]['OrgNDesgNCode'] = $value['OrgNDesgNCode'];
                    $data['staff_master'][$key]['StfStatus'] = $value['StfStatus'];
                    $data['staff_master'][$key]['StfCategory'] = $value['StfCategory'];
                    $data['staff_master'][$key]['UsrLogInName'] = $value['UsrLogInName'];
                    $data['staff_master'][$key]['UsrLogInPW'] = $value['UsrLogInPW'];
                    $data['staff_master'][$key]['StfAdminRole'] = $value['StfAdminRole'];
                }
            }// end of GPSSubGroupAttributes


            $gps_lup_list_sql = "SELECT [GrpCode]
      ,[SubGrpCode]
      ,[AttributeCode]
      ,[LupValueCode]
      ,[LupValueText]
      ,[EntryBy]
      ,[EntryDate]
  FROM [dbo].[GPSLUPList]";

            $this->query($gps_lup_list_sql);
            $gps_lup_list = $this->resultset();

            if ($gps_lup_list != false) {

                foreach ($gps_lup_list as $key => $value) {
                    $data['gps_lup_list'][$key]['GrpCode'] = $value['GrpCode'];
                    $data['gps_lup_list'][$key]['SubGrpCode'] = $value['SubGrpCode'];
                    $data['gps_lup_list'][$key]['AttributeCode'] = $value['AttributeCode'];
                    $data['gps_lup_list'][$key]['LupValueCode'] = $value['LupValueCode'];
                    $data['gps_lup_list'][$key]['LupValueText'] = $value['LupValueText'];
                }
            }// end of GPS look up
        } else {
            return false;
        }

        return $data;
    }

// End function is_valid_user
}

// End class PCIModel
?>
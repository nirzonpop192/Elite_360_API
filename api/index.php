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
 *    {
 *        $_POST['key'] = 'PhEUT5R251';
 *        $_POST['task'] = 'is_valid_user';
 *
 *        $_POST['user_name'] = 'test';
 *        $_POST['password'] = '1';
 *    }
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

if (isset($_GET['check'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_valid_user';
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000201010201"}]';


    $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000403010214"},{"selectedLayR4Code":"000403020303"}]';
   // $_POST['lay_r_code_j'] = '[]';
   // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"00"}]';
    $_POST['operation_mode'] = '5';
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000202050701001"},{"selectedLayR4Code":"000201010101838"}]';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} 
else if (isset($_GET['train'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'down_load_training_n_activity';
   
    // village
    $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000201010401"}]';
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002029"},{"selectedLayR4Code":"0002009"}]';
    $_POST['operation_mode'] = '5';
    //$_POST['lay_r_code_j'] = '["000403010214","000403020303"]';
    
    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';
	

} 
else if (isset($_GET['hh'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_reg_house_hold';
    /*$_POST['user_name'] = 'alick.singini';
    $_POST['password'] = 'alicksii';*/
    // village
    $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000202050200"}]';
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002029"},{"selectedLayR4Code":"0002009"}]';
    $_POST['operation_mode'] = '5';
    //$_POST['lay_r_code_j'] = '["000403010214","000403020303"]';
    
    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


    //$_POST['user_name'] = 'ttseng';
    //$_POST['password'] = '123';

} else if (isset($_GET['prog'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_programName';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} else if (isset($_GET['enu'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_enu_table';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} else if (isset($_GET['ver'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_adm_apk_version';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} 
else if (isset($_GET['servicecen'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_service_center_name';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';
    $_POST['country_code'] = '0002';

    $_POST['donor_code'] = '01';
    $_POST['award_code'] = '01';
    $_POST['program_code'] = '001';
    $_POST['opMothCode'] = 'FoodFlag';
    $_POST['distFlag'] = '05';

	

} else if (isset($_GET['village'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_village_name';
    /*$_POST['user_name'] = 'alick.singini';
    $_POST['password'] = 'alicksii';*/
//$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000403010214"},{"selectedLayR4Code":"000403020303"}]';
    //$_POST['lay_r_code_j'] = '["000403010214","000403020303"]';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';




} else if (isset($_GET['mem'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_reg_member';

    // village
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000202050701001"},{"selectedLayR4Code":"000201010101838"}]';

   $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000403010214"},{"selectedLayR4Code":"000403020303"}]';
    // fro fdp
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002002"},{"selectedLayR4Code":"0002001"}]';
    // for service Center
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002010"},{"selectedLayR4Code":"0002313"}]';
    $_POST['operation_mode'] = '5';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} else if (isset($_GET['srv'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_service_data';

    // FOR VILLAGE
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000202050701001"},{"selectedLayR4Code":"000201010101838"}]';

  // FOR VILLAGE
      $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000402030312"},{"selectedLayR4Code":"000201010101"}]';
    // FOR fDP CENTER
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002021"},{"selectedLayR4Code":"0002001"}]';
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002021"}]';
    /// FOR SERVICE cENTER
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002010"},{"selectedLayR4Code":"0002313"}]';
    $_POST['operation_mode'] = '5';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';




    //$_POST['user_name'] = 'ttseng';
    //$_POST['password'] = '123

} else if (isset($_GET['grp'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_reg_mem_grp_data';

    // FOR VILLAGE
    // FOR VILLAGE
   // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000201010101"},{"selectedLayR4Code":"000201010101"}]';
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000403010214"},{"selectedLayR4Code":"000403020303"}]';


    // FOR fDP CENTER
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002021"},{"selectedLayR4Code":"0002001"}]';
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002021"}]';
    /// FOR SERVICE cENTER
    $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002633"},{"selectedLayR4Code":"0002633"}]';
    $_POST['operation_mode'] = '5';
    /*$_POST['user_name'] = 'lusako.mwalweni';
    $_POST['password'] = '123';*/

    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} else if (isset($_GET['asg'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_reg_assn_prog';


    // FOR VILLAGE
   // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000402030312"}]';
  // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002"}]';
    // FOR VILLAGE
      $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000402030312"},{"selectedLayR4Code":"000201010101"}]';

    // FOR fDP CENTER
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002002"},{"selectedLayR4Code":"0002001"}]';

    // FOR SERVICE CENTER
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002010"}]';
    $_POST['operation_mode'] = '5';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} else if (isset($_GET['ref'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'get_reference_data';
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000402010101"},{"selectedLayR4Code":"000403010214"}]';

    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';
    // $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000201010101"},{"selectedLayR4Code":"000403020303"}]';

    // FOR VILLAGE
    $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"000202050701001"},{"selectedLayR4Code":"000201010101838"}]';
    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002002"},{"selectedLayR4Code":"0002001"}]';
    $_POST['operation_mode'] = '5';


} else if (isset($_GET['get_fdp'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_fdp_name';


    $_POST['user_name'] = 'nkalam';
    $_POST['password'] = 'p3';


} else if (isset($_GET['get_DT'])) {
    $_POST['key'] = 'PhEUT5R251';
    $_POST['task'] = 'is_down_load_dynamic_table';

    $_POST['operation_mode'] = '4';
    # for village
    $_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"00"},{"selectedLayR4Code":"00"}]';

    

    //$_POST['lay_r_code_j'] = '[{"selectedLayR4Code":"0002002"},{"selectedLayR4Code":"0002001"}]';


    $_POST['user_name'] = 'vitumbiko.mkinga';
    $_POST['password'] = '0';
}


if (isset($_POST['key']) && !empty($_POST['key'])) {
    $key = $_POST['key'];
    $data = array();

    require_once 'connection.php';
    require_once "class.DBEngine.php";
    require_once "class.PCIModel.php";

    if (validate_key($key)) {
        if (isset($_POST['task']) && $_POST['task'] != '') {
            $task = $_POST['task'];

            $db = new PCIModel();

            switch ($task) {


                //******************** End  Reg Registration Liberia*************
                //  EXEQUTE THE SQL QUERY FOR DESKTOP & FOR IN FUTURE IN ANDROID
                case 'exequte_sql_query':
                    $sql_string = $_POST['sql_string'];
                    if (strlen($sql_string) > 10 && !is_null($sql_string)) {


                        $result = $db->query_exe($sql_string);


                        if ($result != false) {
                            //echo " ok the data  insert";
                            $data["error"] = FALSE;
                            $data["Last_ID"] = "Insert ID = " . $result;
                        } else {
                            $data["error"] = TRUE;
                            $data["error_msg"] = "Failed to synchronize!";
                            //echo "Failed to insert!";
                        }
                        _json($data);

                    } else
                        echo "query length is null ";
                    break;
                // END EXEQUTE THE SQL QUERY
                // only assign program Service
 # Dynamic Data Block
                case 'down_load_training_n_activity':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];

			
                    $result = $db->down_load_training_n_activity($user_name, $password,  $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }
						
					
						//Training Activity 
                        if (array_key_exists('T_A_master', $result) && !is_null($result['T_A_master'])) {
                            $data["T_A_master"] = $result['T_A_master'];
                        }
                 
						if (array_key_exists('T_A_category', $result) && !is_null($result['T_A_category'])) {
                            $data["T_A_category"] = $result['T_A_category'];
                        }
						if (array_key_exists('T_A_eventTopic', $result) && !is_null($result['T_A_eventTopic'])) {
                            $data["T_A_eventTopic"] = $result['T_A_eventTopic'];
                        }

						if (array_key_exists('T_A_group', $result) && !is_null($result['T_A_group'])) {
                            $data["T_A_group"] = $result['T_A_group'];
                        }
						
						if (array_key_exists('T_A_partOrgN', $result) && !is_null($result['T_A_partOrgN'])) {
                            $data["T_A_partOrgN"] = $result['T_A_partOrgN'];
                        }
						
						if (array_key_exists('T_A_posParticipants', $result) && !is_null($result['T_A_posParticipants'])) {
                            $data["T_A_posParticipants"] = $result['T_A_posParticipants'];
                        }
						
						if (array_key_exists('T_A_subGroup', $result) && !is_null($result['T_A_subGroup'])) {
                            $data["T_A_subGroup"] = $result['T_A_subGroup'];
                        }
						
						if (array_key_exists('T_A_topicChild', $result) && !is_null($result['T_A_topicChild'])) {
                            $data["T_A_topicChild"] = $result['T_A_topicChild'];
                        }
						
						if (array_key_exists('T_A_topicMaster', $result) && !is_null($result['T_A_topicMaster'])) {
                            $data["T_A_topicMaster"] = $result['T_A_topicMaster'];
                        }
						if (array_key_exists('LUP_TAParticipantCat', $result) && !is_null($result['LUP_TAParticipantCat'])) {
                            $data["LUP_TAParticipantCat"] = $result['LUP_TAParticipantCat'];
                        }
						
                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;

                # Dynamic Data Block
                case 'is_down_load_dynamic_table':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_dynamic_table($user_name, $password,  $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }

                        //Dynamic Table
                        if (array_key_exists('D_T_answer', $result) && !is_null($result['D_T_answer'])) {
                            $data["D_T_answer"] = $result['D_T_answer'];
                        }
                        if (array_key_exists('D_T_basic', $result) && !is_null($result['D_T_basic'])) {
                            $data["D_T_basic"] = $result['D_T_basic'];
                        }
                        if (array_key_exists('D_T_category', $result) && !is_null($result['D_T_category'])) {
                            $data["D_T_category"] = $result['D_T_category'];
                        }
                        if (array_key_exists('D_T_CountryProgram', $result) && !is_null($result['D_T_CountryProgram'])) {
                            $data["D_T_CountryProgram"] = $result['D_T_CountryProgram'];
                        }
                        if (array_key_exists('D_T_GeoListLevel', $result) && !is_null($result['D_T_GeoListLevel'])) {
                            $data["D_T_GeoListLevel"] = $result['D_T_GeoListLevel'];
                        }

                        if (array_key_exists('D_T_QResMode', $result) && !is_null($result['D_T_QResMode'])) {
                            $data["D_T_QResMode"] = $result['D_T_QResMode'];
                        }
                        if (array_key_exists('D_T_QTable', $result) && !is_null($result['D_T_QTable'])) {
                            $data["D_T_QTable"] = $result['D_T_QTable'];
                        }
                        if (array_key_exists('D_T_ResponseTable', $result) && !is_null($result['D_T_ResponseTable'])) {
                            $data["D_T_ResponseTable"] = $result['D_T_ResponseTable'];
                        }
                        if (array_key_exists('D_T_TableDefinition', $result) && !is_null($result['D_T_TableDefinition'])) {
                            $data["D_T_TableDefinition"] = $result['D_T_TableDefinition'];
                        }

                        if (array_key_exists('D_T_TableListCategory', $result) && !is_null($result['D_T_TableListCategory'])) {
                            $data["D_T_TableListCategory"] = $result['D_T_TableListCategory'];
                        }

                        if (array_key_exists('D_T_LUP', $result) && !is_null($result['D_T_LUP'])) {
                            $data["D_T_LUP"] = $result['D_T_LUP'];
                        }

						if (array_key_exists('DTA_Skip_Table', $result) && !is_null($result['DTA_Skip_Table'])) {
                            $data["DTA_Skip_Table"] = $result['DTA_Skip_Table'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;

                # end of Dynamic Data Block

                case 'is_down_load_reg_assn_prog':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_reg_assn_prog($user_name, $password, $lay_r_code_j, $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }

                        if (array_key_exists('reg_m_assign_prog_srv', $result) && !is_null($result['reg_m_assign_prog_srv'])) {
                            $data["reg_m_assign_prog_srv"] = $result['reg_m_assign_prog_srv'];
                        }


                        if (array_key_exists('regn_pw', $result) && !is_null($result['regn_pw'])) {
                            $data["regn_pw"] = $result['regn_pw'];
                        }
                        if (array_key_exists('regn_lm', $result) && !is_null($result['regn_lm'])) {
                            $data["regn_lm"] = $result['regn_lm'];
                        }
                        if (array_key_exists('regn_cu2', $result) && !is_null($result['regn_cu2'])) {
                            $data["regn_cu2"] = $result['regn_cu2'];
                        }
                        if (array_key_exists('regn_ca2', $result) && !is_null($result['regn_ca2'])) {
                            $data["regn_ca2"] = $result['regn_ca2'];
                        }

                        if (array_key_exists('reg_n_agr', $result) && !is_null($result['reg_n_agr'])) {
                            $data["reg_n_agr"] = $result['reg_n_agr'];
                        }

                        if (array_key_exists('reg_n_ct', $result) && !is_null($result['reg_n_ct'])) {
                            $data["reg_n_ct"] = $result['reg_n_ct'];
                        }

                        if (array_key_exists('reg_n_ffa', $result) && !is_null($result['reg_n_ffa'])) {
                            $data["reg_n_ffa"] = $result['reg_n_ffa'];
                        }
						 if (array_key_exists('reg_n_we', $result) && !is_null($result['reg_n_we'])) {
                            $data["reg_n_we"] = $result['reg_n_we'];
                        }

                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;
                /** load village **/
                case 'is_down_load_service_center_name':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $country_code = $_POST['country_code'];
                    $donor_code = $_POST['donor_code'];
                    $award_code = $_POST['award_code'];
                    $program_code = $_POST['program_code'];
                    $opMothCode = $_POST['opMothCode'];
                    $distFlag = $_POST['distFlag'];


                    $result = $db->is_down_load_service_center_name($user_name, $password, $country_code, $donor_code, $award_code, $program_code,$opMothCode, $distFlag );


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            $data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        if (array_key_exists('countrie_no', $result) && !is_null($result['countrie_no'])) {
                            $data["countrie_no"] = $result['countrie_no'];
                        }
                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }
                        if (array_key_exists('dob_service_center', $result) && !is_null($result['dob_service_center'])) {
                            $data["dob_service_center"] = $result['dob_service_center'];
                        }

                     

                       /* if (array_key_exists('community_group', $result) && !is_null($result['community_group'])) {
                            $data["community_group"] = $result['community_group'];
                        }*/

                   


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;


                /** load program name  **/
                case 'is_down_load_programName':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];


                    $result = $db->is_down_load_programName($user_name, $password);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            $data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        if (array_key_exists('countrie_no', $result) && !is_null($result['countrie_no'])) {
                            $data["countrie_no"] = $result['countrie_no'];
                        }
                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }


                        if (array_key_exists('adm_program_master', $result) && !is_null($result['adm_program_master'])) {
                            $data["adm_program_master"] = $result['adm_program_master'];
                        }
						
						
                        if (array_key_exists('adm_op_month', $result) && !is_null($result['adm_op_month'])) {
                            $data["adm_op_month"] = $result['adm_op_month'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;
					
				/*load enu table*/	
				case 'is_down_load_enu_table':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];


                    $result = $db->is_down_load_enu_table($user_name, $password);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            $data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        if (array_key_exists('countrie_no', $result) && !is_null($result['countrie_no'])) {
                            $data["countrie_no"] = $result['countrie_no'];
                        }
                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }

				
                        if (array_key_exists('enu_table', $result) && !is_null($result['enu_table'])) {
                            $data["enu_table"] = $result['enu_table'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;	
					
					
					/*load enu table*/	
				case 'is_down_load_adm_apk_version':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];


                    $result = $db->is_down_load_adm_apk_version($user_name, $password);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            $data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                       /* if (array_key_exists('countrie_no', $result) && !is_null($result['countrie_no'])) {
                            $data["countrie_no"] = $result['countrie_no'];
                        }
                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }*/

				
                        if (array_key_exists('apk_version', $result) && !is_null($result['apk_version'])) {
                            $data["apk_version"] = $result['apk_version'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;	


                /** load village **/
                case 'is_down_load_village_name':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
					$operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_village_name($user_name, $password, $operation_mode);



                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
							if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}                            
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        if (array_key_exists('countrie_no', $result) && !is_null($result['countrie_no'])) {
                            $data["countrie_no"] = $result['countrie_no'];
                        }
                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }
                        if (array_key_exists('village', $result) && !is_null($result['village'])) {
                            $data["village"] = $result['village'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;


                /** load fdp name **/
                case 'is_down_load_fdp_name':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];


                    $result = $db->is_down_load_fdp_name($user_name, $password);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            $data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        if (array_key_exists('countrie_no', $result) && !is_null($result['countrie_no'])) {
                            $data["countrie_no"] = $result['countrie_no'];
                        }
                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }
                        if (array_key_exists('staff_fdp_access', $result) && !is_null($result['staff_fdp_access'])) {
                            $data["staff_fdp_access"] = $result['staff_fdp_access'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;


                /**@date: 2015-10-27
                 *THIS IS THE REQUEST  FOR House hold   DATA DOWN LOAD
                 * BUT IT DON'T NEED ANY MORE
                 */
                case 'is_down_load_reg_house_hold':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_reg_house_hold($user_name, $password, $lay_r_code_j, $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        if (array_key_exists('registration', $result) && !is_null($result['registration'])) {
                            $data["registration"] = $result['registration'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;
                // member data request

                case 'is_down_load_reg_member':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_reg_member($user_name, $password, $lay_r_code_j, $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        // only json member data
                        if (array_key_exists('members', $result) && !is_null($result['members'])) {
                            $data["members"] = $result['members'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;


                case 'is_down_load_service_data':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_service_data($user_name, $password, $lay_r_code_j, $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        // only json service data
                        if (array_key_exists('service_table', $result) && !is_null($result['service_table'])) {
                            $data["service_table"] = $result['service_table'];
                        }
                        if (array_key_exists('service_exe_table', $result) && !is_null($result['service_exe_table'])) {
                            $data["service_exe_table"] = $result['service_exe_table'];
                        }


                        if (array_key_exists('service_specific_table', $result) && !is_null($result['service_specific_table'])) {
                            $data["service_specific_table"] = $result['service_specific_table'];
                        }

                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;

                /**
                 * Grop Table
                 */
                case 'is_down_load_reg_mem_grp_data':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_down_load_reg_mem_grp_data($user_name, $password, $lay_r_code_j, $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }


                        // only json
                        if (array_key_exists('reg_n_mem_prog_grp', $result) && !is_null($result['reg_n_mem_prog_grp'])) {
                            $data["reg_n_mem_prog_grp"] = $result['reg_n_mem_prog_grp'];

                        }

                        if (array_key_exists('gps_location_content', $result) && !is_null($result['gps_location_content'])) {
                            $data["gps_location_content"] = $result['gps_location_content'];

                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;


                /** End of LOG In*/

                case 'is_valid_user':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->is_valid_user($user_name, $password, $lay_r_code_j, $operation_mode);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            if($operation_mode=="4"){
								$data["user"]["AdmCountryCode"] = $result['user']["OrigAdmCountryCode"];
							}else{
								$data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];	
							}
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }

                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }

                        if (array_key_exists('valid_dates', $result) && !is_null($result['valid_dates'])) {
                            $data["valid_dates"] = $result['valid_dates'];
                        }

                        if (array_key_exists('layer_labels', $result) && !is_null($result['layer_labels'])) {
                            $data["layer_labels"] = $result['layer_labels'];
                        }

                        if (array_key_exists('district', $result) && !is_null($result['district'])) {
                            $data["district"] = $result['district'];
                        }

                        if (array_key_exists('upazilla', $result) && !is_null($result['upazilla'])) {
                            $data["upazilla"] = $result['upazilla'];
                        }

                        if (array_key_exists('unit', $result) && !is_null($result['unit'])) {
                            $data["unit"] = $result['unit'];
                        }

                        if (array_key_exists('village', $result) && !is_null($result['village'])) {
                            $data["village"] = $result['village'];
                        }

                        if (array_key_exists('relation', $result) && !is_null($result['relation'])) {
                            $data["relation"] = $result['relation'];
                        }

                        if (array_key_exists('gps_group', $result) && !is_null($result['gps_group'])) {
                            $data["gps_group"] = $result['gps_group'];
                        }

                        if (array_key_exists('gps_subgroup', $result) && !is_null($result['gps_subgroup'])) {
                            $data["gps_subgroup"] = $result['gps_subgroup'];
                        }

                        if (array_key_exists('gps_location', $result) && !is_null($result['gps_location'])) {
                            $data["gps_location"] = $result['gps_location'];
                        }

                        if (array_key_exists('adm_countryaward', $result) && !is_null($result['adm_countryaward'])) {
                            $data["adm_countryaward"] = $result['adm_countryaward'];
                        }
						if (array_key_exists('adm_award', $result) && !is_null($result['adm_award'])) {
                            $data["adm_award"] = $result['adm_award'];
                        }

                        if (array_key_exists('adm_donor', $result) && !is_null($result['adm_donor'])) {
                            $data["adm_donor"] = $result['adm_donor'];
                        }


                        if (array_key_exists('adm_program_master', $result) && !is_null($result['adm_program_master'])) {
                            $data["adm_program_master"] = $result['adm_program_master'];
                        }

                        if (array_key_exists('adm_service_master', $result) && !is_null($result['adm_service_master'])) {
                            $data["adm_service_master"] = $result['adm_service_master'];
                        }

                        if (array_key_exists('adm_op_month', $result) && !is_null($result['adm_op_month'])) {
                            $data["adm_op_month"] = $result['adm_op_month'];
                        }

                        if (array_key_exists('adm_country_program', $result) && !is_null($result['adm_country_program'])) {
                            $data["adm_country_program"] = $result['adm_country_program'];
                        }

                        if (array_key_exists('dob_service_center', $result) && !is_null($result['dob_service_center'])) {
                            $data["dob_service_center"] = $result['dob_service_center'];
                        }

                        if (array_key_exists('staff_access_info', $result) && !is_null($result['staff_access_info'])) {
                            $data["staff_access_info"] = $result['staff_access_info'];
                        }


                        if (array_key_exists('lb_reg_hh_category', $result) && !is_null($result['lb_reg_hh_category'])) {
                            $data["lb_reg_hh_category"] = $result['lb_reg_hh_category'];
                        }


                        if (array_key_exists('reg_lup_graduation', $result) && !is_null($result['reg_lup_graduation'])) {
                            $data["reg_lup_graduation"] = $result['reg_lup_graduation'];
                        }

                        if (array_key_exists('report_template', $result) && !is_null($result['report_template'])) {
                            $data["report_template"] = $result['report_template'];
                        }


                        if (array_key_exists('card_print_reason', $result) && !is_null($result['card_print_reason'])) {
                            $data["card_print_reason"] = $result['card_print_reason'];
                        }

                        if (array_key_exists('reg_mem_card_request', $result) && !is_null($result['reg_mem_card_request'])) {
                            $data["reg_mem_card_request"] = $result['reg_mem_card_request'];
                        }

                        if (array_key_exists('staff_fdp_access', $result) && !is_null($result['staff_fdp_access'])) {
                            $data["staff_fdp_access"] = $result['staff_fdp_access'];
                        }

                        if (array_key_exists('fdp_master', $result) && !is_null($result['fdp_master'])) {
                            $data["fdp_master"] = $result['fdp_master'];
                        }

                        if (array_key_exists('distribution_table', $result) && !is_null($result['distribution_table'])) {
                            $data["distribution_table"] = $result['distribution_table'];
                        }

                        if (array_key_exists('distribution_ext_table', $result) && !is_null($result['distribution_ext_table'])) {
                            $data["distribution_ext_table"] = $result['distribution_ext_table'];
                        }

                        if (array_key_exists('distbasic_table', $result) && !is_null($result['distbasic_table'])) {
                            $data["distbasic_table"] = $result['distbasic_table'];
                        }


                        if (array_key_exists('lup_srv_option_list', $result) && !is_null($result['lup_srv_option_list'])) {
                            $data["lup_srv_option_list"] = $result['lup_srv_option_list'];
                        }

                        if (array_key_exists('vo_itm_table', $result) && !is_null($result['vo_itm_table'])) {
                            $data["vo_itm_table"] = $result['vo_itm_table'];
                        }


                        if (array_key_exists('vo_itm_meas_table', $result) && !is_null($result['vo_itm_meas_table'])) {
                            $data["vo_itm_meas_table"] = $result['vo_itm_meas_table'];
                        }


                        if (array_key_exists('vo_country_prog_itm', $result) && !is_null($result['vo_country_prog_itm'])) {
                            $data["vo_country_prog_itm"] = $result['vo_country_prog_itm'];
                        }

                        if (array_key_exists('lup_gps_table', $result) && !is_null($result['lup_gps_table'])) {
                            $data["lup_gps_table"] = $result['lup_gps_table'];
                        }

                        if (array_key_exists('gps_sub_group_attributes', $result) && !is_null($result['gps_sub_group_attributes'])) {
                            $data["gps_sub_group_attributes"] = $result['gps_sub_group_attributes'];
                        }

                        if (array_key_exists('gps_location_attributes', $result) && !is_null($result['gps_location_attributes'])) {
                            $data["gps_location_attributes"] = $result['gps_location_attributes'];
                        }

                        if (array_key_exists('community_group', $result) && !is_null($result['community_group'])) {
                            $data["community_group"] = $result['community_group'];
                        }

                        if (array_key_exists('lup_community_animal', $result) && !is_null($result['lup_community_animal'])) {
                            $data["lup_community_animal"] = $result['lup_community_animal'];
                        }


                        if (array_key_exists('lup_prog_group_crop', $result) && !is_null($result['lup_prog_group_crop'])) {
                            $data["lup_prog_group_crop"] = $result['lup_prog_group_crop'];
                        }

                        if (array_key_exists('lup_community_loan_source', $result) && !is_null($result['lup_community_loan_source'])) {
                            $data["lup_community_loan_source"] = $result['lup_community_loan_source'];
                        }

						
                        if (array_key_exists('lup_community_fund_source', $result) && !is_null($result['lup_community_fund_source'])) {
                            $data["lup_community_fund_source"] = $result['lup_community_fund_source'];
                        }
						  if (array_key_exists('lup_community_irrigation_system', $result) && !is_null($result['lup_community_irrigation_system'])) {
                            $data["lup_community_irrigation_system"] = $result['lup_community_irrigation_system'];
                        }
						
                        if (array_key_exists('lup_community__lead_position', $result) && !is_null($result['lup_community__lead_position'])) {
                            $data["lup_community__lead_position"] = $result['lup_community__lead_position'];
                        }

                        if (array_key_exists('community_group_category', $result) && !is_null($result['community_group_category'])) {
                            $data["community_group_category"] = $result['community_group_category'];
                        }

                        if (array_key_exists('lup_reg_n_add_lookup', $result) && !is_null($result['lup_reg_n_add_lookup'])) {
                            $data["lup_reg_n_add_lookup"] = $result['lup_reg_n_add_lookup'];
                        }
                        if (array_key_exists('prog_org_n_role', $result) && !is_null($result['prog_org_n_role'])) {
                            $data["prog_org_n_role"] = $result['prog_org_n_role'];
                        }

                        if (array_key_exists('org_n_code', $result) && !is_null($result['org_n_code'])) {
                            $data["org_n_code"] = $result['org_n_code'];
                        }

                        if (array_key_exists('community_grp_detail', $result) && !is_null($result['community_grp_detail'])) {
                            $data["community_grp_detail"] = $result['community_grp_detail'];
                        }

                        if (array_key_exists('staff_master', $result) && !is_null($result['staff_master'])) {
                            $data["staff_master"] = $result['staff_master'];
                        }

                        if (array_key_exists('gps_lup_list', $result) && !is_null($result['gps_lup_list'])) {
                            $data["gps_lup_list"] = $result['gps_lup_list'];
                        }
						
						 if (array_key_exists('staff_srv_center_access', $result) && !is_null($result['staff_srv_center_access'])) {
                            $data["staff_srv_center_access"] = $result['staff_srv_center_access'];
                        }


                        _json($data);

                    } else {
                        // user not found
                        $data["error"] = TRUE;
                        $data["error_msg"] = "Incorrect Username or password!";
                        _json($data);
                    }

                    break;


                case 'get_reference_data':

                    $user_name = $_POST['user_name'];
                    $password = $_POST['password'];
                    $lay_r_code_j = $_POST['lay_r_code_j'];
                    $operation_mode = $_POST['operation_mode'];


                    $result = $db->get_reference_data($user_name, $password, $lay_r_code_j, $operation_mode);
                    //$result = $db->get_reference_data($user_name,$password);


                    if ($result != false) {

                        if ($result['user'] != false) {
                            // user found
                            $data["error"] = FALSE;
                            $data["UsrID"] = $result['user']["StfCode"];
                            $data["user"]["AdmCountryCode"] = $result['user']["AdmCountryCode"];
                            $data["user"]["UsrLogInName"] = $result['user']["UsrLogInName"];
                            $data["user"]["UsrLogInPW"] = $result['user']["UsrLogInPW"];
                            $data["user"]["UsrFirstName"] = $result['user']["StfName"];
                            $data["user"]["UsrLastName"] = $result['user']["StfName"];
                            $data["user"]["UsrEmail"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrEmailVerification"] = $result['user']["UsrEmail"];
                            $data["user"]["UsrStatus"] = $result['user']["StfStatus"];
                            $data["user"]["EntryBy"] = $result['user']["EntryBy"];
                            $data["user"]["EntryDate"] = $result['user']["EntryDate"];
                        }

                        if (array_key_exists('countries', $result) && !is_null($result['countries'])) {
                            $data["countries"] = $result['countries'];
                        }

                        if (array_key_exists('valid_dates', $result) && !is_null($result['valid_dates'])) {
                            $data["valid_dates"] = $result['valid_dates'];
                        }

                        if (array_key_exists('layer_labels', $result) && !is_null($result['layer_labels'])) {
                            $data["layer_labels"] = $result['layer_labels'];
                        }

                        if (array_key_exists('district', $result) && !is_null($result['district'])) {
                            $data["district"] = $result['district'];
                        }

                        if (array_key_exists('upazilla', $result) && !is_null($result['upazilla'])) {
                            $data["upazilla"] = $result['upazilla'];
                        }

                        if (array_key_exists('unit', $result) && !is_null($result['unit'])) {
                            $data["unit"] = $result['unit'];
                        }

                        if (array_key_exists('village', $result) && !is_null($result['village'])) {
                            $data["village"] = $result['village'];
                        }

                        if (array_key_exists('relation', $result) && !is_null($result['relation'])) {
                            $data["relation"] = $result['relation'];
                        }

                        if (array_key_exists('gps_group', $result) && !is_null($result['gps_group'])) {
                            $data["gps_group"] = $result['gps_group'];
                        }

                        if (array_key_exists('gps_subgroup', $result) && !is_null($result['gps_subgroup'])) {
                            $data["gps_subgroup"] = $result['gps_subgroup'];
                        }

                        /*if(array_key_exists('gps_location', $result) && !is_null($result['gps_location']))	{
                            $data["gps_location"] 			= $result['gps_location'];}
                        */
                        if (array_key_exists('adm_countryaward', $result) && !is_null($result['adm_countryaward'])) {
                            $data["adm_countryaward"] = $result['adm_countryaward'];
                        }

                        if (array_key_exists('adm_donor', $result) && !is_null($result['adm_donor'])) {
                            $data["adm_donor"] = $result['adm_donor'];
                        }


                        if (array_key_exists('adm_program_master', $result) && !is_null($result['adm_program_master'])) {
                            $data["adm_program_master"] = $result['adm_program_master'];
                        }

                        if (array_key_exists('adm_service_master', $result) && !is_null($result['adm_service_master'])) {
                            $data["adm_service_master"] = $result['adm_service_master'];
                        }

                        if (array_key_exists('adm_op_month', $result) && !is_null($result['adm_op_month'])) {
                            $data["adm_op_month"] = $result['adm_op_month'];
                        }

                        if (array_key_exists('adm_country_program', $result) && !is_null($result['adm_country_program'])) {
                            $data["adm_country_program"] = $result['adm_country_program'];
                        }

                        if (array_key_exists('dob_service_center', $result) && !is_null($result['dob_service_center'])) {
                            $data["dob_service_center"] = $result['dob_service_center'];
                        }

                        if (array_key_exists('staff_access_info', $result) && !is_null($result['staff_access_info'])) {
                            $data["staff_access_info"] = $result['staff_access_info'];
                        }


                        if (array_key_exists('lb_reg_hh_category', $result) && !is_null($result['lb_reg_hh_category'])) {
                            $data["lb_reg_hh_category"] = $result['lb_reg_hh_category'];
                        }


                        if (array_key_exists('reg_lup_graduation', $result) && !is_null($result['reg_lup_graduation'])) {
                            $data["reg_lup_graduation"] = $result['reg_lup_graduation'];
                        }
                        // by pop @2015-11-04
                        if (array_key_exists('report_template', $result) && !is_null($result['report_template'])) {
                            $data["report_template"] = $result['report_template'];
                        }

                        // by pop @2015-11-05
                        if (array_key_exists('card_print_reason', $result) && !is_null($result['card_print_reason'])) {
                            $data["card_print_reason"] = $result['card_print_reason'];
                        }


                        if (array_key_exists('staff_fdp_access', $result) && !is_null($result['staff_fdp_access'])) {
                            $data["staff_fdp_access"] = $result['staff_fdp_access'];
                        }

                        if (array_key_exists('fdp_master', $result) && !is_null($result['fdp_master'])) {
                            $data["fdp_master"] = $result['fdp_master'];
                        }

                        // add  ref table


                        if (array_key_exists('lup_srv_option_list', $result) && !is_null($result['lup_srv_option_list'])) {
                            $data["lup_srv_option_list"] = $result['lup_srv_option_list'];
                        }

                        // only json service data
                        if (array_key_exists('service_table', $result) && !is_null($result['service_table'])) {
                            $data["service_table"] = $result['service_table'];
                        }
                        if (array_key_exists('service_exe_table', $result) && !is_null($result['service_exe_table'])) {
                            $data["service_exe_table"] = $result['service_exe_table'];
                        }

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
        } else {
            $data["error"] = TRUE;
            $data["error_msg"] = "Task not found!";
            _json($data);
        } // End Task missing
    } else {
        $data["error"] = TRUE;
        $data["error_msg"] = "Invalid API key!";
        _json($data);
    } // End valid_key
} else {
    $data["error"] = TRUE;
    $data["error_msg"] = "Required parameter is missing!";
    _json($data);
}

function validate_key($key)
{
    // need to check API key from Cloud Server
    if ($key == 'PhEUT5R251')
        return true;
    else
        return false;
}

function _json($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
}

?>
<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

    $loadScripts[] = 'includes/stlistitems';
try 
{    

    if (!EfrontUser::isOptionVisible('stlistitems')) 
    {
        eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
    }
    else
    {
        $number_course = eF_getTableData("groups INNER JOIN users_to_groups ON groups.id = users_to_groups.groups_ID AND users_to_groups.users_LOGIN='".$currentUser -> user['login']."'", "number_course");
        //$smarty -> assign("T_NC_DATA", $number_course);
        //$currentGroups -> applyRoleOptions($number_course[0]['number_course']);
        if (empty($number_course)) {
            throw new Exception(_UNAUTHORIZEDACCESS);
        }
        $resultfirst = eF_getTableData("listitems", "*", "active=1 and semestr=1 and kafedra_id='".$currentUser -> user['id_departments']."' and number_course='".$number_course[0]['number_course']."'");

        $smarty -> assign("T_USERTYPESFIRST_DATA", $resultfirst);

        $resultsecond = eF_getTableData("listitems", "*", "active=1 and semestr=2 and kafedra_id='".$currentUser -> user['id_departments']."' and number_course='".$number_course[0]['number_course']."'");

        $smarty -> assign("T_USERTYPESSECOND_DATA", $resultsecond);

                $rolespr = EfrontUser :: getProfRoles(true);
                $rolespr = array(0 => _NAMELIST) + $rolespr; 
        $smarty -> assign("T_PR_PATHS", $rolespr);

        $type_listitems = array("Екзамен" , "Залік", "Диф. залік");
        $smarty -> assign("T_TL_PATHS", $type_listitems);

        $number_course = array(1 => "I", 2=> "II",3 => "III", 4 => "IV", 5 => "V м", 6 => "V sp", 7 => "VI");
        $smarty -> assign("T_NC_PATHS", $number_course);
    }
    
        

         
    
} 
catch (Exception $e) 
{
    $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
    $message      = _SOMEPROBLEMOCCURED.': '.$e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(event, \''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
    $message_type = 'failure';
}

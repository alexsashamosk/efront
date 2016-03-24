<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

/*If the user is not the administrator, then */
if ($currentUser -> user['user_type'] != 'administrator') {
    exit;
}
$smarty -> assign("T_OPTION", $_GET['option']);

try {


    
    
        $rolesdep = EfrontUser :: getDep();
        $smarty -> assign("T_DEP_PATHS", $rolesdep);

        $groups     = EfrontGroup :: getGroups();
        $smarty -> assign("T_GROUPS", $groups);

   $number_course = array(1 => "I", 2=> "II",3 => "III", 4 => "IV", 5 => "V м", 6 => "V sp", 7 => "VI");
        $smarty -> assign("T_NUMBERCOURSE", $number_course);

        $prepod     = EfrontUser :: getPr();
        $smarty -> assign("T_PR_PATHS", $prepod);

        

    

    $users   = array();
    if(isset($_GET['group_filter']) && isset($_GET['dep_filter']))
    {
        $result  = eF_getTableData("courses INNER join courses_to_groups ON courses.id = courses_to_groups.courses_ID,groups groups INNER join users_to_groups ON groups.id = users_to_groups.groups_ID INNER JOIN users ON users_to_groups.users_LOGIN = users.login", " users.name", "courses_to_groups.groups_ID = users_to_groups.groups_ID and courses_to_groups.groups_ID=".$_GET['group_filter']." and users.id_departments=".$_GET['dep_filter']);
         $smarty -> assign("T_GRDEP", $result);
          $result1  = eF_getTableData("courses INNER join courses_to_groups ON courses.id = courses_to_groups.courses_ID,groups groups INNER join users_to_groups ON groups.id = users_to_groups.groups_ID INNER JOIN users ON users_to_groups.users_LOGIN = users.login", " courses.name", "courses_to_groups.groups_ID = users_to_groups.groups_ID and courses_to_groups.groups_ID=".$_GET['group_filter']."  and users.id_departments=".$_GET['dep_filter']." GROUP BY courses.name");
         $smarty -> assign("T_USERPR", $result1);
         $result2  = eF_getTableData("courses INNER join courses_to_groups ON courses.id = courses_to_groups.courses_ID,groups groups INNER join users_to_groups ON groups.id = users_to_groups.groups_ID INNER join users ON users_to_groups.users_LOGIN = users.login INNER JOIN users_to_courses ON users.login = users_to_courses.users_LOGIN", " users_to_courses.final_grade", "courses_to_groups.groups_ID = users_to_groups.groups_ID and courses.id=users_to_courses.courses_ID and courses_to_groups.groups_ID=".$_GET['group_filter']."  and users.id_departments=".$_GET['dep_filter']);
         $smarty -> assign("T_SCORE", $result2);
    }
    
//   
    

    

} catch (Exception $e) {
    handleNormalFlowExceptions($e);
}



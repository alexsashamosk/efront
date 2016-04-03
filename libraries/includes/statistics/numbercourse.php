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


        $coursesreport = EfrontCourse::getCoursesReport();
        $smarty -> assign("T_COURSEREP_PATHS", $coursesreport);
    
        $rolesdep = EfrontUser :: getDep();
        $smarty -> assign("T_DEP_PATHS", $rolesdep);

       // var_dump($T_DEP_PATHS['name']);

        $groups     = EfrontGroup :: getGroups();
        $smarty -> assign("T_GROUPS", $groups);

   $number_course = array(1 => "I", 2=> "II",3 => "III", 4 => "IV", 5 => "V м", 6 => "V sp", 7 => "VI");
        $smarty -> assign("T_NUMBERCOURSE", $number_course);



        $prepod     = EfrontUser :: getPr();
        $smarty -> assign("T_PR_PATHS", $prepod);

        

    
        $users   = array();

    if(isset($_GET['group_filter']) && isset($_GET['dep_filter']) && isset($_GET['course_filter']))
    {

        $users   = array();
         $result  = eF_getTableData("courses INNER join courses_to_groups ON courses.id = courses_to_groups.courses_ID,groups groups INNER join users_to_groups ON groups.id = users_to_groups.groups_ID INNER join users ON users_to_groups.users_LOGIN = users.login INNER JOIN users_to_courses ON users.login = users_to_courses.users_LOGIN", "users.name, users.surname, users_to_courses.final_grade", "courses_to_groups.groups_ID = users_to_groups.groups_ID and courses.id=users_to_courses.courses_ID and courses.id=".$_GET['course_filter']." and courses_to_groups.groups_ID=".$_GET['group_filter']."  and users.id_departments=".$_GET['dep_filter']." GROUP BY users.login");
         //var_dump($result);
         $smarty -> assign("T_REPORT_PATHS", $result);

         foreach($result as $value) 
        {
            $users[$value['name']]['name']     = $value['name'];
            $users[$value['name']]['surname']    = $value['surname'];
            $users[$value['name']]['final_grade']    = $value['final_grade'];
        }




    }
    
       
        




}
 catch (Exception $e) 
{
    handleNormalFlowExceptions($e);
}

if (isset($_GET['excel'])) {
    require_once 'Spreadsheet/Excel/Writer.php';

    $workBook  = new Spreadsheet_Excel_Writer();
    $workBook -> setTempDir(G_UPLOADPATH);
    $workBook -> setVersion(8);
    
    $formatExcelHeaders = & $workBook -> addFormat(array('Size' => 14, 'Bold' => 1, 'HAlign' => 'left'));
    $headerFormat       = & $workBook -> addFormat(array('border' => 0, 'bold' => '1', 'size' => '11', 'color' => 'black', 'fgcolor' => 22, 'align' => 'center'));
    $formatContent      = & $workBook -> addFormat(array('HAlign' => 'left', 'Valign' => 'top', 'TextWrap' => 1));
    $headerBigFormat    = & $workBook -> addFormat(array('HAlign' => 'center', 'FgColor' => 22, 'Size' => 16, 'Bold' => 1));
    $titleCenterFormat  = & $workBook -> addFormat(array('HAlign' => 'center', 'Size' => 11, 'Bold' => 1));
    $titleLeftFormat    = & $workBook -> addFormat(array('HAlign' => 'left', 'Size' => 11, 'Bold' => 1));
    $fieldLeftFormat    = & $workBook -> addFormat(array('HAlign' => 'left', 'Size' => 10));
    $fieldRightFormat   = & $workBook -> addFormat(array('HAlign' => 'right', 'Size' => 10));
    $fieldCenterFormat  = & $workBook -> addFormat(array('HAlign' => 'center', 'Size' => 10));
    $titleCenterHeaderFormat  = & $workBook -> addFormat(array('HAlign' => 'center', 'Size' => 14, 'bold' => 2));
    
    //first tab
    $workSheet = & $workBook -> addWorksheet("System info");
    $workSheet -> setInputEncoding('utf-8');

    $workSheet -> setColumn(0, 0, 5);
    $workSheet -> write(1, 1, _BASICINFO, $headerFormat);
    $workSheet -> mergeCells(1, 1, 1, 2);
    $workSheet -> setColumn(1, 3, 30);
    $workSheet -> write(2, 1, _GROUPONLY, $fieldLeftFormat);
    $workSheet -> write(2, 2, $groups[$_GET['group_filter']][name], $fieldCenterFormat);
    $workSheet -> write(3, 1, _NAMEDEP, $fieldLeftFormat);
    $workSheet -> write(3, 2, $rolesdep[$_GET['dep_filter']][name], $fieldCenterFormat);
    $workSheet -> write(4, 1, _COURSE, $fieldLeftFormat);
    $workSheet -> write(4, 2, $coursesreport[$_GET['course_filter']][name], $fieldCenterFormat);


    
    $workSheet -> write(6, 1, _FINALGRADES, $headerFormat);
    $workSheet -> mergeCells(6, 1, 6, 2);

    $workSheet -> write(6, 1, _STPR, $titleLeftFormat);
    $row =7;
    foreach ($users as $name => $value) 
    {
        $workSheet -> write($row, 1, $value['name']." ".$value['surname'], $fieldLeftFormat);
        $workSheet -> write($row, 2, $value['final_grade']." б.", $fieldCenterFormat);
        $row++;

    }

    $workSheet = & $workBook -> addWorkSheet("Analytic log");
    $workSheet -> setInputEncoding('utf-8');
    $workSheet -> setColumn(0, 0, 5);
    $workSheet -> write(1, 1, _ANALYTICLOG, $headerFormat);
    $workSheet -> mergeCells(1, 1, 1, 7);
    $workSheet -> setColumn(1, 6, 30);
    $workSheet -> write(2, 1, _LOGIN, $fieldLeftFormat);
    $workSheet -> write(2, 2, _LESSON, $fieldLeftFormat);
    $workSheet -> write(2, 3, _UNIT, $fieldLeftFormat);
    $workSheet -> write(2, 4, _ACTION, $fieldLeftFormat);
    $workSheet -> write(2, 5, _TIME, $fieldLeftFormat);
    $workSheet -> write(2, 6, _IPADDRESS, $fieldLeftFormat);
    
    $row=3;
               

    
    $workBook -> send('courses.xls');
    $workBook -> close();
    exit(0);
}
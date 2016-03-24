<?php

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

/*
 User types is the page that concerns direction administration. Here the administrator can view, add, delete and modify User types
 There are 5 sub options in this page, denoted by an extra link part:
 - &add_user_type=1                       When we are adding a new user_type
 - &delete_user_type=<user_type>          When we want to delete user type <user_type>
 - &edit_user_type=<user_type>            When we want to edit user type <user_type>
 - &deactivate_user_type=<user_type>      When we deactivate user type <user_type>
 - &activate_user_type=<user_type>        When we activate user type <user_type>
 */
    $loadScripts[] = 'includes/final_grade';
try {    

    if (!EfrontUser::isOptionVisible('final_grade')) {
        eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
    }
    
    if ((isset($_GET['sel_user'])) && (isset($_GET['course'])))
    {

            $sel_user = "sel_user=".$_GET['sel_user'];
            $course = "course=".$_GET['course'];
       
           if($stg == final_grade)
           {
                $currentCourse = new EfrontCourse($_GET['course']);
                $smarty -> assign("T_CURRENT_COURSE", $currentCourse);
           }
           


            $form = new HTML_QuickForm("add_final_grade", "post", basename($_SERVER['PHP_SELF'])."?ctg=final_grade&$sel_user&$course", "", null, true);
            $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
            $form -> addElement('text', 'final_grade', _FINALGRADE, 'class = "inputText"');
            $form -> addElement('static', 'note', _FINALGRADEINFO);
        
            $form -> addRule('final_grade', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');
            if($stg == final_grade)
            {
                $form -> setDefaults($currentCourse -> course);   
            }


            if (isset($currentUser -> coreAccess['course'])) 
            {
                $form -> freeze();
            } 
            else 
            {
            $form -> addElement('submit', 'submit_type', _SUBMIT, 'class = "flatButton"');

            if ($form -> isSubmitted() && $form -> validate()) {
                try {
                    $values = $form -> exportValues();
                    $fields['final_grade']          = $values['final_grade'];

                    if($values['final_grade']>=0 && $values['final_grade']<=100)
                    {
                        $currentCourse -> course = array_merge($currentCourse -> course, $fields);
                        eF_updateTableData("users_to_courses",array("final_grade" => $values['final_grade']), "courses_ID=".$_GET['course']." and users_LOGIN='".$_GET['sel_user']."'");
                    }

                    eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=lessons&course=".$_GET['course']."&op=course_certificates");
                } catch (Exception $e){
                    handleNormalFlowExceptions($e);
                }
            }
        }

            $smarty -> assign('T_FINALGRADE_FORM', $form -> toArray());

    }
    else {
        $result = eF_getTableData("users_to_courses", "*");
        $smarty -> assign("T_USERTYPES_DATA", $result);
    }
     
} catch (Exception $e) {
   
}

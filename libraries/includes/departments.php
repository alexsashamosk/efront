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
    $loadScripts[] = 'includes/departments';
try {    

    if (!EfrontUser::isOptionVisible('departments')) {
        eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
    }
    
    if (isset($_GET['delete_departments']) && eF_checkParameter($_GET['delete_departments'], 'id')) {
        if (isset($currentUser -> coreAccess['departments']) && $currentUser -> coreAccess['departments'] != 'change') {
            eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
        }
        try {
            $departments = new EfrontDepartments($_GET['delete_departments']);
            $departments -> delete();
            //header("Location: administrator.php?ctg=departments");
        } catch (Exception $e) {
            handleAjaxExceptions($e);
        }
        exit;
    } elseif (isset($_GET['deactivate_departments']) && eF_checkParameter($_GET['deactivate_departments'], 'id')) {
        if (isset($currentUser -> coreAccess['departments']) && $currentUser -> coreAccess['departments'] != 'change') {
            echo _UNAUTHORIZEDACCESS;
            exit;
        }
        try {
            eF_updateTableData("departments", array('active' => 0), "id='".$_GET['deactivate_departments']."'");
            echo "0";
            header("Location: administrator.php?ctg=departments");
        } catch (Exception $e) {
            header("HTTP/1.0 500 ");
            echo _SOMEPROBLEMEMERGED;
        } 
        exit;
    } elseif (isset($_GET['activate_departments']) && eF_checkParameter($_GET['activate_departments'], 'id')) {
        if (isset($currentUser -> coreAccess['departments']) && $currentUser -> coreAccess['departments'] != 'change') {
            echo _UNAUTHORIZEDACCESS;
            exit;
        }
        try {
            eF_updateTableData("departments", array('active' => 1), "id='".$_GET['activate_departments']."'");
            echo "1";
            header("Location: administrator.php?ctg=departments");
        } catch (Exception $e) {
            header("HTTP/1.0 500 ");
            echo _SOMEPROBLEMEMERGED;
        } 
        exit;
    } elseif (isset($_GET['add_departments']) || (isset($_GET['edit_departments']) && eF_checkParameter($_GET['edit_departments'], 'text'))) {

        if (isset($_GET['edit_departments'])) {
            $currentDepartments = new EfrontDepartments($_GET['edit_departments']);
            $smarty -> assign("T_CURRENT_DEPARTMENTS", $currentDepartments);
        }

        isset($_GET['add_departments']) ? $postTarget = 'add_departments=1' : $postTarget = "edit_departments=".$_GET['edit_departments'];
        $form = new HTML_QuickForm("add_departments_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=departments&$postTarget", "", null, true);
        $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
        
        $roles = EfrontLessonUser :: getFacultiesRoles(true);
        //array_unshift($roles, _DONTUSEDEFAULTGROUP);
        $roles = array(0 => _NAMEFAC) + $roles;

        $form -> addElement('text', 'name', _NAMEDEP, 'class = "inputText"');
        $form -> addElement('text', 'code', _CODEDEP, 'class = "inputText"');
        $form -> addElement('select', 'faculty_id' , _NAMEFAC, $roles, 'class = "inputText"');
        $form -> addElement('text', 'link', _CODEDEP, 'class = "inputText"');



        $form -> addRule('name', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');

        if (isset($_GET['edit_departments'])) {
            $form -> setDefaults($currentDepartments -> departments);
        }
        if (isset($currentUser -> coreAccess['departments']) && $currentUser -> coreAccess['departments'] != 'change') {
            $form -> freeze();
        } else {
            $form -> addElement('submit', 'submit_type', _SUBMIT, 'class = "flatButton"');

            if ($form -> isSubmitted() && $form -> validate()) {
                try {
                    $values = $form -> exportValues();
                    $fields['name']          = $values['name'];
                    $fields['code']   = $values['code'];
                    $fields['link']   = $values['link'];
                    if($values['faculty_id'] != 0)
                    $fields['faculty_id']   = $values['faculty_id'];
                    else
                        $fields['faculty_id']   = $values['null'];
                    if (isset($_GET['edit_departments'])) {
                        $currentDepartments -> departments = array_merge($currentDepartments -> departments, $fields);
                        $currentDepartments -> persist();
                    } else {
                        $currentDepartments = EfrontDepartments::create($fields);
                    }
                    eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=departments&edit_departments=".$currentDepartments -> departments['id']."&message=".urlencode(_OPERATIONCOMPLETEDSUCCESSFULLY)."&message_type=success");
                } catch (Exception $e){
                    handleNormalFlowExceptions($e);
                }
            }
        }
        $smarty -> assign('T_DEPARTMENTS_FORM', $form -> toArray());


    } else {
        $result = eF_getTableData("departments", "*");
        $smarty -> assign("T_USERTYPES_DATA", $result);
        $roles = EfrontLessonUser :: getFacultiesRoles(true);
        $roles = array(0 => _NAMEFAC) + $roles;
        $smarty -> assign("T_DIRECTIONS_PATHS", $roles);
    }
} catch (Exception $e) {
    $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
    $message      = _SOMEPROBLEMOCCURED.': '.$e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(event, \''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
    $message_type = 'failure';
}

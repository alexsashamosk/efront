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
    $loadScripts[] = 'includes/faculties';
try {    

    if (!EfrontUser::isOptionVisible('faculties')) {
        eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
    }
    
    if (isset($_GET['delete_faculties']) && eF_checkParameter($_GET['delete_faculties'], 'id')) {
        if (isset($currentUser -> coreAccess['faculties']) && $currentUser -> coreAccess['faculties'] != 'change') {
            eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
        }
        try {
            $faculties = new EfrontFaculties($_GET['delete_faculties']);
            $faculties -> delete();
            //header("Location: administrator.php?ctg=faculties");
        } catch (Exception $e) {
            handleAjaxExceptions($e);
        }
        exit;
    } elseif (isset($_GET['deactivate_faculties']) && eF_checkParameter($_GET['deactivate_faculties'], 'id')) {
        if (isset($currentUser -> coreAccess['faculties']) && $currentUser -> coreAccess['faculties'] != 'change') {
            echo _UNAUTHORIZEDACCESS;
            exit;
        }
        try {
            eF_updateTableData("faculties", array('active' => 0), "id='".$_GET['deactivate_faculties']."'");
            echo "0";
            header("Location: administrator.php?ctg=faculties");
        } catch (Exception $e) {
            header("HTTP/1.0 500 ");
            echo _SOMEPROBLEMEMERGED;
        } 
        exit;
    } elseif (isset($_GET['activate_faculties']) && eF_checkParameter($_GET['activate_faculties'], 'id')) {
        if (isset($currentUser -> coreAccess['faculties']) && $currentUser -> coreAccess['faculties'] != 'change') {
            echo _UNAUTHORIZEDACCESS;
            exit;
        }
        try {
            eF_updateTableData("faculties", array('active' => 1), "id='".$_GET['activate_faculties']."'");
            echo "1";
            header("Location: administrator.php?ctg=faculties");
        } catch (Exception $e) {
            header("HTTP/1.0 500 ");
            echo _SOMEPROBLEMEMERGED;
        } 
        exit;
    } elseif (isset($_GET['add_faculties']) || (isset($_GET['edit_faculties']) && eF_checkParameter($_GET['edit_faculties'], 'text'))) {

        if (isset($_GET['edit_faculties'])) {
            $currentFaculties = new EfrontFaculties($_GET['edit_faculties']);
            $smarty -> assign("T_CURRENT_FACULTIES", $currentFaculties);
        }

        isset($_GET['add_faculties']) ? $postTarget = 'add_faculties=1' : $postTarget = "edit_faculties=".$_GET['edit_faculties'];
        $form = new HTML_QuickForm("add_faculties_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=faculties&$postTarget", "", null, true);
        $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
        
        $form -> addElement('text', 'name', _NAMEFAC, 'class = "inputText"');
        $form -> addElement('text', 'code', _CODEFAC, 'class = "inputText"');

        $form -> addRule('name', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');

        if (isset($_GET['edit_faculties'])) {
            $form -> setDefaults($currentFaculties -> faculties);
        }
        if (isset($currentUser -> coreAccess['faculties']) && $currentUser -> coreAccess['faculties'] != 'change') {
            $form -> freeze();
        } else {
            $form -> addElement('submit', 'submit_type', _SUBMIT, 'class = "flatButton"');

            if ($form -> isSubmitted() && $form -> validate()) {
                try {
                    $values = $form -> exportValues();
                    $fields['name']          = $values['name'];
                    $fields['code']   = $values['code'];
                    if (isset($_GET['edit_faculties'])) {
                        $currentFaculties -> faculties = array_merge($currentFaculties -> faculties, $fields);
                        $currentFaculties -> persist();
                    } else {
                        $currentFaculties = EfrontFaculties::create($fields);
                    }
                    eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=faculties&edit_faculties=".$currentFaculties -> faculties['id']."&message=".urlencode(_OPERATIONCOMPLETEDSUCCESSFULLY)."&message_type=success");
                } catch (Exception $e){
                    handleNormalFlowExceptions($e);
                }
            }
        }
        $smarty -> assign('T_FACULTIES_FORM', $form -> toArray());


    } else {
        $result = eF_getTableData("faculties", "*");
        $smarty -> assign("T_USERTYPES_DATA", $result);
    }
} catch (Exception $e) {
    $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
    $message      = _SOMEPROBLEMOCCURED.': '.$e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(event, \''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
    $message_type = 'failure';
}

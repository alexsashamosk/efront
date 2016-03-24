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
    $loadScripts[] = 'includes/listitems';
try {    

    if (!EfrontUser::isOptionVisible('listitems')) {
        eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
    }
    
    if (isset($_GET['delete_listitems']) && eF_checkParameter($_GET['delete_listitems'], 'id')) {
        if (isset($currentUser -> coreAccess['listitems']) && $currentUser -> coreAccess['listitems'] != 'change') {
            eF_redirect("".basename($_SERVER['PHP_SELF'])."?ctg=control_panel&message=".urlencode(_UNAUTHORIZEDACCESS)."&message_type=failure");
        }
        try {
            $listitems = new EfrontListitems($_GET['delete_listitems']);
            $listitems -> delete();
            //header("Location: administrator.php?ctg=faculties");
        } catch (Exception $e) {
            handleAjaxExceptions($e);
        }
        exit;
    } elseif (isset($_GET['deactivate_listitems']) && eF_checkParameter($_GET['deactivate_listitems'], 'id')) {
        if (isset($currentUser -> coreAccess['listitems']) && $currentUser -> coreAccess['listitems'] != 'change') {
            echo _UNAUTHORIZEDACCESS;
            exit;
        }
        try {
            eF_updateTableData("listitems", array('active' => 0), "id='".$_GET['deactivate_listitems']."'");
            echo "0";
            //header("Location: administrator.php?ctg=faculties");
        } catch (Exception $e) {
            header("HTTP/1.0 500 ");
            echo _SOMEPROBLEMEMERGED;
        } 
        exit;
    } elseif (isset($_GET['activate_listitems']) && eF_checkParameter($_GET['activate_listitems'], 'id')) {
        if (isset($currentUser -> coreAccess['listitems']) && $currentUser -> coreAccess['listitems'] != 'change') {
            echo _UNAUTHORIZEDACCESS;
            exit;
        }
        try {
            eF_updateTableData("listitems", array('active' => 1), "id='".$_GET['activate_listitems']."'");
            echo "1";
           // header("Location: administrator.php?ctg=faculties");
        } catch (Exception $e) {
            header("HTTP/1.0 500 ");
            echo _SOMEPROBLEMEMERGED;
        } 
        exit;
    } elseif (isset($_GET['add_listitems']) || (isset($_GET['edit_listitems']) && eF_checkParameter($_GET['edit_listitems'], 'text'))) {

        if (isset($_GET['edit_listitems'])) {
            $currentListitems = new EfrontListitems($_GET['edit_listitems']);
            $smarty -> assign("T_CURRENT_LISTITEMS", $currentListitems);
        }

        isset($_GET['add_listitems']) ? $postTarget = 'add_listitems=1' : $postTarget = "edit_listitems=".$_GET['edit_listitems'];
        $form = new HTML_QuickForm("add_listitems_form", "post", basename($_SERVER['PHP_SELF'])."?ctg=listitems&$postTarget", "", null, true);
        $form -> registerRule('checkParameter', 'callback', 'eF_checkParameter');
        
        $rolesdep = EfrontUser :: getDepartmentsRoles(true);
        $rolesdep = array(0 => _NAMEDEP) + $rolesdep; 

        $rolespr = EfrontUser :: getProfRoles(true);
        $rolespr = array(0 => _NAMELIST) + $rolespr; 

        

        $number_course = array(1 => "I", 2=> "II",3 => "III", 4 => "IV", 5 => "V м", 6 => "V sp", 7 => "VI");

        $semestr  = array(1 => "I", 2 => "II");

        $type_listitems = array("Екзамен" , "Залік", "Диф. залік", "Курсова робота");

        $form -> addElement('text', 'name', _NAMELIST, 'class = "inputText"');
        $form -> addElement('text', 'code', _CODELIST, 'class = "inputText"');
        $form -> addElement('select', 'pz_prepod', _PZLIST,$rolespr, 'class = "inputText"');
        $form -> addElement('select', 'lk_prepod', _LKLIST, $rolespr, 'class = "inputText"');
        $form -> addElement('select', 'type', _TYPELIST,$type_listitems, 'class = "inputText"');
        $form -> addElement('select', 'number_course', _NUMBERCOURSE,$number_course, 'class = "inputText"');
        $form -> addElement('select', 'kafedra_id', _DEPLIST, $rolesdep , 'class = "inputText"');
        $form -> addElement('select', 'semestr', _SEMESTR, $semestr , 'class = "inputText"');

        $form -> addRule('name', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');
        $form -> addRule('type', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');
        $form -> addRule('number_course', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');
        $form -> addRule('kafedra_id', _THEFIELD.' '._TYPENAME.' '._ISMANDATORY, 'required', null, 'client');


        if (isset($_GET['edit_listitems'])) {
            $form -> setDefaults($currentListitems -> listitems);
        }
        if (isset($currentUser -> coreAccess['listitems']) && $currentUser -> coreAccess['listitems'] != 'change') {
            $form -> freeze();
        } else {
            $form -> addElement('submit', 'submit_type', _SUBMIT, 'class = "flatButton"');

            if ($form -> isSubmitted() && $form -> validate()) {
                try {
                    $values = $form -> exportValues();
                    $fields['name']          = $values['name'];
                    $fields['code']   = $values['code'];
                    $fields['pz_prepod']          = $values['pz_prepod'];
                    $fields['lk_prepod']   = $values['lk_prepod'];
                    $fields['type']          = $values['type'];
                    $fields['number_course']   = $values['number_course'];
                    $fields['kafedra_id']          = $values['kafedra_id'];
                    $fields['semestr']          = $values['semestr'];
                    if (isset($_GET['edit_listitems'])) {
                        $currentListitems -> listitems = array_merge($currentListitems -> listitems, $fields);
                        $currentListitems -> persist();
                    } else {
                        $currentListitems = EfrontListitems::create($fields);
                    }
                    eF_redirect(basename($_SERVER['PHP_SELF'])."?ctg=listitems&edit_listitems=".$currentListitems -> listitems['id']."&message=".urlencode(_OPERATIONCOMPLETEDSUCCESSFULLY)."&message_type=success");
                } catch (Exception $e){
                    handleNormalFlowExceptions($e);
                }
            }
        }
        $smarty -> assign('T_LISTITEMS_FORM', $form -> toArray());


    } else {
        $result = eF_getTableData("listitems", "*");
        $smarty -> assign("T_USERTYPES_DATA", $result);
        $rolesdep = EfrontUser :: getDepartmentsRoles(true);
        $rolesdep = array(0 => _NAMEDEP) + $rolesdep; 
        $smarty -> assign("T_DIRECTIONS_PATHS", $rolesdep);
        $rolespr = EfrontUser :: getProfRoles(true);
       // $rolespr = array_keys($rolespr);
        $rolespr = array(0 => _NAMELIST) + $rolespr; 
        $smarty -> assign("T_PR_PATHS", $rolespr);

        $type_listitems = array("Екзамен" , "Залік", "Диф. залік");
        $smarty -> assign("T_TL_PATHS", $type_listitems);

        $number_course = array(1 => "I", 2=> "II",3 => "III", 4 => "IV", 5 => "V м", 6 => "V sp", 7 => "VI");
        $smarty -> assign("T_NC_PATHS", $number_course);

        $semestr  = array(1 => "I", 2 => "II");
        $smarty -> assign("T_SEMESTR", $semestr);
         
    }
} catch (Exception $e) {
    $smarty -> assign("T_EXCEPTION_TRACE", $e -> getTraceAsString());
    $message      = _SOMEPROBLEMOCCURED.': '.$e -> getMessage().' ('.$e -> getCode().') &nbsp;<a href = "javascript:void(0)" onclick = "eF_js_showDivPopup(event, \''._ERRORDETAILS.'\', 2, \'error_details\')">'._MOREINFO.'</a>';
    $message_type = 'failure';
}

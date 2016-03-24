<?php
/**
 * File for groups
 *
 * @package eFront
*/

//This file cannot be called directly, only included.
if (str_replace(DIRECTORY_SEPARATOR, "/", __FILE__) == $_SERVER['SCRIPT_FILENAME']) {
    exit;
}

/**
 * EfrontGroupException class
 *
 * This class extends Exception class and is used to issue errors regarding groups
 * @package eFront
 * @author Antonellis Panagiotis
 * @version 1.0
 */
class EfrontDepartmentsException extends Exception
{
    const NO_ERROR          = 0;
    const GROUP_NOT_EXISTS  = 301;
    const INVALID_ID        = 302;
    const INVALID_USER      = 303;
    const USER_NOT_EXISTS   = 304;
    const USER_ALREADY_MEMBER = 305;
    const ASSIGNMENT_ERROR = 306;
    const GROUPKEYEXISTS 	= 307;
    const INVALID_TYPE 	   = 308;
}

/**
 * EfrontGroup class
 *
 * This class represents a group
 * @package eFront
 * @author Antonellis Panagiotis
 * @version 1.0
 */
class EfrontDepartments
{
    /**
     * The faculties array.
     *
     * @since 3.5.0
     * @var array
     * @access protected
     */
    public $departments = array();

    /**
     * The departments users. Calling getUsers() initializes it; otherwise, it evaluates to false.
     *
     * @since 3.5.0
     * @var array
     * @access protected
     */

    /*
     * The group lessons. Lessons correlated with this group can be either directly assigned to
     * all users of the group or be automatically assigned to every user joining the group
     */
    protected $lessons = false;

    /*
     * The group courses. Lessons correlated with this group can be either directly assigned to
     * all users of the group or be automatically assigned to every user joining the group
     */
    protected $courses = false;


    /**
     * Class constructor
     *
     * This function is used to instantiate the class. The instatiation is done
     * based on a group id. If an entry with this id is not found in the database,
     * an EfrontGroupException is thrown.
     * <br/>Example:
     * <code>
     * $group = new EfrontGroup(32);                     //32 is a group id
     * </code>
     *
     * @param int $group_id The group id or array with group's info array
     * @since 3.5.0
     * @access public
     */
    function __construct($departments_id) {

        if (is_array($departments_id)) {
            $departments[0] = $departments_id;
        } else {
	        if (!eF_checkParameter($departments_id, 'id')) {
	            throw new EfrontGroupException(_INVALIDID.": $departments_id", EfrontGroupException :: INVALID_ID);
	        }
            $departments = eF_getTableData("departments", "*", "id = $departments_id");
        }

        if (sizeof($departments) == 0) {
            throw new EfrontGroupException(_GROUPDOESNOTEXIST, EfrontGroupException :: GROUP_NOT_EXISTS);
        } else {
            $this -> departments = $departments[0];
        }
    }

    /**
     * Create group
     *
     * This function creates a new group. This involves creating
     * the database instance..
     * The function argument is an array with field values, corresponding to database
     * fields. All fields are optional, and if absent they are filled with default values,
     * but 'name' is strongly recommended to be defined
     * <br/>Example:
     * <code>
     * $fields = array('name' => 'Test group', 'description' => 'Description of the group');
     * try {
     *   $newGroup = EfrontGroup :: create($fields);                     //$newgroup is now a new group object
     * } catch (Exception $e) {
     *   echo $e -> getMessage();
     * }
     * </code><br/>
     *
     * @param array $fields The new group characteristics
     * @return EfrontGroup the new group object
     * @since 3.5.0
     * @access public
     * @static
     */
    public static function create($fields = array()) {
        !isset($fields['name'])    ? $fields['name'] = 'Default name' : null;

        $newId   = eF_insertTableData("departments", $fields);
        $result = eF_getTableData("departments", "*", "id=".$newId);                                         //We perform an extra step/query for retrieving data, sinve this way we make sure that the array fields will be in correct order (forst id, then name, etc)
        $direction = new EfrontDirection($result[0]);

        return $direction;
    }

   
    /**
     * Delete group
     *
     * This function is used to delete an existing group. In order to do
     * this, it caclulates all the group dependendant elements, deletes them
     * and finally deletes the group itself.
     *
     * <br/>Example:
     * <code>
     * try {
     *   $group = new EfrontGroup(32);                     //32 is the group id
     *   $group -> delete();
     * } catch (Exception $e) {
     *   echo $e -> getMessage();
     * }
     * </code>
     *
     * @return boolean True if everything is ok
     * @since 3.5.0
     * @access public
     */
    public function delete() {
        eF_deleteTableData("departments", "id=".$this -> departments['id']);
        return true;
    }

    public function persist() {

        $ok = eF_updateTableData("departments", $this -> departments, "id=".$this -> departments['id']);
        return $ok;
    }

	

   /**
     * Returns the existing groups
     *
     * This function returns the existing groups
     * group.
     * <br/>Example:
     * <code>
     * $groups -> EfrontGroup :: getGroups();
     * </code>
     *
     * @param boolean Flat to indicate whether to return group objects or not
     * @param boolean Flag to indicate whether to return disabled groups
     * @return array An array of groups. Each element is the group array
     * @since 3.5.0
     * @access public
     */
    public static function getDepartments($returnObjects = false, $returnDisabled = false){
        $departments = array();
        if ($returnDisabled){
            $data = eF_getTableData("departments", "id, code, name", "", "code");
        }
        else{
            $data = eF_getTableData("departments", "id, code, name", "active = 1", "code");
        }
        if ($returnObjects){
            foreach ($data as $departments_info){
                $departments = new EfrontDepartments($departments_info['id']);
                $departments[$departments_info['id']] = $departments;
            }
        }
        else{
            foreach ($data as $departments_info){
                $departments[$departments_info['id']] = $departments_info;
            }
        }
        return $departments;
    }

   /**
     * Returns the lessons that are associated with the group's users (NOT necessarily with the group)
     *
     * <br/>Example:
     * <code>
     * $group = new EfrontGroup(2);
     * $group->getLessonGroupUsers();
     * </code>
     *
     * @param boolean Flat to indicate whether to return lesson objects or not
     * @param boolean Flag to indicate whether to return disabled lessons
     * @return array An array of lessons. Each element is the lesson array
     * @since 3.6.0
     * @access public
     */

}





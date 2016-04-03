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
class EfrontFacultiesException extends Exception
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


class EfrontFaculties
{
    /**
     * The faculties array.
     *
     * @since 3.5.0
     * @var array
     * @access protected
     */
    public $faculties = array();


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
    function __construct($faculties_id) {

        if (is_array($faculties_id)) {
            $faculties[0] = $faculties_id;
        } else {
	        if (!eF_checkParameter($faculties_id, 'id')) {
	            throw new EfrontGroupException(_INVALIDID.": $faculties_id", EfrontGroupException :: INVALID_ID);
	        }
            $faculties = eF_getTableData("faculties", "*", "id = $faculties_id");
        }

        if (sizeof($faculties) == 0) {
            throw new EfrontGroupException(_GROUPDOESNOTEXIST, EfrontGroupException :: GROUP_NOT_EXISTS);
        } else {
            $this -> faculties = $faculties[0];
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

        $newId   = eF_insertTableData("faculties", $fields);
        $result = eF_getTableData("faculties", "*", "id=".$newId);                                         //We perform an extra step/query for retrieving data, sinve this way we make sure that the array fields will be in correct order (forst id, then name, etc)
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
        eF_deleteTableData("departments", "faculty_id=".$this -> faculties['id']);
        eF_deleteTableData("faculties", "id=".$this -> faculties['id']);

        return true;
    }

    public function persist() {

        $ok = eF_updateTableData("faculties", $this -> faculties, "id=".$this -> faculties['id']);
        return $ok;
    }

	public function toPathString($includeLeaf = true, $onlyActive = false) {
        if ($onlyActive) {
            $iterator = new EfrontNodeFilterIterator(new RecursiveIteratorIterator(new RecursiveArrayIterator($this -> tree), RecursiveIteratorIterator :: SELF_FIRST), array('active' => 1));
        } else {
            $iterator = new EfrontNodeFilterIterator(new RecursiveIteratorIterator(new RecursiveArrayIterator($this -> tree), RecursiveIteratorIterator :: SELF_FIRST));
        }
        foreach ($iterator as $id => $value) {
            $values = array();
            foreach ($this -> getNodeAncestors($id) as $direction) {
                $values[] = $direction['name'];
            }
            if (!$includeLeaf) {
                unset($values[0]);
            }
            $parentsString[$id] = implode('&nbsp;&rarr;&nbsp;', array_reverse($values));
        }

        return $parentsString;
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
    public static function getFaculties($returnObjects = false, $returnDisabled = false){
        $faculties = array();
        if ($returnDisabled){
            $data = eF_getTableData("faculties", "id, code, name", "", "code");
        }
        else{
            $data = eF_getTableData("faculties", "id, code, name", "active = 1", "code");
        }
        if ($returnObjects){
            foreach ($data as $faculties_info){
                $faculties = new EfrontFaculties($faculties_info['id']);
                $faculties[$faculties_info['id']] = $faculties;
            }
        }
        else{
            foreach ($data as $faculties_info){
                $faculties[$faculties_info['id']] = $faculties_info;
            }
        }
        return $faculties;
    }

     public static function getFacultiestree($returnObjects = false){

            $result = eF_getTableData("faculties", "id, code, name", "active = 1");

            foreach ($result as $value) {
            if ($returnObjects){
                $faculties[$value['id']] = new EfrontFaculties($value);
            } else {
               // $value['name']    = unserialize($value['name']);
                $faculties[$value['id']] = $value;
            }
        }

        return $faculties;
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

/**
* 
*/




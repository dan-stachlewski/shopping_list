<?php
/**
 * Retrieves the tag_id's for the shopping list from the dBase which indicates why and from whom the list is created
 * This is a STATIC METHOD from the dBase Class
 * :: = SCOPE OPERATOR RESTRICTING USAGE TO THE dBase CLASS
 * @return array
 */

/**
 * Queries the dBase retrieves corresponding tags to be listed along w their names.
 * Tags are used to group shopping lists into reasons why shopping list is written, for which supermarket the list is filled and why a shopping list is deleted
 * @return array tag_id => tag_name
 */
function get_all_tags() {
    //1. Connect to dBase
    $db = Database::getDB();
    //2. Query the dBase
    $query = "SELECT tags.id AS `tag_id`,
                     tags.name AS `tag_name`
              FROM tags
              WHERE tags_id > 1";
    try {
    //3. Prepare the Query
        $stmnt = $db->prepare($query);
    //4. Execute the Query
        $stmnt->execute();
    //5. Retrieve the Results
        $result = $stmnt->fetchAll(PDO::FETCH_ASSOC);
    //6. Close the Connection
        $stmnt->closeCursor();
    //7. Return the Results    
        return $result;
    } catch (PDOException $e) {
    //8. Display an error if a connection could not be established
        display_db_error($e->getMessage());
        exit();
    }
} //END function get_all_tags

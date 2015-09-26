<?php

/* 
 * The FUNCTIONS below COLLECT & MANIPULATE the data.
 * ALL LISTS are CONVERTED & STORED as JSON Files.
 */

//DATA Hardcoded b/c dBase has no USERS at present
define("CURRENT_LIST_TAG", 1);
define("ERROR_TAG", 7);

/**
 * Adds Items to the shopping list.
 * @return array
 */
function add_shopping_item($item, $user_id) {
    $items = get_current_list($user_id);
    $items[] = $item;
    save_list_to_db($items, $user_id);
    return $items;
} //END function add_shopping_item

/**
 * Deletes the selected/checked Items from the list.
 * @return array
 */
function delete_items($idxs_to_be_deleted, $tag_id, $user_id) {
    /**
     * Retrieves current list via get_current_list METHOD
     * which uses the user_id to retrieve the list from the dBase
     */
    $items = get_current_list($user_id);

    /**
     * Uses unset() function to delete the checked items in the array
     */
    $deleted_items = [];
    foreach ($idxs_to_be_deleted as $idx) {
        $deleted_items[] = $items[$idx];
        unset($items[$idx]);
    }
    $items = array_values($items);
    save_list_to_db($items, $user_id);
    
    if ($tag_id != ERROR_TAG) {
        log_purchases_to_db($deleted_items, $tag_id, $user_id);
    }
    return $items;
} //END function delete_items

/**
 * Sorts Items in the shopping list when requested.
 * @return sorted array
 */
function sort_items($user_id) {
    $items = get_current_list($user_id);
    sort($items);
    return $items;
} //END function sort_items

/**
 * Clears all Items in the shopping list when requested, deletes the whole list.
 * @return empty array
 */
function clear_all_items($user_id) {
    $items = [];
    save_list_to_db($items, $user_id);
    return $items;
} //END function clear_all_items

/**
 * Retrieves the shopping list from the dBase based on $user_id.
 * @return array
 */
function get_current_list($user_id) {
    //1. Connect to dBase
    $db = Database::getDB();
    //2. Query the dBase
    $query = "SELECT lists.list
              FROM lists
              WHERE lists.user_id = :user_id
              AND lists.tag_id = :tag_id";
    try {
    //3. Prepare the Query
        $stmnt = $db->prepare($query);
    //4. Bind the values being queried for security
        $stmnt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmnt->bindValue(':tag_id', CURRENT_LIST_TAG, PDO::PARAM_INT);
    //5. Execute the Query
        $stmnt->execute();
    //6. Retrieve the Results
        $result = $stmnt->fetch();
    //7. Close the Connection
        $stmnt->closeCursor();
    //8. Convert the Results from JSON file to an array [ $items ]
        $items = json_decode($result['list']);
    //9. Return the Results    
        return $items;
    } catch (PDOException $e) {
    //10. Display an error if a connection could not be established
        display_db_error($e->getMessage());
        exit();
    }
} //END function get_current_list

/**
 * Takes user created shopping list and saves it to the dBase based on $items, $tag_id & $user_id.
 * @return JSON file
 */
function log_purchases_to_db($items, $tag_id, $user_id) {
    //1. Connect to dBase
    $db = Database::getDB();
    //2. Convert $items list to JSON file
    $list = json_encode($items);
    //3. Query the dBase
    $query = "INSERT INTO lists
              (completed, list, user_id, tag_id)
              VALUES
              (NOW(), :list, :user_id, :tag_id)";
    try {
    //4. Prepare the Query
        $stmnt = $db->prepare($query);
    //5. Bind the values being saved to dBase for security
        $stmnt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmnt->bindValue(':tag_id', $tag_id, PDO::PARAM_INT);
        $stmnt->bindValue(':list', $list, PDO::PARAM_STR);
    //6. Execute the Query
        $stmnt->execute();
    //7. Close the Connection
        $stmnt->closeCursor();
    } catch (PDOException $e) {
    //8. Display an error if a connection could not be established
        $error_message($e->getMessage());
        display_db_error($error_message);
    }
} //END function log_purchases_to_db

/**
 * Saves current user modified shopping list to the dBase based on $items & $user_id.
 * @return JSON file
 */
function save_list_to_db($items, $user_id) {
    //1. Connect to dBase
    $db = Database::getDB();
    //2. Convert $items list to JSON file
    $list = json_encode($items);
    //3. Query the dBase
    $query = "UPDATE lists
              SET list = :list
              WHERE user_id = :user_id
              AND tag_id = :tag_id";
    try {
    //4. Prepare the Query
        $stmnt = $db->prepare($query);
    //5. Bind the values being saved to dBase for security
        $stmnt->bindValue(':list', $list, PDO::PARAM_STR);
        $stmnt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmnt->bindValue(':tag_id', $tag_id, PDO::PARAM_INT);
    //6. Execute the Query & Check the Row Count
        $row_count = $stmnt->execute();
    //7. Close the Connection
        $stmnt->closeCursor();
    //8. Check the Row Count to see if something has been changed
        return $row_count;
    } catch (PDOException $e) {
    //9. Display an error if a connection could not be established
        $error_message($e->getMessage());
        display_db_error($error_message);
        exit();
    }
} //END function save_list_to_db

/**
 * Creates a new shopping list and saves it to the dBase via $user_id.
 * @return JSON file
 */
function create_new_list($user_id) {
    //1. Connect to dBase
    $db = Database::getDB();
    //2. Convert $items list to JSON file
    $list = json_encode(array());
    //3. Query the dBase
    $query = "INSERT INTO lists
              (list, user_id, tag_id)
              VALUES
              (:list, :user_id, :tag_id)";
    try {
    //4. Prepare the Query
        $stmnt = $db->prepare($query);
    //5. Bind the values being saved to dBase for security
        $stmnt->bindValue(':list', $list, PDO::PARAM_STR);
        $stmnt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmnt->bindValue(':tag_id', $tag_id, PDO::PARAM_INT);
    //6. Execute the Query
        $stmnt->execute();
    //7. Close the Connection
        $stmnt->closeCursor();
    } catch (PDOException $e) {
    //8. Display an error if a connection could not be established
        $error_message($e->getMessage());
        display_db_error($error_message);
        exit();
    }
} //END function save_list_to_db










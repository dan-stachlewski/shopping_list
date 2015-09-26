<?php include APP_DIR . "/view/header.php"; ?>

<div id="main">
    <!-- PART 2: The Shopping List -->
    <h2>Items to Buy</h2>
        <a href="index.php?action=sort">Re-Order</a> |
        <a href="index.php?action=clear">Clear All</a>
    <?php if (count($shopping_list) == 0) : ?>
        <p>There are no items in your shopping list!</p>
    <?php else : ?>
        <form action="." method="POST">
            <table>
                <?php foreach ($shopping_list as $id => $item) : ?>
                <tr>
                    <td><?= ($id + 1) . "."; ?></td>
                    <td><?= htmlentities($item, ENT_HTML5); ?></td>
                    <td><input type="checkbox" name="checkeditems[]" value="<?= $id; ?>"></td>
                <?php endforeach; ?>
                </tr>
                <tr>
                    <td colspan="2">Reason</td>
                    <td>
                        <select name="tag_id">
                            <?php foreach($tags as $tag) : ?>
                            <option value="<?= $tag['tag_id'];?>">
                                <?= $tag['tag_name']; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="action" value="delete_items">
            <input type="submit" name="btnDelete" value="Delete">
        </form>
        <?php endif; ?>
        <br />
        
       <!-- PART 3: The Add Form -->
       <h2>Add Item</h2>
       <form action="." method="POST">
           <label>Item:</label>
           <input type="text" name="newitem" id="newitem">
           <br />
           <input type="hidden" name="action" value="add_item">
           <input type="submit" name="btnAdd" value="Add Item">
       </form>
       <br/>
       <a href="index.php?action=print">Print List</a>
</div> <!-- END main -->

<?php include APP_DIR . "/view/footer.php"; ?>
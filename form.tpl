<form autocomplete="off" action="./" method="get" accept-charset="utf-8" class="mform">
    <input placeholder="Search" results=5 autosave=directory name="q" type="search" autofocus="1" value="<?php echo htmlspecialchars($formdata['q']);?>" id="id_term" />
    <input value="Search" type="submit" id="id_submitbutton" />
    <?php
    foreach($searchhandler->getnavigationfilter($searchoptions) as $k => $row) {
        echo sprintf('<input name="%s" value="%s" type="hidden">', $k, htmlspecialchars($row));
    }
    ?>
</form>

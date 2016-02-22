<form autocomplete="off" action="/local/directory/" method="get" accept-charset="utf-8" id="mform1" class="mform">
    <fieldset class="hidden"><div>
            <div id="fitem_id_term" class="fitem fitem_ftext ">
                <div class="felement ftext">
                    <input placeholder="Search" name="q" type="text" autofocus="1" value="<?php echo htmlspecialchars($formdata['q']);?>" id="id_term" />
                    <input value="Search" type="submit" id="id_submitbutton" />
                </div>
            </div>
        </div>
    </fieldset>
    <fieldset class="hidden"><div>
            <div id="fitem_id_submitbutton" class="fitem fitem_actionbuttons fitem_fsubmit">
                <div class="felement fsubmit">
                    <?php
                    foreach($searchhandler->getnavigationfilter($searchoptions) as $k => $row) {
                        echo sprintf('<input name="%s" value="%s" type="hidden">', $k, htmlspecialchars($row));
                    }
                    ?>
                    </div>
            </div>
        </div>
    </fieldset>
</form>
<?php
require_once('../../config.php');
require('config.php');

$configfieldsdisplay = array_flip(explode(',', get_config('local_directory', 'fields_display')));
$configfieldsdisplay = array_intersect_key($searchfieldsarray, $configfieldsdisplay);
if(count($configfieldsdisplay) == 0) {
    $configfieldsdisplay = $searchfieldsarray;
}

?>
<div class="directory">
    <img data-src="personAvatar" data-alt="firstname"/>
    <div class="directory-container">
        <?php if (in_array('firstname', $configfieldsdisplay)): ?>
        <div data-content="firstname"></div>
        <?php endif;?>
        <div data-content="lastname"></div>
        <div class="email" data-content="email"></div>
        <div data-content="skype"></div>
        <div data-content="phone1"></div>
        <div data-content="phone2"></div>
        <div data-content="description"></div>
        <div data-content="lastnamephonetic"></div>
        <div data-content="firstnamephonetic"></div>
        <div data-content="middlename"></div>
        <div data-content="alternatename"></div>
    </div>
</div>
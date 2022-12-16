<?php
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox(
        'block_prayertimes/showall',
        get_string('showall', 'block_prayertimes'),
        get_string('showalldesc', 'block_prayertimes'),
        0
    ));
}

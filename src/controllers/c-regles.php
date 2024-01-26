<?php

require_once('src/model.php');

function regles(){
    $menu['page'] = 'regles';
    require('view/inc/inc.head.php');
    require('view/v-regles.php');
    require('view/inc/inc.footer.php');
}

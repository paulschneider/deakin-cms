<?php

    $emogrifier = new \Pelago\Emogrifier();

    $emogrifier->disableInvisibleNodeRemoval();
    $emogrifier->setHtml($__env->yieldContent('html'));

    echo  $emogrifier->emogrify();

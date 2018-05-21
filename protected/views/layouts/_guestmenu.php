<?php
$this->widget ( 'zii.widgets.CMenu',
        array (
                'items' => array (
                        array (
                                'label' => __ ( 'Home' ),
                                'url' => array (
                                        '/site/index'
                                )
                        ),
                        array (
                                'label' => __ ( 'About' ),
                                'url' => array (
                                        '/site/page',
                                        'view' => 'about'
                                )
                        ),
                        array (
                                'label' => __ ( 'Data Report' ),
                                'url' => array (
                                        '/site/report'
                                )
                        ),
                        array (
                                'label' => __ ( 'Contact' ),
                                'url' => array (
                                        '/site/contact'
                                )
                        ),
                        [
                                'encodeLabel' => false,
                                'label' => "<i class='fa fa-github'></i>",
                                'url' => 'https://github.com/adoptrti/kono'
                        ]

                )
        ) );
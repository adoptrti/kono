<?php 
$this->widget ( 'zii.widgets.CMenu',array (
                    'items' => array (
                            array (
                                    'label' => __ ( 'States' ),
                                    'url' => array (
                                            '/state'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Committee' ),
                                    'url' => array (
                                            '/committee'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Committee Members' ),
                                    'url' => array (
                                            '/committeeMember'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Assembly Results' ),
                                    'url' => array (
                                            '/assemblyresults'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Officer' ),
                                    'url' => array (
                                            '/officer'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Elections' ),
                                    'url' => array (
                                            '/election'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Districts' ),
                                    'url' => array (
                                            '/localgov/district'
                                    )
                            ),
                            array (
                                    'label' => __ ( 'Data Report' ),
                                    'url' => array (
                                            '/site/report'
                                    )
                            ),                            
                            // array('label'=>'Login',
                            // 'url'=>array('/site/login'),
                            // 'visible'=>Yii::app()->user->isGuest),
                            array (
                                    'label' => __ ( 'Logout ({uname})',
                                            [
                                                    '{uname}' => Yii::app ()->user->name
                                            ] ),
                                    'url' => array (
                                            '/site/logout'
                                    ),
                                    'visible' => ! Yii::app ()->user->isGuest
                            )
                    )
            ));
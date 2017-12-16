<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     *
     * @var string the default layout for the controller view. Defaults to
     *      '//layouts/column1',
     *      meaning using a single column layout. See
     *      'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     *
     * @var array context menu items. This property will be assigned to {@link
     *      CMenu::items}.
     */
    public $menu = array ();
    /**
     *
     * @var array the breadcrumbs of the current page. The value of this
     *      property will
     *      be assigned to {@link CBreadcrumbs::links}. Please refer to {@link
     *      CBreadcrumbs::links}
     *      for more details on how to specify this property.
     */
    public $breadcrumbs = array ();

    public function disableWebLog()
    {
        foreach ( Yii::app ()->log->routes as $route )
        {
            if (get_class ( $route ) == 'CWebLogRoute')
                $route->enabled = false;
        }
    }

    public function init()
    {
        $this->initLanguage ();
        
        parent::init ();
    }

    /**
     * Inits language defaults depending on session, GET and config defaults
     * This method can overloaded by inheritence.
     * 
     * If the lang is not getting set properly here, you copuld also check the URL resolvers
     * 
     * 201604261540:vikas:#454:Gurgaon
     */
    public function initLanguage()
    {
        if (! empty ( $_GET ['lang'] ))
        {
            Yii::app ()->language = $_GET ['lang'];
        }
        else if (! empty ( Yii::app ()->session ['lang'] ))
        {
            $_GET ['lang'] = Yii::app ()->language = Yii::app ()->session ['lang'];
        }
        else if(!empty(Yii::app ()->params ['defaultLanguage']))
            $_GET ['lang'] = Yii::app ()->language = Yii::app ()->params ['defaultLanguage'];
        else 
        #201710051333:Kovai:thevikas:#25:incase default language not specified in config file, we hardcode it to english
            $_GET ['lang'] = Yii::app ()->language = 'en';
        
        if (empty ( Yii::app ()->session ['lang'] ) || Yii::app ()->session ['lang'] != Yii::app ()->language)
        {
            Yii::app ()->session ['lang'] = Yii::app ()->language;
        }
    }
}
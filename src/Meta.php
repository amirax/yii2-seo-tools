<?php

namespace Amirax\SeoTools;

use yii;
use yii\web\View;
use yii\base\Component;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use Amirax\SeoTools\models\SeoMeta;


/**
 * Amirax SEO Tools: Meta tags
 *
 * @author Max Voronov <v0id@list.ru>
 *
 * @link http://www.amirax.ru/
 * @link https://github.com/amirax/yii2-seo-tools
 * @license https://github.com/amirax/yii2-seo-tools/blob/master/LICENSE.md
 */
class Meta extends Component
{

    protected $_view = null;
    protected $_routeMetaData = [];
    protected $_paramsMetaData = [];
    protected $_defaultMetaData = [];
    protected $_userMetaData = [];
    protected $_variables = [];

    /**
     * Init component
     */
    public function init()
    {
        Yii::$app->view->on(View::EVENT_BEGIN_PAGE, [$this, '_applyMeta']);
    }


    /**
     * Set variables for autoreplace
     *
     * @param string|array $name
     * @param string $value
     * @return $this
     */
    public function setVar($name, $value = '')
    {
        if (!empty($name)) {
            if (is_array($name)) {
                foreach ($name AS $varName => $value) {
                    $this->_variables['%' . $varName . '%'] = $value;
                }
            } else {
                $this->_variables['%' . $name . '%'] = $value;
            }
        }
        return $this;
    }


    /**
     * Apply metatags to page
     *
     * @param $event
     */
    protected function _applyMeta($event)
    {
        $this->_view = $event->sender;
        $this->_getMetaData(Yii::$app->requestedRoute, Yii::$app->requestedParams);
        $data = ArrayHelper::merge(
            $this->_defaultMetaData,
            $this->_routeMetaData,
            $this->_paramsMetaData,
            $this->_userMetaData
        );
        $this->_prepareVars()
            ->_setTitle($data)
            ->_setMeta($data)
            ->_setRobots($data)
            ->_setTags($data);
    }


    /**
     * Init default variables for autoreplace
     *
     * @return $this
     */
    protected function _prepareVars()
    {
        $this->setVar([
            'HOME_URL' => Url::home(true),
            'CANONICAL_URL' => Url::canonical(),
            'LOCALE' => Yii::$app->formatter->locale
        ]);
        return $this;
    }


    /**
     * Set tag <title>
     *
     * @param array $data
     * @return $this
     */
    protected function _setTitle($data)
    {
        $data['title'] = str_replace(array_keys($this->_variables), $this->_variables, trim($data['title']));
        $this->setVar('SEO_TITLE', $data['title']);
        $this->_view->title = $data['title'];
        return $this;
    }


    /**
     * Set meta keywords and meta description tags
     *
     * @param array $data
     * @return $this
     */
    protected function _setMeta($data)
    {
        $data['metakeys'] = str_replace(array_keys($this->_variables), $this->_variables, trim($data['metakeys']));
        $data['metakeys'] = preg_replace('|,( )+|', ',', $data['metakeys']);
        $this->_view->registerMetaTag(['name' => 'keywords', 'content' => $data['metakeys']]);
        $this->setVar('SEO_METAKEYS', $data['metakeys']);

        $data['metadesc'] = str_replace(array_keys($this->_variables), $this->_variables, trim($data['metadesc']));
        $this->_view->registerMetaTag(['name' => 'description', 'content' => $data['metadesc']]);
        $this->setVar('SEO_METADESC', $data['metadesc']);

        return $this;
    }


    /**
     * Set meta robots tag
     *
     * @param array $data
     * @return $this
     */
    protected function _setRobots($data)
    {
        if ($data['robots'] > 0) {
            $robots = new Robots();
            if ($robots->idExists($data['robots'])) {
                $this->_view->registerMetaTag(['name' => 'robots', 'content' => $robots->getPropById($data['robots'])]);
            }
        }
        return $this;
    }


    /**
     * Set other meta tags
     * For example, OpenGraph tags
     *
     * @param array $data
     * @return $this
     */
    protected function _setTags($data)
    {
        $tags = ArrayHelper::merge(
            array_key_exists('tags', $this->_defaultMetaData) ? $this->_defaultMetaData['tags'] : [],
            $data['tags']
        );
        if (!empty($tags)) {
            foreach ($tags AS $tagName => $tagProp) {
                if (!empty($tagProp) && is_string($tagProp))
                    $tagProp = str_replace(array_keys($this->_variables), $this->_variables, $tagProp);
                $this->_view->registerMetaTag(['property' => $tagName, 'content' => $tagProp]);
            }
        }
        return $this;
    }


    /**
     * Get data from database
     *
     * @param string $route
     * @param array $params
     */
    protected function _getMetaData($route, $params = null)
    {
        $params = json_encode($params);
        $model = SeoMeta::find()
            ->where(['route' => '-'])
            ->orWhere(
                ['and', 'route=:route', ['or', 'params IS NULL', 'params=:params']],
                [':route' => $route, ':params' => $params]
            )->asArray()
            ->all();

        foreach ($model AS $item) {
            $item = array_filter($item, 'strlen');
            if (!empty($item['tags'])) $item['tags'] = (array)json_decode($item['tags']);
            if ($item['route'] == '-') $this->_defaultMetaData = $item;
            elseif ($item['route'] != '-' && empty($item['params'])) $this->_routeMetaData = $item;
            elseif ($item['route'] != '-' && !empty($item['params'])) $this->_paramsMetaData = $item;
        }
    }


    public function &__get($prop)
    {
        return $this->_userMetaData[$prop];
    }


    public function __set($prop, $value)
    {
        if (empty($prop)) return;
        $this->_userMetaData[$prop] = &$value;
    }

}
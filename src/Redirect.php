<?php
namespace Amirax\SeoTools;

use yii;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use Amirax\SeoTools\models\SeoRedirects;

/**
 * Amirax SEO Tools: Redirects
 *
 * @author Max Voronov <v0id@list.ru>
 *
 * @link http://www.amirax.ru/
 * @link https://github.com/amirax/yii2-seo-tools
 * @license https://github.com/amirax/yii2-seo-tools/blob/master/LICENSE.md
 */
class Redirect extends ErrorHandler {

    public function handleException($exception)
    {
        $redirectModel = SeoRedirects::find()
            ->where(['old_url' => Yii::$app->request->url])
            ->asArray()
            ->one();

        if(!empty($redirectModel)) {
            $redirectStatus = ($redirectModel['status'] == 302) ? 302 : 301;
            header("Location: " . Url::toRoute($redirectModel['new_url']), true, $redirectStatus);
            exit;
        }

        parent::handleException($exception);
    }

}
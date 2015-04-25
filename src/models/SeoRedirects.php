<?php
namespace Amirax\SeoTools\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "seo_redirects".
 *
 * @property integer $id
 * @property string $old_url
 * @property string $new_url
 * @property string $status
 */
class SeoRedirects extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo_redirects}}';
    }

}
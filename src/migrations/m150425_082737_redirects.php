<?php
use yii\db\Schema;
use yii\db\Migration;

class m150425_082737_redirects extends Migration
{

    public function up()
    {
        $this->createTable('{{%seo_redirects}}', [
            'id'      => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'old_url' => Schema::TYPE_STRING . '(255) NOT NULL',
            'new_url' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT "/"',
            'status'  => 'ENUM("301","302") NOT NULL DEFAULT "301"'
        ]);
        $this->createIndex('idx_old_url', '{{%seo_redirects}}', 'old_url', true);
    }


    public function down()
    {
        $this->dropTable('{{%seo_redirects}}');
    }

}
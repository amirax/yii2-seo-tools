<?php
use yii\db\Schema;
use yii\db\Migration;

class m150425_012013_init extends Migration
{

    public function up()
    {
        $this->createTable('{{%seo_meta}}', [
            'id'       => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'route'    => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'params'   => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'title'    => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'metakeys' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'metadesc' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'tags'     => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'robots'   => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0',
        ]);
        $this->createIndex('idx_route', '{{%seo_meta}}', 'route', true);
        $this->createIndex('idx_params', '{{%seo_meta}}', 'params', true);

        $this->insert('{{%seo_meta}}', [
            'route'    => '-',
            'title'    => 'Default page title by Amirax SEO tools',
            'metakeys' => 'yii2,extension,amirax,seo,tools',
            'metadesc' => 'Default meta description by Amirax SEO tools',
            'tags'     => json_encode(['og:type' => 'website', 'og:url' => '%CANONICAL_URL%']),
            'robots'   => 0
        ]);
    }


    public function down()
    {
        $this->dropTable('{{%seo_meta}}');
    }

}
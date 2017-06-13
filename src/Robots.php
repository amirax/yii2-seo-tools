<?php

namespace Amirax\SeoTools;

/**
 * Amirax SEO Tools: Robots
 *
 * @author Max Voronov <v0id@list.ru>
 *
 * @link http://www.amirax.ru/
 * @link https://github.com/amirax/yii2-seo-tools
 * @license https://github.com/amirax/yii2-seo-tools/blob/master/LICENSE.md
 */
class Robots
{

    const ROBOTS_INDEX_FOLLOW = 0;
    const ROBOTS_NOINDEX_NOFOLLOW = 1;
    const ROBOTS_ONLY_NOINDEX = 2;
    const ROBOTS_ONLY_NOFOLLOW = 3;

    protected $_robotsAvailableValues = [
        'index, follow',
        'noindex, nofollow',
        'noindex, follow',
        'index, nofollow'
    ];

    /**
     * Checking for exist value by ID
     *
     * @param integer $id
     * @return bool
     */
    public function idExists($id)
    {
        return (array_key_exists($id, $this->_robotsAvailableValues));
    }


    /**
     * Return value for meta tag by ID
     *
     * @param integer $id
     * @return bool
     */
    public function getPropById($id)
    {
        if (!$this->idExists($id)) return false;
        return $this->_robotsAvailableValues[$id];
    }


    /**
     * Generate content for robots.txt
     */
    public function generateRobotsTxt()
    {
        // ToDo
    }

}
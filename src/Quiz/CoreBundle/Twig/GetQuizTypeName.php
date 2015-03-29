<?php
/**
 * Created by PhpStorm.
 * User: Giannis
 * Date: 28/3/2015
 * Time: 10:53 μμ
 */

class GetQuizTypeName
{


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return "GetQuizTypeName";
    }

    public function nameFilter($type) {
        return strtoupper($type);
    }

    public function getFilters() {
        return array (new \Twig_SimpleFilter('name', array($this, 'nameFilter')));
    }
}
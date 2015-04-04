<?php
/**
 * Created by PhpStorm.
 * User: Giannis
 * Date: 28/3/2015
 * Time: 10:53 μμ
 */

namespace Quiz\CoreBundle\Twig;

class GetQuizTypeName extends \Twig_Extension
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
        switch($type) {
            case 'multiple':
                return 'πολλαπλής επιλογής';
            case 'match':
                return 'αντιστοίχισης';
            case 'truefalse':
                return 'σωστού λάθους';
            case 'essay':
                return 'ανάπτυξης';
        }
    }

    public function getFilters() {
        return array (new \Twig_SimpleFilter('name', array($this, 'nameFilter')));
    }
}
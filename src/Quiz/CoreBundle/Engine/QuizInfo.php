<?php
/**
 * Created by PhpStorm.
 * User: Giannis
 * Date: 27/3/2015
 * Time: 2:20 μμ
 */

namespace Quiz\CoreBundle\Engine;

class QuizInfo {

    private static  $quizes = [];



    public static function addToQuizes( $quiz) {
       array_push(self::$quizes, $quiz);
    }

    public static function getQuizes() {
        return self::$quizes;
    }
}
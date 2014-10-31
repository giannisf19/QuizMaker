<?php
/**
 * Created by PhpStorm.
 * User: gfoulidis
 * Date: 31/10/2014
 * Time: 3:47 μμ
 */

namespace Quiz\CoreBundle\Engine\QuizEngine;

  class  QuizTest {

      private $data = "sdf";

      function __construct() {
          $this->data = "Set from constructor";
      }

    public  function TellMeSomething() {
        return $this->data;
    }
} 
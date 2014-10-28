<?php


namespace Acme\DemoBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/***
 * @ORM\Entity
 * @ORM\Table(name="Question")
 */
class Question {

    /***
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="auto")
     */
    protected $id;
} 
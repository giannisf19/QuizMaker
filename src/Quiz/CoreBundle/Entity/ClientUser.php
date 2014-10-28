<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ClientUser")
 * @ORM\Entity
 */

class ClientUser {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /***
     * @ORM\Column(type="string", length=100)
     */
    protected $name;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}

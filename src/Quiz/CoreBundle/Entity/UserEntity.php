<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users_table")
 * @ORM\Entity
 */

class UserEntity extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $firstName;


    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $registryNumber;


    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $semester;


    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $lastName;


    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Department", inversedBy="users")
     */
    protected $department;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    public function __construct() {
        parent::__construct();
        $this->setRoles(["ROLE_STUDENT"]);
    }



    /**
     * Set name
     *
     * @param string $name
     * @return UserEntity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     * @return UserEntity
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return UserEntity
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set department
     *
     * @param \Quiz\CoreBundle\Entity\Department $department
     * @return UserEntity
     */
    public function setDepartment(\Quiz\CoreBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Quiz\CoreBundle\Entity\Department 
     */
    public function getDepartment()
    {
        return $this->department;
    }

    public function __toString() {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Set registryNumber
     *
     * @param string $registryNumber
     * @return UserEntity
     */
    public function setRegistryNumber($registryNumber)
    {
        $this->registryNumber = $registryNumber;

        return $this;
    }

    /**
     * Get registryNumber
     *
     * @return string 
     */
    public function getRegistryNumber()
    {
        return $this->registryNumber;
    }

    /**
     * Set semester
     *
     * @param string $semester
     * @return UserEntity
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester
     *
     * @return string 
     */
    public function getSemester()
    {
        return $this->semester;
    }
}

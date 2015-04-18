<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * TestResult
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Quiz\CoreBundle\Entity\TestResultRepository")
 */
class TestResult
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\UserEntity")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */

    private $user;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Quiz", inversedBy="TestResults")
     */
    private $quiz;


    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Result")
     * @ORM\JoinTable(name="test_results",
     * joinColumns={@ORM\JoinColumn(name="test_result_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="result_id", referencedColumnName="id", unique=true)})
     */
    protected $results;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed;

    /**
     * @var string
     *
     * @ORM\Column(name="degree", type="string", length=255)
     */
    private $degree;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quiz
     *
     * @param \stdClass $quiz
     * @return TestResult
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \stdClass 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }



    /**
     * Set date
     *
     * @param \DateTime $date
     * @return TestResult
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return TestResult
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set degree
     *
     * @param string $degree
     * @return TestResult
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return string 
     */
    public function getDegree()
    {
        return $this->degree;
    }


    public function __construct() {
        $this->results = new ArrayCollection();
    }

    /**
     * Add results
     *
     * @param \Quiz\CoreBundle\Entity\Result $results
     * @return TestResult
     */
    public function addResult(\Quiz\CoreBundle\Entity\Result $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \Quiz\CoreBundle\Entity\Result $results
     */
    public function removeResult(\Quiz\CoreBundle\Entity\Result $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set user
     *
     * @param \Quiz\CoreBundle\Entity\UserEntity $user
     * @return TestResult
     */
    public function setUser(UserEntity $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Quiz\CoreBundle\Entity\UserEntity 
     */
    public function getUser()
    {
        return $this->user;
    }
}

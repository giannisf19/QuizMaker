<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation\Exclude;

/**
 * Quiz
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Quiz\CoreBundle\Entity\QuizRepository")
 */
class Quiz
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
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="Private", type="boolean")
     */

    private $isPrivate;


    /**
     * @ORM\Column(name="Disabled", type="boolean")
     */

    private $isDisabled;


    /**
     * @ORM\Column(name="Time", type="integer")
     */

    private $time;


    /**
     * @ORM\OneToMany(targetEntity="Quiz\CoreBundle\Entity\TestResult", mappedBy="quiz", cascade={"persist"})
     * @Exclude()
     */

    private $TestResults;

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
     * Set name
     *
     * @param string $name
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Question", inversedBy="quizes", cascade={"persist"})
     */
    protected $questions;


    public function __construct() {
        $this->questions = new ArrayCollection();
        $this->TestResults = new ArrayCollection();
        $this->isPrivate = true;
        $this->isDisabled = false;
        $this->time = 60;
    }

    /**
     * Add questions
     *
     * @param \Quiz\CoreBundle\Entity\Question $questions
     * @return Quiz
     */
    public function addQuestion(\Quiz\CoreBundle\Entity\Question $question)
    {
        $this->questions->add($question);
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Quiz\CoreBundle\Entity\Question $questions
     */
    public function removeQuestion(\Quiz\CoreBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set isPrivate
     *
     * @param boolean $isPrivate
     * @return Quiz
     */
    public function setIsPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    /**
     * Get isPrivate
     *
     * @return boolean 
     */
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }

    /**
     * Set isDisabled
     *
     * @param boolean $isDisabled
     * @return Quiz
     */
    public function setIsDisabled($isDisabled)
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    /**
     * Get isDisabled
     *
     * @return boolean 
     */
    public function getIsDisabled()
    {
        return $this->isDisabled;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Quiz
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Add TestResults
     *
     * @param \Quiz\CoreBundle\Entity\TestResult $testResults
     * @return Quiz
     */
    public function addTestResult(\Quiz\CoreBundle\Entity\TestResult $testResults)
    {
        $this->TestResults[] = $testResults;

        return $this;
    }

    /**
     * Remove TestResults
     *
     * @param \Quiz\CoreBundle\Entity\TestResult $testResults
     */
    public function removeTestResult(\Quiz\CoreBundle\Entity\TestResult $testResults)
    {
        $this->TestResults->removeElement($testResults);
    }

    /**
     * Get TestResults
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTestResults()
    {
        return $this->TestResults;
    }
}

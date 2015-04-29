<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;


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
     * @Groups({"public", "admin"})
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\UserEntity", inversedBy="quizes")
     * @Groups({"admin"})
     * @Type("Quiz\CoreBundle\Entity\UserEntity")
     * @Exclude()
     *
     */

    protected $owners;


    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255)
     * @Groups({"public", "admin"})
     */
    protected $name;

    /**
     * @ORM\Column(name="Private", type="boolean")
     * @Groups({"public", "admin"})
     */

    protected $isPrivate;


    /**
     * @ORM\Column(name="Disabled", type="boolean")
     * @Groups({"public", "admin"})
     */

    protected $isDisabled;


    /**
     * @ORM\Column(name="quizTime", type="integer")
     * @Groups({"public", "admin"})
     */

    protected $time;




    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Question", mappedBy="quizes", cascade={"persist", "remove", "merge"})
     * @Groups({"public", "admin"})
     */
    protected $questions;





    /**
     * @ORM\OneToMany(targetEntity="Quiz\CoreBundle\Entity\TestResult", mappedBy="quiz", cascade={"all"})
     * @Exclude()
     */

    protected $TestResults;

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
        $question->addQuiz($this);
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
     * @return ArrayCollection
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
     * @param ArrayCollection $questions
     * @return Quiz
     */

    public function setQuestions( $questions ) {
        $this->questions = $questions;
        return $this;
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

    /**
     * Add owners
     *
     * @param \Quiz\CoreBundle\Entity\UserEntity $owners
     * @return Quiz
     */
    public function addOwner(\Quiz\CoreBundle\Entity\UserEntity $owners)
    {
        $this->owners[] = $owners;
        return $this;
    }

    /**
     * Remove owners
     *
     * @param \Quiz\CoreBundle\Entity\UserEntity $owners
     */
    public function removeOwner(\Quiz\CoreBundle\Entity\UserEntity $owners)
    {
        $this->owners->removeElement($owners);
    }

    /**
     * Get owners
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * Set quizRunTime
     *
     * @param integer $quizRunTime
     *
     * @return Quiz
     */
    public function setQuizRunTime($quizRunTime)
    {
        $this->quizRunTime = $quizRunTime;

        return $this;
    }

    /**
     * Get quizRunTime
     *
     * @return integer
     */
    public function getQuizRunTime()
    {
        return $this->quizRunTime;
    }

    /**
     * Set owners
     *
     * @param \Quiz\CoreBundle\Entity\UserEntity $owners
     *
     * @return Quiz
     */
    public function setOwners(\Quiz\CoreBundle\Entity\UserEntity $owners = null)
    {
        $this->owners = $owners;

        return $this;
    }



}

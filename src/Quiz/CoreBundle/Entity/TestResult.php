<?php

namespace Quiz\CoreBundle\Entity;

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
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Quiz", inversedBy="TestResults")
     */
    private $quiz;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="question", type="object")
     */
    private $question;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="answer", type="object")
     */
    private $answer;

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
     * Set question
     *
     * @param \stdClass $question
     * @return TestResult
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \stdClass 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param \stdClass $answer
     * @return TestResult
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \stdClass 
     */
    public function getAnswer()
    {
        return $this->answer;
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
}

<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;

/**
 * Result
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Result
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"admin", "public", "results"})
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Question")
     * @Groups({"admin", "public", "results"})
     */
    private $question;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Answer")
     * @Groups({"admin", "public", "results"})
     */
    private $answer;


    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\TestResult", inversedBy="results")
     * @Groups({"admin", "public", "results"})
     */

    protected $testResult;



    /**
     * @ORM\Column(type="boolean")
     * @Groups({"admin", "public", "results"})
     */

    protected $isCorrect;


    /**
     * @ORM\Column(type="decimal")
     * @Groups({"admin", "public", "results"})
     */

    protected $degree;

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
     * Set question
     *
     * @param string $question
     * @return Result
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     * @return Result
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set isCorrect
     *
     * @param boolean $isCorrect
     *
     * @return Result
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * Get isCorrect
     *
     * @return boolean
     */
    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    /**
     * Set degree
     *
     * @param string $degree
     *
     * @return Result
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

    /**
     * Set testResult
     *
     * @param \Quiz\CoreBundle\Entity\TestResult $testResult
     *
     * @return Result
     */
    public function setTestResult(\Quiz\CoreBundle\Entity\TestResult $testResult = null)
    {
        $this->testResult = $testResult;

        return $this;
    }

    /**
     * Get testResult
     *
     * @return \Quiz\CoreBundle\Entity\TestResult
     */
    public function getTestResult()
    {
        return $this->testResult;
    }
}

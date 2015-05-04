<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;


/**
 * Answer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Quiz\CoreBundle\Entity\AnswerRepository")
 */
class Answer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"public", "admin", "results"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="AnswerText", type="string", length=255)
     * @Groups({"public", "admin", "results"})
     */
    protected $answerText;


    /**
     * @ORM\Column(name="feedback", type="string", length=100000)
     * @Groups({"admin", "results"})
     */

    protected $feedback;


    /**
     * @ORM\Column(name="Correct", type="boolean")
     * @Groups({"admin", "results"})
     */
    protected $isCorrect;



    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
     * @Groups({"public", "admin", "results"})
     */
    protected $question;


    /**
     * @ORM\Column(type="string")
     * @Groups({"public", "admin", "results"})
     */
    protected $leftOrRight;

    /**
     *
     * @ORM\OneToOne(targetEntity="Quiz\CoreBundle\Entity\Answer", cascade={"persist"})
     * @Groups({"public", "admin", "results"})
     */
    protected $answer;


    public function __construct() {
        $this->isCorrect = false;

    }



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
     * @param Question $question
     * @return Answer
     */
    public function setQuestion( $question )
    {
        $this->question = $question;
        $question->addAnswer($this);
        return $this;
    }


    /**
     * Get question
     *
     * @return \Quiz\CoreBundle\Entity\Answer 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answerText
     *
     * @param string $answerText
     * @return Answer
     */
    public function setAnswerText($answerText)
    {
        $this->answerText = $answerText;

        return $this;
    }

    /**
     * Get answerText
     *
     * @return string 
     */
    public function getAnswerText()
    {
        return $this->answerText;
    }

    /**
     * Set isCorrect
     *
     * @param boolean $isCorrect
     * @return Answer
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
     * Set leftOrRight
     *
     * @param string $leftOrRight
     * @return Answer
     */
    public function setLeftOrRight($leftOrRight)
    {
        $this->leftOrRight = $leftOrRight;

        return $this;
    }

    /**
     * Get leftOrRight
     *
     * @return string 
     */
    public function getLeftOrRight()
    {
        return $this->leftOrRight;
    }




    /**
     * Set answer
     *
     * @param \Quiz\CoreBundle\Entity\Answer $answer
     * @return Answer
     */
    public function setAnswer(\Quiz\CoreBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Quiz\CoreBundle\Entity\Answer 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Add question
     *
     * @param Question $question
     *
     * @return Answer
     */
    public function addQuestion(Question $question)
    {
        $this->question[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param Question $question
     * @return $this
     */
    public function removeQuestion(Question $question)
    {
        $this->question = null;
        return $this;
    }

    /**
     * Set feedback
     *
     * @param string $feedback
     *
     * @return Answer
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * Get feedback
     *
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}

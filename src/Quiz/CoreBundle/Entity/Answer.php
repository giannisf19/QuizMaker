<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="AnswerText", type="string", length=255)
     */
    private $answerText;


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
     * Set questionText
     *
     * @param string $questionText
     * @return Answer
     */
    public function setQuestionText($questionText)
    {
        $this->questionText = $questionText;

        return $this;
    }

    /**
     * Get questionText
     *
     * @return string 
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question;

    /**
     * Set question
     *
     * @param \Quiz\CoreBundle\Entity\Question $question
     * @return Answer
     */
    public function setQuestion(\Quiz\CoreBundle\Entity\Question $question = null)
    {
        $this->question = $question;

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
}

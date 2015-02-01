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
     * @ORM\Column(name="Correct", type="boolean")
     */
    private $isCorrect;



    /**
     * @ORM\ManyToOne(targetEntity="Quiz\CoreBundle\Entity\Question", inversedBy="answers")
     */
    private $question;






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
     * @param \Quiz\CoreBundle\Entity\Question $question
     * @return Answer
     */
    public function setQuestion( $question )
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
}

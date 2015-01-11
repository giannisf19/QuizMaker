<?php


namespace Quiz\CoreBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="Question")
 */
class Question {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="string")
     */
    protected $questionText;

   /**
    * @ORM\OneToMany(targetEntity="Quiz\CoreBundle\Entity\Answer", mappedBy="question")
    */
    protected $answers;


    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Quiz", inversedBy="questions")
     */
    protected $quizes;

    public function __construct() {
        $this->answers = new ArrayCollection();
        $this->quizes = new ArrayCollection();
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
     * Set questionText
     *
     * @param string $questionText
     * @return Question
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
     * Add answers
     *
     * @param \Quiz\CoreBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\Quiz\CoreBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Quiz\CoreBundle\Entity\Answer $answers
     */
    public function removeAnswer(\Quiz\CoreBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Add quizes
     *
     * @param \Quiz\CoreBundle\Entity\Quiz $quizes
     * @return Question
     */
    public function addQuiz(\Quiz\CoreBundle\Entity\Quiz $quizes)
    {
        $this->quizes[] = $quizes;
        return $this;
    }

    /**
     * Remove quizes
     *
     * @param \Quiz\CoreBundle\Entity\Question $quizes
     */
    public function removeQuize(\Quiz\CoreBundle\Entity\Question $quizes)
    {
        $this->quizes->removeElement($quizes);
    }

    /**
     * Get quizes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuizes()
    {
        return $this->quizes;
    }

    /**
     * Add quizes
     *
     * @param \Quiz\CoreBundle\Entity\Quiz $quizes
     * @return Question
     */
    public function addQuize(\Quiz\CoreBundle\Entity\Quiz $quizes)
    {
        $this->quizes[] = $quizes;

        return $this;
    }
}

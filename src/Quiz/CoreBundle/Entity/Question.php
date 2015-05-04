<?php


namespace Quiz\CoreBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity
 * @ORM\Table(name="Question")
 */
class Question {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"public", "admin", "results"})
     */
    protected $id;


    /**
     * @ORM\Column(name="question_order",type="integer")
     * @Groups({"public", "admin", "results"})
     */

    protected $order;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Groups({"public", "admin", "results"})
     */

    protected $type;








    /**
     * @ORM\Column(type="decimal")
     * @Groups({"public", "admin", "results"})
     */

    protected $wrongAnswerGrade;


    /**
     * @ORM\Column(type="decimal")
     * @Groups({"public", "admin", "results"})
     */
    protected $correctAnswerGrade;










    /**
     * @ORM\Column(type="string")
     * @Groups({"public", "admin", "results"})
     */
    protected $questionText;




    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\MediaElement", mappedBy="questions", cascade={"persist", "remove", "merge"})
     * @Groups({"public", "admin"})
     */
    protected $mediaElements;



   /**
    * @ORM\OneToMany(targetEntity="Quiz\CoreBundle\Entity\Answer", mappedBy="question", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
    * @Groups({"public", "admin", "results"})
    */
    protected $answers;


    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Quiz", inversedBy="questions")
     * @Exclude()
     */
    protected $quizes;

    public function __construct() {
        $this->answers = new ArrayCollection();
        $this->quizes = new ArrayCollection();
        $this->mediaElements = new ArrayCollection();

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
     * @param Answer $answer
     * @return Question
     */
    public function addAnswer(Answer $answer)
    {
        $this->answers->add($answer);
        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Quiz\CoreBundle\Entity\Answer $answers
     */
    public function removeAnswer( $answers)
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
        $this->quizes->add($quizes);
        return $this;
    }

    /**
     * Remove quizes
     *
     * @param \Quiz\CoreBundle\Entity\Quiz $quizes
     */
    public function removeQuize(Quiz $quizes)
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
        $this->quizes->add($quizes);
        return $this;
    }

   public function __toString() {
       return $this->questionText;
   }

    /**
     * Set type
     *
     * @param string $type
     * @return Question
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Question
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }



    /**
     * Set answers
     *
     * @param ArrayCollection $answers
     *
     * @return Question
     */
    public function setAnswers(ArrayCollection $answers = null)
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * Set quiz
     *
     * @param \Quiz\CoreBundle\Entity\Quiz $quiz
     *
     * @return Question
     */
    public function setQuiz(\Quiz\CoreBundle\Entity\Quiz $quiz = null)
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * Get quiz
     *
     * @return \Quiz\CoreBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Add mediaElement
     *
     * @param \Quiz\CoreBundle\Entity\MediaElement $mediaElement
     *
     * @return Question
     */
    public function addMediaElement(\Quiz\CoreBundle\Entity\MediaElement $mediaElement)
    {
        $this->mediaElements[] = $mediaElement;
        $mediaElement->addQuestion($this);
        return $this;
    }

    /**
     * Remove mediaElement
     *
     * @param \Quiz\CoreBundle\Entity\MediaElement $mediaElement
     */
    public function removeMediaElement(\Quiz\CoreBundle\Entity\MediaElement $mediaElement)
    {
        $this->mediaElements->removeElement($mediaElement);
    }

    /**
     * Get mediaElements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMediaElements()
    {
        return $this->mediaElements;
    }

    /**
     * Set wrongAnswerGrade
     *
     * @param string $wrongAnswerGrade
     *
     * @return Question
     */
    public function setWrongAnswerGrade($wrongAnswerGrade)
    {
        $this->wrongAnswerGrade = $wrongAnswerGrade;

        return $this;
    }

    /**
     * Get wrongAnswerGrade
     *
     * @return string
     */
    public function getWrongAnswerGrade()
    {
        return $this->wrongAnswerGrade;
    }

    /**
     * Set correctAnswerGrade
     *
     * @param string $correctAnswerGrade
     *
     * @return Question
     */
    public function setCorrectAnswerGrade($correctAnswerGrade)
    {
        $this->correctAnswerGrade = $correctAnswerGrade;

        return $this;
    }

    /**
     * Get correctAnswerGrade
     *
     * @return float
     */
    public function getCorrectAnswerGrade()
    {
        return $this->correctAnswerGrade;
    }
}

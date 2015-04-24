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
     * @Groups({"public", "admin"})
     */
    protected $id;


    /**
     * @ORM\Column(type="integer")
     * @Groups({"public", "admin"})
     */

    protected $order;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Groups({"public", "admin"})
     */

    protected $type;

    /**
     * @ORM\Column(type="string")
     * @Groups({"public", "admin"})
     */
    protected $questionText;

   /**
    * @ORM\OneToMany(targetEntity="Quiz\CoreBundle\Entity\Answer", mappedBy="question", cascade={"persist"})
    * @Groups({"public", "admin"})
    */
    private $answers;


    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Quiz", mappedBy="questions" )
     * @Exclude()
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
    public function addAnswer(\Quiz\CoreBundle\Entity\Answer $answer)
    {
        $this->answers->add($answer);
        $answer->setQuestion($this);

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
        $this->quizes->add($quizes);
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
}

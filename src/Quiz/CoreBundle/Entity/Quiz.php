<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Question", mappedBy="quizes")
     */
    protected $questions;

    public function __construct() {
        $this->questions = new ArrayCollection();
    }

    /**
     * Add questions
     *
     * @param \Quiz\CoreBundle\Entity\Question $questions
     * @return Quiz
     */
    public function addQuestion(\Quiz\CoreBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

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
}

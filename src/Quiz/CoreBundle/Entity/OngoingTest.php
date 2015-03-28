<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OngoingTest
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class OngoingTest
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
     * @var integer
     *
     * @ORM\Column(name="QuizId", type="integer")
     */
    private $quizId;

    /**
     * @var integer
     *
     * @ORM\Column(name="UserId", type="integer")
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="StartTime", type="datetime")
     */
    private $startTime;


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
     * Set quizId
     *
     * @param integer $quizId
     * @return OngoingTest
     */
    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;

        return $this;
    }

    /**
     * Get quizId
     *
     * @return integer 
     */
    public function getQuizId()
    {
        return $this->quizId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return OngoingTest
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return OngoingTest
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
}

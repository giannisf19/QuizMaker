<?php

namespace Quiz\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;

/**
 * MediaElement
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MediaElement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"public", "admin"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Groups({"public", "admin"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Groups({"public", "admin"})
     */
    private $mediaType;



    /**
     * @var string
     *
     * @ORM\Column(name="src", type="string", length=1000000)
     * @Groups({"public", "admin"})
     */
    private $src;

    /**
     * @ORM\ManyToMany(targetEntity="Quiz\CoreBundle\Entity\Question", inversedBy="mediaElements")
     * @Exclude()
     */
    protected $questions;

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
     *
     * @return MediaElement
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
     * Set type
     *
     * @param string $type
     *
     * @return MediaElement
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
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add question
     *
     * @param \Quiz\CoreBundle\Entity\Question $question
     *
     * @return MediaElement
     */
    public function addQuestion(\Quiz\CoreBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \Quiz\CoreBundle\Entity\Question $question
     */
    public function removeQuestion(\Quiz\CoreBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
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

    /**
     * Set mediaType
     *
     * @param string $mediaType
     *
     * @return MediaElement
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    /**
     * Get mediaType
     *
     * @return string
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Set src
     *
     * @param string $src
     *
     * @return MediaElement
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }
}

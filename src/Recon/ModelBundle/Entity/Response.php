<?php

namespace Recon\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Recon\ModelBundle\Repository\ResponseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Response
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
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @ORM\ManyToOne(targetEntity="Project", cascade={"all"}, fetch="LAZY")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="Answer", cascade={"all"}, fetch="LAZY")
     */
    private $answer;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setDateAdded(new \DateTime('now'));
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
     * Set value
     *
     * @param string $value
     * @return Response
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Response
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }


    /**
     * Set project
     *
     * @param \Recon\ModelBundle\Entity\Project $project
     * @return Response
     */
    public function setProject(\Recon\ModelBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Recon\ModelBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set answer
     *
     * @param \Recon\ModelBundle\Entity\Answer $answer
     * @return Response
     */
    public function setAnswer(\Recon\ModelBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Recon\ModelBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}

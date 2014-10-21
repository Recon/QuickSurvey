<?php

namespace Recon\ModelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Recon\ModelBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var type Recon\ModelBundle\Entity\Answer
     *
     * @OneToMany(targetEntity="Answer", mappedBy="question", cascade={"persist"}, orphanRemoval=true)
     */
    private $answers;

    /**
     * @var Project
     *
     * @ManyToMany(targetEntity="Project", mappedBy="questions", cascade={"persist"})
     */
    private $projects;

    /**
     * @ORM\Column(name="position", type="integer", unique=true)
     */
    private $position;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param string $text
     * @return Question
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Question
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add answers
     *
     * @param \Recon\ModelBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\Recon\ModelBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \Recon\ModelBundle\Entity\Answer $answers
     */
    public function removeAnswer(\Recon\ModelBundle\Entity\Answer $answers)
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
     * Add projects
     *
     * @param \Recon\ModelBundle\Entity\Project $projects
     * @return Question
     */
    public function addProject(\Recon\ModelBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Recon\ModelBundle\Entity\Project $projects
     */
    public function removeProject(\Recon\ModelBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function hasOnlyRadios()
    {
        foreach ($this->getAnswers() As $answer) {
            if ($answer->getType() !== 'RADIO') {
                return false;
            }
        }

        return true;
    }

}

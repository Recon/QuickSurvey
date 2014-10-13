<?php

namespace Recon\ModelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Recon\ModelBundle\Repository\ProjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_url", type="string", length=511, nullable=true)
     */
    private $referenceUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=255, nullable=true)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="client_email", type="string", length=255, nullable=true)
     */
    private $clientEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", unique=true, length=13)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="is_completed", type="boolean")
     */
    private $isCompleted = false;

    /**
     * @var Question
     * @ManyToMany(targetEntity="Question", inversedBy="projects", cascade={"persist"})
     * @JoinTable(name="ProjectQuestions",
     *      joinColumns={@JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="question_id", referencedColumnName="id")}
     * )
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="Response", mappedBy="project", cascade={"persist"}, orphanRemoval=true)
     */
    private $responses;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @ORM\PrePersist
     */
    public function onInsert()
    {
        if (!$this->getSlug()) {
            $this->setSlug(uniqid());
        }
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
     * Set name
     *
     * @param string $name
     * @return Project
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
     * Set referenceUrl
     *
     * @param string $referenceUrl
     * @return Project
     */
    public function setReferenceUrl($referenceUrl)
    {
        $this->referenceUrl = $referenceUrl;

        return $this;
    }

    /**
     * Get referenceUrl
     *
     * @return string
     */
    public function getReferenceUrl()
    {
        return $this->referenceUrl;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     * @return Project
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientEmail
     *
     * @param string $clientEmail
     * @return Project
     */
    public function setClientEmail($clientEmail)
    {
        $this->clientEmail = $clientEmail;

        return $this;
    }

    /**
     * Get clientEmail
     *
     * @return string
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * Add questions
     *
     * @param Question $questions
     * @return Project
     */
    public function addQuestion(Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param Question $questions
     */
    public function removeQuestion(Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add responses
     *
     * @param \Recon\ModelBundle\Entity\Response $response
     * @return Project
     */
    public function addResponse(\Recon\ModelBundle\Entity\Response $response)
    {
        foreach ($this->responses As $existingResponse) {
            if ($existingResponse->getAnswer()->getId() == $response->getAnswer()->getId()) {
                return $this;
            }
        }

        $this->responses[] = $response;

        return $this;
    }

    /**
     * Remove responses
     *
     * @param \Recon\ModelBundle\Entity\Response $responses
     */
    public function removeResponse(\Recon\ModelBundle\Entity\Response $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponses()
    {
        return $this->responses;
    }


    /**
     * Set isCompleted
     *
     * @param boolean $isCompleted
     * @return Project
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * Get isCompleted
     *
     * @return boolean 
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }
}

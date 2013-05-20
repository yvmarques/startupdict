<?php

namespace Infuse\DictionaryBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Word
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Infuse\DictionaryBundle\Entity\WordRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Word
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
     * @Assert\NotBlank()
     * @ORM\Column(name="word", type="string", length=255)
     */
    private $word;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"word"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="definition", type="text")
     */
    private $definition;

    /**
     * @var  string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="example", type="text")
     */
    private $example;


    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var  boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", cascade="persist", inversedBy="words")
     */
    private $tags;


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
     * Set word
     *
     * @param string $word
     * @return Word
     */
    public function setWord($word)
    {
        $this->word = $word;
    
        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Word
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
     * Set definition
     *
     * @param string $definition
     * @return Word
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    
        return $this;
    }

    /**
     * Get definition
     *
     * @return string 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set example
     *
     * @param string $example
     * @return Word
     */
    public function setExample($example)
    {
        $this->example = $example;
    
        return $this;
    }

    /**
     * Get example
     *
     * @return string 
     */
    public function getExample()
    {
        return $this->example;
    }

    /**
     * Set created
     *
     * @param string $created
     * @return Word
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->created = new \DateTime();
    }

    /**
     * Get created
     *
     * @return string 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set status
     *
     * @return Word
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setStatusValue() {
        $this->status = 0;
    }

    /**
     * Get status
     *
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tags
     *
     * @param \Infuse\DictionaryBundle\Entity\Tag $tags
     * @return Word
     */
    public function addTag(\Infuse\DictionaryBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Infuse\DictionaryBundle\Entity\Tag $tags
     */
    public function removeTag(\Infuse\DictionaryBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}

<?php

namespace App\Entity;

use App\Repository\PostRepository;
use App\Entity\Comment;
use App\Entity\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @UniqueEntity("slug")
 * @ORM\Table()
 *
 * @Serializer\ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_post_show",
 *          parameters = { "slug" = "expr(object.getSlug())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"default"})
 * )
 * @Hateoas\Relation(
 *      "modify",
 *      href = @Hateoas\Route(
 *          "api_post_update",
 *          parameters = { "slug" = "expr(object.getSlug())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"default"})
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "api_post_delete",
 *          parameters = { "slug" = "expr(object.getSlug())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"default"})
 * )
 *
 */
class Post
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * Hook blameable behavior
     * updates createdBy, updatedBy fields
     */
    use BlameableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"post_id"})
     */
    private $id;

    /**
     * @ORM\Column(length=128)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"default", "post_input"})
     *
     * @Assert\NotBlank()
     */
    private $title;
    
    /**
     * @ORM\Column(length=156, unique=true)
     * 
     * @Gedmo\Slug(fields={"title", "createdAt"})
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"default", "post_identifier"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"default", "post_input"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"default", "post_input"})
     */
    private $body;

    /**
     * Undocumented variable
     *
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     *
     * @Serializer\Expose
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"default"})
     */
    private $comments;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return ''.$this->getId();
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of body
     *
     * @return  string
     */ 
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set the value of body
     *
     * @param  string  $body
     *
     * @return  self
     */ 
    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  Comment[]
     */ 
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set undocumented variable
     *
     * @param  Comment[]  $comments  Undocumented variable
     *
     * @return  self
     */ 
    public function setComments(array $comments)
    {
        $this->comments = $comments;

        return $this;
    }
}

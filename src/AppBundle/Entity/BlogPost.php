<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogPost
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class BlogPost
{
    function __construct()
    {
        $this->pv = 0;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="blogPosts")
     */
    private $category;
    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="blogPosts")
     */
    private $subject;
    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="blogpost_tag",
     *   joinColumns={@ORM\JoinColumn(name="blogpost_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $tags;


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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     */
    private $image;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime")
     */
    private $createTime;

    /**
     * @var int
     *
     * @ORM\Column(name="pv", type="integer", options={"default": "0"})
     */
    private $pv;

    /**
     * @return mixed
     */
    public function getPv()
    {
        return $this->pv;
    }

    /**
     * @param mixed $pv
     */
    public function setPv($pv)
    {
        $this->pv = $pv;
    }

    /**
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param \DateTime $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }


    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Media $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return BlogPost
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return BlogPost
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * body去标签然后截取前多少个字,在twig中使用方法:{{ article.abstract }}
     * @return string
     */
    public function getAbstract()
    {
        $pos = strpos($this->body, '<div style="page-break-after');
        if ($pos > 0) {
            return strip_tags(substr($this->body, 0, $pos));
        } else {
            return strip_tags(mb_substr($this->body, 0, 100, "utf-8")) . '...';
        }
    }

    /**
     * 创建时间转成字符串才能展示
     * @return string
     */
    public function getCreateTimeStr()
    {
        $newDate = $this->createTime->format('Y-m-d H:i');
        return $newDate;
    }

    public function getCreateDate()
    {
        $newDate = $this->createTime->format('m-d');
        return $newDate;
    }

    public function getSimpleTitle()
    {
        if (!empty($this->tags) && sizeof($this->tags) > 0) {
            $tagName = $this->tags[0]->getName();
            return str_replace($tagName, '', $this->title);
        } else {
            return $this->title;
        }
    }
}

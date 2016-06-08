<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrawlPage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CrawlPage
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
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var text
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var text
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime")
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var string
     * @ORM\Column(name="isTec", type="string", length=8, options={"default": "0"})
     */
    private $isTec;

    /**
     * @var string
     * @ORM\Column(name="isSoup", type="string", length=8, options={"default": "0"})
     */
    private $isSoup;

    /**
     * @var string
     * @ORM\Column(name="isML", type="string", length=8, options={"default": "0"})
     */
    private $isML;

    /**
     * @var string
     * @ORM\Column(name="isMath", type="string", length=8, options={"default": "0"})
     */
    private $isMath;

    /**
     * @var string
     * @ORM\Column(name="isNews", type="string", length=8, options={"default": "0"})
     */
    private $isNews;

    /**
     * @var text
     * @ORM\Column(name="segment", type="text", nullable=true)
     */
    private $segment;

    /**
     * @return text
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * @param text $segment
     */
    public function setSegment($segment)
    {
        $this->segment = $segment;
    }

    /**
     * @return string
     */
    public function getIsNews()
    {
        return $this->isNews;
    }

    /**
     * @param string $isNews
     */
    public function setIsNews($isNews)
    {
        $this->isNews = $isNews;
    }

    /**
     * @return string
     */
    public function getIsTec()
    {
        return $this->isTec;
    }

    /**
     * @param string $isTec
     */
    public function setIsTec($isTec)
    {
        $this->isTec = $isTec;
    }

    /**
     * @return string
     */
    public function getIsSoup()
    {
        return $this->isSoup;
    }

    /**
     * @param string $isSoup
     */
    public function setIsSoup($isSoup)
    {
        $this->isSoup = $isSoup;
    }

    /**
     * @return string
     */
    public function getIsML()
    {
        return $this->isML;
    }

    /**
     * @param string $isML
     */
    public function setIsML($isML)
    {
        $this->isML = $isML;
    }

    /**
     * @return string
     */
    public function getIsMath()
    {
        return $this->isMath;
    }

    /**
     * @param string $isMath
     */
    public function setIsMath($isMath)
    {
        $this->isMath = $isMath;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * body去标签然后截取前多少个字,在twig中使用方法:{{ article.abstract }}
     * @return string
     */
    public function getAbstract()
    {
        return strip_tags(mb_substr($this->content, 0, 100, "utf-8")) . '...';
    }

    /**
     * 创建时间转成字符串才能展示
     * @return string
     */
    public function getCreateTimeStr()
    {
//        $newDate = $this->createTime->format('Y-m-d H:i');
        $newDate = $this->createTime->format('Y-m-d');
        return $newDate;
    }
}

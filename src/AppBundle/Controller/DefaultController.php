<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Subject;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class DefaultController extends Controller
{
    /**
     * @var ObjectRepository
     */
    protected $subjectRepository;

    /**
     * @var Subject
     */
    protected $subject;

    /**
     * @var ObjectRepository
     */
    protected $blogPostRepository;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var QueryBuilder
     */
    protected $builder;

    public function indexAction()
    {
        $this->subjectRepository = $this->getDoctrine()->getRepository('AppBundle:Subject');
        $subjects = $this->subjectRepository->findAll();
//        $this->swapSubject($subjects);

        $tagList = array(
            '自己动手做聊天机器人' => 'http://www.shareditor.com/uploads/media/default/0001/01/thumb_336_default_big.png',
            '机器学习精简入门教程' => 'http://www.shareditor.com/uploads/media/default/0001/01/1fcb6045aae4bf395756b77c26538d554eccbb89.jpeg',
            '教你成为全栈工程师(Full Stack Developer)' => 'http://www.shareditor.com/uploads/media/default/0001/01/c187f650ea1c870544c320fa452f5da1615b59e8.jpeg',
        );
        $tagData = $this->getTagData($tagList);
        return $this->render('default/index.html.twig', array(
            'taglist' => $tagList,
            'tagcounts' => $tagData['countmap'],
            'tagblogs' => $tagData['blogmap'],
            'subjects' => $subjects,
            'blogcounts' => $this->getSubjectBlogCountMap($subjects),
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this),
        ));
    }

    public function getSubjectBlogCountMap($subjects)
    {
        $this->blogPostRepository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $map = array();
        for ($i = 0; $i < sizeof($subjects); $i = $i + 1)
        {
            $this->subject = $subjects[$i];
            $map[$this->subject->getId()] = sizeof($this->blogPostRepository->findBy(array('subject' => $this->subject->getId())));
        }
        return $map;
    }

    public function getTagData($tagList)
    {
        $countMap = array();
        $blogMap = array();
        while ($tag = key($tagList)) {
            $countMap[$tag] = $this->getTagBlogCount($tag);
            $blogMap[$tag] = $this->getTagNewestBlog($tag);
            next($tagList);
        }
        return array(
            'countmap' => $countMap,
            'blogmap' => $blogMap
        );
    }

    public function getTagBlogCount($tagName)
    {
        $this->em = $this->get('doctrine.orm.entity_manager');
        $this->builder = $this->em->createQueryBuilder();
        $query = $this->builder->select('b')
            ->add('from', 'AppBundle:BlogPost b INNER JOIN b.tags t')
            ->where('t.name=:tag_name')
            ->setParameter('tag_name', $tagName)
            ->getQuery();
        return sizeof($query->getArrayResult());
    }

    public function getTagNewestBlog($tagName)
    {
        $this->em = $this->get('doctrine.orm.entity_manager');
        $this->builder = $this->em->createQueryBuilder();
        $query = $this->builder->select('b')
            ->add('from', 'AppBundle:BlogPost b INNER JOIN b.tags t')
            ->where('t.name=:tag_name')
            ->orderBy('b.id','DESC')
            ->setMaxResults(5)
            ->setParameter('tag_name', $tagName)
            ->getQuery();
        return $query->getResult();
    }

    public function getTagPopularBlog($tagName)
    {
        $this->em = $this->get('doctrine.orm.entity_manager');
        $this->builder = $this->em->createQueryBuilder();
        $query = $this->builder->select('b')
            ->add('from', 'AppBundle:BlogPost b INNER JOIN b.tags t')
            ->where('t.name=:tag_name')
            ->orderBy('b.pv','DESC')
            ->setMaxResults(5)
            ->setParameter('tag_name', $tagName)
            ->getQuery();
        return $query->getResult();
    }

    public function swapSubject(&$subjects) {
        $tmpSubject = $subjects[0];
        $subjects[0] = $subjects[1];
        $subjects[1] = $tmpSubject;
        return $subjects;
    }
}

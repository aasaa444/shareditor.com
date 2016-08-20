<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Subject;
use Symfony\Component\HttpFoundation\Response;

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

    public function indexAction()
    {
        $this->subjectRepository = $this->getDoctrine()->getRepository('AppBundle:Subject');
        $subjects = $this->subjectRepository->findAll();
//        $this->swapSubject($subjects);

        return $this->render('default/index.html.twig', array(
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

    public function swapSubject(&$subjects) {
        $tmpSubject = $subjects[0];
        $subjects[0] = $subjects[1];
        $subjects[1] = $tmpSubject;
        return $subjects;
    }
}

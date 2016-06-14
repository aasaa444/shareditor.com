<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Doctrine\Common\Persistence\ObjectRepository;

class BlogController extends Controller
{

    /**
     * @var ObjectRepository
     */
    protected $blogPostRepository;

    /**
     * @var ObjectRepository
     */
    protected $subjectRepository;

    public function listAction(Request $request, $subjectId)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM AppBundle:BlogPost a WHERE a.subject=" . $subjectId . " ORDER BY a.createTime DESC";
        $query = $em->createQuery($dql);

        $this->subjectRepository = $this->getDoctrine()->getRepository('AppBundle:Subject');
        $subject = $this->subjectRepository->find($subjectId);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('blog/list.html.twig', array('pagination' => $pagination,
            'subject' => $subject,
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this)));
    }

    public function showAction(Request $request)
    {
        $blogId = $request->get('blogId');
        $this->blogPostRepository = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $blogPost = $this->blogPostRepository->find($blogId);
        if (!empty($blogPost)) {
            return $this->render('blog/show.html.twig', array('blogpost' => $blogPost,
                'latestblogs' => BlogController::getLatestBlogs($this),
                'tophotblogs' => BlogController::getTopHotBlogs($this),
                'is_original' => true,
                'lastblog' => $this->findLastBlog($blogId),
                'nextblog' => $this->findNextBlog($blogId)
            ));
        } else {
            return $this->render('blog/nofound.html.twig', array(
                'latestblogs' => BlogController::getLatestBlogs($this),
                'tophotblogs' => BlogController::getTopHotBlogs($this)
            ));
        }
    }

    private function findLastBlog($blogId)
    {
        $blog = null;
        $blogId--;
        while ($blogId >= 0) {
            $blog = $this->blogPostRepository->find($blogId);
            if (!empty($blog)) {
                break;
            } else {
                $blogId--;
            }
        }
        return $blog;
    }

    private function findNextBlog($blogId)
    {
        $blog = null;
        $blogId++;
        $tryCount = 20;
        while ($tryCount >= 0) {
            $blog = $this->blogPostRepository->find($blogId);
            if (!empty($blog)) {
                break;
            } else {
                $blogId++;
                $tryCount--;
            }
        }
        return $blog;
    }

    public function searchAction(Request $request)
    {
        $query = $request->get('q');
        $finder = $this->container->get('fos_elastica.finder.app.blogpost');
        $paginator = $this->get('knp_paginator');
        $results = $finder->createPaginatorAdapter($query);
        $pagination = $paginator->paginate($results, 1, 10);

        return $this->render('blog/search.html.twig', array('pagination' => $pagination,
            'query' => $query,
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this)));
    }

    public static function getLatestBlogs($contoller)
    {
        $blogPostRepository = $contoller->getDoctrine()->getRepository('AppBundle:BlogPost');
        $blogposts = $blogPostRepository->findBy(array(), array('createTime' => 'DESC'), 5);
        return $blogposts;
    }

    public static function getTopHotBlogs($contoller)
    {
        $blogPostRepository = $contoller->getDoctrine()->getRepository('AppBundle:BlogPost');
        $tophotblogs = $blogPostRepository->findBy(array(), array('pv' => 'DESC'), 5);
        return $tophotblogs;
    }

    public static function genRandList($min, $max, $num)
    {
        $num = min($num, $max-$min);
        $map = array();
        while (sizeof($map) < $num) {
            $r = rand($min, $max-1);
            $map[$r] = 1;
        }
        return $map;
    }
}

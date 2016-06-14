<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;

class FavoriteController extends Controller
{
    /**
     * @var ObjectRepository
     */
    protected $crawlPageRepository;

    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getEntityManager();
        $sources = $entityManager->createQuery('select p.source from AppBundle:CrawlPage p group by p.source')->getResult();

        $this->crawlPageRepository = $this->getDoctrine()->getRepository('AppBundle:CrawlPage');
        $crawlPagesArray = array();
        foreach ($sources as $sourceObject) {
            $source = $sourceObject['source'];
            $crawlPages = $this->crawlPageRepository->findBy(array('source' => $source), array('createTime' => 'DESC'));
            if (sizeof($crawlPages) < 10) {
                continue;
            }
            $crawlPagesObject = array();
            $crawlPagesObject['source'] = $source;
            $crawlPagesObject['data'] = $crawlPages;
            $crawlPagesArray[] = $crawlPagesObject;
        }

        return $this->render('favorite/index.html.twig', array(
            'crawl_pages_array' => $crawlPagesArray,
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this),
        ));
    }

    public function sourcelistAction(Request $request)
    {
        $source = $request->get('source');
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM AppBundle:CrawlPage a WHERE a.source='" . $source . "' ORDER BY a.createTime DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('favorite/sourcelist.html.twig', array('pagination' => $pagination,
            'source' => $source,
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this)));
    }

    public function categorylistAction(Request $request)
    {
        $category = $request->get('category');
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql = "";
        if ($category == '技术文章') {
            $dql   = "SELECT a FROM AppBundle:CrawlPage a WHERE a.isTec='1' ORDER BY a.createTime DESC";
        } else if ($category == '机器学习') {
            $dql   = "SELECT a FROM AppBundle:CrawlPage a WHERE a.isML='1' ORDER BY a.createTime DESC";
        } else if ($category == '数学知识') {
            $dql   = "SELECT a FROM AppBundle:CrawlPage a WHERE a.isMath='1' ORDER BY a.createTime DESC";
        } else if ($category == '新闻资讯') {
            $dql   = "SELECT a FROM AppBundle:CrawlPage a WHERE a.isNews='1' ORDER BY a.createTime DESC";
        } else if ($category == '鸡汤文章') {
            $dql   = "SELECT a FROM AppBundle:CrawlPage a WHERE a.isSoup='1' ORDER BY a.createTime DESC";
        }

        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('favorite/categorylist.html.twig', array('pagination' => $pagination,
            'category' => $category,
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this)));
    }

    public function showAction(Request $request)
    {
        $crawlPageId = $request->get('pageid');
        $this->crawlPageRepository = $this->getDoctrine()->getRepository('AppBundle:CrawlPage');
        return $this->render('favorite/show.html.twig', array('blogpost' => $this->crawlPageRepository->find($crawlPageId),
            'latestblogs' => BlogController::getLatestBlogs($this),
            'tophotblogs' => BlogController::getTopHotBlogs($this),
            'is_original' => false
        ));
    }
}

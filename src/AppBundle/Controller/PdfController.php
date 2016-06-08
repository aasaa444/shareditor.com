<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\Common\Persistence\ObjectRepository;

class PdfController extends Controller
{

    /**
     * @var ObjectRepository
     */
    protected $blogPostRepository;
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var EntityManager
     */
    protected $em;

    public function generateAction(Request $request, $title, $year, $month, $day)
    {
        $time = mktime(0, 0, 0, $month, $day, $year);
        $now = time();
        if ($time > $now) {
            return new Response('file not exist');
        }
        $this->kernel = $this->get('kernel');
        $rootDir = $this->kernel->getRootDir();
        $pdfFilePath = $rootDir . '/../web/pdf/' . $title . '/' . $year . "-" .  $month . "-" .  $day . '.pdf';

        $this->em    = $this->get('doctrine.orm.entity_manager');

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select(array('blogpost'))->from('AppBundle:BlogPost', 'blogpost')
            ->where($qb->expr()
                ->lt('blogpost.createTime', '\'' . date("Y-m-d 00:00:00", $time) . '\'')
            )
            ->andWhere($qb->expr()
                ->like('blogpost.title', '\'%' . $title . '%\'')
            )
            ->getQuery();

        if (file_exists($pdfFilePath)) {
            unlink($pdfFilePath);
        }
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'pdf/generate.html.twig',
                array('blogposts' => $q->getresult())
            ),
            $pdfFilePath
        );


        return new BinaryFileResponse($pdfFilePath);
    }

}

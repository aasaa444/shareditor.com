<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatBotController extends Controller
{

    public function indexAction()
    {
        return $this->render('chatbot/index.html.twig',
            array(
                'tophotblogs' => BlogController::getTopHotBlogs($this, 10)
            )
        );
    }

    public function queryAction(Request $request)
    {
        $q = $request->get('input');
        if ($q == '机器学习资料') {
            return new Response('链接: https://pan.baidu.com/s/1nuL8Lfz 密码: eqtt');
        }
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'timeout'=>60,
            )
        );
        $context = stream_context_create($opts);
        $clientIp = $request->getClientIp();
        $response = file_get_contents('http://10.162.223.224:8765/?q=' . urlencode($q) . '&clientIp=' . $clientIp, false, $context);
        $res = json_decode($response, true);
        $total = $res['total'];
        $result = '';
        if ($total > 0) {
            $result = $res['result'][0]['answer'];
        }
        return new Response($result);
    }
}

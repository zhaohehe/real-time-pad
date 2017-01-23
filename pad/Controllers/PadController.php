<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Controllers;

use Interop\Container\ContainerInterface;
use Pad\Models\Pad;

class PadController extends BaseController
{
    protected $pad;

    protected $container;

    protected $pages = [
        'home',
        'instruction',    //说明文档
        'discuss',        //公共频道
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->pad = new Pad();
        $this->container = $container;
    }

    /**
     * 首页
     */
    public function home($request, $response, $args)
    {
        return $response->withStatus(200)->withHeader('Location', 'pad/home');
    }


    /**
     * 显示指定的pad
     *
     * @return response
     */
    public function show($request, $response, $args)
    {
        $padId = $args['id'];

        if ($this->pad->exeist($padId)) {
            $socket_client = config('web_socket.client');

            return $this->container->view->render($response, 'index.twig', [
                'padId' => $padId,
                'web_socket' => 'ws://'.$socket_client['host'].':'.$socket_client['port'],
                'content' => (string)$this->pad->getContent($padId)->content
            ]);

        } else {
            return '没有这个pad';
        }
    }


    public function secret($request, $response, $args)
    {
        $ip = $_SERVER["REMOTE_ADDR"];

        if ($pad = $this->pad->getByIp($ip)) {    //get content when you have already create a pad before
            return $response->withStatus(200)->withHeader('Location', $pad->pad_id);
        } else {
            //you can create only one pad with each ip address
            if ($this->pad->checkIp($ip)) {
                $padId = str_random(32);
                $this->pad->create($padId, $ip);

                return $response->withStatus(200)->withHeader('Location', $padId);
            } else {
                return '你暂时只能创建一个pad';
            }
        }
    }

}
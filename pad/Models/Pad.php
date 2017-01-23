<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Models;

class Pad extends Base
{

    protected $table = 'pad';

    protected $privateContent = '{"ops":[{"insert":"\u0068\u0065\u006c\u006c\u006f\u002c\u0020\u8fd9\u662f\u4f60\u7684\u79c1\u6709\u0070\u0061\u0064\uff0c\u4f60\u53ef\u4ee5\u628a\u0075\u0072\u006c\u5206\u4eab\u7ed9\u4f60\u7684\u5c0f\u4f19\u4f34\n"}]}';


    public function create($padId, $ip)
    {
        $this->model->updateOne(
            ['pad_id' => $padId],
            ['$set' => [
                'ip'      => $ip,
                'content' => $this->privateContent
                ]
            ],
            ['upsert' => true]
        );
    }


    public function exeist($padId)
    {
        return $this->model->findOne(['pad_id' => $padId]);
    }


    public function checkIp($ip)
    {
        return ! $this->model->findOne(['ip' => $ip]);
    }


    public function updateContent($content, $padId)
    {
        $this->model->updateOne(['pad_id' => $padId], ['$set' => ['content' => $content]]);
    }


    public function getContent($padId)
    {
        return $this->model->findOne(['pad_id' => $padId]);
    }

    public function getByIp($ip)
    {
        return $this->model->findOne(['ip' => $ip]);
    }




    public function getMembersById($id)
    {
        return $this->redis->smembers('pad:'.$id);
    }


    public function join($user, $pad)
    {
        $this->redis->sadd('pad:'.$pad, $user);
    }


    public function remove($user, $pad)
    {

    }

}
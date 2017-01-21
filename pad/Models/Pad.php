<?php

/*
 * Sometime too hot the eye of heaven shines
 */

namespace Pad\Models;

class Pad extends Base
{

    protected $table = 'pad';


    public function getMembersById($id)
    {
        return $this->redis->smembers('pad:'.$id);
    }

    public function updateContent($content, $padId)
    {
        $this->model->updateOne(['pad_id' => $padId], ['$set' => ['content' => $content]]);
    }

    public function join($user, $pad)
    {
        $this->redis->sadd('pad:'.$pad, $user);
    }

    public function remove($user, $pad)
    {

    }

    public function getContent($id)
    {
        return $this->model->findOne(['pad_id' => $id]);
    }
}
<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Service_model $service
 * @property General_model $wowgeneral
 */
class Forum_model extends CI_Model
{
    /**
     * Forum_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param $id
     *
     * @return array|array[]|object|object[]
     */
    public function getCategory($id = null)
    {
        $value = is_null($id) ? $this->db->get('forum_category')->result() : $this->db->where('category', $id)->get('forum')->result();

        return $value;
    }

    /**
     * @param $column
     * @param $id
     *
     * @return int
     */
    public function getCountTopics($column = null, $id = null): int
    {
        $value = (is_null($id) && is_null($column))
            ? $this->db->get('forum_topics')->num_rows()
            :
            $this->db->where($column, $id)->get('forum_topics')->num_rows();

        return $value;
    }

    /**
     * @param $column
     * @param $id
     *
     * @return int
     */
    public function getCountReplies($column = null, $id = null): int
    {
        $value = (is_null($id) && is_null($column))
            ? $this->db->get('forum_replies')->num_rows()
            :
            $this->db->where($column, $id)->get('forum_replies')->num_rows();

        return $value;
    }

    /**
     * @param $column
     * @param $id
     *
     * @return int
     */
    public function getCountUsers($column = null, $id = null): int
    {
        $value = (is_null($id) && is_null($column))
            ? $this->db->get('users')->num_rows()
            :
            $this->db->where($column, $id)->get('users')->num_rows();

        return $value;
    }

    /**
     * @param $id
     *
     * @return array|array[]|object|object[]
     */
    public function getLastPosts($id = null)
    {
        $value = is_null($id)
            ? $this->db->limit('5')->order_by('date', 'ASC')->get('forum_topics')->result()
            :
            $this->db->select('*')->where('forums', $id)->limit('1')->order_by('date', 'DESC')->get('forum_topics')->result();

        return $value;
    }

    /**
     * @param $id
     *
     * @return CI_DB_result
     */
    public function getLastReplies($id): CI_DB_result
    {
        $value = $this->db->where('topic', $id)->limit('1')->order_by('date', 'DESC')->get('forum_replies');

        return $value;
    }

    /**
     * @param $id
     *
     * @return array|mixed|object|null
     */
    public function authType($id)
    {
        return $this->db->select('type')->where('id', $id)->get('forum')->row('type');
    }

    /**
     * @param $id
     * @param $row
     *
     * @return array|mixed|object|null
     */
    public function getForumRow($id, $row)
    {
        return $this->db->where('id', $id)->get('forum')->row($row);
    }

    /**
     * @param $id
     * @param $row
     *
     * @return array|mixed|object|null
     */
    public function getTopicRow($id, $row)
    {
        return $this->db->where('id', $id)->get('forum_topics')->row($row);
    }

    /**
     * @param $id
     *
     * @return array|mixed|object|null
     */
    public function getTopicTitle($id)
    {
        return $this->db->select('title')->where('id', $id)->get('forum_topics')->row('title');
    }

    /**
     * @param $id
     *
     * @return array|mixed|object|null
     */
    public function getTopicContent($id)
    {
        return $this->db->select('content')->where('id', $id)->get('forum_topics')->row('content');
    }

    /**
     * @param $id
     *
     * @return CI_DB_result
     */
    public function getPosts($id): CI_DB_result
    {
        return $this->db->where('forums', $id)->order_by('pinned', 'DESC')->order_by('id', 'ASC')->get('forum_topics');
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function removeComment($id): bool
    {
        $this->db->where('id', $id)->delete('forum_replies');

        return true;
    }

    /**
     * @param $id
     *
     * @return CI_DB_result
     */
    public function getComments($id): CI_DB_result
    {
        return $this->db->where('topic', $id)->get('forum_replies');
    }

    /**
     * @param $category
     * @param $title
     * @param $userid
     * @param $description
     * @param $locked
     * @param $pinned
     *
     * @return bool
     */
    public function newTopic($category, $title, $userid, $description, $locked, $pinned): bool
    {
        $date = $this->wowgeneral->getTimestamp();

        $data = array(
            'forums'  => $category,
            'title'   => $title,
            'author'  => $userid,
            'date'    => $date,
            'content' => $description,
            'locked'  => $locked,
            'pinned'  => $pinned
        );

        $this->db->insert('forum_topics', $data);

        $idTopic = $this->db->select('*')->order_by('id', "desc")->limit(1)->get('forum_topics')->row('id');

        $this->service->logsService->send($userid, 1, $idTopic, '[Guardian - Created Topic]', $title);

        return true;
    }

    /**
     * @param       $idlink
     * @param       $title
     * @param       $description
     * @param  int  $locked
     * @param  int  $pinned
     *
     * @return bool
     */
    public function updateTopic($idlink, $title, $description, int $locked = 0, int $pinned = 0): bool
    {
        $data = array(
            'title'   => $title,
            'content' => $description,
            'locked'  => $locked,
            'pinned'  => $pinned
        );

        $this->db->where('id', $idlink)->update('forum_topics', $data);

        return true;
    }

    /**
     * @param $reply
     * @param $topicid
     * @param $author
     *
     * @return bool
     */
    public function newComment($reply, $topicid, $author): bool
    {
        $date = $this->wowgeneral->getTimestamp();

        $data = array(
            'topic'      => $topicid,
            'author'     => $author,
            'commentary' => $reply,
            'date'       => $date
        );

        $this->db->insert('forum_replies', $data);

        $this->service->logsService->send($author, 2, $topicid, '[Guardian - Created Reply]', $reply);

        return true;
    }
}

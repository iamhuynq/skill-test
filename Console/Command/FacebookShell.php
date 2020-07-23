<?php
App::uses('Shell', 'Console');
App::uses('ComponentCollection', 'Controller');
App::uses('FacebookComponent', 'Controller/Component');

class FacebookShell extends Shell {
    public $uses = array('t_facebook_accounts', 't_facebook_posts');
    public $components = array('Facebook');

    /**
    * Setup use component for Facebook
    *
    */
    public function startup() {
        //Load componentFacebook
        $collecFacebook = new ComponentCollection();
        $this->FacebookComponent = $collecFacebook->load('Facebook');
    }

    /**
    * This method is called when a shell is called with no additional commands: Console/cake NameShell
    *
    */
    public function main() {
        $this->getFeedOfFace();
    }

    /**
    * This method get data feed by api of Face
    *
    */
    public function getFeedOfFace() {
        //set up fields
        $fields = array();
        $fields[] = 'message';
        $fields[] = 'reactions.summary(total_count).limit(0).type(LIKE)';
        $fields[] = 'comments.filter(toplevel).summary(true).limit(0)';
        $fields[] = 'created_time';
        $param['fields'] = implode(',', $fields);

        $response = $this->FacebookComponent->apiGetData(
            'TMH.TechLabDev/feed?fields='
            . $param['fields']
            . '&limit=100'
        );

        if (empty($response)) {
            return $this->out('Data empty. Please check connect agent!');
        }
            
        $facebook_id = $this->t_facebook_accounts->find('first')['t_facebook_accounts']['facebook_id'];
        //request save data Feed of Facebook
        $this->__saveFeedFace($response, $facebook_id);
    }

    /**
    * This method handle do save data of facebook.
    *
    * @param object $feeds data feed of facebook.
    *
    * @return String Returns text confirm save success.
    */
    private function __saveFeedFace($feeds, $facebook_id) {
        //Request Delete old data.
        $condition = array('t_facebook_posts.facebook_id' => $facebook_id);
        $this->t_facebook_posts->deleteAll($condition, false);

        foreach ($feeds['data'] as $data_feed) {
            $feed_id = $data_feed['id'];
            $message = isset($data_feed['message']) ? $data_feed['message'] : '';
            $like = $data_feed['reactions']['summary']['total_count'];
            $comment = isset($data_feed['comments']['summary']['total_count']) ? $data_feed['comments']['summary']['total_count'] : '';
            $created_at = $data_feed['created_time'];

            //format data_feed
            $results = array(
                'facebook_id' => $facebook_id,
                'feed_id' => $feed_id,
                'message' => $message,
                'like' => $like,
                'comment' => $comment,
                'created_at' => $created_at
            );

            //save data to database
            $this->t_facebook_posts->create();
            $this->t_facebook_posts->save($results);
        }
        return $this->out('Saved Feed Facebook!');
    }
}

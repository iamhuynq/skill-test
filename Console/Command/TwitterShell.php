<?php
App::uses('Shell', 'Console');
App::uses('ComponentCollection', 'Controller');
App::uses('TwitterComponent', 'Controller/Component');

class TwitterShell extends Shell {
    public $uses = array('t_twitter_accounts','t_twitter_posts');
    public $components = array('Twitter');

    /**
    * Setup use component for Twitter
    *
    */
    public function startup() {
        //Load componentTwitter
        $collecTwitter = new ComponentCollection();
        $this->TwitterComponent = $collecTwitter->load('Twitter');
    }

    /**
    * This method is called when a shell is called with no additional commands: Console/cake NameShell
    *
    */
    public function main() {
        $this->getFeedOFTwitter();
    }

    /**
    * This method request get data feed of Twitter
    *
    */
    public function getFeedOFTwitter() {
        $path = 'statuses/user_timeline';

        //Request Get data of Twitter.
        $status = $this->TwitterComponent->apiGetData($path, array('screen_name' => 'TMH_TechLabDev', 'count' => 100));
        if (empty($status)) {
            return $this->out('Not Saved! Maybe have some error.');
        }

        $twitter_id = $this->t_twitter_accounts->find('first')['t_twitter_accounts']['twitter_id'];
        //Request save data of twitter.
        $this->__saveFeedTwitter($status, $twitter_id);
    }

    /**
    * This method handle do save data of Twitter.
    *
    * @param object $feeds data feed of Twitter.
    *
    * @return String Returns text confirm save success.
    */
    private function __saveFeedTwitter($status, $id_twitter) {
        //Request Delete old data.
        $condition = array('t_twitter_posts.twitter_id' => $id_twitter);
        $this->t_twitter_posts->deleteAll($condition, false);

        //format data_status twitter
        foreach ($status as $data) {
            $tweet_id = $data->id;
            $message = isset($data->text) ? $data->text : '';
            $retweet = $data->retweet_count;
            $favorite = $data->favorite_count;
            $created_at = $data->created_at;
            $strtotime = strtotime($created_at);
            $format_time = date('Y-m-d h:m:s', $strtotime);

            $results = array (
                'twitter_id' => $id_twitter,
                'tweet_id' => $tweet_id,
                'message' => $message,
                'retweet' => $retweet,
                'favorite' => $favorite,
                'created_at' => $format_time
            );

            $this->t_twitter_posts->create();
            $this->t_twitter_posts->save($results);
        }

        return $this->out('Saved Status Twitter!');
    }
}
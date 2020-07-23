<?php
App::uses('Component', 'Controller');
App::import('Vendor', 'autoload', array('file' => 'twitteroauth' . DS . 'autoload.php'));
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterComponent extends Component {
    private $__connecTwritter = null;

    public function __construct() {
        $this->__connecTwritter();
    }

    private function __connecTwritter() {
        $consumer_key = Configure::read('twitter_app.consumer_key');
        $consumer_secret = Configure::read('twitter_app.consumer_secret');
        $access_token = Configure::read('twitter_app.access_token');
        $access_token_secret = Configure::read('twitter_app.access_token_secret');

        $this->__connecTwritter = new TwitterOAuth($consumer_key , $consumer_secret, $access_token, $access_token_secret);
        return $this->__connecTwritter;
    }

    public function apiGetData($path, $feild) {
        try {
            return $this->__connecTwritter->get($path, $feild);
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }

    public function apiPostData($path, $feild) {
        try {
            $post = $this->__connecTwritter->post($path, $feild);
            if ($post->id) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }

    public function apiUploadMedia($path, $feild) {
        try {
            return $this->__connecTwritter->upload($path, $feild);
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }

    public function apiDeleteData($id_twitter) {
        try {
            return $this->__connecTwritter->post('statuses/destroy', ['id' => $id_twitter]);
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }
}
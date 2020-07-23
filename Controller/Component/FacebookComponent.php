<?php
App::uses('Component', 'Controller');
App::import('Vendor', 'sdk', array('file' => 'php-graph-sdk' . DS . 'src' . DS . 'Facebook' . DS . 'autoload.php'));

class FacebookComponent extends Component {
    private $__connecFacebook = null;

    public function __construct() {
        $this->__connecFacebook();
    }

    private function __connecFacebook() {
        $app_id = Configure::read('facebook_app.app_id');
        $app_secret = Configure::read('facebook_app.app_secret');
        $access_token = Configure::read('facebook_app.access_token');

        $config = array (
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_access_token' => $access_token
        );
        $this->__connecFacebook = new Facebook\Facebook($config);

        return $this->__connecFacebook;
    }

    public function apiGetData($path) {
        try {
            return $this->__connecFacebook->get($path)->getDecodedBody();
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }

    public function apiDeleteData($id_feed) {
        try {
            $this->__connecFacebook->delete($id_feed);

            return true;
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }

    public function apiPostData($path, $attachment) {
        $this->layout = null;
        try {
            if (isset($attachment['url'])) {
                $attachment = [
                    'message' => $attachment['caption'],
                    'source' => $this->__connecFacebook->fileToUpload($attachment['url'])
                ];
            }

            $post = $this->__connecFacebook->post($path, $attachment);
            $this->Log($post);
            return true;
            
        } catch (Exception $e) {
            $this->log($e->getMessage());

            return false;
        }
    }
}
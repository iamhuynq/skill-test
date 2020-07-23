<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class TwitterController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
    public $uses = array('t_twitter_posts');

 /**
 * This controller does not use a components
 *
 * @var array
 */ 
    public $components = array('Twitter');
/**
 * Displays a view
 *
 * @return CakeResponse|null
 * @throws ForbiddenException When a directory traversal attempt.
 * @throws NotFoundException When the view file could not be found
 *   or MissingViewException in debug mode.
 */
    public function index() {
        $dataStatusTwitter = $this->t_twitter_posts->find('all');
        if ($this->request->is('post')) {
            $keyword = $this->request->data['keyword'];
            $dataSearchTwitter = $this->t_twitter_posts->find('all', array (
                'conditions' => array ('t_twitter_posts.message LIKE' => '%' . $keyword . '%'),
                'order' => array('t_twitter_posts.id' => 'ASC')
            ));
            $this->set('key', $keyword);
            $this->set('dataTwitter', $dataSearchTwitter);
        } else {
            $this->set('dataTwitter', $dataStatusTwitter);
        }
    }

    /**
     * Method change language for website
     *
     */
    public function change($key) {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->Session->write('Config.language', $key);
        }
    }

    public function post() {

    }

    /**
     * Method handle Post Status of Twitter
     *
     * @return AjaxResponse boolean
     * @return true When handle post success
     * @return false When have error
     */
    public function ajaxPostTwet() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $cap = $this->request->data['cap'];
            
            if ($this->request->data['flag'] != 1) { // Post have not image file.
                $attachment = array (
                    'status' => $cap
                );
            } else { // Post have image file.
                $targetFile = TMP . 'data/photos/' . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $file = file_get_contents($targetFile);
                    $media = $this->Twitter->apiUploadMedia('media/upload', array('media' => $targetFile));
                    if ($media) {
                        $attachment = array (
                            'status' => $cap,
                            'media_ids' => $media->media_id_string
                        );
                    }
                }
            }
            // Reques Api Post Twitter
            if ($this->Twitter->apiPostData('statuses/update', $attachment)){
                return json_encode(true);
            }

            return json_encode(false);
        }
    }

    /**
     * Method Request Api Delete Status of Twitter and Data on DB
     *
     * @return AjaxResponse boolean
     * @return true When handle delete broth success
     * @return false When have some error
     */
    public function ajaxDelStatus($idStatus) {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {

            $dataFeed = $this->t_twitter_posts->find('first', array (
                'conditions' => array ('t_twitter_posts.id' => $idStatus)
            ));

            if ($this->Twitter->apiDeleteData($dataFeed['t_twitter_posts']['tweet_id'])) {
                $this->t_twitter_posts->delete($idStatus);
                return json_encode(true);
            }
            return json_encode(false);
        }
    }

    /**
     * Method get data status to download
     *
     */
    public function ajaxGetDataDown() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            if ($this->request->data['dateTo'] == '') {
                $dateTo = date('Y-m-d', time() + 86400);
            } else {
                $dateTo = date('Y-m-d', strtotime($this->request->data['dateTo']));
            }

            if ($this->request->data['dateFrom'] == '') {
                $dateFrom = date('1970-01-01');
            } else {
                $dateFrom = date('Y-m-d', strtotime($this->request->data['dateFrom']));
            }

            $dataDown = $this->t_twitter_posts->find('all', array (
                'conditions' => array ('t_twitter_posts.created_at between ? and ?' => array ($dateFrom, $dateTo)),
                'fields' => array ('tweet_id', 'message', 'retweet', 'favorite', 'created_at'),
                'order' => array('t_twitter_posts.id' => 'ASC')
            ));
            //Request create file CSV
            if ($dataDown) {
                $this->__generateCSVFile($dataDown);

                return json_encode(true);
            }
            return json_encode(false);
        }
    }

    /**
     * Method create file CSV Twitter
     *
     */
    private function __generateCSVFile($data) {
        $headers = 'Tweet_id, Message, Retweet, Favorite, Post Date';
        $myfile = fopen(WWW_ROOT . 'files/' . 'Twitter.csv', 'w+');

        fputcsv($myfile, explode(',', $headers));
        foreach ($data as $item) {
            fputcsv($myfile, $item['t_twitter_posts']);
        }
        fclose($myfile);
    }

    /**
     * Method Download file CSV Twitter
     *
     */
    public function ajaxDownData() {
        $this->autoRender = false;
        $file = WWW_ROOT . 'files/' . 'Twitter.csv';

        if (file_exists($file)) {
            $this->response->file($file, array ('download' => true));
            return true;
        }
    }
}

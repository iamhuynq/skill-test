<?php
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class FacebookController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
    public $uses = array('t_facebook_posts', 't_facebook_posts');
 /**
 * This controller does not use a components
 *
 * @var array
 */   
    public $components = array('Facebook');

/**
 * Displays a view
 *
 * @return CakeResponse|null
 * @throws ForbiddenException When a directory traversal attempt.
 * @throws NotFoundException When the view file could not be found
 *   or MissingViewException in debug mode.
 */
    public function index() {
        $dataFeedsFace = $this->t_facebook_posts->find('all', array (
            'order' => array('t_facebook_posts.id' => 'ASC'))
        );

        if ($this->request->is('post')) {
            $keyword = $this->request->data['keyword'];
            $dataSearchFace = $this->t_facebook_posts->find('all', array (
                'conditions' => array ('t_facebook_posts.message LIKE' => '%' . $keyword . '%'),
                'order' => array('t_facebook_posts.id' => 'ASC')
            ));
            $this->set('key', $keyword);
            $this->set('dataFacebook', $dataSearchFace);
        } else {
            $this->set('dataFacebook', $dataFeedsFace);
        }
    }

    public function post() {

    }

    /**
     * Method handle Post Feed of Facebook
     *
     * @return AjaxResponse boolean
     * @return true When handle post success
     * @return false When have error
     */
    public function ajaxPostFace() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $cap = $this->request->data['cap'];
            $id_page_face = $this->t_facebook_accounts->find('first')['t_facebook_accounts']['facebook_id'];

            if ($this->request->data['flag'] == 1) { // Post have image file.
                $targetFile = TMP . 'data/photos/' . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $attachment = array(
                        'url' => TMP . 'data/photos/' . $_FILES['image']['name'],
                        'caption' => $cap
                    );
                    // Reques Api Post Feed FaceBook
                    $this->Facebook->apiPostData('/' . $id_page_face . '/photos', $attachment);

                    return json_encode(true);
                }

                return json_encode(false);
            } else { // Post have not image file.
                $attachment = array(
                    'message' => $cap
                );
                // Reques Api Post Feed FaceBook
                if($this->Facebook->apiPostData('/' . $id_page_face . '/feed', $attachment)) {
                    return json_encode(true);
                }

                return json_encode(false);
            }
        }
    }

    /**
     * Method Request Api Delete Feed of Facebook and Data on DB
     *
     * @return AjaxResponse boolean
     * @return true When handle delete broth success
     * @return false When have some error
     */
    public function ajaxDelFeed($idFeed) {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $dataFeed = $this->t_facebook_posts->find('first', array (
                'conditions' => array ('t_facebook_posts.id' => $idFeed)
            ));
            // Reques Api Delete Feed FaceBook
            if ($this->Facebook->apiDeleteData($dataFeed['t_facebook_posts']['feed_id'])) {
                // Reques Delete Feed FaceBook on DB
                $this->t_facebook_posts->delete($idFeed);
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

            $dataDown = $this->t_facebook_posts->find('all', array (
                'conditions' => array (
                    't_facebook_posts.created_at between ? and ?' => array ($dateFrom, $dateTo)
                ),
                'fields' => array ('feed_id', 'message', 'comment', 'like', 'created_at'),
                'order' => array('t_facebook_posts.id' => 'ASC')
            ));
            if ($dataDown) {
                //Request create file CSV Facebok
                $this->__generateCSVFile($dataDown);

                return json_encode(true);
            }
            return json_encode(false);
        }
    }

    /**
     * Method create file CSV Facebook
     *
     */
    private function __generateCSVFile($data) {
        $headers = 'Feed_id, Message, Comment, Like, Post Date';
        $myfile = fopen(WWW_ROOT . 'files/' . 'Facebook.csv', 'w+');

        fputcsv($myfile, explode(',', $headers));
        foreach($data as $item){
            fputcsv($myfile, $item['t_facebook_posts']);
        }
        fclose($myfile);
    }

    /**
     * Method Download file CSV Facebook
     *
     */
    public function ajaxDownData() {
        $this->autoRender = false;
        $file = WWW_ROOT . 'files/' . 'FaceBook.csv';

        if (file_exists($file)) {
            $this->response->file($file, array ('download' => true));
            return true;
        }
    }
}
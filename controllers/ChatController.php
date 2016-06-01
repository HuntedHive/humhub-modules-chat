<?php

/**
 * @package humhub.modules_core.admin.controllers
 * @since 0.5
 */
class ChatController extends Controller
{
    private $imageUrl;
    private $imageHost;
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.mention
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
//            array('allow',
//                'expression' => 'Yii::app()->user->isAdmin()'
//            ),
            array('deny', // deny all users
                'users' => array('?'),
            ),
        );
    }

    public function actionIndex()
    {
        $icons = WBSChatSmile::model()->findAll();
        $sql = 'SELECT * 
                FROM (SELECT * FROM wbs_chat
                      ORDER BY id DESC 
                      LIMIT 0,20) t
                ORDER BY id ASC';
        $modelMessage = Yii::app()->db->createCommand($sql)->queryAll();
        $messages = $this->generateMessages($modelMessage);
        $htmlImg = $this->getIcons($icons);
        $this->render("index", [
            'htmlImg' => $htmlImg,
            'messages' => $messages,
        ]);
    }
    
    public function actionHistory()
    {
        $count = $_POST['count'];
        
        $sql = 'SELECT * FROM (SELECT * FROM wbs_chat ORDER BY id DESC  LIMIT '. $count . ',' . ($count+20) . ') t ORDER BY id ASC';
        $modelMessage = Yii::app()->db->createCommand($sql)->queryAll();
        $messages = $this->generateMessages($modelMessage);
        
        echo $messages;
    }
    
    public function actionUsers()
    {
        $users = Profile::model()->findAll();
        $data = $this->getNames($users);
        echo json_encode($data);
    }
    
    public function actionEdit()
    {
        if (isset($_POST['pk']) && isset($_POST['value']) && (bool)Yii::app()->user->id) {
            $pk = $_POST['pk'];
            $value = $_POST['value'];
            $value = $this->validateText($value);
            $p = new CHtmlPurifier;
            $p->setOptions(array('HTML.Allowed'=>'br'));
            $value = $p->purify($value);
            WBSChat::model()->updateAll(['text' => $value], 'id=' . $pk);
            $value = $this->toLink($value);
            $value = $this->toSmile($value);
            $value = $this->getMentions($value);
            echo $value;
        } else {
            echo "Erorr of data editing";
        }
    }
    
    protected function getMentions($messages)
    {
        return preg_replace('/[\s]?(@[a-zA-z0-9]+)[\s]/', " <span class='mention'>$1</span> ", $messages);
    }
    
    protected function getNames($users)
    {
        $array = [];
        foreach ($users as $user) {
            $array[] = $user->firstname . '_' . $user->lastname;
        }
        
        return $array;
    }
    
    protected function generateMessages($messages)
    {
        $msg = '';
        $tmp = '';
        $msg.= '<div class="part-message">';
        foreach ($messages as $message) {
                $this->imageUrl = '';
                $profile = Profile::model()->find('user_id='. $message['user_id']);
                if(!empty($profile)) {
                    $user_name = $profile->firstname . " " . $profile->lastname;
                } else {
                    $user_name = 'user_'. $message['user_id'];
                }

                $span = ($message['user_id'] == Yii::app()->user->id)?
                                                                        "<div class='col-xs-12 col-sm-6'>
                                                                        <div class='pull-right edit-mes'>
                                                                            <i style='display:none' class='pull-right edit-icon glyphicon glyphicon-edit'></i>
                                                                        </div> 
                                                                        <span class='mes-time pull-right'>".
                                                                            date("F j, Y, g:i a", strtotime($message['created_at']))  .
                                                                        "</span></div>".
                                                                        "<div class='clearfix'></div>
                                                                        <div class='col-xs-12 mes-body'><span data-pk='$message[id]' class='message-edit editable-click'>:msg</span></div>"
                                                                    :
                                                                        "<span data-pk='$message[id]' class='message-default'>
                                                                            <div class='col-xs-12 col-sm-6'><span class='mes-time mes-time-other pull-right'>". date("F j, Y, g:i a", strtotime($message['created_at']))  . "</span></div>
                                                                            <div class='clearfix'></div>
                                                                            <div class='col-xs-12 mes-body'>:msg</div>
                                                                        </span>";


                $tmp = $this->toLink($message['text']);
                $tmp = $this->toSmile($tmp);
                $tmp = $this->getMentions($tmp);
                $photoUser = file_exists(Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_image" . DIRECTORY_SEPARATOR . User::model()->findByPk($message['user_id'])->guid. ".jpg")?Yii::app()->request->getBaseUrl("/") . "/uploads/profile_image/" . User::model()->findByPk($message['user_id'])->guid. ".jpg":Yii::app()->request->getBaseUrl("/") ."/img/default_user.jpg?cacheId=0";
                $span .= (!empty($this->imageUrl))?"<a target='_blank' href='$this->imageHost'><img class='img-responsive mes-attachment' width=300 src='$this->imageUrl'></a>":'';
                $respond = "<div class='mes'>
                                <div class='profile-size-sm profile-img-navbar'>
                                    <img id='user-account-image profile-size-sm' class='img-rounded' src='$photoUser' alt='32x32' data-src='holder.js/32x32' height='32' width='32'>
                                    <div class='profile-overlay-img profile-overlay-img-sm'></div>
                                </div>
                                <div class='col-xs-12 col-sm-5 no-padding'>".$user_name.":</div> ".str_replace(":msg", $tmp, $span) .
                            "</div>";
                $msg.=$respond;
        }
        $msg.= '</div>';
        return $msg;
    }
    
    public function validateText($msg)
    {
        $msg = str_replace("/[\r\n]{2,}/i", "\r\n", $msg);
        $msg = str_replace("/[\s]+/", "", $msg);
        $msg = trim($msg);
        $msg = nl2br($msg);
        $msg = rtrim(preg_replace('/((\<br \/>([\s]*)){2,})/', ' <br>', $msg), ' <br>');
        return $msg;
    }
    
    public function toSmile($data)
    {
        $smiles = WBSChatSmile::model()->findAll();
        foreach ($smiles as $smile) {
            $data = preg_replace('/'. quotemeta($smile->symbol) .'/', "<img src='$smile->link' data-symbol='$smile->symbol'>", $data);
        }
        
        return $data;
    }
    
    public function toLink($data)
    {
        $linkReplace = preg_replace('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', " <a target='_blank' style='color:blue;text-decoration:underline;' href='$0'> $0 </a> ", $data);
        $this->getImage($linkReplace);
        return $linkReplace;
    }

    protected function getImage($data)
    {
        require_once dirname(__DIR__) . "/lib/DOM/dom.php";
        $htmlText = str_get_html($data);
        $imageText = '';
        if(!empty($htmlText->find('a', 0))) {
            preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $htmlText->find('a', 0)->href, $matches);
            if(!empty($matches)) {
                if($this->ifImage($matches[0])) {
                    $this->imageUrl = $matches[0];
                    return;
                }
                $url = $matches[0];
                $htmlContent = file_get_html($url);
                $urlHost = parse_url($url)['scheme'] ."://".parse_url($url)['host'] . "";
                // Find all images
                if(isset($htmlContent->find('img', 1)->src)) {
                    preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $htmlContent->find('img', 1)->src, $matchesContent);
                    try {
                        if (empty($matchesContent)) {
                            if (@getimagesize($urlHost . DIRECTORY_SEPARATOR . $htmlContent->find('img', 1)->src)) {
                                $this->imageHost = $urlHost;
                                $this->imageUrl = $urlHost . DIRECTORY_SEPARATOR . $htmlContent->find('img', 1)->src;
                            }
                        } else {
                            if (@getimagesize($htmlContent->find('img', 1)->src)) {
                                $this->imageHost = $htmlContent->find('img', 1)->src;
                                $this->imageUrl = $htmlContent->find('img', 1)->src;
                            }
                        }
                    } catch (\Exception $e) {
                        //
                    }
                }
            }
        }
    }

    protected function ifImage($string)
    {
        preg_match('/(http|https|ftp|ftps)\:\/\/([\w\W]*).(png|jpg|gif|jpeg)/', $string, $matches);
        if(!empty($matches[0])){
            return true;
        }

        return false;
    }

    protected function getIcons($icons)
    {
        $img = '';
        foreach ($icons as $icon) {
            $img .= "<img data-symbol='$icon->symbol' class='icon' src='$icon->link' />";
        }
        
        return $img;
    }
}

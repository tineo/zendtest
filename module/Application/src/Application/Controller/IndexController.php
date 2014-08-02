<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MakiUser\Entity\User;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function listusersAction(){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
        $view = new ViewModel();
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $query = $em->createQuery('SELECT u FROM MakiUser\Entity\User u');
        $users = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $view->setVariable("users",$users);
        return $view;
        }else{
            return $this->redirect()->toRoute('zfcuser/login');
        }
    }
    public function viewuserAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
        $view =new ViewModel();
        $twitter_client = new \GuzzleHttp\Client(['base_url' => 'https://api.twitter.com/1.1/']);

        $oauth = new \GuzzleHttp\Subscriber\Oauth\Oauth1([
            'consumer_key'  => 'zZI8U5XsIHpgyzKAZNBQZP8NJ',
            'consumer_secret' => 'jfiTIhTbVC78iQzEV9L0LoCiwG3ycOb3fTDslj4WEnjXmGtbzR',
            'token'       => '28170519-0nEiJJwqywmJkYkfVL7PXyN10I0BvtCKhibfc00DP',
            'token_secret'  => 'dq2WFMj8P4zoaJ2rraNLocoJreKnYUHSuQRHsAx79sMPU'
        ]);

        $u = $this->params()->fromRoute('username');

        $twitter_client->getEmitter()->attach($oauth);
        $res = $twitter_client->get(
            'statuses/user_timeline.json',
            ['auth' => 'oauth',
                'query'=>["screen_name"=>$u,"count"=>10],
                'exceptions' => false
            ]
        );

        try{
            if($res->getStatusCode()!=400||$res->getStatusCode()!=500){
                $view->setVariables(array("username" => $u,"tweets"=>json_decode($res->getBody())) );
            }
        }catch (\GuzzleHttp\Exception\ClientException $e){

        }
        return $view;
        }else{return $this->redirect()->toRoute('zfcuser/login');}
    }
}

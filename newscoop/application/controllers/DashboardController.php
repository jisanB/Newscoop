<?php
/**
 * @package Newscoop
 * @copyright 2011 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

use Newscoop\Entity\User;

/**
 */
class DashboardController extends Zend_Controller_Action
{
    /** @var Newscoop\Services\UserService */
    private $service;

    /** @var Newscoop\Entity\User */
    private $user;

    public function init()
    {
        $GLOBALS['controller'] = $this;
        $this->_helper->layout->disableLayout();

        $this->service = $this->_helper->service('user');
        $this->user = $this->service->getCurrentUser();

        $this->_helper->contextSwitch()
            ->addActionContext('update-topics', 'json')
            ->initContext();
    }

    public function preDispatch()
    {
        if (empty($this->user)) {
            $this->_helper->redirector('index', 'auth');
        }
    }

    public function indexAction()
    {
        $form = $this->_helper->form('profile');
        $form->setMethod('POST');
        $form->setDefaults((array) $this->user->getView());

        $request = $this->getRequest();
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $values = $form->getValues();

            try {
                if (!empty($values['image'])) {
                    $imageInfo = array_pop($form->image->getFileInfo());
                    $values['image'] = $this->_helper->service('image')->save($imageInfo);
                }
                $this->service->save($values, $this->user);
                $this->_helper->redirector('index');
            } catch (\InvalidArgumentException $e) {
                switch ($e->getMessage()) {
                    case 'username_conflict':
                        $form->username->addError($this->view->translate("User with given username exists."));
                        break;

                    default:
                        $form->image->addError($e->getMessage());
                        break;
                }
            }
        }

        $this->view->user = new MetaUser($this->user);
        $this->view->form = $form;
    }

    public function newsletterAction()
    {
        $this->mailchimpGroups = $this->_helper->service('mailchimp')->getListGroups($this->getMailChimpListId());
        $this->userGroups = $this->_helper->service('mailchimp')->getUserGroups($this->user, $this->getMailChimpListId());
        $form->setMailChimpGroups($this->mailchimpGroups, $this->userGroups);

        $request = $this->getRequest();
        $this->saveMailchimp($values);
    }

    public function updateTopicsAction()
    {
        try {
            $this->_helper->service('user.topic')->updateTopics($this->user, $this->_getParam('topics', array()));
            $this->view->status = '0';
        } catch (Exception $e) {
            $this->view->status = -1;
            $this->view->message = $e->getMessage();
        }
    }

    public function followTopicAction()
    {
        $service = $this->_helper->service('user.topic');
        $topic = $service->findTopic($this->_getParam('topic'));
        if (!$topic) {
            $this->_helper->flashMessenger(array('error', "No topic to follow"));
            $this->_helper->redirector('index', 'index', 'default');
        }

        $service = $this->_helper->service('user.topic');
        $service->followTopic($this->user, $topic);

        $this->_helper->flashMessenger("Topic added to followed");
        $this->_helper->redirector->gotoUrl($_SERVER['HTTP_REFERER']);
    }

    public function addTopicByNameAction()
    {
        if (!$this->_getParam('topic')) {
            $this->_helper->json(array());
            return;
        }

        $topic = $this->_helper->service('em')->getRepository('Newscoop\Entity\Topic')->findOneBy(array(
            'name' => $this->_getParam('topic'),
        ));

        if ($topic !== null) {
            $this->_helper->service('user.topic')->followTopic($this->user, $topic);
        }

        $this->_helper->json(array());
    }

    public function saveNewsletterAction()
    {
        $form = $this->getMailChimpForm();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $this->_helper->service('mailchimp')->subscribe($this->user, $this->getMailChimpListId(), $form->getValues());
        }

        $url = $this->view->url(array('action' => 'index')) . '#meine-newsletter';

        $this->_helper->redirector->goToUrl($url);
    }

    /**
     * Get mailchimp list id
     *
     * @return string
     */
    private function getMailChimpListId()
    {
        $mailchimpOptions = $this->getInvokeArg('bootstrap')->getOption('mailchimp');
        return $mailchimpOptions['list_id'];
    }

    /**
     * Save mailchimp info
     *
     * @param array $values
     * @return void
     */
    private function saveMailchimp(array $values)
    {
        $this->_helper->service('mailchimp')->subscribe($this->user, $this->getMailChimpListId(), $values['mailchimp']);
    }
}

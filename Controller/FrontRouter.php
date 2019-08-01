<?php
    
    namespace WebGate\CustomerStatus\Controller;
    
    use Magento\Customer\Model\Session;
    use Magento\Framework\App\Action\Forward;
    use Magento\Framework\App\Action\Redirect;
    use Magento\Framework\App\ActionFactory;
    use Magento\Framework\App\ActionInterface;
    use Magento\Framework\App\RequestInterface;
    use Magento\Framework\App\ResponseInterface;
    use Magento\Framework\App\RouterInterface;
    use Magento\Framework\App\State;
    use Magento\Framework\DataObject;
    use Magento\Framework\Event\ManagerInterface;
    use Magento\Framework\Url;
    use Magento\Framework\UrlInterface;
    use Magento\Store\Model\StoreManagerInterface;

    class FrontRouter implements RouterInterface
    {
        /**
         * @var ActionFactory
         */
        protected $actionFactory;
        
        /**
         * Event manager
         *
         * @var ManagerInterface
         */
        protected $_eventManager;
        
        /**
         * Store manager
         *
         * @var StoreManagerInterface
         */
        protected $_storeManager;
        
        /**
         * Config primary
         *
         * @var State
         */
        protected $_appState;
        
        /**
         * Url
         *
         * @var UrlInterface
         */
        protected $_url;
        
        /**
         * Response
         *
         * @var ResponseInterface
         */
        protected $_response;
        /**
         * @var Session
         */
        private $customerSession;
    
        /**
         * @param ActionFactory       $actionFactory
         * @param ManagerInterface  $eventManager
         * @param UrlInterface            $url
         * @param StoreManagerInterface $storeManager
         * @param ResponseInterface   $response
         */
        public function __construct(
            ActionFactory $actionFactory ,
            ManagerInterface $eventManager ,
            UrlInterface $url ,
            StoreManagerInterface $storeManager ,
            ResponseInterface $response,
            Session $customerSession
        )
        {
            $this->actionFactory = $actionFactory;
            $this->_eventManager = $eventManager;
            $this->_url = $url;
            $this->_storeManager = $storeManager;
            $this->_response = $response;
            $this->customerSession = $customerSession;
        }
        
        /**
         * Validate and  modify request
         *
         * @param RequestInterface $request
         *
         * @return ActionInterface|null
         */
        public function match(RequestInterface $request)
        {
    
            $identifier = trim($request->getPathInfo() , '/');
            
            $condition = new DataObject([ 'identifier' => $identifier , 'continue' => true ]);
            
            $identifier = $condition->getIdentifier();
            
            
            if($condition->getRedirectUrl())
            {
                $this->_response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->actionFactory->create(Redirect::class);
            }
            
            if(!$this->customerSession->isLoggedIn())
            {
                $this->_response->setRedirect('/customer/account/login');
                $request->setDispatched(true);
                return $this->actionFactory->create(Redirect::class);
            }
            
            if(!$condition->getContinue())
            {
                return null;
            }
            
            $all_params = explode("/" , $identifier);
            
            if($all_params[0] == 'customer-status')
            {
                $condition->setContinue(false);
            }
    
            if(($condition->getContinue()) == false && $all_params[1] == 'edit')
            {
                $request
                    ->setModuleName('customer-status')
                    ->setControllerName('Customer')
                    ->setActionName('Edit');
            }
    
            if(($condition->getContinue()) == false && $all_params[1] == 'update')
            {
                $request
                    ->setModuleName('customer-status')
                    ->setControllerName('Customer')
                    ->setActionName('Update');
            }
    
            if(($condition->getContinue()) == false)
            {
                $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS , $identifier);
                return $this->actionFactory->create(Forward::class);
            }
            
            return null;
        }
        
    }
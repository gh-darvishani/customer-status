<?php
    
    namespace WebGate\CustomerStatus\Controller\Customer;
    
    use Magento\Customer\Api\CustomerRepositoryInterface;
    use Magento\Customer\Model\CustomerFactory;
    use Magento\Customer\Model\Session;
    use Magento\Framework\App\Action\Action;
    use Magento\Framework\App\Action\Context;
    use Magento\Framework\App\ResponseInterface;
    
    class Update extends Action
    {
        /**
         * @var CustomerFactory
         */
        private $customer;
        /**
         * @var Session
         */
        private $customerSession;
        /**
         * @var CustomerRepositoryInterface
         */
        private $customerRepository;
        
        
        /**     * @param Context $context */
        public function __construct(
            Context $context ,
            CustomerRepositoryInterface $customerRepository ,
            Session $customerSession
        )
        {
            parent::__construct($context);
            $this->customerSession = $customerSession;
            $this->customerRepository = $customerRepository;
        }
        
        /**
         * Blog Index, shows a list of recent blog posts.
         *
         * @return ResponseInterface
         */
        public function execute()
        {
            $customer = $this->customerRepository->getById($this->customerSession->getId());
            
            $customer->setCustomAttribute(
                'customer_status' ,
                $this->getRequest()->getParam('status')
            );
    
            $this->customerRepository->save($customer);
            
            $this->messageManager->addSuccessMessage(__('You saved the Status.'));
            
            return $this->_redirect('customer-status/edit');
        }
    }

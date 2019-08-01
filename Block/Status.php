<?php
    
    namespace WebGate\CustomerStatus\Block;
    
    use Magento\Customer\Api\CustomerRepositoryInterface;
    use Magento\Customer\Model\Session;
    use Magento\Framework\Exception\LocalizedException;
    use Magento\Framework\Exception\NoSuchEntityException;
    use Magento\Framework\View\Element\Template;
    
    class Status extends Template
    {
        /**
         * @var CustomerRepositoryInterface
         */
        private $customerRepository;
        /**
         * @var Session
         */
        private $customerSession;
        
        public function __construct(
            CustomerRepositoryInterface $customerRepository ,
            Session $customerSession ,
            Template\Context $context ,
            array $data = []
        )
        {
            parent::__construct($context , $data);
            $this->customerRepository = $customerRepository;
            $this->customerSession = $customerSession;
        }
        
        /**
         * @return bool
         */
        public function isLoggedIn()
        {
            return $this->customerSession->isLoggedIn();
        }
        
        /**
         * @return string
         * @throws LocalizedException
         * @throws NoSuchEntityException
         */
        public function getStatus()
        {
            $customer = $this->customerRepository->getById($this->customerSession->getId());
            $status = $customer->getCustomAttribute('customer_status');

            return is_null($status) ? null : $status->getValue();
        }

        public function getCacheLifetime()
        {
            return null;
        }
    }
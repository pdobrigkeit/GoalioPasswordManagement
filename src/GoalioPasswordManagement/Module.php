<?php
namespace GoalioPasswordManagement;

use Zend\Loader\StandardAutoloader;
use Zend\Loader\AutoloaderFactory;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Hydrator\ClassMethods;

class Module {

    public function getAutoloaderConfig() {
        return array(
            AutoloaderFactory::STANDARD_AUTOLOADER => array(
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'invokables' => array(
                'GoalioPasswordManagement\Authentication\Adapter\LoginAttempt'        => 'GoalioPasswordManagement\Authentication\Adapter\LoginAttempt',
                'GoalioPasswordManagement\Authentication\Adapter\LoginChangePassword' => 'GoalioPasswordManagement\Authentication\Adapter\LoginChangePassword',
            ),

            'factories' => array(

                'goaliopasswordmanagement_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['goaliopasswordmanagement']) ? $config['goaliopasswordmanagement'] : array());
                },

                'goaliopasswordmanagement_loginattempt_mapper' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $passwordOptions = $sm->get('goaliopasswordmanagement_module_options');
                    $mapper = new Mapper\LoginAttempt();
                    $mapper->setDbAdapter($sm->get('zfcuser_zend_db_adapter'));
                    $entityClass = $passwordOptions->getLoginAttemptsEntityClass();
                    $mapper->setEntityPrototype(new $entityClass);
                    $mapper->setHydrator(new ClassMethods());
                    return $mapper;
                },
            ),
        );
    }

    public function onBootstrap(MvcEvent $event) {
//        $eventManager = $event->getApplication()->getEventManager();
//        $eventManager->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $event) {
//            $routeMatch = $event->getRouteMatch();
//
//
//            $routeMatch->setParam('controller', 'foo');
//            $routeMatch->setParam('action', 'bar');
//
//
//        }, -100);


    }
}


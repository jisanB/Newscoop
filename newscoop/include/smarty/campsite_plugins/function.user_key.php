<?php

function smarty_function_user_key($p_params, &$p_smarty)
{
    global $controller;
    
    $userService = $controller->getHelper('service')->getService('user');
    $userSubscriptionService = $controller->getHelper('service')->getService('user_subscription');
    
    $user = $userService->getCurrentUser();
    
    if ($user) {
        $userSubscriptionKey = $userSubscriptionService->createKey($user);
        $userSubscriptionService->setKey($user, $userSubscriptionKey);
        
        return($userSubscriptionKey);
    }
    else {
        return('');
    }
}

?>
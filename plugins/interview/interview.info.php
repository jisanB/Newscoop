<?php
$info = array( 
    'name' => 'interview',
    'version' => '0.1',
    'label' => 'Interview',
    'description' => 'This plugin provides functionality to perform online interviews.',  
    'menu' => array(
        'name' => 'interview',
        'label' => 'Interview',
        'icon' => 'interview.png',
        'sub' => array(
            array(
                'permission' => 'plugin_interview_admin',
                'path' => "interview/admin/index.php",
                'label' => 'Administrate Interviews',
                'icon' => 'interview.png',
            ),
            array(
                'permission' => 'plugin_interview_moderator',
                'path' => "interview/moderator/index.php",
                'label' => 'Moderate Interviews',
                'icon' => 'interview.png',
            ),
            array(
                'permission' => 'plugin_interview_guest',
                'path' => "interview/guest/index.php",
                'label' => 'Interview Guest',
                'icon' => 'interview.png',
            ),
        ),
    ),
    'userDefaultConfig' => array(
        'plugin_manager' => 'N',
        'plugin_interview_notify'=>'N',
        'plugin_interview_admin'=>'N',
        'plugin_interview_moderator'=>'N',
        'plugin_interview_guest'=>'N',
    ),
    'permissions' => array(
        'plugin_interview_notify' => 'User recives notification about new interviews',
        'plugin_interview_admin' => 'User is interview admin',
        'plugin_interview_moderator' => 'User is interview moderator',
        'plugin_interview_guest' => 'User is interview guest'
    ),
    'no_menu_scripts' => array(
    	'/interview/admin/edit.php',
    	'/interview/admin/edit_item.php',
    	'/interview/moderator/edit.php',
    	'/interview/moderator/edit_item.php',
    	'/interview/guest/edit.php',
    	'/interview/guest/edit_item.php',
    	'/interview/admin/invitation.php'
    ),
    'template_engine' => array(
        'objecttypes' => array(
            array('interview' => array('class' => 'Interview')),
            array('interviewitem' => array('class' => 'InterviewItem')),
        ),
        'listobjects' => array(
            array('interviews' => array('class' => 'Interviews', 'list' => 'interviews')),
            array('interviewitems' => array('class' => 'InterviewItems', 'list' => 'interviewitems')),
        ),
        'init' => 'plugin_interview_init'
    ),
    'install' => 'plugin_interview_install',
    'enable'  => '',
    'update'  => '',
    'disable' => '',
    'uninstall' => 'plugin_interview_uninstall'
);


if (!defined('PLUGIN_INTERVIEW_FUNCTIONS')) {
    define('PLUGIN_INTERVIEW_FUNCTIONS', true);
     
    function plugin_interview_install()
    {
        global $LiveUserAdmin, $g_documentRoot;
        
        $LiveUserAdmin->addRight(array('area_id' => 0, 'right_define_name' => 'plugin_interview_notify', 'has_implied' => 1));
        $LiveUserAdmin->addRight(array('area_id' => 0, 'right_define_name' => 'plugin_interview_guest', 'has_implied' => 1));
        $LiveUserAdmin->addRight(array('area_id' => 0, 'right_define_name' => 'plugin_interview_moderator', 'has_implied' => 1));
        $LiveUserAdmin->addRight(array('area_id' => 0, 'right_define_name' => 'plugin_interview_admin', 'has_implied' => 1));
        
        require_once($g_documentRoot.'/install/classes/CampInstallationBase.php');
        CampInstallationBaseHelper::copyFiles($g_documentRoot.DIR_SEP.PLUGINS_DIR.'/interview/css', $g_documentRoot.'/css');
        CampInstallationBaseHelper::copyFiles($g_documentRoot.DIR_SEP.PLUGINS_DIR.'/interview/javascript', $g_documentRoot.'/javascript');
        $GLOBALS['g_db'] =& $GLOBALS['g_ado_db'];
        $errors = CampInstallationBaseHelper::ImportDB($g_documentRoot.DIR_SEP.PLUGINS_DIR.DIR_SEP.'interview/install/sql/plugin_interview.sql', &$error_queries);
        unset($GLOBALS['g_db']);       
    }
    
    function plugin_interview_uninstall()
    {
        global $LiveUserAdmin, $g_documentRoot, $g_ado_db;
        
        foreach (array('plugin_interview_notify', 'plugin_interview_guest', 'plugin_interview_moderator', 'plugin_interview_admin') as $right_def_name) {
            $filter = array(
                "fields" => array("right_id"),
                "filters" => array("right_define_name" => $right_def_name)
            );
            $rights = $LiveUserAdmin->getRights($filter);
            if(!empty($rights)) {
                $LiveUserAdmin->removeRight(array('right_id' => $rights[0]['right_id']));
            }
        }
        
        $g_ado_db->execute('DROP TABLE plugin_interview_interviews');
        $g_ado_db->execute('DROP TABLE plugin_interview_items');
        
        system('rm -rf '.$g_documentRoot.DIR_SEP.PLUGINS_DIR.'/interview/');    
    }
    
    function plugin_interview_init(&$p_context)
    {      
        $interview_id = Input::Get("f_interview_id", "int");
        $interviewitem_id = Input::Get("f_interviewitem_id", "int");

        $p_context->interviewitem = new MetaInterviewItem($interviewitem_id, $interview_id);

        if ($p_context->interviewitem->defined) {
            $p_context->interview = new MetaInterview($context->interviewitem->interview_id);
        } else {
            $p_context->interview = new MetaInterview($interview_id);
        }
    }
}
?>
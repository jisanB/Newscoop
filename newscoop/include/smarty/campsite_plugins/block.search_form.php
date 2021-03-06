<?php
/**
 * Campsite customized Smarty plugin
 * @package Campsite
 */

/**
 * Campsite search_form block plugin
 *
 * Type:     block
 * Name:     search_form
 * Purpose:  Provides a...
 *
 * @param string $p_params
 *
 * @param string $p_content
 *
 * @param string $p_smarty
 *
 *
 * @return string $html
 */
function smarty_block_search_form($p_params, $p_content, $p_smarty)
{
    if (!isset($p_content)) {
        return '';
    }

    $p_smarty->smarty->loadPlugin('smarty_shared_escape_special_chars');
    $campsite = $p_smarty->getTemplateVars('gimme');

    $url = $campsite->url;
    $url->uri_parameter = "";
    $template = null;
    if (isset($p_params['template'])) {
        $themePath = $campsite->issue->defined() ? $campsite->issue->theme_path : $campsite->publication->theme_path;
        $template = new MetaTemplate($p_params['template'], $themePath);
        if (!$template->defined()) {
            CampTemplate::singleton()->trigger_error('invalid template "' . $p_params['template']
            . '" specified in the search form');
            return false;
        }
    } elseif (is_numeric($url->get_parameter('tpl'))) {
        $template = $campsite->default_template;
    }
    if (!isset($p_params['submit_button'])) {
        $p_params['submit_button'] = 'Submit';
    }
    if (!isset($p_params['html_code']) || empty($p_params['html_code'])) {
        $p_params['html_code'] = '';
    }
    if (!isset($p_params['button_html_code']) || empty($p_params['button_html_code'])) {
        $p_params['button_html_code'] = '';
    }

    $searchListIdPrefix = $campsite->list_id_prefix('SearchResultsList');
    if (isset($template)) {
        $url->uri_parameter = "template " . str_replace(' ', "\\ ", $template->name);
    }
    $html = "<form name=\"search_articles\" action=\"" . $url->uri_path . "\" method=\"post\" "
    .$p_params['html_code'].">\n";
    if (isset($template)) {
        $html .= "<input type=\"hidden\" name=\"tpl\" value=\"" . $template->identifier . "\" />\n";
    }
    foreach ($campsite->url->form_parameters as $param) {
        if (is_array($param['value'])) {
            continue;
        }

        if (strncasecmp($param['name'], $searchListIdPrefix, strlen($searchListIdPrefix)) == 0) {
            continue;
        }

        if ($param['name'] == 'tpl') {
            continue;
        }

        $html .= '<input type="hidden" name="'.$param['name']
        .'" value="'.htmlentities($param['value'])."\" />\n";
    }
    $html .= $p_content;
    $html .= "<input type=\"submit\" name=\"f_search_articles\" value=\""
    .smarty_function_escape_special_chars($p_params['submit_button'])
    ."\" ".$p_params['button_html_code']." />\n</form>\n";

    return $html;
} // fn smarty_block_search_form

?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/** 
 * Partial lets include  additional templates
 * 
 * @param string $template
 * @param array $data
 * @return string
 */
function partial($template, $data = array()){
  $CI = &get_instance();
  return $CI->load->view($template, $data, true);
}
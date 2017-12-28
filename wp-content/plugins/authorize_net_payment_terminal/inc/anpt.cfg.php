<?php
$tera_plugin_name='anpt-payment-terminal';

$anpt_cfg_arr = array(
	/* terminal =>( LIVE ( field => value ) , TEST ( field => value ) )*/
	'Authorize'=>array(
		'LIVE'=>array(
			'API Login ID'=>'your_LIVE_login_id',
			'API Transaction Key'=>'your_LIVE_transaction_key'
		),
		'TEST'=>array(
			'API Login ID'=>'your_SANDBOX_login_id',
			'API Transaction Key'=>'your_SANDBOX_transaction_key'
		)
	)
);

function anptCRUD($payment_terminals,$action){
	$newarr=array();
	$anpt_test = get_option('anpt_test');
	foreach($payment_terminals as $terminal => $details)
	{
		$terminal = strtoupper(str_replace(array(',',' ','.','\''), '_',$terminal));
		$wpdbVars = (is_string(get_option('anpt_details_'.$terminal)))?unserialize(get_option('anpt_details_'.$terminal)):get_option('anpt_details_'.$terminal);
		$postVars = array();
		$pseudocase = '';
		foreach($details as $case => $fields)
		{
			if($case=='TEST'||$case == 'LIVE')
			{
				$case_array = array();
				foreach($fields as $field => $value)
				{					
					$post_name = strtoupper(str_replace(array(',',' ','.','\''), '_', $field));
					$input_id = strtolower(str_replace(array(' ','"','\'','.'), '_', $terminal."_".$case."_".$field));
					if(isset($_POST['anpt_submit_settings']) && $_POST['anpt_submit_settings'] == 'yes' && isset($_POST["{$input_id}"]))
					{
						$post_value = stripslashes_deep($_POST["{$input_id}"]);
					}
					else
					if(isset($_POST['anpt_submit_settings']) && $_POST['anpt_submit_settings'] == 'yes' && isanptfilter($input_id) !=null && isset($_FILES[isanptfilter($input_id)]) && strlen($_FILES[isanptfilter($input_id)]['name']) > 0)
					{
						$input_id = isanptfilter($input_id);
						if(strtolower(end(explode('.', $_FILES["{$input_id}"]['name']))) == 'pem')
						{
							$uploads = wp_upload_dir();
						 	$path    = $uploads['path'];
						 	if(move_uploaded_file($_FILES["{$input_id}"]['tmp_name'], $path."/".$_FILES["{$input_id}"]['name']))
							{
						 		$post_name = strtoupper(str_replace(array(',',' ','.','\''), '_', $value));
						 		$post_value=  $path."/".$_FILES["{$input_id}"]['name'];
						 		if(isset($case_array[$post_name]))
									unset($case_array[$post_name]);
								$case_array[$post_name] = $post_value;
								$newarr[$terminal][$case][$value] = $post_value;
								$newarr[$terminal][$case][$field] = 'fgm';
								continue;
							}
							else
							{
								$newarr['CUSTOM_ERROR'][$terminal][] = "Please make sure that upload directory '{$path}' has enough permissions to upload the Certificate.";
							}
						}
						else
						{
							$newarr['CUSTOM_ERROR'][$terminal][] = "The Certificat File for {$terminal} terminal must be .pem Type!-".$_FILES["{$input_id}"]['name'];
						}
					}	 	 	
					else
					if($action == "install" || $action == "uninstall")
						$post_value = $value;	
					else
						$post_value = $wpdbVars[strtolower($case)][$post_name];
	
					$case_array[$post_name] = $post_value;
					$newarr[$terminal][$case][$field] = $post_value;	
				}
			}
			if($case == "TIP")
			{
				$newarr[$terminal][$case]=$fields;
				continue;
			}
			$case = strtolower($case);
			$postVars[$case] = $case_array;
		}
		if($action == "install" || (isset($_POST['anpt_submit_settings']) && $_POST['anpt_submit_settings'] == 'yes'))
		{ 
			$postVars = serialize($postVars);
			update_option('anpt_details_'.$terminal,$postVars);	
		}
		else
		if($action == "uninstall")
		{
			delete_option('anpt_details_'.$terminal);	
		}
	}
	unset($postVars);unset($case_array); 
	return $newarr;
}

function displaySelectedCond($i,$anpt_processor){
	return
	(($anpt_processor==$i && strlen($anpt_processor) > 0)
	 || 
	($i==1 && strlen($anpt_processor) < 1 && !isset($_GET['active_terminal']))
	 || 
	(isset($_GET['active_terminal']) && $i==$_GET['active_terminal'])
	 ? ' selected' : '');
}
function isanptfilter($field_name){
	if(strpos($field_name, '[filter]') === false)
		return null;
	else
		return str_replace('[filter]','', $field_name);
}	
function anptDisplayCustomErrors($array){
	foreach($array as $terminal=>$error)
	{
		foreach($error as $key=>$error_text)
		{
			echo "ERROR :".$error_text."<br />";
		}
	}
}
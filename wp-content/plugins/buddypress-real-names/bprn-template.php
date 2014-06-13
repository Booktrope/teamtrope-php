<?php




function bprn_fullname($component='display_name',$user_id=false,$fallback=false){
    echo bprn_get_fullname($component,$user_id,$fallback);
}

function bprn_get_fullname($component='display_name',$user_id=false,$fallback=false){
    
    if(!$user_id) $user_id = bp_displayed_user_id();

    if(!$fallback){
        $fallback = bp_get_profile_field_data( array('field' => 1,'user_id' => $user_id) );
    }

    $rule = bprn()->options['components'][$component]['rule'];

    $bprn_fields_ids=bprn()->get_rule_fields_ids($rule);

    if(!$bprn_fields_ids) return $fallback;
    
    //get words starting by 'field-'
    preg_match_all('(field-\d+)', $rule, $fields);
    $fields = $fields[0];
    
    $has_any_value=false;
    
    foreach ((array)$fields as $key=>$field){
        
        $field_arr = explode('-',$field);
        $field_ids[$key] = $field_arr[1];
        
        $field_id = $field_ids[$key];
        
        $field_value = bprn_get_profile_field_value($field_id,$user_id,$component);
        
        $patterns[$key]='/field-'.$field_ids[$key].'/';
        
        if($field_value){
        	
        	$replacements[$key]=$field_value;
        	$has_any_value=true;
        }else{
        	$replacements[$key]='';
        }

    }
    
    if($has_any_value){
        $fullname = preg_replace($patterns, $replacements, $rule);
        $fullname = trim($fullname);
    }else{
        return $fallback;
    }
    


    return apply_filters('bprn_get_fullname',$fullname,$component,$user_id,$field_ids,$patterns,$replacements,$fallback);

}

function bprn_get_profile_field_value($field_id,$user_id=false,$component='display_name',$separator=' | '){
	if(!$user_id) $user_id = bp_displayed_user_id();
        
	$field_value = bp_get_profile_field_data( array('field' => $field_id,'user_id' => $user_id));
        
        //transform arrays
        if (is_array($field_value)){
            if (count($field_value)==1){
                $field_value = $field_value[0];
            }else{
                $field_value=implode(' | ',$field_value);
            }
        }
        
	return apply_filters('bprn_get_profile_field_value',$field_value,$field_id,$user_id,$component,$separator=' | ');
}


    
function bprn_is_base_field($field=false){
    
    if (!$field){
        global $field;
    }

    if($field->id==1)return true;
    return false;
}
    
?>

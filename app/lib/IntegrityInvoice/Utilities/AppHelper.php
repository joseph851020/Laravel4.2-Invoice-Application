<?php namespace IntegrityInvoice\Utilities;

use Currency;
use Preference;
use DateTimeZone;
use DateTime;
use Symfony\Component\Intl\Intl;

class AppHelper{
	
	public static function two_decimal($number = 0)
	{
		return number_format((float)$number, 2, '.', '');
	}
	
	// Encrypt Algorithm
	public static function encrypt($string, $key = "*2018OUFT0778+)8_")
	{
		$key = $key.$key;
		
		$iv = mcrypt_create_iv(
	    mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC),
	    MCRYPT_DEV_URANDOM
		);
		
		return $encrypted = base64_encode(
		    $iv .
		    mcrypt_encrypt(
		        MCRYPT_RIJNDAEL_256,
		        hash('sha256', $key, true),
		        $string,
		        MCRYPT_MODE_CBC,
		        $iv
		    )
		);
	}
	
	
	// Decrypt Algorithm
	public static function decrypt($encrypted, $key = "*2018OUFT0778+)8_")
	{
		$key = $key.$key;
		
		$data = base64_decode($encrypted);
		$iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));
		
		return $decrypted = rtrim(
		    mcrypt_decrypt(
		        MCRYPT_RIJNDAEL_256,
		        hash('sha256', $key, true),
		        substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)),
		        MCRYPT_MODE_CBC,
		        $iv
		    ),
		    "\0"
		);
	}
	
	public static function alphaID($in, $to_num = false, $pad_up = false, $pass_key = null)
	{
	  $out   =   '';
	  $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	  $base  = strlen($index);
	
	  if ($pass_key !== null) {
	    // Although this function's purpose is to just make the
	    // ID short - and not so much secure,
	    // with this patch by Simon Franz (http://blog.snaky.org/)
	    // you can optionally supply a password to make it harder
	    // to calculate the corresponding numeric ID
	
	    for ($n = 0; $n < strlen($index); $n++) {
	      $i[] = substr($index, $n, 1);
	    }
	
	    $pass_hash = hash('sha256',$pass_key);
	    $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);
	
	    for ($n = 0; $n < strlen($index); $n++) {
	      $p[] =  substr($pass_hash, $n, 1);
	    }
	
	    array_multisort($p, SORT_DESC, $i);
	    $index = implode($i);
	  }
	
	  if ($to_num) {
	    // Digital number  <<--  alphabet letter code
	    $len = strlen($in) - 1;
	
	    for ($t = $len; $t >= 0; $t--) {
	      $bcp = bcpow($base, $len - $t);
	      $out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
	    }
	
	    if (is_numeric($pad_up)) {
	      $pad_up--;
	
	      if ($pad_up > 0) {
	        $out -= pow($base, $pad_up);
	      }
	    }
	  } else {
	    // Digital number  -->>  alphabet letter code
	    if (is_numeric($pad_up)) {
	      $pad_up--;
	
	      if ($pad_up > 0) {
	        $in += pow($base, $pad_up);
	      }
	    }
	
	    for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
	      $bcp = bcpow($base, $t);
	      $a   = floor($in / $bcp) % $base;
	      $out = $out . substr($index, $a, 1);
	      $in  = $in - ($a * $bcp);
	    }
	  }
	
	  return $out;
	}


	public static function convert_currency($from_Currency, $to_Currency, $amount) {
 
			$amount = urlencode($amount);
			$from_Currency = urlencode($from_Currency);
			$to_Currency = urlencode($to_Currency);
			 
		    $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
		 
			$ch = curl_init();
			$timeout = 0;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			 
			curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$rawdata = curl_exec($ch);
			curl_close($ch);
			$data = explode('bld>', $rawdata);
			
			if(!isset($data[1]) || !isset($data[0]))
			{
				return ;
			}
			
			$data = explode($to_Currency, $data[1]);
			return round($data[0], 5);
	 }
	 
	 
	
	public static function escapeRegex($character){
		
		if($character == '_'){
			return '\\_';
		}elseif($character == '$'){
			return '\\$';
		}elseif($character == '{'){
			return '\\{';
		}elseif($character == '}'){
			return '\\}';
		}elseif($character == '#'){
			return '\\#';
		}elseif($character == '%'){
			return '\\%';
		}elseif($character == '&'){
			return '\\&';
		}else{
			return $character;
		}
		
	}
	
	
	 public static function getTimeZonesList($selected = ""){
  		
		$selected = $selected;
		
		$regions = array(
			'Africa' => DateTimeZone::AFRICA,
			'America' => DateTimeZone::AMERICA,
			'Antarctica' => DateTimeZone::ANTARCTICA,
			'Asia' => DateTimeZone::ASIA,
			'Atlantic' => DateTimeZone::ATLANTIC,
			'Europe' => DateTimeZone::EUROPE,
			'Indian' => DateTimeZone::INDIAN,
			'Pacific' => DateTimeZone::PACIFIC
		);
		 
		$timezones = array();
		foreach ($regions as $value => $mask)
		{
			$zones = DateTimeZone::listIdentifiers($mask);
			foreach($zones as $timezone)
			{
			// Lets sample the time there right now
			$time = new DateTime(NULL, new DateTimeZone($timezone));
			 
			// Us dumb Americans can't handle millitary time
			$ampm = $time->format('H') > 12 ? ' ('. $time->format('g:i a'). ')' : '';
			 
			// Remove region name and add a sample time
			$timezones[$value][$timezone] = substr($timezone, strlen($value) + 1) . ' - ' . $time->format('H:i') . $ampm;
			}
		}
		
		// View
        $string = '<select name="time_zone" id="time_zone" class="sel">';
		$string .= '<option value="">- select -</option>';
		foreach($timezones as $region => $list)
		{
			$string .= '<optgroup label="' . $region . '">' . "\n";
			foreach($list as $timezone => $value)
			{
				$selected_tag = "";
				if($selected == $timezone){
					$selected_tag = 'selected="selected"';
				}
				$string .= '<option '. $selected_tag .' value="' . $timezone . '">' . $value . '</option>' . "\n";
			}
			$string .=  '<optgroup>' . "\n";
		}
		print $string .=  '</select>';
		 
	}
	
	
	
	// Convert array to object
	public static function arrayToObject($array) {
	    if(!is_array($array)) {
	        return $array;
	    }
	    
	    $object = new stdClass();
	    if (is_array($array) && count($array) > 0) {
	      foreach ($array as $name=>$value) {
	         $name = strtolower(trim($name));
	         if (!empty($name)) {
	            $object->$name = arrayToObject($value);
	         }
	      }
	      return $object; 
	    }
	    else {
	      return FALSE;
	    }
	}
	
	
	/* CONVERT yyyy-mm-dd to dd-mm-yyyy */
	public static function convert_to_ddmmyyyy($today, $format="dd/mm/yyyy"){
		
	  $format = trim($format);
	  
	  //UK Format
	  if($format == "dd/mm/yyyy"){
	  	  $date_array = explode("-",$today); // split the array
		  $var_day = $date_array[2]; //day seqment
		  $var_month = $date_array[1]; //month segment
		  $var_year = $date_array[0]; //year segment
		  return $new_date_format = "$var_day-$var_month-$var_year"; // join them together
	  }
	  
	  // US format
	   if($format == "mm/dd/yyyy"){
	  	  $date_array = explode("-",$today); // split the array
		  $var_day = $date_array[2]; //day seqment
		  $var_month = $date_array[1]; //month segment
		  $var_year = $date_array[0]; //year segment
		  return $new_date_format = "$var_month-$var_day-$var_year"; // join them together
	  }
	
	} // END
	
	/* CONVERT yyyy-mm-dd to dd-mm-yyyy */
	public static function convert_to_mysql_yyyymmdd($today, $format="dd/mm/yyyy"){
		
	   $format = trim($format);
	  
	  //UK Format
	  if($format == "dd/mm/yyyy"){
	  	  $date_array = explode("/",$today); // split the array
		  $var_day = $date_array[0]; //day seqment
		  $var_month = $date_array[1]; //month segment
		  $var_year = $date_array[2]; //year segment
		 return $new_date_format = "$var_year-$var_month-$var_day"; // join them together
	  }
	  
	  // US format
	   if($format == "mm/dd/yyyy"){
	  	  $date_array = explode("/",$today); // split the array
		  $var_day = $date_array[1]; //day seqment
		  $var_month = $date_array[0]; //month segment
		  $var_year = $date_array[2]; //year segment
		  return $new_date_format = "$var_year-$var_month-$var_day"; // join them together
	  }
	   
	  /*
	  $date_array = explode("/",$today); // split the array
	  $var_day = $date_array[0]; //day seqment
	  $var_month = $date_array[1]; //month segment
	  $var_year = $date_array[2]; //year segment
	  return $new_date_format = "$var_year-$var_month-$var_day"; // join them together
	  
	  */
	}
	
	
	
	/*
		@desc: strips zero from dates when use strftime function
		@param : String $marked_string
		@return: String $cleaned string
	*/
	public static function strip_zero_from_date($marked_string=""){
		// first remove the marked zeros
		$no_zeros = str_replace('*0','', $marked_string);
		// then remove any remaining marks
		$cleaned_string = str_replace('*','',$no_zeros);
		return $cleaned_string;
	}
	
	
	// later we can move this into the database class if needed
	public static function datetime_to_text($datetime="", $format="dd/mm/yyyy"){
		$unixdatetime = strtotime($datetime);
		$format = trim($format);
		// UK Format
		if($format == "dd/mm/yyyy"){
			return strftime("%d %B %Y at %I:%M %p", $unixdatetime);
		}
		// US format
		if($format == "mm/dd/yyyy"){
			return strftime("%B %d %Y at %I:%M %p", $unixdatetime);
		}
	
	}
	
	// later we can move this into the database class if needed
	public static function date_to_text($datetime="", $format="dd/mm/yyyy"){
		$unixdatetime = strtotime($datetime);
		$format = trim($format);
		// UK Format
		if($format == "dd/mm/yyyy"){
			return strftime("%d/%m/%Y", $unixdatetime);
		}
		// US format
		if($format == "mm/dd/yyyy"){
			return strftime("%m/%d/%Y", $unixdatetime);
		}
		
	}
	
	
	/* Recurring frequency */
	public static function recurring_frequency($num){
		$frequency;
	
		switch ($num) {
				
			case 1:
				$frequency = "Weekly";
				break;
				
			case 2:
				$frequency = "Every two weeks";
				break;
				
			case 3:
				$frequency = "Monthly";
				break;
				
			case 4:
				$frequency = "Quartly";
				break;
				
			case 5:
				$frequency = "6 Monthly";
				break;
			case 6:
				$frequency = "Yearly";
				break;
			
			default:
				$frequency = "";
				break;
		}
		
		return $frequency;
	}
	
	
	/* GET TAX TYPE  & DISCOUNT TYPE */
	public static function get_tax_type($num, $perc1, $perc2){
	
		$the_tax;
	
		switch ($num) {
			case 0:
				$the_tax = " - ";
				break;
				
			case 1:
				$the_tax = $perc1."% ";
				break;
				
			case 2:
				$the_tax = $perc2."% ";
				break;
			
			default:
				$the_tax = " - ";
				break;
		}
		
		return $the_tax;
		
	}
	
	
	public static function get_discount_type($num, $discount_value, $cost, $qty){
		 
		$the_type;
		
		switch ($num) {
			case '0':
				$the_type = " - ";
				break;
				
			case '1':

                if($discount_value == 0){
                    $the_type = '-';
                }else{
                    if(round(($discount_value * 100) / ($cost * $qty) ) <= 0){
                        $the_type = '-';
                    }else{
                        $the_type = $discount_value != 0 ? round(($discount_value * 100) / ($cost * $qty) ) : 0;
                        $the_type .= "%";
                    }
                }

				break;
				
			case '2':
				$the_type = $discount_value;
				break;
			
			default:
				$the_type = " - ";
				break;
		}
		
		return $the_type;
		
	}
	
	
	
	/* GET Invoice  Sent Status */
	public static function get_status($inv_status){
		switch($inv_status){
	        case 0:
	            return "Draft";
	        break;
	        
	        case 1:
	            return "Sent";
	        break;
	
	        default:
	            return "not known";
	        break;
	    } // End switch
	}
	
	
	
	/* GET Invoice Payment Status */
	public static function get_payment_status($payment){
		switch($payment){
	        case 0:
	            return "Unpaid";
	        break;
	        
	        case 1:
	            return "Part paid";
	        break;
			
			case 2:
	            return "Fully paid";
	        break;
	
	        default:
	            return "not known";
	        break;
	    } // End switch
	}
	
	
	/* GET Invoice Receipt Status */
	public static function get_receipt_status($receipt){
		switch($receipt){
	        case 0:
	            return "Pending";
	        break;
	        
	        case 1:
	            return "Issued";
	        break;
	
	        default:
	            return "not known";
	        break;
	    } // End switch
	}
	
	
	
	/** Generate new Receipt ID Number **/
	/** $param1 is the last insertID **/
	public static function newReceiptID($myId)
	{
		$myId += 1;
        return $myId;
	}
	
	/** Generate new Invoice ID Number **/
	/** $param1 is the last insertID **/
	public static function newInvoiceID($myId)
	{
		$myId += 1;
		return $myId;
	}
	
	/** Generate Invoice ID Number **/
	/** $param1 is the real insertID **/
	public static function invoiceId($myId)
	{
		$id = (int)$myId;
		return $id;
	}
	
	
	/** Generate new Quote ID Number **/
	/** $param1 is the last insertID **/
	public static function newQuoteID($myId)
	{
		$myId += 1;
		return $myId;
	}
	
	/** Generate Quote ID Number **/
	/** $param1 is the real insertID **/
	public static function quoteId($myId)
	{
		$id = (int)$myId;
		return $id;
	}
	
	
	public static function dumCurrencyCode($code=''){

		if($code== ''){
			return false;
		}
		
		if($code == 'NGN'){
			return '₦';
		}

        if($code == 'XOF'){
            return 'CFA ';
        }

		if($code == 'CNY'){
			return '¥';
		}
	 
		return Intl::getCurrencyBundle()->getCurrencySymbol($code);
	}
	
	public static function invoice_template_name($id){
		$id = (int)$id;
		$name ="";
		
		switch($id){
			
			case 1:
				$name = "Professional elite";
			break;
				
			case 2:
				$name = "Business elite";
			break;
					
			case 3:
				$name = "Rav pro v1.2";
			break;
				
			case 4:
				$name = "Mighty four";
			break;
				
			default:
				$name = "unknown";
			break;
	
		}
		return $name;
	}//
	
	
	// Get subscription type
	public static function get_subscription_plan($plan_id){
	
		$plan = "";
		switch($plan_id){
			
			case 1:
			$plan = "Starter";
			break;
			
			case 2:
			$plan = "Premium";
			break;
			
			case 3:
			$plan = "Super Premium";
			break;

			default:
			$plan = "not set";
			break;
			
		}
		return $plan;
	} // ENd Get Plan 
	
	
	public static function getCurrencyList($default_currency = 'USD')
	{
		$currencies = Currency::all();
		// $string = '<select name="currency" id="currencylist" class="sel"><option value="">- select -</option>';
		$string = '<select name="currency" id="currencylist" class="sel">';
		foreach($currencies as $currency)
		{
			$selected = ($default_currency == $currency->three_code ) ? "selected" : "";
			$title = $currency->country_currency. ' ' .$currency->three_code;
			$string .= sprintf('<option %s value="%s">%s</option>', $selected, $currency->three_code, $title);
		}
		return $string .= '</select>';
	}
	
	
	public static function getUserCurrencyListOptionsForInvoice($currencyRatesList, $default_currency = "")
	{ 
		$currencies = Currency::whereIn('three_code', $currencyRatesList)->get();
		$string = '';
		
		foreach($currencies as $currency)
		{
			$string .= '<option cur_code="'.$currency->three_code.'" cur_id="'.$currency->id.'" '. ($default_currency == $currency->three_code ? "selected=\"selected\"" : "").' value="'.\Symfony\Component\Intl\Intl::getCurrencyBundle()->getCurrencySymbol($currency->three_code).'">'.$currency->country_currency. ' - '.$currency->three_code. '</option>';
		}
		return $string .= '';
	}
	
	public static function getUserCurrencyListOptionsForExpense($currencyRatesList, $default_currency = "")
	{ 
		$currencies = Currency::whereIn('three_code', $currencyRatesList)->get();
		$string = '';
		
		foreach($currencies as $currency)
		{
			$string .= '<option cur_code="'.$currency->three_code.'" cur_id="'.$currency->id.'" '. ($default_currency == $currency->three_code ? "selected=\"selected\"" : "").' value="'.$currency->three_code.'">'.$currency->country_currency. ' - '.$currency->three_code. '</option>';
		}
		return $string .= '';
	}
	
	
	public static function getUserCurrencyListOptionsExpExist($currencyRatesList, $default_currency = "")
	{ 
		$currencies = Currency::whereNotIn('three_code', $currencyRatesList)->get();
		$string = '';
		
		foreach($currencies as $currency)
		{
			$string .= '<option cur_code="'.$currency->three_code.'" cur_id="'.$currency->id.'" '. ($default_currency == $currency->three_code ? "selected=\"selected\"" : "").' value="'.$currency->three_code.'">'.$currency->country_currency. ' - '.$currency->three_code. '</option>';
		}
		return $string .= '';
	}
	
	
	public static function getCountryList()
	{
		return '<select id="country" name="country" class="sel">
					<option value="">- select -</option>					
					<option value="Afghanistan">Afghanistan</option> 
					<option value="Albania">Albania</option> 
					<option value="Algeria">Algeria</option> 
					<option value="American Samoa">American Samoa</option> 
					<option value="Andorra">Andorra</option> 
					<option value="Angola">Angola</option> 
					<option value="Anguilla">Anguilla</option> 
					<option value="Antarctica">Antarctica</option> 
					<option value="Antigua and Barbuda">Antigua and Barbuda</option> 
					<option value="Argentina">Argentina</option> 
					<option value="Armenia">Armenia</option> 
					<option value="Aruba">Aruba</option> 
					<option value="Australia">Australia</option> 
					<option value="Austria">Austria</option> 
					<option value="Azerbaijan">Azerbaijan</option> 
					<option value="Bahamas">Bahamas</option> 
					<option value="Bahrain">Bahrain</option> 
					<option value="Bangladesh">Bangladesh</option> 
					<option value="Barbados">Barbados</option> 
					<option value="Belarus">Belarus</option> 
					<option value="Belgium">Belgium</option> 
					<option value="Belize">Belize</option> 
					<option value="Benin">Benin</option> 
					<option value="Bermuda">Bermuda</option> 
					<option value="Bhutan">Bhutan</option> 
					<option value="Bolivia">Bolivia</option> 
					<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
					<option value="Botswana">Botswana</option> 
					<option value="Bouvet Island">Bouvet Island</option> 
					<option value="Brazil">Brazil</option> 
					<option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
					<option value="Brunei Darussalam">Brunei Darussalam</option> 
					<option value="Bulgaria">Bulgaria</option> 
					<option value="Burkina Faso">Burkina Faso</option> 
					<option value="Burundi">Burundi</option> 
					<option value="Cambodia">Cambodia</option> 
					<option value="Cameroon">Cameroon</option> 
					<option value="Canada">Canada</option> 
					<option value="Cape Verde">Cape Verde</option> 
					<option value="Cayman Islands">Cayman Islands</option> 
					<option value="Central African Republic">Central African Republic</option> 
					<option value="Chad">Chad</option> 
					<option value="Chile">Chile</option> 
					<option value="China">China</option> 
					<option value="Christmas Island">Christmas Island</option> 
					<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
					<option value="Colombia">Colombia</option> 
					<option value="Comoros">Comoros</option> 
					<option value="Congo">Congo</option> 
					<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
					<option value="Cook Islands">Cook Islands</option> 
					<option value="Costa Rica">Costa Rica</option> 
					<option value="Cote D\'ivoire">Cote D\'ivoire</option> 
					<option value="Croatia">Croatia</option> 
					<option value="Cuba">Cuba</option> 
					<option value="Cyprus">Cyprus</option> 
					<option value="Czech Republic">Czech Republic</option> 
					<option value="Denmark">Denmark</option> 
					<option value="Djibouti">Djibouti</option> 
					<option value="Dominica">Dominica</option> 
					<option value="Dominican Republic">Dominican Republic</option> 
					<option value="Ecuador">Ecuador</option> 
					<option value="Egypt">Egypt</option> 
					<option value="El Salvador">El Salvador</option> 
					<option value="Equatorial Guinea">Equatorial Guinea</option> 
					<option value="Eritrea">Eritrea</option> 
					<option value="Estonia">Estonia</option> 
					<option value="Ethiopia">Ethiopia</option> 
					<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
					<option value="Faroe Islands">Faroe Islands</option> 
					<option value="Fiji">Fiji</option> 
					<option value="Finland">Finland</option> 
					<option value="France">France</option> 
					<option value="French Guiana">French Guiana</option> 
					<option value="French Polynesia">French Polynesia</option> 
					<option value="French Southern Territories">French Southern Territories</option> 
					<option value="Gabon">Gabon</option> 
					<option value="Gambia">Gambia</option> 
					<option value="Georgia">Georgia</option> 
					<option value="Germany">Germany</option> 
					<option value="Ghana">Ghana</option> 
					<option value="Gibraltar">Gibraltar</option> 
					<option value="Greece">Greece</option> 
					<option value="Greenland">Greenland</option> 
					<option value="Grenada">Grenada</option> 
					<option value="Guadeloupe">Guadeloupe</option> 
					<option value="Guam">Guam</option> 
					<option value="Guatemala">Guatemala</option> 
					<option value="Guinea">Guinea</option> 
					<option value="Guinea-bissau">Guinea-bissau</option> 
					<option value="Guyana">Guyana</option> 
					<option value="Haiti">Haiti</option> 
					<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
					<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
					<option value="Honduras">Honduras</option> 
					<option value="Hong Kong">Hong Kong</option> 
					<option value="Hungary">Hungary</option> 
					<option value="Iceland">Iceland</option> 
					<option value="India">India</option> 
					<option value="Indonesia">Indonesia</option> 
					<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
					<option value="Iraq">Iraq</option> 
					<option value="Ireland">Ireland</option> 
					<option value="Israel">Israel</option> 
					<option value="Italy">Italy</option> 
					<option value="Jamaica">Jamaica</option> 
					<option value="Japan">Japan</option> 
					<option value="Jordan">Jordan</option> 
					<option value="Kazakhstan">Kazakhstan</option> 
					<option value="Kenya">Kenya</option> 
					<option value="Kiribati">Kiribati</option> 
					<option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option> 
					<option value="Korea, Republic of">Korea, Republic of</option> 
					<option value="Kuwait">Kuwait</option> 
					<option value="Kyrgyzstan">Kyrgyzstan</option> 
					<option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option> 
					<option value="Latvia">Latvia</option> 
					<option value="Lebanon">Lebanon</option> 
					<option value="Lesotho">Lesotho</option> 
					<option value="Liberia">Liberia</option> 
					<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
					<option value="Liechtenstein">Liechtenstein</option> 
					<option value="Lithuania">Lithuania</option> 
					<option value="Luxembourg">Luxembourg</option> 
					<option value="Macao">Macao</option> 
					<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
					<option value="Madagascar">Madagascar</option> 
					<option value="Malawi">Malawi</option> 
					<option value="Malaysia">Malaysia</option> 
					<option value="Maldives">Maldives</option> 
					<option value="Mali">Mali</option> 
					<option value="Malta">Malta</option> 
					<option value="Marshall Islands">Marshall Islands</option> 
					<option value="Martinique">Martinique</option> 
					<option value="Mauritania">Mauritania</option> 
					<option value="Mauritius">Mauritius</option> 
					<option value="Mayotte">Mayotte</option> 
					<option value="Mexico">Mexico</option> 
					<option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
					<option value="Moldova, Republic of">Moldova, Republic of</option> 
					<option value="Monaco">Monaco</option> 
					<option value="Mongolia">Mongolia</option> 
					<option value="Montserrat">Montserrat</option> 
					<option value="Morocco">Morocco</option> 
					<option value="Mozambique">Mozambique</option> 
					<option value="Myanmar">Myanmar</option> 
					<option value="Namibia">Namibia</option> 
					<option value="Nauru">Nauru</option> 
					<option value="Nepal">Nepal</option> 
					<option value="Netherlands">Netherlands</option> 
					<option value="Netherlands Antilles">Netherlands Antilles</option> 
					<option value="New Caledonia">New Caledonia</option> 
					<option value="New Zealand">New Zealand</option> 
					<option value="Nicaragua">Nicaragua</option> 
					<option value="Niger">Niger</option> 
					<option value="Nigeria">Nigeria</option> 
					<option value="Niue">Niue</option> 
					<option value="Norfolk Island">Norfolk Island</option> 
					<option value="Northern Mariana Islands">Northern Mariana Islands</option> 
					<option value="Norway">Norway</option> 
					<option value="Oman">Oman</option> 
					<option value="Pakistan">Pakistan</option> 
					<option value="Palau">Palau</option> 
					<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
					<option value="Panama">Panama</option> 
					<option value="Papua New Guinea">Papua New Guinea</option> 
					<option value="Paraguay">Paraguay</option> 
					<option value="Peru">Peru</option> 
					<option value="Philippines">Philippines</option> 
					<option value="Pitcairn">Pitcairn</option> 
					<option value="Poland">Poland</option> 
					<option value="Portugal">Portugal</option> 
					<option value="Puerto Rico">Puerto Rico</option> 
					<option value="Qatar">Qatar</option> 
					<option value="Reunion">Reunion</option> 
					<option value="Romania">Romania</option> 
					<option value="Russian Federation">Russian Federation</option> 
					<option value="Rwanda">Rwanda</option> 
					<option value="Saint Helena">Saint Helena</option> 
					<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
					<option value="Saint Lucia">Saint Lucia</option> 
					<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
					<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
					<option value="Samoa">Samoa</option> 
					<option value="San Marino">San Marino</option> 
					<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
					<option value="Saudi Arabia">Saudi Arabia</option> 
					<option value="Senegal">Senegal</option> 
					<option value="Serbia and Montenegro">Serbia and Montenegro</option> 
					<option value="Seychelles">Seychelles</option> 
					<option value="Sierra Leone">Sierra Leone</option> 
					<option value="Singapore">Singapore</option> 
					<option value="Slovakia">Slovakia</option> 
					<option value="Slovenia">Slovenia</option> 
					<option value="Solomon Islands">Solomon Islands</option> 
					<option value="Somalia">Somalia</option> 
					<option value="South Africa">South Africa</option> 
					<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
					<option value="Spain">Spain</option> 
					<option value="Sri Lanka">Sri Lanka</option> 
					<option value="Sudan">Sudan</option> 
					<option value="Suriname">Suriname</option> 
					<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
					<option value="Swaziland">Swaziland</option> 
					<option value="Sweden">Sweden</option> 
					<option value="Switzerland">Switzerland</option> 
					<option value="Syrian Arab Republic">Syrian Arab Republic</option> 
					<option value="Taiwan, Province of China">Taiwan, Province of China</option> 
					<option value="Tajikistan">Tajikistan</option> 
					<option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
					<option value="Thailand">Thailand</option> 
					<option value="Timor-leste">Timor-leste</option> 
					<option value="Togo">Togo</option> 
					<option value="Tokelau">Tokelau</option> 
					<option value="Tonga">Tonga</option> 
					<option value="Trinidad and Tobago">Trinidad and Tobago</option> 
					<option value="Tunisia">Tunisia</option> 
					<option value="Turkey">Turkey</option> 
					<option value="Turkmenistan">Turkmenistan</option> 
					<option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
					<option value="Tuvalu">Tuvalu</option> 
					<option value="Uganda">Uganda</option> 
					<option value="Ukraine">Ukraine</option> 
					<option value="United Arab Emirates">United Arab Emirates</option> 
					<option value="United Kingdom">United Kingdom</option> 
					<option value="United States">United States</option> 
					<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
					<option value="Uruguay">Uruguay</option> 
					<option value="Uzbekistan">Uzbekistan</option> 
					<option value="Vanuatu">Vanuatu</option> 
					<option value="Venezuela">Venezuela</option> 
					<option value="Viet Nam">Viet Nam</option> 
					<option value="Virgin Islands, British">Virgin Islands, British</option> 
					<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
					<option value="Wallis and Futuna">Wallis and Futuna</option> 
					<option value="Western Sahara">Western Sahara</option> 
					<option value="Yemen">Yemen</option> 
					<option value="Zambia">Zambia</option> 
					<option value="Zimbabwe">Zimbabwe</option>	
				</select>';
	}
	
	
	
	public static function getIndustryList($selected = ""){
		
		$selected = $selected;	
		
		return '<select id="industry" tabindex="16" name="industry" class="sel">
   
		  		<option '.($selected == "" ? "selected=\"selected\"" : "").' value="">
		    Please specify …
		  </option>
		  
		  <option '.($selected == "Agriculture and Food Services" ? "selected=\"selected\"" : "").' value="Agriculture and Food Services">
		    Agriculture and Food Services
		  </option>
		  
		  <option '.($selected == "Architecture and Construction" ? "selected=\"selected\"" : "").' value="Architecture and Construction">
		    Architecture and Construction
		  </option>
		  
		  <option '.($selected == "Arts and Artists" ? "selected=\"selected\"" : "").' value="Arts and Artists">
		    Arts and Artists
		  </option>
		  
		  <option '.($selected == "Beauty and Personal Care" ? "selected=\"selected\"" : "").' value="Beauty and Personal Care">
		    Beauty and Personal Care
		  </option>
		  
		  <option '.($selected == "Business and Finance" ? "selected=\"selected\"" : "").' value="Business and Finance">
		    Business and Finance
		  </option>
		  
		  <option '.($selected == "Computers and Electronics" ? "selected=\"selected\"" : "").' value="Computers and Electronics">
		    Computers and Electronics
		  </option>
		  
		  <option '.($selected == "Construction" ? "selected=\"selected\"" : "").' value="Construction">
		    Construction 
		  </option>
		  
		  <option '.($selected == "Consulting" ? "selected=\"selected\"" : "").' value="Consulting">
		    Consulting
		  </option>
		  
		  <option '.($selected == "Creative Services / Agency" ? "selected=\"selected\"" : "").' value="Creative Services / Agency">
		    Creative Services/Agency
		  </option>
		  
		  <option '.($selected == "Daily Deals / E-Coupons" ? "selected=\"selected\"" : "").' value="Daily Deals/E-Coupons">
		    Daily Deals/E-Coupons
		  </option>
		  
		  <option '.($selected == "eCommerce" ? "selected=\"selected\"" : "").' value="eCommerce">
		    eCommerce
		  </option>
		  
		  <option '.($selected == "Education and Training" ? "selected=\"selected\"" : "").' value="Education and Training">
		    Education and Training
		  </option>
		  
		  <option '.($selected == "Entertainment and Events" ? "selected=\"selected\"" : "").' value="Entertainment and Events">
		    Entertainment and Events
		  </option>
		  
		  <option '.($selected == "Gambling" ? "selected=\"selected\"" : "").' value="Gambling">
		    Gambling
		  </option>
		  
		  <option '.($selected == "Games" ? "selected=\"selected\"" : "").' value="Games">
		    Games
		  </option>
		  
		  <option '.($selected == "Government" ? "selected=\"selected\"" : "").' value="Government">
		    Government
		  </option>
		  
		  <option '.($selected == "Health and Fitness" ? "selected=\"selected\"" : "").' value="Health and Fitness">
		    Health and Fitness
		  </option>
		  
		  <option '.($selected == "Hobbies" ? "selected=\"selected\"" : "").' value="Hobbies">
		    Hobbies
		  </option>
		  
		  <option '.($selected == "Home and Garden" ? "selected=\"selected\"" : "").' value="Home and Garden">
		    Home and Garden
		  </option>
		  
		  <option '.($selected == "Insurance" ? "selected=\"selected\"" : "").' value="Insurance">
		    Insurance
		  </option>
		  
		  <option '.($selected == "Legal" ? "selected=\"selected\"" : "").' value="Legal">
		    Legal
		  </option>
		  
		  <option '.($selected == "Manufacturing" ? "selected=\"selected\"" : "").' value="Manufacturing">
		    Manufacturing
		  </option>
		  
		  <option '.($selected == "Marketing and Advertising" ? "selected=\"selected\"" : "").' value="Marketing and Advertising">
		    Marketing and Advertising
		  </option>
		  
		  <option '.($selected == "Media and Publishing" ? "selected=\"selected\"" : "").' value="Media and Publishing">
		    Media and Publishing
		  </option>
		  
		  <option '.($selected == "Medical, Dental, and Healthcare" ? "selected=\"selected\"" : "").' value="Medical, Dental, and Healthcare">
		    Medical, Dental, and Healthcare
		  </option>
		  
		  <option '.($selected == "Mobile" ? "selected=\"selected\"" : "").' value="Mobile">
		    Mobile
		  </option>
		  
		  <option '.($selected == "Music and Musicians" ? "selected=\"selected\"" : "").' value="Music and Musicians">
		    Music and Musicians
		  </option>
		  
		  <option '.($selected == "Non-Profit" ? "selected=\"selected\"" : "").' value="Non-Profit">
		    Non-Profit
		  </option>
		  
		  <option '.($selected == "Pharmaceuticals" ? "selected=\"selected\"" : "").' value="Pharmaceuticals">
		    Pharmaceuticals
		  </option>
		  
		  <option '.($selected == "Photo and Video" ? "selected=\"selected\"" : "").' value="Photo and Video">
		    Photo and Video
		  </option>
		  
		  <option '.($selected == "Politics" ? "selected=\"selected\"" : "").' value="Politics">
		    Politics
		  </option>
		  
		  <option '.($selected == "Professional Services" ? "selected=\"selected\"" : "").' value="Professional Services">
		    Professional Services
		  </option>
		  
		  <option '.($selected == "Public Relations" ? "selected=\"selected\"" : "").' value="Public Relations">
		    Public Relations
		  </option>
		  
		  <option '.($selected == "Real Estate" ? "selected=\"selected\"" : "").' value="Real Estate">
		    Real Estate
		  </option>
		  
		  <option '.($selected == "Recruitment and Staffing" ? "selected=\"selected\"" : "").' value="Recruitment and Staffing">
		    Recruitment and Staffing
		  </option>
		  
		  <option '.($selected == "Religion" ? "selected=\"selected\"" : "").' value="Religion">
		    Religion
		  </option>
		  
		  <option '.($selected == "Restaurant and Venue" ? "selected=\"selected\"" : "").' value="Restaurant and Venue">
		    Restaurant and Venue
		  </option>
		  
		  <option '.($selected == "Retail" ? "selected=\"selected\"" : "").' value="Retail">
		    Retail
		  </option>
		  
		  <option '.($selected == "Social Networks and Online Communities" ? "selected=\"selected\"" : "").' value="Social / Online Communities">
		    Social Networks and Online Communities
		  </option>
		  
		  <option '.($selected == "Software and Web App" ? "selected=\"selected\"" : "").' value="Software and Web App">
		    Software and Web App
		  </option>
		  
		  <option '.($selected == "Sports" ? "selected=\"selected\"" : "").' value="Sports">
		    Sports
		  </option>
		  
		   <option '.($selected == "Technology / Internet" ? "selected=\"selected\"" : "").' value="Technology / Internet">
		    Technology / Internet
		  </option>
		  
		  <option '.($selected == "Telecommunications" ? "selected=\"selected\"" : "").' value="Telecommunications">
		    Telecommunications
		  </option>
		  
		  <option '.($selected == "Travel and Transportation" ? "selected=\"selected\"" : "").' value="Travel and Transportation">
		    Travel and Transportation
		  </option>
		  
		  <option '.($selected == "Vitamin supplements" ? "selected=\"selected\"" : "").' value="Vitamin supplements">
		    Vitamin supplements
		  </option>
		  
		  <option '.($selected == "Other" ? "selected=\"selected\"" : "").' value="Other">
		    Other
		  </option>
		  
		</select>';
	}


	public static function getIndustryExpandedList()
	{
		return '<select name="industry" id="industry" class="sel full_width">
					<option value="">- select -</option>
					<option value="Accountants">Accountants</option>
					<option value="Advertising/Public Relations">Advertising/Public Relations</option>
					<option value="Aerospace, Defense Contractors">Aerospace, Defense Contractors</option>
					<option value="Agribusiness">Agribusiness</option>
					<option value="Agricultural Services & Products">Agricultural Services & Products</option>
					<option value="Agriculture">Agriculture</option>
					<option value="Air Transport">Air Transport</option>
					<option value="Air Transport Unions">Air Transport Unions</option>
					<option value="Airlines">Airlines</option>
					<option value="Alcoholic Beverages">Alcoholic Beverages</option>
					<option value="Alternative Energy Production & Services">Alternative Energy Production & Services</option>
					<option value="Architectural Services">Architectural Services</option>
					<option value="Attorneys/Law Firms">Attorneys/Law Firms</option>
					<option value="Auto Dealers">Auto Dealers</option>
					<option value="Auto Dealers, Japanese">Auto Dealers, Japanese</option>
					<option value="Auto Manufacturers">Auto Manufacturers</option>
					<option value="Automotive">Automotive</option>
					<option value="Banking, Mortgage">Banking, Mortgage</option>
					<option value="Banks, Commercial">Banks, Commercial</option>
					<option value="Banks, Savings & Loans">Banks, Savings & Loans</option>
					<option value="Bars & Restaurants">Bars & Restaurants</option>
					<option value="Beer, Wine & Liquor">Beer, Wine & Liquor</option>
					<option value="Books, Magazines & Newspapers">Books, Magazines & Newspapers</option>
					<option value="Broadcasters, Radio/TV">Broadcasters, Radio/TV</option>
					<option value="Builders/General Contractors">Builders/General Contractors</option>
					<option value="Builders/Residential">Builders/Residential</option>
					<option value="Building Materials & Equipment">Building Materials & Equipment</option>
					<option value="Building Trade Unions">Building Trade Unions</option>
					<option value="Business Associations">Business Associations</option>
					<option value="Business Services">Business Services</option>
					<option value="Cable & Satellite TV Production & Distribution">Cable & Satellite TV Production & Distribution</option>
					<option value="Candidate Committees">Candidate Committees</option>
					<option value="Candidate Committees, Democratic">Candidate Committees, Democratic</option>
					<option value="Candidate Committees, Republican">Candidate Committees, Republican</option>
					<option value="Car Dealers">Car Dealers</option>
					<option value="Car Dealers, Imports">Car Dealers, Imports</option>
					<option value="Car Manufacturers">Car Manufacturers</option>
					<option value="Casinos / Gambling">Casinos / Gambling</option>
					<option value="Cattle Ranchers/Livestock">Cattle Ranchers/Livestock</option>
					<option value="Chemical & Related Manufacturing">Chemical & Related Manufacturing</option>
					<option value="Chiropractors">Chiropractors</option>
					<option value="Civil Servants/Public Officials">Civil Servants/Public Officials</option>
					<option value="Clergy & Religious Organizations">Clergy & Religious Organizations</option>
					<option value="Clothing Manufacturing">Clothing Manufacturing</option>
					<option value="Coal Mining">Coal Mining</option>
					<option value="Colleges, Universities & Schools">Colleges, Universities & Schools</option>
					<option value="Commercial Banks">Commercial Banks</option>
					<option value="Commercial TV & Radio Stations">Commercial TV & Radio Stations</option>
					<option value="Communications/Electronics">Communications/Electronics</option>
					<option value="Computer Software">Computer Software</option>
					<option value="Computers/Internet">Computers/Internet</option>
					<option value="Conservative/Republican">Conservative/Republican</option>
					<option value="Construction">Construction</option>
					<option value="Construction Services">Construction Services</option>
					<option value="Construction Unions">Construction Unions</option>
					<option value="Credit Unions">Credit Unions</option>
					<option value="Crop Production & Basic Processing">Crop Production & Basic Processing</option>
					<option value="Cruise Lines">Cruise Lines</option>
					<option value="Cruise Ships & Lines">Cruise Ships & Lines</option>
					<option value="Dairy">Dairy</option>
					<option value="Defense">Defense</option>
					<option value="Defense Aerospace">Defense Aerospace</option>
					<option value="Defense Electronics">Defense Electronics</option>
					<option value="Defense/Foreign Policy Advocates">Defense/Foreign Policy Advocates</option>
					<option value="Democratic Candidate Committees">Democratic Candidate Committees</option>
					<option value="Democratic Leadership PACs">Democratic Leadership PACs</option>
					<option value="Democratic/Liberal">Democratic/Liberal</option>
					<option value="Dentists">Dentists</option>
					<option value="Doctors & Other Health Professionals">Doctors & Other Health Professionals</option>
					<option value="Drug Manufacturers">Drug Manufacturers</option>
					<option value="Education">Education</option>
					<option value="Electric Utilities">Electric Utilities</option>
					<option value="Electronics, Defense Contractors">Electronics, Defense Contractors</option>
					<option value="Energy & Natural Resources">Energy & Natural Resources</option>
					<option value="Entertainment Industry">Entertainment Industry</option>
					<option value="Environment">Environment</option>
					<option value="Farming">Farming</option>
					<option value="Finance / Credit Companies">Finance / Credit Companies</option>
					<option value="Finance, Insurance & Real Estate">Finance, Insurance & Real Estate</option>
					<option value="Food & Beverage">Food & Beverage</option>
					<option value="Food Processing & Sales">Food Processing & Sales</option>
					<option value="Food Products Manufacturing">Food Products Manufacturing</option>
					<option value="Food Stores">Food Stores</option>
					<option value="For-profit Education">For-profit Education</option>
					<option value="Foreign & Defense Policy">Foreign & Defense Policy</option>
					<option value="Forestry & Forest Products">Forestry & Forest Products</option>
					<option value="Foundations, Philanthropists & Non-Profits">Foundations, Philanthropists & Non-Profits</option>
					<option value="Funeral Services">Funeral Services</option>
					<option value="Gambling & Casinos">Gambling & Casinos</option>
					<option value="Gambling, Indian Casinos">Gambling, Indian Casinos</option>
					<option value="Garbage Collection/Waste Management">Garbage Collection/Waste Management</option>
					<option value="Gas & Oil">Gas & Oil</option>
					<option value="Gay & Lesbian Rights & Issues">Gay & Lesbian Rights & Issues</option>
					<option value="General Contractors">General Contractors</option>
					<option value="Government Employee Unions">Government Employee Unions</option>
					<option value="Government Employees">Government Employees</option>
					<option value="Gun Control">Gun Control</option>
					<option value="Gun Rights">Gun Rights</option>
					<option value="Health">Health</option>
					<option value="Health Professionals">Health Professionals</option>
					<option value="Health Services/HMOs">Health Services/HMOs</option>
					<option value="Hedge Funds">Hedge Funds</option>
					<option value="HMOs & Health Care Services">HMOs & Health Care Services</option>
					<option value="Home Builders">Home Builders</option>
					<option value="Hospitals & Nursing Homes">Hospitals & Nursing Homes</option>
					<option value="Hotels, Motels & Tourism">Hotels, Motels & Tourism</option>
					<option value="Human Rights">Human Rights</option>
					<option value="Ideological/Single-Issue">Ideological/Single-Issue</option>
					<option value="Indian Gaming">Indian Gaming</option>
					<option value="Industrial Unions">Industrial Unions</option>
					<option value="Insurance">Insurance</option>
					<option value="Israel Policy">Israel Policy</option>
					<option value="Labor">Labor</option>
					<option value="Lawyers & Lobbyists">Lawyers & Lobbyists</option>
					<option value="Lawyers / Law Firms">Lawyers / Law Firms</option>
					<option value="Leadership PACs">Leadership PACs</option>
					<option value="Liberal/Democratic">Liberal/Democratic</option>
					<option value="Liquor, Wine & Beer">Liquor, Wine & Beer</option>
					<option value="Livestock">Livestock</option>
					<option value="Lobbyists">Lobbyists</option>
					<option value="Lodging / Tourism">Lodging / Tourism</option>
					<option value="Logging, Timber & Paper Mills">Logging, Timber & Paper Mills</option>
					<option value="Manufacturing, Misc">Manufacturing, Misc</option>
					<option value="Marine Transport">Marine Transport</option>
					<option value="Meat processing & products">Meat processing & products</option>
					<option value="Medical Devices & Supplies">Medical Devices & Supplies</option>
					<option value="Mining">Mining</option>
					<option value="Misc Business">Misc Business</option>
					<option value="Misc Manufacturing & Distributing">Misc Manufacturing & Distributing</option>
					<option value="Misc Unions">Misc Unions</option>
					<option value="Miscellaneous Defense">Miscellaneous Defense</option>
					<option value="Miscellaneous Services">Miscellaneous Services</option>
					<option value="Mortgage Bankers & Brokers">Mortgage Bankers & Brokers</option>
					<option value="Motion Picture Production & Distribution">Motion Picture Production & Distribution</option>
					<option value="Music Production">Music Production</option>
					<option value="Natural Gas Pipelines">Natural Gas Pipelines</option>
					<option value="Newspaper, Magazine & Book Publishing">Newspaper, Magazine & Book Publishing</option>
					<option value="Non-profits, Foundations & Philanthropists">Non-profits, Foundations & Philanthropists</option>
					<option value="Nurses">Nurses</option>
					<option value="Nursing Homes/Hospitals">Nursing Homes/Hospitals</option>
					<option value="Nutritional & Dietary Supplements">Nutritional & Dietary Supplements</option>
					<option value="Oil & Gas">Oil & Gas</option>
					<option value="Other">Other</option>
					<option value="Payday Lenders">Payday Lenders</option>
					<option value="Pharmaceutical Manufacturing">Pharmaceutical Manufacturing</option>
					<option value="Pharmaceuticals / Health Products">Pharmaceuticals / Health Products</option>
					<option value="Phone Companies">Phone Companies</option>
					<option value="Physicians & Other Health Professionals">Physicians & Other Health Professionals</option>
					<option value="Postal Unions">Postal Unions</option>
					<option value="Poultry & Eggs">Poultry & Eggs</option>
					<option value="Power Utilities">Power Utilities</option>
					<option value="Printing & Publishing">Printing & Publishing</option>
					<option value="Private Equity & Investment Firms">Private Equity & Investment Firms</option>
					<option value="Pro-Israel">Pro-Israel</option>
					<option value="Professional Sports, Sports Arenas & Related Equipment & Services">Professional Sports, Sports Arenas & Related Equipment & Services</option>
					<option value="Progressive/Democratic">Progressive/Democratic</option>
					<option value="Public Employees">Public Employees</option>
					<option value="Public Sector Unions">Public Sector Unions</option>
					<option value="Publishing & Printing">Publishing & Printing</option>
					<option value="Radio/TV Stations">Radio/TV Stations</option>
					<option value="Railroads">Railroads</option>
					<option value="Real Estate">Real Estate</option>
					<option value="Record Companies/Singers">Record Companies/Singers</option>
					<option value="Recorded Music & Music Production">Recorded Music & Music Production</option>
					<option value="Recreation / Live Entertainment">Recreation / Live Entertainment</option>
					<option value="Religious Organizations/Clergy">Religious Organizations/Clergy</option>
					<option value="Republican Candidate Committees">Republican Candidate Committees</option>
					<option value="Republican Leadership PACs">Republican Leadership PACs</option>
					<option value="Republican/Conservative">Republican/Conservative</option>
					<option value="Residential Construction">Residential Construction</option>
					<option value="Restaurants & Drinking Establishments">Restaurants & Drinking Establishments</option>
					<option value="Retail Sales">Retail Sales</option>
					<option value="Retired">Retired</option>
					<option value="Savings & Loans">Savings & Loans</option>
					<option value="Schools/Education">Schools/Education</option>
					<option value="Sea Transport">Sea Transport</option>
					<option value="Securities & Investment">Securities & Investment</option>
					<option value="Special Trade Contractors">Special Trade Contractors</option>
					<option value="Sports, Professional">Sports, Professional</option>
					<option value="Steel Production">Steel Production</option>
					<option value="Stock Brokers/Investment Industry">Stock Brokers/Investment Industry</option>
					<option value="Student Loan Companies">Student Loan Companies</option>
					<option value="Sugar Cane & Sugar Beets">Sugar Cane & Sugar Beets</option>
					<option value="Teachers Unions">Teachers Unions</option>
					<option value="Teachers/Education">Teachers/Education</option>
					<option value="Technology">Technology</option>
					<option value="Telecom Services & Equipment">Telecom Services & Equipment</option>
					<option value="Telephone Utilities">Telephone Utilities</option>
					<option value="Textiles">Textiles</option>
					<option value="Timber, Logging & Paper Mills">Timber, Logging & Paper Mills</option>
					<option value="Tobacco">Tobacco</option>
					<option value="Transportation">Transportation</option>
					<option value="Transportation Unions">Transportation Unions</option>
					<option value="Trash Collection/Waste Management">Trash Collection/Waste Management</option>
					<option value="Trucking">Trucking</option>
					<option value="TV / Movies / Music">TV / Movies / Music</option>
					<option value="TV Production & Distribution">TV Production & Distribution</option>
					<option value="Unions">Unions</option>
					<option value="Unions, Airline">Unions, Airline</option>
					<option value="Unions, Building Trades">Unions, Building Trades</option>
					<option value="Unions, Industrial">Unions, Industrial</option>
					<option value="Unions, Misc">Unions, Misc</option>
					<option value="Unions, Public Sector">Unions, Public Sector</option>
					<option value="Unions, Teacher">Unions, Teacher</option>
					<option value="Unions, Transportation">Unions, Transportation</option>
					<option value="Universities, Colleges & Schools">Universities, Colleges & Schools</option>
					<option value="Vegetables & Fruits">Vegetables & Fruits</option>
					<option value="Venture Capital">Venture Capital</option>
					<option value="Waste Management">Waste Management</option>
					<option value="Wine, Beer & Liquor">Wine, Beer & Liquor</option>
					<option value="Women\'s Issues">Women\'s Issues</option>
				</select>';
	}
	
}
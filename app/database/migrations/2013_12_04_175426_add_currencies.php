<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Intl\Intl;

class AddCurrencies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
  
		$currency_codes = $this->getIntl_iso_4217();
		foreach($currency_codes as $code => $name)
		{ 
			DB::table('currency')->insert(array(
			'country_currency' => $name,
			'three_code' => $code
			));	
			
		}
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{ 
		$currency_codes = $this->getIntl_iso_4217();
		foreach($currency_codes as $code => $name)
		{
			DB::table('currency')->where('three_code', '=', $code)->delete();			 
		}
	}
	
	public function getIntl_iso_4217()
	{
		return $currencies = Intl::getCurrencyBundle()->getCurrencyNames('en');
	}
	
	
	function get_iso_4217_currency_codes(){
			$a = array();
			$a['AFA'] = array('Afghan Afghani', '971');
			$a['AWG'] = array('Aruban Florin', '533');
			$a['AUD'] = array('Australian Dollars', '036');
			$a['ARS'] = array('Argentine Pes', '    03');
			$a['AZN'] = array('Azerbaijanian Manat', '944');
			$a['BSD'] = array('Bahamian Dollar', '044');
			$a['BDT'] = array('Bangladeshi Taka', '050');
			$a['BBD'] = array('Barbados Dollar', '052');
			$a['BYR'] = array('Belarussian Rouble', '974');
			$a['BOB'] = array('Bolivian Boliviano', '068');
			$a['BRL'] = array('Brazilian Real', '986');
			$a['GBP'] = array('British Pounds Sterling', '826');
			$a['BGN'] = array('Bulgarian Lev', '975');
			$a['KHR'] = array('Cambodia Riel', '116');
			$a['CAD'] = array('Canadian Dollars', '124');
			$a['KYD'] = array('Cayman Islands Dollar', '136');
			$a['CLP'] = array('Chilean Peso', '152');
			$a['CNY'] = array('Chinese Renminbi Yuan', '156');
			$a['COP'] = array('Colombian Peso', '170');
			$a['CRC'] = array('Costa Rican Colon', '188');
			$a['HRK'] = array('Croatia Kuna', '191');
			$a['CPY'] = array('Cypriot Pounds', '196');
			$a['CZK'] = array('Czech Koruna', '203');
			$a['DKK'] = array('Danish Krone', '208');
			$a['DOP'] = array('Dominican Republic Peso', '214');
			$a['XCD'] = array('East Caribbean Dollar', '951');
			$a['EGP'] = array('Egyptian Pound', '818');
			$a['ERN'] = array('Eritrean Nakfa', '232');
			$a['EEK'] = array('Estonia Kroon', '233');
			$a['EUR'] = array('Euro', '978');
			$a['GEL'] = array('Georgian Lari', '981');
			$a['GHC'] = array('Ghana Cedi', '288');
			$a['GIP'] = array('Gibraltar Pound', '292');
			$a['GTQ'] = array('Guatemala Quetzal', '320');
			$a['HNL'] = array('Honduras Lempira', '340');
			$a['HKD'] = array('Hong Kong Dollars', '344');
			$a['HUF'] = array('Hungary Forint', '348');
			$a['ISK'] = array('Icelandic Krona', '352');
			$a['INR'] = array('Indian Rupee', '356');
			$a['IDR'] = array('Indonesia Rupiah', '360');
			$a['ILS'] = array('Israel Shekel', '376');
			$a['JMD'] = array('Jamaican Dollar', '388');
			$a['JPY'] = array('Japanese yen', '392');
			$a['KZT'] = array('Kazakhstan Tenge', '368');
			$a['KES'] = array('Kenyan Shilling', '404');
			$a['KWD'] = array('Kuwaiti Dinar', '414');
			$a['LVL'] = array('Latvia Lat', '428');
			$a['LBP'] = array('Lebanese Pound', '422');
			$a['LTL'] = array('Lithuania Litas', '440');
			$a['MOP'] = array('Macau Pataca', '446');
			$a['MKD'] = array('Macedonian Denar', '807');
			$a['MGA'] = array('Malagascy Ariary', '969');
			$a['MYR'] = array('Malaysian Ringgit', '458');
			$a['MTL'] = array('Maltese Lira', '470');
			$a['BAM'] = array('Marka', '977');
			$a['MUR'] = array('Mauritius Rupee', '480');
			$a['MXN'] = array('Mexican Pesos', '484');
			$a['MZM'] = array('Mozambique Metical', '508');
			$a['NPR'] = array('Nepalese Rupee', '524');
			$a['ANG'] = array('Netherlands Antilles Guilder', '532');
			$a['TWD'] = array('New Taiwanese Dollars', '901');
			$a['NZD'] = array('New Zealand Dollars', '554');
			$a['NIO'] = array('Nicaragua Cordoba', '558');
			$a['NGN'] = array('Nigeria Naira', '566');
			$a['KPW'] = array('North Korean Won', '408');
			$a['NOK'] = array('Norwegian Krone', '578');
			$a['OMR'] = array('Omani Riyal', '512');
			$a['PKR'] = array('Pakistani Rupee', '586');
			$a['PYG'] = array('Paraguay Guarani', '600');
			$a['PEN'] = array('Peru New Sol', '604');
			$a['PHP'] = array('Philippine Pesos', '608');
			$a['QAR'] = array('Qatari Riyal', '634');
			$a['RON'] = array('Romanian New Leu', '946');
			$a['RUB'] = array('Russian Federation Ruble', '643');
			$a['SAR'] = array('Saudi Riyal', '682');
			$a['CSD'] = array('Serbian Dinar', '891');
			$a['SCR'] = array('Seychelles Rupee', '690');
			$a['SGD'] = array('Singapore Dollars', '702');
			$a['SKK'] = array('Slovak Koruna', '703');
			$a['SIT'] = array('Slovenia Tolar', '705');
			$a['ZAR'] = array('South African Rand', '710');
			$a['KRW'] = array('South Korean Won', '410');
			$a['LKR'] = array('Sri Lankan Rupee', '144');
			$a['SRD'] = array('Surinam Dollar', '968');
			$a['SEK'] = array('Swedish Krona', '752');
			$a['CHF'] = array('Swiss Francs', '756');
			$a['TZS'] = array('Tanzanian Shilling', '834');
			$a['THB'] = array('Thai Baht', '764');
			$a['TTD'] = array('Trinidad and Tobago Dollar', '780');
			$a['TRY'] = array('Turkish New Lira', '949');
			$a['AED'] = array('UAE Dirham', '784');
			$a['USD'] = array('US Dollars', '840');
			$a['UGX'] = array('Ugandian Shilling', '800');
			$a['UAH'] = array('Ukraine Hryvna', '980');
			$a['UYU'] = array('Uruguayan Peso', '858');
			$a['UZS'] = array('Uzbekistani Som', '860');
			$a['VEB'] = array('Venezuela Bolivar', '862');
			$a['VND'] = array('Vietnam Dong', '704');
			$a['AMK'] = array('Zambian Kwacha', '894');
			$a['ZWD'] = array('Zimbabwe Dollar', '716');
			return $a;
		}

}
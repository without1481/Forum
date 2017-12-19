<?php
/*========================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2017 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/

/**
 * @package vBulletin
 */

/**
 * @package vBulletin
 */
class vB_Utility_String
{
	private $defaultcharset;

	//these are protected to allow the unit test to override the class to test both implementations
	protected $iconvenabled;
	protected $mbstringenabled;

	/**
	 *	Constructor
	 *
	 *	@param $charset -- the default charset
	 *
	 *	Will throw an exception if the $charset is not an accepted value
	 */
	public function __construct($charset)
	{
		$this->defaultcharset = $this->getCanonicalBrowserEncoding($charset);

		$this->iconvenabled = function_exists('iconv');
		$this->mbstringenabled = function_exists('mb_convert_encoding');
	}

	/**
	 * Get the default charset for the class
	 *
	 * @string string -- the canonical charset for the class default
	 */
	public function getCharset()
	{
		return $this->defaultcharset;
	}

	/**
	 *	Encoding aware htmlspecialchars
	 *
	 *	This takes a string and produces an html escaped version.  It uses specified charset.
	 *
	 *	@param string $value -- string to be escaped
	 *	@param integer $flags -- flags per php function htmlspecialchars
	 *	@param string $encoding -- the browser encoding to use.  Note that this is *not* the
	 *		encoding value for the php function.  Use the same values as you would use for
	 *		the http/html value and would pass to this class.  If null, the class default
	 *		will be used.
	 *	@retun string -- escaped string
	 */
	public function htmlspecialchars($value, $flags = ENT_COMPAT | ENT_HTML401, $encoding = null)
	{
		//figure out what charset we are actually dealing with.
		$actualencoding = $encoding;
		if (empty($actualencoding))
		{
			$actualencoding = $this->defaultcharset;
		}
		$actualencoding = $this->getCanonicalBrowserEncoding($actualencoding);

		//htmlspecialchars supports out charset, yay!
		if (isset(self::$specialcharsCharsetMap[$actualencoding]))
		{
			return htmlspecialchars($value, $flags, self::$specialcharsCharsetMap[$actualencoding]);
		}

		//okay this isn't good so let's dance
		else
		{
			$newvalue = $this->toCharsetInternal($value, $actualencoding, 'utf-8');
			$newvalue = htmlspecialchars($newvalue, $flags, 'utf-8');
			$newvalue = $this->toCharsetInternal($newvalue, 'utf-8', $actualencoding);
			return $newvalue;
		}
	}


	/**
	 *	Does the charset match the default charset for the class
	 *
	 *	@param string $charset
	 *	@return bool
	 *	@see areCharsetsEqual
	 */
	public function isDefaultCharset($charset)
	{
		return $this->areCharsetsEqual($charset, $this->defaultcharset);
	}

	/**
	 *	Are the two charsets the same
	 *
	 *	This uses the charset matching rules to look up the charsets and then
	 *	compares the canoncical value for each charset to see if they match.
	 *	If either charset is invalid according to the matching rule, the
	 *	function will return false (even if both are the same invalid value)
	 *
	 *	@param string $charset1
	 *	@param string $charset2
	 *	@return bool
	 */
	public function areCharsetsEqual($charset1, $charset2)
	{
		try
		{
			$charset1 = $this->getCanonicalBrowserEncoding($charset1);
			$charset2 = $this->getCanonicalBrowserEncoding($charset2);
		}
		catch(Exception $e)
		{
			//not really sure what the best thing to do here is
			//for now just return false.
			return false;
		}

		return ($charset1 == $charset2);
	}

	/**
	 * Converts from the internal charset to utf8
	 *
	 * @param	string|array $value -- The variable to convert
	 * @return string|array The converted variable.
	 * @see toCharset
	 */
	public function toUtf8($value)
	{
		return $this->toCharsetInternal($value, $this->defaultcharset, 'utf-8');
	}

	/**
	 * Converts to the default charset
	 *
	 * @param	string|array $value -- The variable to convert
	 * @param	string $sourceEncoding -- The source encoding
	 * @return string|array The converted variable.
	 * @see toCharset
	 */
	public function toDefault($value, $sourceEncoding)
	{
		$source = $this->getCanonicalBrowserEncoding($sourceEncoding);
		return $this->toCharsetInternal($value, $source, $this->defaultcharset);
	}

	/**
	 * Converts a variable from one character encoding to another.
	 *
	 * If the variable is a string it is converted.  If it is array
	 * will attempt to recurse over it and convert any string values located.
	 * Any other types will be returned unchanged.
	 *
	 * Note that this does not attempt to deal with reference loops so is
	 * not suitable for complex objects.
	 *
	 * @param	string|array $value -- The variable to convert
	 * @param	string $sourceEncoding -- The source encoding
	 * @param string $targetEncoding -- The target encoding
	 *
	 * @return string|array The converted variable.
	 */
	public function toCharset($value, $sourceEncoding, $targetEncoding)
	{
		$source = $this->getCanonicalBrowserEncoding($sourceEncoding);
		$target = $this->getCanonicalBrowserEncoding($targetEncoding);

		return $this->toCharsetInternal($value, $source, $target);
	}


	/**
	 * Converts a variable from one character encoding to another.
	 *
	 * If the variable is a string it is converted.  If it is array
	 * will attempt to recurse over it and convert any string values located.
	 * Any other types will be returned unchanged.
	 *
	 * Note that the caller is responsible for ensuring that the charsets
	 * match the canonical charset including case
	 *
	 * @param	string|array $in -- The variable to convert
	 * @param	string $in_encoding -- The source encoding (must be one of the mapped canonical browser values)
	 * @param string $target_encoding -- The target encoding (must be one of the mapped canonical browser values)
	 *
	 * @return string|array The converted variable.
	 */
	private function toCharsetInternal($in, $in_encoding, $target_encoding)
	{
		if (is_array($in))
		{
			foreach ($in AS $key => $val)
			{
				$in[$key] = $this->toCharsetInternal($val, $in_encoding, $target_encoding);
			}

			return $in;
		}

		if (is_string($in))
		{
			// Try iconv
			if ($this->iconvenabled)
			{
				//this should never actually fail since iconv handles all of our accepted encodings
				//and we should always be converting to a canonical encoding prior to getting here
				//but let's check anyway.
				$source = (isset(self::$iconvMap[$in_encoding]) ? self::$iconvMap[$in_encoding] : false);
				$target = (isset(self::$iconvMap[$target_encoding]) ? self::$iconvMap[$target_encoding] : false);

				if ($source AND $target)
				{
					return @iconv($source, $target . '//IGNORE', $in);
				}
				else
				{
					throw new Exception("Could not convert $in_encoding to $target_encoding using inconv");
				}
			}

			// Try mbstring
			if ($this->mbstringenabled)
			{
				//this should never actually fail since iconv handles all of our accepted encodings
				//and we should always be converting to a canonical encoding prior to getting here
				//but let's check anyway.
				$source = (isset(self::$mbstringMap[$in_encoding]) ? self::$mbstringMap[$in_encoding] : false);
				$target = (isset(self::$mbstringMap[$target_encoding]) ? self::$mbstringMap[$target_encoding] : false);

				if ($source AND $target)
				{
					return @mb_convert_encoding($in, $target, $source);
				}
				else
				{
					throw new Exception("Could not convert $in_encoding to $target_encoding using mbstring");
				}
			}

			throw new Exception("Could not convert string character encoding, one of 'mbstring' or 'iconv' must be installed");
		}


		// if it's not a string, array, or object, don't modify it
		return $in;
	}

	/**
	 * Look up the canonical charset from the map based on
	 */
	private function getCanonicalBrowserEncoding($charset)
	{
		$charset = strtolower($charset);
		if(isset(self::$browserCharsetMap[$charset]))
		{
			return self::$browserCharsetMap[$charset];
		}
		else
		{
			throw new Exception('Invalid Charset: ' . $charset);
		}
	}

	//CHARSET MAPS

	//put at the end because thesre are long an not very interesting.
	//each function/application/etc appears to have different ideas about
	//what the various charsets should be called.  This is an attempt to map from
	//one to the other in variouis instances so that things work.

	//maps from the canonical broswer charsets to the values expected by
	//htmlspecialchars.  If the charset isn't listed in the keys then
	//it isn't supported by htmlspecialchars.
	// taken from http://php.net/manual/en/function.htmlspecialchars.php
	//
	//if we attempt to map user input without first going through the character
	//map below, we risk failure.  For example, htmlspecialchars does not accept
	//"latin1" as a valid encoding.
	//
	//Side note, in most cases the key value will actually be accepted
	//as an alias for the "primary" value we map to but we need a map anyway for
	//a fast check to see if the charset is accepted by the function and a couple of
	//exceptions. So we might as well stick with the main versions in the documentation
	private static $specialcharsCharsetMap = array(
		'iso-8859-1' => 'iso-8859-1', //not actually used since we map iso-8859-1 to windows-1252
		'utf-8' => 'utf-8',
		'windows-1252' => 'cp1252',
		'iso-8859-5' => 'iso-8859-5',
		'iso-8859-15' => 'iso-8859-15',
		'ibm866' => 'cp866',
		'windows-1251' => 'cp1251',
		'koi8-r' => 'koi8-r',
		'big5' => 'big5',
		'big5-hkscs' => 'big5-hkscs', //not used, the standard below maps big5-hkscs to big5
		'gbk' => 'gb2312', //mapping relevant as gbk is not accepted by htmlspecialchars
		'shift_jis' => 'shift_jis',
		'euc-jp' => 'euc-jp',
		'macintosh' => 'macroman', //mapping relevant
	);

	private static $iconvMap = array(
		'utf-8' => 'utf-8',
		'ibm866' => 'cp866',
		'iso-8859-2' =>'iso-8859-2',
		'iso-8859-3' => 'iso-8859-3',
		'iso-8859-4' => 'iso-8859-4',
		'iso-8859-5' => 'iso-8859-5',
		'iso-8859-6' => 'iso-8859-6',
		'iso-8859-7' => 'iso-8859-7',
		'iso-8859-8'  => 'iso-8859-8',
		'iso-8859-8-i' => 'iso-8859-8',
		'iso-8859-10' => 'iso-8859-10',
		'iso-8859-13' => 'iso-8859-13',
		'iso-8859-14' => 'iso-8859-14',
		'iso-8859-15' => 'iso-8859-15',
		'iso-8859-16' => 'iso-8859-16',
		'koi8-r' => 'koi8-r',
		'koi8-u' => 'koi8-u',
		'macintosh' => 'macintosh',
		'windows-874' => 'windows-874',
		'windows-1250' => 'windows-1250',
		'windows-1251' => 'windows-1251',
		'windows-1252' => 'windows-1252',
		'windows-1253' => 'windows-1253',
		'windows-1254' => 'windows-1254',
		'windows-1255' => 'windows-1255',
		'windows-1256' => 'windows-1256',
		'windows-1257' => 'windows-1257',
		'windows-1258' => 'windows-1258',
		'x-mac-cyrillic' => 'maccyrillic',
		'gbk' => 'gbk',
		'gb18030' => 'gb18030',
		'big5' => 'big5',
		'euc-jp' => 'euc-jp',
		'iso-2022-jp' => 'iso-2022-jp',
		'shift_jis' => 'shift_jis',
		'euc-kr' => 'euc-kr',
		'utf-16be' => 'utf-16be',
		'utf-16le' => 'utf-16le',
	);

	//show map for all canonical browser languages
	//comment out entries that mbstring does not support.
	private static $mbstringMap = array(
		'utf-8' => 'utf-8',
		'ibm866' => 'cp866',
		'iso-8859-2' =>'iso-8859-2',
		'iso-8859-3' => 'iso-8859-3',
		'iso-8859-4' => 'iso-8859-4',
		'iso-8859-5' => 'iso-8859-5',
		'iso-8859-6' => 'iso-8859-6',
		'iso-8859-7' => 'iso-8859-7',
		'iso-8859-8'  => 'iso-8859-8',
		'iso-8859-8-i' => 'iso-8859-8',
		'iso-8859-10' => 'iso-8859-10',
		'iso-8859-13' => 'iso-8859-13',
		'iso-8859-14' => 'iso-8859-14',
		'iso-8859-15' => 'iso-8859-15',
		'iso-8859-16' => 'iso-8859-16',
		'koi8-r' => 'koi8-r',
		'koi8-u' => 'koi8-u',
		'windows-1251' => 'windows-1251',
		'windows-1252' => 'windows-1252',
		'gbk' => 'gbk',
		'gb18030' => 'gb18030',
		'big5' => 'big5',
		'euc-jp' => 'euc-jp',
		'iso-2022-jp' => 'iso-2022-jp',
		'shift_jis' => 'shift_jis',
		'euc-kr' => 'euc-kr',
		'utf-16be' => 'utf-16be',
		'utf-16le' => 'utf-16le',
	);


	//copied from https://www.w3.org/TR/encoding/#names-and-labels this is all the
	//charsets browsers are required (and allowed) to support.  We may want to trim
	//this list -- especially based on is supported by mb_string and iconv -- but
	//its a point to start.  Certainly we aren't going to support anything not on this
	//list.  We will need other mappings.  If nothing else the list for mysql is completely
	//different.
	//commented out entries for things that don't make sense to support
	private static $browserCharsetMap = array(
		//utf-8
		'unicode-1-1-utf-8' => 'utf-8',
		'utf-8' => 'utf-8',
		'utf8' => 'utf-8',

		//Legacy single-byte encodings
		'866' => 'ibm866',
		'cp866' => 'ibm866',
		'csibm866' => 'ibm866',
		'ibm866' => 'ibm866',

		'csisolatin2' =>'iso-8859-2',
		'iso-8859-2' =>'iso-8859-2',
		'iso-ir-101' =>'iso-8859-2',
		'iso8859-2' =>'iso-8859-2',
		'iso88592' =>'iso-8859-2',
		'iso_8859-2' =>'iso-8859-2',
		'iso_8859-2:1987' =>'iso-8859-2',
		'l2' =>'iso-8859-2',
		'latin2' =>'iso-8859-2',

		'csisolatin3' => 'iso-8859-3',
		'iso-8859-3' => 'iso-8859-3',
		'iso-ir-109' => 'iso-8859-3',
		'iso8859-3' => 'iso-8859-3',
		'iso88593' => 'iso-8859-3',
		'iso_8859-3' => 'iso-8859-3',
		'iso_8859-3:1988' => 'iso-8859-3',
		'l3' => 'iso-8859-3',
		'latin3' => 'iso-8859-3',

		'csisolatin4' => 'iso-8859-4',
		'iso-8859-4' => 'iso-8859-4',
		'iso-ir-110' => 'iso-8859-4',
		'iso8859-4' => 'iso-8859-4',
		'iso88594' => 'iso-8859-4',
		'iso_8859-4' => 'iso-8859-4',
		'iso_8859-4:1988' => 'iso-8859-4',
		'l4' => 'iso-8859-4',
		'latin4' => 'iso-8859-4',

		'csisolatincyrillic' => 'iso-8859-5',
		'cyrillic' => 'iso-8859-5',
		'iso-8859-5' => 'iso-8859-5',
		'iso-ir-144' => 'iso-8859-5',
		'iso8859-5' => 'iso-8859-5',
		'iso88595' => 'iso-8859-5',
		'iso_8859-5' => 'iso-8859-5',
		'iso_8859-5:1988' => 'iso-8859-5',

		'arabic' => 'iso-8859-6',
		'asmo-708' => 'iso-8859-6',
		'csiso88596e' => 'iso-8859-6',
		'csiso88596i' => 'iso-8859-6',
		'csisolatinarabic' => 'iso-8859-6',
		'ecma-114' => 'iso-8859-6',
		'iso-8859-6' => 'iso-8859-6',
		'iso-8859-6-e' => 'iso-8859-6',
		'iso-8859-6-i' => 'iso-8859-6',
		'iso-ir-127' => 'iso-8859-6',
		'iso8859-6' => 'iso-8859-6',
		'iso88596' => 'iso-8859-6',
		'iso_8859-6' => 'iso-8859-6',
		'iso_8859-6:1987' => 'iso-8859-6',

		'csisolatingreek' => 'iso-8859-7',
		'ecma-118' => 'iso-8859-7',
		'elot_928' => 'iso-8859-7',
		'greek' => 'iso-8859-7',
		'greek8' => 'iso-8859-7',
		'iso-8859-7' => 'iso-8859-7',
		'iso-ir-126' => 'iso-8859-7',
		'iso8859-7' => 'iso-8859-7',
		'iso88597' => 'iso-8859-7',
		'iso_8859-7' => 'iso-8859-7',
		'iso_8859-7:1987' => 'iso-8859-7',
		'sun_eu_greek' => 'iso-8859-7',

		'csiso88598e'  => 'iso-8859-8',
		'csisolatinhebrew'  => 'iso-8859-8',
		'hebrew'  => 'iso-8859-8',
		'iso-8859-8'  => 'iso-8859-8',
		'iso-8859-8-e'  => 'iso-8859-8',
		'iso-ir-138'  => 'iso-8859-8',
		'iso8859-8'  => 'iso-8859-8',
		'iso88598'  => 'iso-8859-8',
		'iso_8859-8'  => 'iso-8859-8',
		'iso_8859-8:1988'  => 'iso-8859-8',
		'visual'  => 'iso-8859-8',

		'csiso88598i' => 'iso-8859-8-i',
		'iso-8859-8-i' => 'iso-8859-8-i',
		'logical' => 'iso-8859-8-i',

		'csisolatin6' => 'iso-8859-10',
		'iso-8859-10' => 'iso-8859-10',
		'iso-ir-157' => 'iso-8859-10',
		'iso8859-10' => 'iso-8859-10',
		'iso885910' => 'iso-8859-10',
		'l6' => 'iso-8859-10',
		'latin6' => 'iso-8859-10',

		'iso-8859-13' => 'iso-8859-13',
		'iso8859-13' => 'iso-8859-13',
		'iso885913' => 'iso-8859-13',

		'iso-8859-14' => 'iso-8859-14',
		'iso8859-14' => 'iso-8859-14',
		'iso885914' => 'iso-8859-14',

		'csisolatin9' => 'iso-8859-15',
		'iso-8859-15' => 'iso-8859-15',
		'iso8859-15' => 'iso-8859-15',
		'iso885915' => 'iso-8859-15',
		'iso_8859-15' => 'iso-8859-15',
		'l9' => 'iso-8859-15',

		'iso-8859-16' => 'iso-8859-16',

		'cskoi8r' => 'koi8-r',
		'koi' => 'koi8-r',
		'koi8' => 'koi8-r',
		'koi8-r' => 'koi8-r',
		'koi8_r' => 'koi8-r',

		'koi8-ru' => 'koi8-u',
		'koi8-u' => 'koi8-u',

		'csmacintosh' => 'macintosh',
		'mac' => 'macintosh',
		'macintosh' => 'macintosh',
		'x-mac-roman' => 'macintosh',

		'dos-874' => 'windows-874',
		'iso-8859-11' => 'windows-874',
		'iso8859-11' => 'windows-874',
		'iso885911' => 'windows-874',
		'tis-620' => 'windows-874',
		'windows-874' => 'windows-874',

		'cp1250' => 'windows-1250',
		'windows-1250' => 'windows-1250',
		'x-cp1250' => 'windows-1250',

		'cp1251' => 'windows-1251',
		'windows-1251' => 'windows-1251',
		'x-cp1251' => 'windows-1251',

		'ansi_x3.4-1968' => 'windows-1252',
		'ascii' => 'windows-1252',
		'cp1252' => 'windows-1252',
		'cp819' => 'windows-1252',
		'csisolatin1' => 'windows-1252',
		'ibm819' => 'windows-1252',
		'iso-8859-1' => 'windows-1252',
		'iso-ir-100' => 'windows-1252',
		'iso8859-1' => 'windows-1252',
		'iso88591' => 'windows-1252',
		'iso_8859-1' => 'windows-1252',
		'iso_8859-1:1987' => 'windows-1252',
		'l1' => 'windows-1252',
		'latin1' => 'windows-1252',
		'us-ascii' => 'windows-1252',
		'windows-1252' => 'windows-1252',
		'x-cp1252' => 'windows-1252',

		'cp1253' => 'windows-1253',
		'windows-1253' => 'windows-1253',
		'x-cp1253' => 'windows-1253',

		'cp1254' => 'windows-1254',
		'csisolatin5' => 'windows-1254',
		'iso-8859-9' => 'windows-1254',
		'iso-ir-148' => 'windows-1254',
		'iso8859-9' => 'windows-1254',
		'iso88599' => 'windows-1254',
		'iso_8859-9' => 'windows-1254',
		'iso_8859-9:1989' => 'windows-1254',
		'l5' => 'windows-1254',
		'latin5' => 'windows-1254',
		'windows-1254' => 'windows-1254',
		'x-cp1254' => 'windows-1254',

		'windows-1255' => 'windows-1255',
		'cp1255' => 'windows-1255',
		'x-cp1255' => 'windows-1255',

		'cp1256' => 'windows-1256',
		'windows-1256' => 'windows-1256',
		'x-cp1256' => 'windows-1256',

		'cp1257' => 'windows-1257',
		'windows-1257' => 'windows-1257',
		'x-cp1257' => 'windows-1257',

		'cp1258' => 'windows-1258',
		'windows-1258' => 'windows-1258',
		'x-cp1258' => 'windows-1258',

		'x-mac-cyrillic' => 'x-mac-cyrillic',
		'x-mac-ukrainian' => 'x-mac-cyrillic',

		//	Legacy multi-byte Chinese (simplified) encodings
		'chinese' => 'gbk',
		'csgb2312' => 'gbk',
		'csiso58gb231280' => 'gbk',
		'gb2312' => 'gbk',
		'gb_2312' => 'gbk',
		'gb_2312-80' => 'gbk',
		'gbk' => 'gbk',
		'iso-ir-58' => 'gbk',
		'x-gbk' => 'gbk',

		'gb18030' => 'gb18030',

		//	Legacy multi-byte Chinese (traditional) encodings
		'big5' => 'big5',
		'big5-hkscs' => 'big5',
		'cn-big5' => 'big5',
		'csbig5' => 'big5',
		'x-x-big5' => 'big5',

		//	Legacy multi-byte Japanese encodings
		'cseucpkdfmtjapanese' => 'euc-jp',
		'euc-jp' => 'euc-jp',
		'x-euc-jp' => 'euc-jp',

		'csiso2022jp' => 'iso-2022-jp',
		'iso-2022-jp' => 'iso-2022-jp',

		'csshiftjis' => 'shift_jis',
		'ms932' => 'shift_jis',
		'ms_kanji' => 'shift_jis',
		'shift-jis' => 'shift_jis',
		'shift_jis' => 'shift_jis',
		'sjis' => 'shift_jis',
		'windows-31j' => 'shift_jis',
		'x-sjis' => 'shift_jis',

		//	Legacy multi-byte Korean encodings
		'cseuckr' => 'euc-kr',
		'csksc56011987' => 'euc-kr',
		'euc-kr' => 'euc-kr',
		'iso-ir-149' => 'euc-kr',
		'korean' => 'euc-kr',
		'ks_c_5601-1987' => 'euc-kr',
		'ks_c_5601-1989' => 'euc-kr',
		'ksc5601' => 'euc-kr',
		'ksc_5601' => 'euc-kr',
		'windows-949' => 'euc-kr',

		//	Legacy miscellaneous encodings
//		'csiso2022kr' => 'replacement',
//		'hz-gb-2312' => 'replacement',
//		'iso-2022-cn' => 'replacement',
//		'iso-2022-cn-ext' => 'replacement',
//		'iso-2022-kr' => 'replacement',

		'utf-16be' => 'utf-16be',

		'utf-16' => 'utf-16le',
		'utf-16le' => 'utf-16le',

//		'x-user-defined' => 'x-user-defined',
	);
}


/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 90893 $
|| #######################################################################
\*=========================================================================*/

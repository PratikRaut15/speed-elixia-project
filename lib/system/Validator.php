<?php
define('validationTypeNumeric', '.1234567890') ;
define('validationTypeAlphabetic', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') ;
define('validationTypeAlphanumeric', '.1234567890') ;

class Validator
{
	public static function isNumeric($variable) {
		return Validator::isValid($variable, validationTypeNumeric);
	}
	
	public static function isAlphabetic($variable) {
		return Validator::isValid($variable, validationTypeAlphabetic); 
	}
	
	public static function isAlphanumeric($variable) {
		return Validator::isValid($variable, validationTypeAlphanumeric);
	}
	
	public static function acceptableLength($variable, $minLength = 0, $maxLength = 100) {
		$acceptableLength = false;
		
		if ($variable != null) {
			$length = strlen($variable);
			
			if ($length >= $minLength
				&& $length <= $maxLength) {
					$acceptableLength = true;
				}
		}
		
		return $acceptableLength;
	}
	
	public static function escapeCharacters($variable, $trimWhiteSpace = true) {
		$escapedVariable = $trimWhiteSpace ? $variable : trim($variable) ;
		if (!Validator::isNumeric($escapedVariable)) {
			$escapedVariable = addslashes($escapedVariable);
		}
		
		return $escapedVariable;
	}
	
	private static function isValid($variable, $allowedCharacterString) {
		$isValid = false;
		
		// does the string contain any unpermited characters
		if(strlen($variable) == strspn($variable, $allowedCharacterString))
		{
			$isValid = true;
		}
		
		return $isValid;
	}
}
?>
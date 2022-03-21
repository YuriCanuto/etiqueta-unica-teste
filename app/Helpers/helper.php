<?php

use Cart as ShoppingCart;

/**
 * Store media
 */
if (! function_exists('storeMedia')):
  function storeMedia($obj, $img, $legend, $collection, $update = false)
  {
    if ($img):
      if ($update):
        $obj->clearMediaCollection($collection);
      endif;

      $obj->addMedia($img)
          ->usingName($legend)
          ->usingFileName(md5($img->getClientOriginalName()) . '.' . $img->getClientOriginalExtension())
          ->toMediaCollection($collection);
    endif;
  }
endif;

/**
 * Parse metatags
 */
if (! function_exists('parseMetaTags')):
  function parseMetaTags($seo, $title = null, $description = null)
  {
    $tags = [];

    $tags['meta_title'] = "{$seo->meta_title} | {$title}" ?? $title;
    $tags['meta_description'] = $seo->meta_description ?? $description;

    return $tags;
  }
endif;

/**
 * Set status name
 */
if (! function_exists('isActive')):
  function isActive($status)
  {
    switch($status):
      case 0:
        $status_name = '<span class="badge badge-danger -status">Desativado</span>';
        break;
      case 1:
        $status_name = '<span class="badge badge-success -status">Ativo</span>';
        break;
    endswitch;
    return $status_name;
  }
endif;

/**
 * Set status viewed
 */
if (! function_exists('isViewed')):
    function isViewed($viewed)
    {
      switch($viewed):
        case 0:
          $viewed_name = '<span class="badge badge-danger -status">Não</span>';
          break;
        case 1:
          $viewed_name = '<span class="badge badge-success -status">Sim</span>';
          break;
      endswitch;
      return $viewed_name;
    }
  endif;

/**
 * Set first name
 */
if (! function_exists('fullNameToFirstName')):
  function fullNameToFirstName($fullName, $checkFirstNameLength = true)
  {
    // Split out name so we can quickly grab the first name part
    $nameParts = explode(' ', $fullName);
    $firstName = $nameParts[0];

    // If the first part of the name is a prefix, then find the name differently
    if(in_array(strtolower($firstName), ['mr', 'ms', 'mrs', 'miss', 'dr', 'sr', 'sra'])):
      if($nameParts[2] != ''):
        // E.g. Mr James Smith -> James
        $firstName = $nameParts[1];
      else:
        // e.g. Mr Smith (no first name given)
        $firstName = $fullName;
      endif;
    endif;

    // make sure the first name is not just "J", e.g. "J Smith" or "Mr J Smith" or even "Mr J. Smith"
    if($checkFirstNameLength && strlen($firstName) < 3):
      $firstName = $fullName;
    endif;

    return $firstName;
  }
endif;

/**
 * Unmask
 */
if (! function_exists('unmaskInput')):
  function unmaskInput($input)
  {
    if (!is_null($input)):
      return trim(preg_replace('#[^0-9]#', '', $input));
    else:
      return $input;
    endif;
  }
endif;

/**
 * Set mask in string
 */
if (! function_exists('mask')):
  function mask($mask, $value)
  {
    for ($i=0; $i < strlen($value); $i++):
      $mask[strpos($mask,'#')] = $value[$i];
    endfor;

    return $mask;
  }
endif;

/**
 * Set mask phone
 */
if (! function_exists('maskPhone')):
  function maskPhone($phone)
  {
    if(strlen($phone) === 10):
      $phone = mask('(##) ####-####', $phone);
    elseif(strlen($phone) === 11):
      $phone = mask('(##) #####-####', $phone);
    endif;

    return $phone;
  }
endif;

/**
 * Set mask postalcode
 */
if (! function_exists('maskPostalCode')):
  function maskPostalCode($postalcode)
  {
    if($postalcode):
      return mask('#####-###', $postalcode);
    endif;

    return $postalcode;
  }
endif;

/**
 * Format Ucwords PT-BR
 */
if (! function_exists('titleCaseBR')):
  function titleCaseBR($string)
  {
    $delimiters = [" ", "-", ".", "'", "O'", "Mc"];
    $exceptions = ["e", "o", "a", "é", "á", "de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI"];

    $string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');

    foreach ($delimiters as $dlnr => $delimiter):
      $words = explode($delimiter, $string);
      $newwords = [];
      foreach ($words as $wordnr => $word):
          if (in_array(mb_strtoupper($word, 'UTF-8'), $exceptions)):
            // check exceptions list for any words that should be in upper case
            $word = mb_strtoupper($word, 'UTF-8');
          elseif (in_array(mb_strtolower($word, 'UTF-8'), $exceptions)):
            // check exceptions list for any words that should be in upper case
            $word = mb_strtolower($word, 'UTF-8');
          elseif (!in_array($word, $exceptions)):
            // convert to uppercase (non-utf8 only)
            $word = ucfirst($word);
          endif;
          array_push($newwords, $word);
      endforeach;
      $string = join($delimiter, $newwords);
    endforeach;

    return $string;
  }
endif;

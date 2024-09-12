<?php

namespace App\Helpers\Templates;

use App\Controllers\AbstractController;
use App\Helpers\Tools\Dump;
use Exception;

class TemplateUtils {
  
  public static function sing(AbstractController $controller, string $html_file_path, array $parameters = []): string {

    $parameters = array_merge($parameters, AbstractController::get_common_parameters($controller));
    if(null !== $_SESSION['fleeting'] && count($_SESSION['fleeting']) > 0) {
      $parameters = array_merge($parameters, $_SESSION['fleeting']);
      $_SESSION['fleeting'] = [];
    }

    $code = file_get_contents($html_file_path);

    $compiled_code = self::compile_parameters($code, $parameters);

    return $compiled_code;
  }

  private static function compile_parameters(string $code, array $parameters = []): string {

    $code = self::replace_blocks($code, $parameters);
    $code = self::replace_calls($code, $parameters);
    $code = self::replace_foreach($code, $parameters);
    $code = self::replace_for($code, $parameters);
    $code = self::replace_variables($code, $parameters);

    $code = self::replace_undefined($code);

    return $code;
  }

  private static function replace_variables(string $code, array $parameters = []): string {

    if(count($parameters) > 0) {
      preg_match_all('~\{{\s*(.*?)\s*\}}~is', $code, $matches);

      foreach($matches[1] as $expression) {
        $replacer = "";
        $data = explode('.', $expression);
        // On vérifie si le tableau $data contient plus d'un élément
        // Ce qui veut dire que c'est un objet 
        // rediriger vers une page d'erreur si l'environement est dev
        if(count($data) > 1) {
          if(isset($parameters[$data[0]])) {
            $object = $parameters[$data[0]];
            if(is_object($object)) {
              $method = "get".ucfirst($data[1]);
              if(method_exists($object, $method)) {
                $replacer = preg_quote($object->$method());
              } elseif(method_exists($object, $data[1])) {
                $method = $data[1];
                $replacer = preg_quote($object->$method());
              } else {
                // throw new Exception("Aucune méthode correspondante à $expression", 0);
                // ERROR MISSING METHOD
                $replacer = "";
              }
            } elseif (is_array($object)) {
              
              $replacer = $object;

              for ($i=1; $i < count($data); $i++) { 
                if(isset($replacer[$data[$i]])) {
                  $replacer = $replacer[$data[$i]];
                } else {
                  // throw new Exception("Aucune valeur de tableau correspondante à $expression", 0);
                  // ERROR MISSING ARRAY VALUE
                  $replacer = "";
                  break;
                }
              }
              //$replacer = $object[$data[1]];
            
            }
            $code = preg_replace('~\{{\s*('.$expression.')\s*\}}~is', $replacer, $code);
          } else {
            //throw new Exception("Aucune valeur correspondante à $expression définie", 0);
            // EXPRESSION NON DEFINI
            $code = preg_replace('~\{{\s*('.$expression.')\s*\}}~is', "???", $code);
          }
        } else {
          if(!is_array($expression) || !is_object($expression)){
            if(isset($parameters[$expression])) {
              $replacer = $parameters[$expression];
            } else {
              throw new Exception("Aucune valeur correspondante à $expression définie", 0);
              // EXPRESSION NON DEFINI
              $replacer = "???";
            }
            $code = preg_replace('~\{{\s*('.$expression.')\s*\}}~is', $replacer , $code);
          }
        }
      }
    }
    return $code;

  }

  private static function replace_blocks(string $code, array $parameters): string {

    preg_match_all('/{% ?include ?(.*?) ?%}/is', $code, $matches);

    foreach ($matches[1] as $block) {
      $path = TEMPLATES_PATH.'/'.trim($block);
      if(file_exists($path)) {
        $blocks = preg_quote($block, '/');
        
        $code = preg_replace('/{% ?include ?('.$blocks.') ?%}/is', file_get_contents($path) , $code);
        $code = self::compile_parameters($code, $parameters);
      }
    }

    return $code;
  }

  private static function replace_foreach(string $code, array $parameters): string {

    preg_match_all('/{% ?foreach ?(.*?) template ?(.*?) ?%}/is', $code, $matches);
    if(count($parameters) < 1 || count($matches[0]) === 0) {
      return $code;
    }

    foreach($matches[1] as $index => $match) {
      $to_replace = "";
      $block = $matches[2][$index];
      $path = TEMPLATES_PATH.'/'.trim($block);
      $block = preg_quote($block, '/');

      if (strpos($match, '.') !== false) {
        $keys = explode('.', $match);
        if(isset($parameters[$keys[0]])) {
          $object = $parameters[$keys[0]];

          $incr = $object;
          for ($i=1; $i < count($keys); $i++) { 
            if(isset($incr[$keys[$i]])) {
              $incr = $incr[$keys[$i]];
            } 
          }
          $key = $incr;
          foreach ($key as $data) {
            if(file_exists($path) && is_array($data)) {
              $content = file_get_contents($path);
              $to_replace .= self::replace_variables($content, array_merge($parameters, $data));
            }
          }
          $code = preg_replace('/{% ?foreach ?('.$match.') template ?('.$block.') ?%}/is', $to_replace, $code);
        }
      } else {
        if(isset($parameters[$match])) {
          foreach ($parameters[$match] as $datas) {
            if(file_exists($path) && is_array($datas)) {
              $content = file_get_contents($path);
              $to_replace .= self::replace_variables($content, array_merge($parameters, $datas));
            }
          }
        }

        $code = preg_replace('/{% ?foreach ?('.$match.') template ?('.$block.') ?%}/is', $to_replace, $code);
      }


    }


    return $code;
  }

  private static function replace_for(string $code, array $parameters): string {

    preg_match_all('/{% ?for ?(.*?) template ?(.*?) ?%}/is', $code, $matches);

    if(count($matches[0]) === 0) {
      return $code;
    }

    foreach($matches[1] as $index => $match) {
      $to_replace = "";
      $block = $matches[2][$index];
      $path = TEMPLATES_PATH.'/'.trim($block);
      $block = preg_quote($block, '/');

      $incr_limit = 0;
      if(is_numeric($match)) {
        $incr_limit = (int) $match;
      } else if (strpos($match, '.') !== false) {
        $keys = explode('.', $match);
        $incr = 0;
        if(count($keys) > 1) {
          if(isset($parameters[$keys[0]])) {
            $object = $parameters[$keys[0]];

            $incr = $object;
            for ($i=1; $i < count($keys); $i++) { 
              if(isset($incr[$keys[$i]])) {
                $incr = $incr[$keys[$i]];
              } 
            }
          }
        }
        $incr_limit = (int) $incr;
      } else {
          // Si c'est une simple chaîne sans point, on l'ajoute directement comme clé
          $incr_limit = (int) $parameters[$match];
      }

      for($i = 0; $i < $incr_limit; $i++) {
        if(file_exists($path)) {
          $content = file_get_contents($path);
          $to_replace .= self::replace_variables($content, array_merge($parameters, ['index' => $i+1]));
        }
      }
      $code = preg_replace('/{% ?for ?('.$match.') template ?('.$block.') ?%}/is', $to_replace, $code);
    }


    return $code;
  }

  private static function replace_calls(string $code, $parameters): string {
    preg_match_all('/{% ?call ?(.*?)(?: ?params ?\((.*?)\))? ?%}/is', $code, $matches);
    
    foreach ($matches[1] as $index => $call) {
      $string_param = '';

      $replacer = "???";
      $function = explode("::", $matches[1][$index]);
      if(count($function) > 1) {
        $call = preg_quote($call, '/');
        $class = $function[0];
        $method = $function[1];

        if(class_exists($class) && method_exists($class, $method)) {
          if(isset($matches[2]) && $matches[2][$index]) {
            $params = [];
            $string_param = $matches[2][$index];
            $params_parts = array_map('trim', explode(',', $matches[2][$index]));
            foreach( $params_parts as $param) {
              preg_match_all('~\{{\s*(.*?)\s*\}}~is', $param, $param_matches);
              $key = $param_matches[1][0] ?? 'noparams';
              if(array_key_exists($key, $parameters)) {
                $params[] = $parameters[$key];
              } else {
                $params[] = $param;
              }
            }
          } 
          $replacer = call_user_func_array([$class, $method], $params);
         
        }

      } else {
        if(function_exists($call)) {
          if(isset($matches[2]) && $matches[2][$index]) {
            $params = [];
            $string_param = $matches[2][$index];
            $params_parts = array_map('trim', explode(',', $matches[2][$index]));
            foreach( $params_parts as $param) {
              preg_match_all('~\{{\s*(.*?)\s*\}}~is', $param, $param_matches);
              $key = $param_matches[1][0] ?? 'noparams';
              if(array_key_exists($key, $parameters)) {
                $params[] = $parameters[$key];
              } else {
                $params[] = $param;
              }
            }
          } 
          $replacer = call_user_func_array($call, $params);
        } 
      }

      $code = preg_replace('/{% ?call ?('.$call.')(?: ?params ?\(('.$string_param.')\))? ?%}/is', html_entity_decode($replacer), $code);
    }

    return $code;
  }

  private static function replace_undefined(string $code): string {
    $code = preg_replace('~\{{\s*(.*?)\s*\}}~is', '' , $code);
    $code = preg_replace('/{% ?block ?(.*?) ?%}/is', '' , $code);
    $code = preg_replace('/{% ?call ?(.*?)(?: ?params ?\((.*?)\))? ?%}/is', '' , $code);
    $code = preg_replace('/{% ?foreach ?(.*?) template ?(.*?) ?%}/is', '' , $code);
    return $code;
  }

}
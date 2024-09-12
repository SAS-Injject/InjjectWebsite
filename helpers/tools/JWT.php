<?php

namespace App\Helpers\Tools;

use DateTimeImmutable;

class JWT 
{

  //on génère le token
  // validity à 0 pour aucune date d'expiration
  public static function generateWebToken(array $header, array $payload, int $validity = 10800): string {

    if($validity > 0) {
      $now = new DateTimeImmutable();
      $expiration_date = $now->getTimestamp() + $validity; 
      $payload['iat'] = $now->getTimestamp();
      $payload['exp'] = $expiration_date;
    }

    // On encode en base64
    $base64Header = base64_encode(json_encode($header));
    $base64Payload = base64_encode(json_encode($payload));

    // On nettoie les valeurs encodées (retraits des +, /, =)
    $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
    $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

    // On génère la signature
    $base64Secret = base64_encode($_ENV['APP_SECRET']);

    $signature = hash_hmac('sha256', $base64Header.'.'.$base64Payload, $base64Secret, true);

    $base64Signature = base64_encode($signature);
    $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

    // On crée le token
    $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

    return $jwt;
  }

  public static function isTokenValid(string $token, array $checkpayload = []): bool {

    $is_payload_ok = true;

    if($checkpayload !== [] && count($checkpayload) > 0) {
      $payload = self::getPayload($token);
      foreach($checkpayload as $key => $value) {
        if(!isset($payload[$key]) || $payload[$key] !== $value) {
          $is_payload_ok = false;
        }
      }
    } 
    return $is_payload_ok && self::isValid($token) && !self::isExpired($token) && self::isSigned($token, $_ENV['APP_SECRET']);
  }

  // On vérifie que le token est valide (correctement formé)
  protected static function isValid(string $token): bool {
    return preg_match(
      '/^[a-zA-Z0-9\-\_]+\.[a-zA-Z0-9\-\_]+\.[a-zA-Z0-9\-\_]+$/',
      $token
    ) === 1;
  }

  // On récupère le payload
  public static function getPayload(string $token): array | null {

    // On démonte le token
    $exploded_token = explode('.', $token);

    // On décode le payload
    $payload = json_decode(base64_decode($exploded_token[1]), true);

    return $payload;
  }

  // On récupère le header
  protected static function getHeader(string $token): array {

    // On démonte le token
    $exploded_token = explode('.', $token);

    // On décode le header
    $header = json_decode(base64_decode($exploded_token[0]), true);

    return $header;
  }

  // On vérifie si le token a expiré
  protected static function isExpired(string $token): int {
    if(!isset($payload['exp'])) {
      return false;
    }
    $payload = self::getPayload($token);

    $now = new DateTimeImmutable();
    return intval($payload['exp']) < $now->getTimestamp();
  }


  // On vérifie la signature du token
  protected static function isSigned(string $token, string $secret): int {
    // On récupère le header et le payload
    $header = self::getHeader($token);
    $payload = self::getPayload($token);

    // On regénère un token
    $verified_token = self::generateWebToken($header, $payload, 0);

    return $token === $verified_token;
  }

}
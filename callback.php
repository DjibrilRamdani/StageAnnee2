<?php

// Initialisation de la session PHP
session_start();

// Configuration OpenID Connect pour Google
$openid_config = [
    'issuer' => 'https://accounts.google.com',
    'authorization_endpoint' => 'https://accounts.google.com/o/oauth2/auth',
    'token_endpoint' => 'https://www.googleapis.com/oauth2/v3/token',
    'userinfo_endpoint' => 'https://www.googleapis.com/oauth2/v3/userinfo',
    'jwks_uri' => 'https://www.googleapis.com/oauth2/v3/certs',
    'client_id' => '538702337787-a4ngl849lhpk917ijqb68pjb47n6bvcg.apps.googleusercontent.com',
    'client_secret' => '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',
    'redirect_uri' => 'http://testopenid.fr/callback.php',
    'scope' => 'openid email profile'
];

// Étape 1 : redirection vers l'endpoint d'autorisation
if (!isset($_GET['code'])) {
    $authorization_url = $openid_config['authorization_endpoint'] . '?'
        . 'response_type=code'
        . '&client_id=' . urlencode($openid_config['client_id'])
        . '&redirect_uri=' . urlencode($openid_config['redirect_uri'])
        . '&scope=' . urlencode($openid_config['scope'])
        . '&state=' . session_id();

    header('Location: ' . $authorization_url);
    exit();
}

// Étape 2 : récupération du jeton d'accès
$token_url = $openid_config['token_endpoint'];
$post_fields = [
    'grant_type' => 'authorization_code',
    'code' => $_GET['code'],
    'redirect_uri' => $openid_config['redirect_uri'],
    'client_id' => $openid_config['client_id'],
    'client_secret' => $openid_config['client_secret']
];

// Définition de la constante CURLOPT_RETURNTRANSFER
define('CURLOPT_RETURNTRANSFER', 1);

$curl_options = [
    CURLOPT_RETURNTRANSFER => true
];
$curl_handle = curl_init($token_url);
curl_setopt_array($curl_handle, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_fields,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded'
    ],
    CURLOPT_RETURNTRANSFER => true
]);
$token_response = curl_exec($curl_handle);
curl_close($curl_handle);

$token_data = json_decode($token_response, true);
$access_token = $token_data['access_token'];

// Étape 3 : récupération des informations utilisateur
$userinfo_url = $openid_config['userinfo_endpoint'];
$curl_handle = curl_init($userinfo_url);
curl_setopt_array($curl_handle, [
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $access_token
    ],
    CURLOPT_RETURNTRANSFER => true
]);
$userinfo_response = curl_exec($curl_handle);
curl_close($curl_handle);

$userinfo_data = json_decode($userinfo_response, true);
$email = $userinfo_data['email'];
$name = $userinfo_data['name'];

// Stocker les informations utilisateur dans la session
$_SESSION['email'] = $email;
$_SESSION['name'] = $name;

// Rediriger vers la page d'accueil
header('Location: home.php');
exit();
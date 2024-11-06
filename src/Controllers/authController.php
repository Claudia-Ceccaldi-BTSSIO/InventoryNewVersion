<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class AuthController
{
    // ID du client (clientId) de l'application Microsoft Entra ID.
    // Obtenez-le dans les paramètres de votre application dans Microsoft Entra ID (Azure AD).
    private $clientId = '123c36d1-0572-4d89-9775-23e0c551a870';

    // ID du locataire (tenantId) de votre annuaire Azure AD. Il est également accessible depuis le portail Microsoft Entra ID.
    private $tenantId = '1d284a89-eb7c-404f-92ab-8197fa00f0a8';

    // Secret du client (clientSecret) généré dans le portail Microsoft Entra ID.
    // Ce champ doit être généré et stocké en toute sécurité.
    private $clientSecret = 'BB48Q~LuOYdHXpxBd~TW.ZLFq-r57t.P1jNdFa-g';

    // URI de redirection (redirectUri) configurée dans le portail Microsoft Entra ID.
    // Assurez-vous que cette URL correspond à celle indiquée dans le portail.
    private $redirectUri = 'http://localhost:8000/callback.php';

    // URL de base pour Microsoft Entra ID (Azure AD).
    private $authBaseUrl = "https://login.microsoftonline.com";
    private $graphBaseUrl = "https://graph.microsoft.com/v1.0";

    // URL de connexion pour rediriger vers Microsoft Entra ID
    public function getLoginUrl() {
        return "{$this->authBaseUrl}/{$this->tenantId}/oauth2/v2.0/authorize" .
               "?client_id={$this->clientId}" .
               "&response_type=code" .
               "&redirect_uri=" . urlencode($this->redirectUri) .
               "&scope=" . urlencode("openid profile email User.Read");
    }

    // Redirection vers Microsoft Entra ID pour la connexion
    public function redirectToEntraID() {
        header("Location: " . $this->getLoginUrl());
        exit();
    }

    // Gestion du callback pour obtenir le token d'accès
    public function handleCallback() {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $tokenUrl = "{$this->authBaseUrl}/{$this->tenantId}/oauth2/v2.0/token";
    
            $params = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->redirectUri,
                'scope' => 'User.Read'
            ];
    
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $tokenUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
    
            // Decode response and check for errors
            $data = json_decode($response, true);
    
            if ($httpCode === 200 && isset($data['access_token'])) {
                $this->fetchUserData($data['access_token']);
            } else {
                // Log response for debugging
                echo "Erreur d'authentification avec Microsoft Entra ID<br>";
                echo "Code HTTP : $httpCode<br>";
                echo "Réponse complète :<pre>" . print_r($data, true) . "</pre>";
                $this->handleError("Erreur lors de l'authentification.");
            }
        } else {
            $this->handleError("Code d'autorisation non reçu.");
        }
    }
    
    // Récupération des données utilisateur depuis Microsoft Graph API
    private function fetchUserData($accessToken) {
        $graphUrl = "{$this->graphBaseUrl}/me";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $graphUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken"
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $userData = json_decode($response, true);

        if ($httpCode === 200 && isset($userData['id'])) {
            // Stockage des informations utilisateur en session
            $_SESSION['id_user'] = $userData['id'];
            $_SESSION['username'] = $userData['displayName'];
            $_SESSION['email'] = $userData['mail'] ?? '';

            // Redirection vers la page principale après connexion
            header("Location: http://localhost:8000/src/Views/parcView.php");
            exit();
        } else {
            $this->handleError("Impossible de récupérer les informations utilisateur.");
        }
    }

    // Gestion des erreurs avec redirection
    private function handleError($message) {
        $_SESSION['error_message'] = $message;
        header("Location: http://localhost:8000/src/Views/parcView.php");
        exit();
    }
}

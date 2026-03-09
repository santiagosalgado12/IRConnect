<?php

namespace App\Controllers;
#INCLUYE LOS MODELOS NECESARIOS
use CodeIgniter\Controller;

use App\Models\Ordenes;

class Paypal extends BaseController{

    //ATRIBUTOS NECESARIOS PARA EL MODULO DE PAYPAL

    private $clientId="AU7kXT2lcfGitavBqTNmysdQ9Z3tS04lx8PYLnqs41sTEV5LKJvxgUv2kawJJt-aSxlHJdT3vYAmslFv";

    private $clientSecret ="EJCqeNo4AUc6FDJNk7pV7w5KgYZJy1dwUwlCnKxg3Mt2IXfO-FIuLVFlEWrdykgePW5Amn-FtMhpBPzN";


     //MODULO DE PAYPAL

     private function getAccessToken()
     {
         $url = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
         $credentials = base64_encode("$this->clientId:$this->clientSecret");
 
         $options = [
             "http" => [
                 "header" => "Authorization: Basic $credentials\r\n" .
                             "Content-Type: application/x-www-form-urlencoded\r\n",
                 "method" => "POST",
                 "content" => "grant_type=client_credentials"
             ]
         ];
         $context = stream_context_create($options);
         $result = file_get_contents($url, false, $context);
         return json_decode($result, true)["access_token"] ?? null;
     }
 
     // Crear una orden en PayPal
     public function createOrder()
     {
    $input = $this->request->getJSON();
    $amount = $input->amount ?? "59.99"; 
    $quantity = $input->quantity ?? 1; // Nueva línea
    $unitPrice = $input->unit_price ?? 59.99;// Monto por defecto si no se envía

         $accessToken = $this->getAccessToken();
         if (!$accessToken) {
             return $this->response->setJSON(["error" => "No se pudo obtener el token"])->setStatusCode(500);
         }
 
         $url = "https://api-m.sandbox.paypal.com/v2/checkout/orders";
    $body = json_encode([
        "intent" => "CAPTURE",
        "purchase_units" => [
            [
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $amount,
                    "breakdown" => [ // Nuevo bloque
                        "item_total" => [
                            "currency_code" => "USD",
                            "value" => $amount
                        ]
                    ]
                ],
                "items" => [ // Nuevo bloque completo
                    [
                        "name" => "IRConnect",
                        "description" => "Dispositivo de control remoto por infrarrojo",
                        "quantity" => $quantity,
                        "unit_amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($unitPrice, 2, '.', '')
                        ]
                    ]
                ]
            ]
        ]
    ]);
 
         $options = [
             "http" => [
                 "header" => "Authorization: Bearer $accessToken\r\n" .
                             "Content-Type: application/json\r\n",
                 "method" => "POST",
                 "content" => $body
             ]
         ];
         $context = stream_context_create($options);
         $result = file_get_contents($url, false, $context);
         $orderResponse = json_decode($result, true);
         
         // Guardar datos de la orden en sesión para usar en capture
         if (isset($orderResponse['id'])) {
             $session = session();
             $session->set('paypal_order_' . $orderResponse['id'], [
                 'quantity' => $quantity,
                 'unit_price' => $unitPrice,
                 'total_amount' => $amount
             ]);
         }
 
         return $this->response->setJSON($orderResponse);
     }
 
     // Capturar el pago
     public function captureOrder()
     {
        $ordermodel=new Ordenes;
         $input = $this->request->getJSON();
         $orderID = $input->orderID ?? null;
 
         if (!$orderID) {
             return $this->response->setJSON(["error" => "No se recibió un Order ID"])->setStatusCode(400);
         }
 
         $accessToken = $this->getAccessToken();
         if (!$accessToken) {
             return $this->response->setJSON(["error" => "No se pudo obtener el token"])->setStatusCode(500);
         }
 
         $url = "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderID/capture";
         $options = [
             "http" => [
                 "header" => "Authorization: Bearer $accessToken\r\n" .
                             "Content-Type: application/json\r\n",
                 "method" => "POST"
             ]
         ];
         $context = stream_context_create($options);
         $result = file_get_contents($url, false, $context);
         $resultData = json_decode($result, true);
         
         // Recuperar datos de la orden desde la sesión
         $session = session();
         $orderData = $session->get('paypal_order_' . $orderID);
         
         if ($orderData) {
             // Usar datos guardados de createOrder
             $quantity = (int)$orderData['quantity'];
             $unitPrice = (float)$orderData['unit_price'];
             $totalAmount = (float)$orderData['total_amount'];
             
             // Limpiar la sesión
             $session->remove('paypal_order_' . $orderID);
         } else {
             // Fallback: intentar extraer de la respuesta de PayPal
             $purchaseUnits = $resultData['purchase_units'][0] ?? [];
             $totalAmount = (float)($purchaseUnits['amount']['value'] ?? 0);
             $items = $purchaseUnits['items'] ?? [];
             $quantity = 1;
             $unitPrice = 59.99;
             
             if (!empty($items) && isset($items[0])) {
                 $quantity = (int)($items[0]['quantity'] ?? 1);
                 $unitPrice = (float)($items[0]['unit_amount']['value'] ?? 59.99);
             } else if ($totalAmount > 0) {
                 $quantity = (int)round($totalAmount / $unitPrice);
             }
         }
         
         // Debug: Enviar email con todos los datos extraídos
         $debugInfo = "Source: " . ($orderData ? "Session" : "PayPal Response") . "<br>" .
                     "Total Amount: $totalAmount<br>" .
                     "Quantity: $quantity<br>" .
                     "Unit Price: $unitPrice<br>" .
                     "Order Data from session: " . json_encode($orderData);
                  
        $paymentSource = $resultData['payment_source'] ?? [];
        $payer = $resultData['payer'] ?? [];
if (isset($paymentSource['paypal'])) {
    // Pago con PayPal
    $email = $paymentSource['paypal']['email_address'] ?? '';
    $nombre = $paymentSource['paypal']['name']['given_name'] ?? '';
    $apellido = $paymentSource['paypal']['name']['surname'] ?? '';
    $direccion = $resultData['purchase_units'][0]['shipping']['address'] ?? [];
} elseif (isset($paymentSource['card'])) {
    // Pago con Tarjeta
    $email = $payer['email_address'] ?? '';
    $nombre = $payer['name']['given_name'] ?? '';
    $apellido = $payer['name']['surname'] ?? '';
    $direccion = $payer['address'] ?? [];
} else {
    $email = $payer['email_address'] ?? '';
    $nombre = $payer['name']['given_name'] ?? '';
    $apellido = $payer['name']['surname'] ?? '';
    $direccion = $resultData['purchase_units'][0]['shipping']['address'] ?? [];
}

// Extraer dirección
$linea1 = $direccion['address_line_1'] ?? '';
$linea2 = $direccion['address_line_2'] ?? '';
$ciudad = $direccion['admin_area_2'] ?? '';
$estado = $direccion['admin_area_1'] ?? '';
$codigoPostal = $direccion['postal_code'] ?? '';
$pais = $direccion['country_code'] ?? '';

// Guardar en la base de datos
    $data = [
        'email' => $email,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'linea1' => $linea1,
        'linea2' => $linea2,
        'ciudad' => $ciudad,
        'estado' => $estado,
        'codigo_postal' => $codigoPostal,
        'pais' => $pais,
        'cantidad' => $quantity, // Nueva línea
        'precio_unitario' => $unitPrice, // Nueva línea
        'total' => $totalAmount // Nueva línea
    ];

$ordermodel->insertOrder($data);

// Enviar correo con los datos y la estructura JSON para debug
$resultDataJson = json_encode($resultData, JSON_PRETTY_PRINT);
\Config\Services::sendEmail($email, '¡Gracias por comprar IRConnect!', 
    "<h1>Su compra ha sido cargada en nuestro sistema</h1>
    <br><br>Cuando reciba el producto, ya podrá disfrutar de todas las funciones de IRConnect.
    "
);

return $this->response->setJSON($resultData);
     
}
 


}
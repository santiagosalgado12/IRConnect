<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar aquí</title>

 <!-- Favicons -->
 <link href="assets/img/logo1.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

</head>
<body>
<section id="pricing" class="pricing section light-background">

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <h2>Adquiere tu IRConnect</h2>
  <p>Adquiere el producto pagando una única vez accediendo a todos los beneficios</p>
</div><!-- End Section Title -->

<div class="container" data-aos="fade-up" data-aos-delay="100">

  <div class="row g-4 justify-content-center">


    <!-- Standard Plan -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
      <div class="pricing-card popular">
        <h3>IRConnect</h3>
        <div class="price">
          <span class="currency">US$</span>
          <span class="amount">59.99</span>
        </div>
        <p class="description">Paga el producto utilizando la plataforma PayPal. Con este único pago, obtendrás el producto y todas sus características</p>

        <h4>Elementos incluídos:</h4>
        <ul class="features-list">
          <li>
            <i class="bi bi-check-circle-fill"></i>
            Acceso total a las características del producto
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            Acceso a una cuenta administrador dentro del sitio web
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            Posibilidad de controlar dispositivos de forma ilimitada
          </li>
          <li>
            <i class="bi bi-check-circle-fill"></i>
            Creación de usuarios ilimitados para poder controlar los dispositivos configurados
          </li>
        </ul>
        <div class="quantity-selector mb-3">
<label for="quantity" class="form-label">Cantidad:</label>
<div style="max-width: 150px;">
<input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="10" oninput="updateTotal()">
</div>
</div>
<div class="total-display mb-3">
<strong id="total-label">Total: $</strong><span id="total-amount">59.99</span>
</div>
        <div id="paypal-button-container"></div>
        <p id="result-message"></p>

      </div>
    </div>

    
  </div>

</div>

</section><!-- /Pricing Section -->

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AU7kXT2lcfGitavBqTNmysdQ9Z3tS04lx8PYLnqs41sTEV5LKJvxgUv2kawJJt-aSxlHJdT3vYAmslFv&currency=USD&disable-funding=credit,card,paylater&enable-funding=paypal"></script>

<script>
  paypal.Buttons({
      createOrder: function(data, actions) {
                const quantity = parseInt(document.getElementById('quantity').value);
      const unitPrice = 59.99;
      const totalAmount = (quantity * unitPrice).toFixed(2);

      

          return fetch("<?= base_url('paypal/createOrder') ?>", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({
                amount: totalAmount,
                quantity: quantity,
                unit_price: unitPrice
              }),
          })
          .then(response => response.json())
          .then(order => order.id);
      },

      onApprove: function(data, actions) {
          return fetch("<?= base_url('paypal/captureOrder') ?>", {
              method: "POST",
              headers: {
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({
                  orderID: data.orderID
              }),
          })
          .then(response => response.json())
          .then(order => {
              alert("Pago realizado con éxito. Muchas gracias! En instantes le llegará un mail a la dirección ingresada para la compra. ");
          })
          .catch(error => console.error("Error al capturar el pago:", error));
      }
  }).render("#paypal-button-container");

function updateTotal() {
  const quantity = parseInt(document.getElementById('quantity').value);
  const paypalContainer = document.getElementById('paypal-button-container');
  
  if(quantity < 1 || quantity > 10 || quantity === "" || isNaN(quantity)) {
      document.getElementById('total-label').textContent = "Cantidad no válida: ";
      document.getElementById('total-amount').textContent = "seleccione una cantidad entre 1 y 10";
      paypalContainer.style.display = "none"; // Ocultar botón de PayPal
      return;
  }
  
  // Si la cantidad es válida, mostrar el total normal y el botón de PayPal
  document.getElementById('total-label').textContent = "Total: $";
  const unitPrice = 59.99;
  const total = (quantity * unitPrice).toFixed(2);
  document.getElementById('total-amount').textContent = total;
  paypalContainer.style.display = "block"; // Mostrar botón de PayPal
}
</script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>

</body>
</html>
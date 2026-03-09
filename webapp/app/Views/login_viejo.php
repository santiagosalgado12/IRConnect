<?php
$session=session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo base_url("/img/logo1.png") ;?>">
	  <link rel="stylesheet" href="<?php echo base_url("/css/estilo.css") . '?v=' . time(); ?>">
    <title>Inicia sesión en el sitio</title>
</head>
<body>

    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>
    <div class="circulo"></div>

	
<div class="login-wrap">
  <div class="form">
      
      <ul class="tab-group">
        <li class="tab active"><a href="#login">INICIAR SESION</a></li>
        <li class="tab"><a href="#signup">REGISTRARSE</a></li>
      </ul>
      
      <div class="tab-content">
        
        <div id="login">   
          
          <form action="<?php echo base_url("/login");?> " method="post">
          
            <div class="field-wrap">
            <label>
              Ingrese usuario<span class="req">*</span>
            </label>
            <input type="text" id="user" class="input"  required name="username">

          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" id="pass" class="input"  required name="password">
          </div>
          
          <div class="group">
					<input type="checkbox" name="remember">
        			<label for="remember">Recordarme</label><br>
				</div>
	        <?php

	            $error_data = $session->getFlashdata("error");
	            $success_data = $session->getFlashdata("success");
                if($error_data){
		        if(!is_array($error_data)){
			    echo $error_data ;
		        }else{
			    foreach ($error_data as $e){
				echo $e."<br>";
		        }
			
	        }
			
	    }

	            if($success_data){
		        echo $success_data;
	        }
	
	?>
				<div class="foot-lnk">
					<p class="forgot"><a href="<?php echo base_url("/reset");?>">Olvidaste la contraseña?</a></p>
				</div>
				<button type="submit" class="button button-block"/>INICIAR SESION</button>
          </form>

        </div>
      
        <div id="signup">             
          <form action="<?php echo base_url("/register");?> " method="post">
          
          <div class="top-row">
            <div class="field-wrap">
              <label>
                NOMBRE DE USUARIO<span class="req">*</span>
              </label>
              <input id="user" type="text" class="input" required name="username">
            </div>
        
            <div class="field-wrap">
              <label>
                CONTRASEÑA<span class="req">*</span>
              </label>
              <input id="pass" type="password" class="input" data-type="password" required name="password">
            </div>
          </div>

          <div class="field-wrap">
            <label>
              CONFIRME CONTRASEÑA<span class="req">*</span>
            </label>
            <input id="pass" type="password" class="input" data-type="password" required name="password_confirm">
          </div>
          
          <div class="field-wrap">
            <label>
              CORREO ELECTRONICO<span class="req">*</span>
            </label>
            <input id="pass" type="mail" class="input" required name="email">
          </div>
          
          <button type="submit" class="button button-block"/>REGISTRARME</button>
          
          </form>

        </div>
      </div>
    </div> 
</div>
<script src="/public/js/login.js"></script>
</body>
</html>
<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$pass = ""; // Cambia por tu contraseña si la tienes
$db = "miapp";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = $_POST["telefono"];
    $password = $_POST["password"];
    $confirmar = $_POST["confirmar_password"];
    $codigo_invitacion = $_POST["codigo_invitacion"];

    if ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (telefono, password, codigo_invitacion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $telefono, $hashed_password, $codigo_invitacion);

        if ($stmt->execute()) {
            header("Location: home.html");
            exit();
        } else {
            $error = "Error al registrar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #e0f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }
    h2 {
      text-align: center;
      color: #00796b;
      margin-bottom: 2rem;
      font-size: 1.6rem;
    }
    .input-group {
      position: relative;
      margin-bottom: 1.8rem;
    }
    .input-group input {
      width: 100%;
      padding: 1rem 0.75rem 0.25rem;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 1rem;
      background: white;
      outline: none;
    }
    .input-group label {
      position: absolute;
      top: 1rem;
      left: 0.75rem;
      color: #999;
      font-size: 1rem;
      pointer-events: none;
      transition: 0.2s ease all;
      background-color: white;
      padding: 0 0.2rem;
    }
    .input-group input:focus + label,
    .input-group input:not(:placeholder-shown) + label {
      top: 0.4rem;
      font-size: 0.75rem;
      color: #00796b;
    }
    .phone-prefix {
      display: flex;
    }
    .phone-prefix span {
      padding: 1rem 0.75rem;
      background-color: white;
      border: 1px solid #ccc;
      border-right: none;
      border-radius: 10px 0 0 10px;
      font-weight: bold;
      color: #00796b;
    }
    .phone-prefix input {
      border-radius: 0 10px 10px 0;
      border-left: none;
    }
    button {
      width: 100%;
      padding: 0.85rem;
      background-color: #00796b;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background-color: #00695c;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <form class="form-container" method="POST">
    <h2>Registro</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <div class="input-group phone-prefix">
      <span>+57</span>
      <input type="text" name="telefono" id="telefono" placeholder=" " required>
      <label for="telefono">Número de teléfono</label>
    </div>

    <div class="input-group">
      <input type="password" name="password" id="password" placeholder=" " required>
      <label for="password">Contraseña</label>
    </div>

    <div class="input-group">
      <input type="password" name="confirmar_password" id="confirmar_password" placeholder=" " required>
      <label for="confirmar_password">Confirmar contraseña</label>
    </div>

    <div class="input-group">
      <input type="text" name="codigo_invitacion" id="codigo_invitacion" placeholder=" " required>
      <label for="codigo_invitacion">Código de Invitación</label>
    </div>

    <button type="submit">Registrarse</button>
  </form>
</body>
</html>

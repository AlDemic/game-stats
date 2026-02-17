<?php 
    require_once __DIR__ . "/core/init.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Statistic</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
   <div class="layout">
      <!--Site header: games as ctgries + game logo-->
      <?php include MODELS_PATH . 'navigation/header.php'; ?>

      <!--main content block by js-->
      <main class='main'>
         <?php include MODELS_PATH . 'gameDetails.php'; ?>
      </main>
   </div>

   <script src='<?php echo JS_PATH ?>getStats.js' defer></script>
   <script src='<?php echo JS_PATH ?>date-state.js' defer></script>
   <script src='<?php echo JS_PATH ?>render.js' defer></script>
   <script src='<?php echo JS_PATH ?>navigation/header.js' defer></script>
   <script src='<?php echo JS_PATH ?>routes.js' defer></script>
</body>
</html>

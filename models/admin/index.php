<?php 
    require_once dirname(__DIR__, 2) . "/core/init.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Statistic - Admin</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
   <div class="layout">
      <!--Site header: games as ctgries + game logo-->
      <?php include MODELS_PATH . 'navigation/header.php'; ?>

      <!--Main part-->
      <main class="main">
        <div class="admin-menu">
          <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1): ?>
            
              <h3>Choose admin action:</h3>
              <a href='api/add-game.php' class="admin-btn">Add game</a>
              <a href='api/add-records.php?stat=online' class="admin-btn">Add game's online</a>
              <a href='api/add-records.php?stat=income' class="admin-btn">Add game's income</a>
              <a href='api/del-records.php?stat=online' class="admin-btn">Delete game's online</a>
              <a href='api/del-records.php?stat=income' class="admin-btn">Delete game's income</a>
              <a href='api/parser-controller.php' class="admin-btn">Parser</a>
          <?php else: 
              require_once MODELS_PATH . '/admin/login.php';
              endif; 
          ?>
          <a href='/' class="back-btn">Back to Main</a>
        </div>
      </main>
   </div>
</body>
</html>

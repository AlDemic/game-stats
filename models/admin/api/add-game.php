<?php 
    include_once dirname(__DIR__, 3) . "/core/init.php";

    //admin only
    adminOnly();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Statistic - Add game</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
   <div class="layout">
      <!--Site header: games as ctgries + game logo-->
      <?php include MODELS_PATH . 'navigation/header.php'; ?>

      <!--Main part-->
      <main class="main">
        <div class="sys-msg"></div>
        <form id="add-game" class="admin__add-game" enctype="multipart/form-data">
            <label>
                <b>Game title:</b>
                <input type="text" name="title" minlength="3" maxlength="64" placeholder="Put title of game" required/>
            </label>
            <label>
                <b>Realesed year:</b>
                <input type="number" name="year" minlength="4" maxlength="4" placeholder="When published" required/>
            </label>
            <input type="file" name="logo"/>
            <button type="submit" class="admin-btn">Add game</button>
        </form>
        <!--Admin BACK block-->
        <?php include 'back-block.php'; ?>
      </main>
   </div>
   <script src="<?php echo JS_PATH ?>admin/add-game.js" defer></script> 
</body>
</html>

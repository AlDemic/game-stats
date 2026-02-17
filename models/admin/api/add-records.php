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
  <title>Game Statistic - Add records</title>
  <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
   <div class="layout">
      <!--Site header: games as ctgries + game logo-->
      <?php include MODELS_PATH . 'navigation/header.php'; ?>

      <!--Main part-->
      <main class="main">
        <div class="games-list"></div>
        <div class="sys-msg"></div>
        <form id="add-record" class="admin__add-record">
            <label>
                <b>Game ID:</b>
                <input type="number" name="id" minlength="1" maxlength="10" placeholder="Put game id" required/>
            </label>
            <label>
                <b>Month and year:</b>
                <input type="month" name="date" required/>
            </label>
            <label>
                <b>Number:</b>
                <input type="number" name="stat_number" minlength="1" maxlength="10" placeholder="Put n param" required/>
            </label>
            <label>
                <b>Source(<small>!Month can't have 2+ same sources):</small></b>
                <input type="text" name="source" minlength="3" maxlength="128" placeholder="Put param source" required/>
            </label>
            <button type="submit"  class="admin-btn">Add game's record</button>
        </form>
        <!--Admin BACK block-->
        <?php include 'back-block.php'; ?>
      </main>
   </div>
   <script src="<?php echo JS_PATH ?>admin/games-list.js" defer></script> 
   <script src="<?php echo JS_PATH ?>admin/add-records.js" defer></script> 
</body>
</html>
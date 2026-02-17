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
  <title>Game Statistic - Del records</title>
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
        <!--block to see game records per month from db-->
        <div class="month-info">
            <label>
                <b>Game ID:</b>
                <input type="number" id="month-info__id" minlength="1" maxlength="10" placeholder="Put game id" required/>
            </label>
            <label>
                <b>Month:</b>
                <input type="month" id="month-info__date" name="month-info__date" required/>
            </label>
            <button id="month-info__show" class="admin-btn">Show records</button>
            <div class="month-info__records"></div>
        </div>

        <div class='records-del'>
            <div class="records-del__btns">
                <button data-mode="one">Delete 1 record</button>
                <button data-mode="month">Delete 1 month records</button>
            </div>

            <!--FORM for deleting 1 record from DB-->
            <form id="del-one" class="del-form hidden">
                <input type="hidden" name="mode" value="one" />
                <label>
                    <b>Game ID:</b>
                    <input type="number" name="del-id" minlength="1" maxlength="10" placeholder="Put game id" required/>
                </label>
                <label>
                    <b>Source:</b>
                    <input type="text" name="del-one__source" minlength="3" maxlength="128" placeholder="Put source here" required/>
                </label>
                <label>
                    <b>Month:</b>
                    <input type="month" name="del-date" required/>
                </label>
                <button type="submit" class="admin-btn">Delete</button>
            </form> 

            <!--FORM to delete ALL records for month-->
            <form id="del-month" class="del-form hidden">
                <input type="hidden" name="mode" value="month" />
                <label>
                    <b>Game ID:</b>
                    <input type="number" name="del-id" minlength="1" maxlength="10" placeholder="Put game id" required/>
                </label>
                <label>
                    <b>Month:</b>
                    <input type="month" name="del-date" required/>
                </label>
                <button type="submit" class="admin-btn">Delete</button>
            </form> 
        </div>
        
        <!--Admin BACK block-->
        <?php include 'back-block.php'; ?>
      </main>
   </div>
   <script src="<?php echo JS_PATH ?>admin/games-list.js" defer></script> 
   <script src="<?php echo JS_PATH ?>admin/get-month-records.js" defer></script> 
   <script src="<?php echo JS_PATH ?>admin/form-hidden-control.js" defer></script> 
   <script src="<?php echo JS_PATH ?>admin/del-records.js" defer></script> 
</body>
</html>
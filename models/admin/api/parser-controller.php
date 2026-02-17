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
  <title>Game Statistic - Parser</title>
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
        <!--FORMS for parser selection-->
        <div class='parser'>
            <div class='parser__btns-select'>
                <button data-mode="run">Select parser</button>
                <button data-mode="loadJson">Load JSON result</button>
            </div>
            <!--select parser-->
            <form id="parser-run" class="parser-form hidden">
                <input type="hidden" name="mode" value="run" />
                <h3 style="color:red">Choose which parser to run:</h3>
                <select name="parser-run">
                    <option value="">Select parser</option>
                    <option value="zzz-activeplayerio">ZZZ - activePlayer.io</option>
                </select>
                <button type="submit" class="admin-btn">Run</button>
            </form>

            <!--Select json parser raw to load in db-->
            <form id="parser-loadJson" class="parser-form hidden">
                <input type="hidden" name="mode" value="load" />
                <small style="color:red">Select json raw file to load in db. <br/>To take only 1 date - select same date FROM - TO</small>
                <label>
                    <b>Game ID:</b>
                    <input type="number" name="id" minlength="1" maxlength="10" placeholder="Put game id" required/>
                </label>
                <label>
                    <b>Source(for db column):</b>
                    <input type="text" name="source" minlength="3" maxlength="128" placeholder="Put game source 3-128 length" required/>
                </label>
                <label>
                    <b>Stat:</b>
                    <select name="stat" id="select__stat">
                        <option value="">Choose stat</option>
                        <option value="online">Online</option>
                        <option value="income">Income</option>
                    </select>
                </label>
                <label>
                    <b>Date FROM</b>
                    <input type="month" name="date-from" required/>
                </label>
                <label>
                    <b>Date TO:</b>
                    <input type="month" name="date-to" required/>
                </label>
                <label>
                    <b>Select JSON raw:</b>
                    <select name="json-raw">
                        <option value="">Select JSON</option>
                        <option value="zzz-activeplayerio">ZZZ - activePlayer.io(online)</option>
                    </select>
                </label>
                <button type="submit" class="admin-btn">Load JSON</button>
            </form>
        </div>

        <!--Admin BACK block-->
        <?php include 'back-block.php'; ?>
      </main>
   </div>
   <script src="<?php echo JS_PATH ?>admin/games-list.js" defer></script> 
   <script src="<?php echo JS_PATH ?>admin/form-hidden-control.js" defer></script> 
   <script src="<?php echo JS_PATH ?>admin/parser-controller.js" defer></script> 

</body>
</html>
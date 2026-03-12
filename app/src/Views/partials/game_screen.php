<?php 

?>

<div class="game-screen">
    <div class="row top-row">
        <div class="col-8">
            <?php require_once(__DIR__ . '/gameLog.php'); ?>
        </div>
        <div class="col-4">
            <?php require_once(__DIR__ . '/monster_screen.php'); ?>
        </div>
    </div>
    <div class="row bottom-row mt-4">
        <div class="col-8">
            <?php require_once(__DIR__ . '/player_screen.php'); ?>
        </div>
        <div class="col-4">
            <?php require_once(__DIR__ . '/action_buttons.php'); ?>
        </div>
</div>
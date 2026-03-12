

<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<?php if(!empty($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <?php require_once(__DIR__ . '/../partials/player_screen.php'); ?>
        </div>
        <div class="col-md-8">  
            <div class="shadow-sm">
                <div class="">
                    Dungeon Log
                </div>
                <div id="game-log" class="card-body game-log">
                </div>
            </div>
            <div class="text-center">

                <button class="btn btn-secondary direction-button" data-direction="north">North</button>

                <div class="my-2">
                    <button class="btn btn-secondary direction-button" data-direction="west">West</button>
                    <button class="btn btn-secondary direction-button" data-direction="east">East</button>
                </div>

                <button class="btn btn-secondary direction-button" data-direction="south">South</button>

                <hr>

                <button id="attack-button" class="btn btn-primary">
                    Attack
                </button>

            </div>
        </div>
    </div>
</div>

<?php require_once(__DIR__ . '/../partials/footer.php'); ?>
<script src="/assets/js/gameLog.js"></script>

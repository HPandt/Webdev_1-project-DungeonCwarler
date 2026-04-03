

<?php require_once(__DIR__ . '/../partials/header.php'); ?>

<?php if(!empty($_GET['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<div class="container-fluid d-flex flex-column p-2" style="height: 100vh;">

    <!-- TOP ROW -->
    <div class="row flex-fill min-h-0">
        <!-- Monster -->
        <div class="col-md-3 d-flex flex-column min-h-0">
            <?php require_once(__DIR__ . '/../partials/monster_screen.php'); ?>
        </div>

        <!-- Game Log -->
        <div class="col-md-9 d-flex flex-column min-h-0">
            <div class="shadow-sm h-100 d-flex flex-column min-h-0">
                
                <div id="game-log" class="game-log flex-grow-1 overflow-auto">
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM ROW -->
    <div class="row flex-fill min-h-0">
        <!-- Player -->
        <div class="col-md-7 d-flex flex-column min-h-0">
            <?php require_once(__DIR__ . '/../partials/player_screen.php'); ?>
        </div>
        <div class="col-md-5 d-flex flex-column justify-content-center align-items-center min-h-0">
            <!-- Actions -->
            <div class="d-flex justify-content-center gap-3 flex-wrap">

                <button class="btn btn-outline-primary direction-button" data-direction="north">North</button>
                <button class="btn btn-outline-primary direction-button" data-direction="west">West</button>
                <button class="btn btn-outline-primary direction-button" data-direction="east">East</button>
                <button class="btn btn-outline-primary direction-button" data-direction="south">South</button>

                <button id="attack-button" style="display: none;" class="btn btn-danger">
                    Attack
                </button>

            </div>
        </div>

        
    </div>

</div>
<script src="/assets/js/gameLog.js"></script>
<?php require_once(__DIR__ . '/../partials/footer.php'); ?>


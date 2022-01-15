<?php include('./layout/header.php'); ?>

<div class="container mt-1r mb-1r">
    <div class="row">
        <div class="col-md-12">
            <div class="table-list mt-1r mb-1r" id="table-list"></div>
            <div class="mt-1r mb-1r table-info hide" id="table-info"></div>
            <div class="mt-1r mb-1r statis-wrap hide" id="statis-wrap">
                <button class="btn btn-primary mt-1r mb-1r">Generate</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-success hide" id="prev_btn">Prev</button>
            <button class="btn btn-success" id="next_btn">Next</button>
        </div>
    </div>
</div>
<script src="./assets/js/app.js"></script>
<?php include('./layout/footer.php'); ?>
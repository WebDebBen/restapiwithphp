<?php include('./layout/header.php'); ?>

<div class="container mt-1r mb-1r">
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-success hide" id="prev_btn">Prev</button>
            <button class="btn btn-success" id="next_btn">Next</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="mt-1r mb-1r" id="table-list">
                <div class="row mt-1r mb-1r">
                    <div class="col-md-12">
                        <input class="form-check-input all-check" type="checkbox" id="all-table-check">
                        <label class="form-check-label table-label" for="all-table-check">Check/Uncheck All</label>
                    </div>
                </div>
                <div class="table-list" id="table-wrap"></div>
            </div>
            <div class="mt-1r mb-1r table-info hide" id="table-info"></div>
            <div class="mt-1r mb-1r statis-wrap hide" id="statis-wrap">
                <h4 id="selected_table"></h4>
                <button class="btn btn-primary mt-1r mb-1r" id="gen_btn">Generate</button>
            </div>
        </div>
    </div>

</div>
<script src="./assets/js/app.js"></script>
<?php include('./layout/footer.php'); ?>
<?php include('./layout/header.php'); ?>

<div class="container mt-1r mb-1r database-gen">
    <div class="row">
        <div class="col-md-12">
            <h5>Server and Database</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"><label>Server Type</label></div>
        <div class="col-md-9"><label>PHP</div>
    </div>
    <div class="row">
        <div class="col-md-3"><label>Database type</lable></div>
        <div class="col-md-9"><label>My SQL</div>
    </div>
    <div class="row">
        <div class="col-md-3"><label>Table Name</label></div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="table-name-input">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"><lable>Primary Key</label></div>
        <div class="col-md-3">
            <input type="text" class="form-control" id="primary-key-input">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12"><h5>Form Table</h5></div>
    </div>
    <div class="row">
        <div class="col-md-8" id="table-property">
            <div class="table-prop-item">
                <div class="fa fa-arrows"></div>
                <div class="field-props-wrap">
                    <div class="field-prpos">
                        <div class="field-title">
                            <label>Title</lablel>
                            <input type="text" class="form-control" id="field-title">
                        </div>
                        <div class="field-type ml-1r mr-1r">
                            <lable>Type</label>
                            <select class="form-control" id="field-type">
                                <option value="varchar(255)">Text</option>
                                <option value="integer">Integer</option>
                                <option value="double">Double</option>
                                <option value="password">Password</option>
                                <option value="text">Text Area</option>
                                <option value="boolean">Check</option>
                                <option value="date">Date</option>
                                <option value="datetime">Datetime</option>
                            </select>
                        </div>
                        <div class="field-action">
                            <button class="btn btn-danger mt-24 remove-table-prop-item">Remove</button>
                        </div>
                    </div>
                    <div class="field-add-props">
                        <a href="javascript:;" class="add-props-edit">
                            SQL column name : 
                            <span class="column-name-span">Not set</span>
                            Default : 
                            <span class="column-default-span">None</span>
                            Required :
                            <span class="column-required-span"> No</span>
                            Edit ...
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-24">
        <div class="col-md-8" id="table-field-add">
            <button class="form-control btn btn-success" id="add-table-prop-item-btn"> + </button>
        </div>
    </div>
</div>



<div class="hide" id="table-prop-item-template">
    <div class="table-prop-item mt-1r">
        <div class="fa fa-arrows"></div>
        <div class="field-props-wrap">
            <div class="field-prpos">
                <div class="field-title">
                    <label>Title</lablel>
                    <input type="text" class="form-control" id="field-title">
                </div>
                <div class="field-type ml-1r mr-1r">
                    <lable>Type</label>
                    <select class="form-control" id="field-type">
                        <option value="varchar(255)">Text</option>
                        <option value="integer">Integer</option>
                        <option value="double">Double</option>
                        <option value="password">Password</option>
                        <option value="text">Text Area</option>
                        <option value="boolean">Check</option>
                        <option value="date">Date</option>
                        <option value="datetime">Datetime</option>
                    </select>
                </div>
                <div class="field-action">
                    <button class="btn btn-danger mt-24 remove-table-prop-item">Remove</button>
                </div>
            </div>
            <div class="field-add-props">
                <a href="javascript:;" class="add-props-edit">
                    SQL column name : 
                    <span class="column-name-span">Not set</span>
                    Default : 
                    <span class="column-default-span">None</span>
                    Required :
                    <span class="column-required-span"> No</span>
                    Edit ... 
                </a>
            </div>
        </div>
    </div>
</div>

<script src="./assets/js/datatable.js"></script>
<?php include('./layout/footer.php'); ?>
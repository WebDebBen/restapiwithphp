<!doctype html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>
            DataTables Editor - test
        </title>
        <link rel="stylesheet" type="text/css" href="/backend/result/assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/backend/result/assets/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="/backend/result/assets/css/style.css">
    
        <script type="text/javascript" src="/backend/result/assets/js/jquery-2.2.4.js"></script>
        <script type="text/javascript" src="/backend/result/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/backend/result/assets/js/jquery.validate.min.js"></script>
        <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="/backend/result/assets/js/table.test.js"></script>
    </head>
    <body class="dataTables">
        <div class='container'>
            <h1 class="mt-1r">
                DataTables Editor 
                <span>
                    test
                </sapn>
            </h1>
            <div class="row mt-1r mb-1r">
                <div class="col-md-12">
                    <button class="btn btn-success" id="test_new">New</button>
                </div>
            </div>
            <div class="row mt-2r">
                <div class="col-md-12">
                    <table cellpadding="0" cellspacing="0" border="0" class="display" id="test_table" width="100%">
                        <thead>
                            <tr>
                                <th>a</th>
                                <th>b</th>
                                <th>c</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="test_body"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal column-detail-modal" tabindex="-1" role="dialog" id="edit-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Test Table</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="form">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="field-default-value" class="col-sm-4 col-form-label text-right">a</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="test_field_a">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="field-default-value" class="col-sm-4 col-form-label text-right">b</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="test_field_b">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="field-default-value" class="col-sm-4 col-form-label text-right">c</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="test_field_c">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <input type="hidden" id="data-id" value="-1"/>
                        <button type="button" class="btn btn-primary" id="save_record">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
var base_url = "/backend/result/test/test.php";
var table;
var sel_tr;

$(document).ready(function(){
    init_table();
    $("#test_new").on("click", new_record);
    $("#test_body").on("click", ".edit-item", edit_record );
    $("#test_body").on("click", ".delete-item", delete_record );
    $("#save_record").on("click", save_record );
});

function save_record(){
    var id = $("#data-id").val();
    var tr_a = $("#test_field_a").val();
    var tr_b = $("#test_field_b").val();
    var tr_c = $("#test_field_c").val();

    $.ajax({
        url: base_url,
        data:{
            id: id,
            a: tr_a,
            b: tr_b,
            c: tr_c,
            type: "save"
        },
        type: "post",
        dataType: "json",
        success: function(data){
            if (data["status"] == "success" ){
                if (id == "-1"){
                    var table_id = data["insert_id"];
                    table.row.add( [
                        tr_a, tr_b, tr_c,
                        '<button class="btn btn-xs btn-sm btn-primary mr-6" data-id="' + table_id + '"><i class="fa fa-edit"></i></button><button class="btn btn-xs btn-sm btn-secondary" data-id"' + table_id + '"><i class="fa fa-trash"></i></button>'
                    ] ).draw( false );
                }else{
                    $("#test_table tr.selected").find(".test_td_a").text(tr_a );
                    $("#test_table tr.selected").find(".test_td_b").text(tr_b );
                    $("#test_table tr.selected").find(".test_td_c").text(tr_c );
                }
                $("#edit-modal").modal("show");
            }
        }
    })
}

function new_record(){
    $("#data-id").val("-1");
    $("#edit-modal").modal("show");
}

function delete_record(){
    var id = $(this).attr('data-id');
    sel_tr = $(this).parent().parent();
    
    if (confirm("Are you going to delete this record?")){
        $.ajax({
            url: base_url,
            data:{
                type: "delete",
                id: id
            },
            type:"post",
            dataType: "json",
            success: function(data){
                if (data["status"] == "success"){
                    table.row('.selected').remove().draw( false );
                }
            }
        });
    }
}

function edit_record(){
    var id = $(this).attr('data-id');
    sel_tr = $(this).parent().parent();
    
    $("#data-id").val(id );
    $("#test_field_a").val($(sel_tr).find(".test_td_a").text());
    $("#test_field_b").val($(sel_tr).find(".test_td_b").text());
    $("#test_field_c").val($(sel_tr).find(".test_td_c").text());
    $("#edit-modal").modal("show");
}

function init_table(){
    $.ajax({
        url: base_url,
        data:{
            type: "init_table"
        },
        dataType: "json",
        type: "post",
        success: function(data ){
            if (data["status"] == "success" ){
                load_data(data["data"]);
            }
        }
    });
}

function load_data(data ){
    var parent = $("#test_body");
    for(var i = 0; i < data.length; i++ ){
        var item = data[i];
        var tr = $("<tr>").attr("data-id", item[0]).appendTo(parent );
        $("<td>").text(item[1]).addClass("test_td_a").appendTo(tr);
        $("<td>").text(item[2]).addClass("test_td_b").appendTo(tr);
        $("<td>").text(item[3]).addClass("test_td_c").appendTo(tr);
        var td = $("<td>").appendTo(tr );
        $("<button>").addClass("btn btn-xs btn-sm btn-primary mr-6 edit-item")
                    .attr("data-id", item[0])
                    .html("<i class='fa fa-edit'></i>").appendTo(td );
        $("<button>").addClass("btn btn-xs btn-sm btn-secondary delete-item")
                    .attr("data-id", item[0])
                    .html("<i class='fa fa-trash'></i>").appendTo(td );
    }
    table = $("#test_table").DataTable();

    $('#test_table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
}
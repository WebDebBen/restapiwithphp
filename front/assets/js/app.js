var base_url;
var base_api_url;
var sel_tables = [];
var table_infos = [];

var wizard_index = 1;

$(document).ready(function(){
    initialize();
    load_table_list();
    $("#prev_btn").on("click", prev_wizard );
    $("#next_btn").on("click", next_wizard );
});

function initialize(){
    base_url = $("#base_url").val();
    base_api_url = $("#base_api_url").val();
    sel_tables = [];
    wizard_index = 1;
}

function load_table_list(){
    $.ajax({
        url: base_api_url + "showtables",
        data: {},
        type: "get",
        success: function(data ){
            if (data["status"] == "success"){
                init_table_list(data["data"]);
            }
        }
    })
}

function init_table_list(data ){
    var parent = $("#table-list");

    for(var i = 0; i < data.length; i++ ){
        var item = data[i];
        var table_item = $("<div>").addClass("form-check table-box").appendTo(parent );
        $("<input>").addClass("form-check-input table-check").attr("type","checkbox")
                    .attr("id", "table-check" + i )
                    .attr("data-table", item )
                    .appendTo(table_item );
        $("<label>").addClass("form-check-label table-label").attr("for", "table-check" + i )
                    .text(item ).appendTo(table_item );
    }
}

function load_table_infos(){
    $.ajax({
        url: base_api_url + "tables/info",
        data: {
            tables: sel_tables
        },
        type: "get",
        success: function(data ){
            if (data["status"] == "success"){
                init_table_info(data["data"]);
            }
        }
    })
}

function init_table_info(data ){
    console.log(data );
}

function prev_wizard(){
    switch(wizard_index ){
        case 1:
            return;
        case 2:
            table_infos = [];
            break;
    }
    wizard_index --;
    switch_wizard(wizard_index, "prev" )
}

function next_wizard(){
    switch(wizard_index ){
        case 1:
            sel_tables = get_sel_tables();
        case 2:
            table_infos = get_table_infos();
            break;
        case 3:
            return;
    }
    wizard_index ++;
    switch_wizard(wizard_index, "next" )
}

function get_sel_tables(){
    var tables = $("#table-list input.table-check:checked");
    var tmp_arr = [];
    for(var i = 0; i < tables.length; i++ ){
        var item = tables[i];
        tmp_arr.push($(item ).data("table"));
    }
    return tmp_arr;
}

function get_table_infos(){

}

function switch_wizard(index, direction ){
    switch(index ){
        case 1:
            $("#table-list").removeClass("hide");
            $("#table-info").addClass("hide");
            $("#statis-wrap").addClass("hide");

            $("#prev_btn").addClass("hide");
            $("#next_btn").removeClass("hide");
            break;
        case 2:
            $("#table-list").addClass("hide");
            $("#table-info").removeClass("hide");
            $("#statis-wrap").addClass("hide");
            $("#prev_btn").removeClass("hide");
            $("#next_btn").removeClass("hide");
            if (direction == "next" ){
                load_table_infos();
            }
            break;
        case 3:
            $("#table-list").addClass("hide");
            $("#table-info").addClass("hide");
            $("#statis-wrap").removeClass("hide");
            $("#prev_btn").removeClass("hide");
            $("#next_btn").addClass("hide");
            break;
    }
}


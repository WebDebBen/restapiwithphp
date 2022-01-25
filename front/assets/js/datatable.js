var sel_item = null;

$(document).ready(function(){
    new Sortable(document.getElementById("table-property"), {
        handle: '.fa-arrows',
        animation: 150,
        ghostClass: 'blue-background-class'
    });

    $("#add-table-prop-item-btn").on("click", add_column_block );
    $("#table-property").on("click", ".remove-table-prop-item", function(e){
        $(this).parent().parent().parent().parent().remove();
    });

    $("#table-property").on("click", ".add-props-edit", function(e){
        edit_field_config(this );
    });

    $("#save_config").on("click", save_config );
    $("#gen_sql").on("click", generate_sql );
    $("#gen_html").on("click", generate_html );
    $("#gen_javascript").on("click", generate_javascript );
    $("#gen_php").on("click", generate_php );
    $("#run_btn").on("click", run_obj );

    add_column_block();
    add_column_block();
    add_column_block();
});

function run_obj(){
    generate_content("run" );
}

function formatHTML(html) {
    var indent = '\n';
    var tab = '\t';
    var i = 0;
    var pre = [];

    html = html
        .replace(new RegExp('<pre>((.|\\t|\\n|\\r)+)?</pre>'), function (x) {
            pre.push({ indent: '', tag: x });
            return '<--TEMPPRE' + i++ + '/-->'
        })
        .replace(new RegExp('<[^<>]+>[^<]?', 'g'), function (x) {
            var ret;
            var tag = /<\/?([^\s/>]+)/.exec(x)[1];
            var p = new RegExp('<--TEMPPRE(\\d+)/-->').exec(x);

            if (p) 
                pre[p[1]].indent = indent;

            if (['area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'].indexOf(tag) >= 0) // self closing tag
                ret = indent + x;
            else {
                if (x.indexOf('</') < 0) { //open tag
                    if (x.charAt(x.length - 1) !== '>')
                        ret = indent + x.substr(0, x.length - 1) + indent + tab + x.substr(x.length - 1, x.length);
                    else 
                        ret = indent + x;
                    !p && (indent += tab);
                }
                else {//close tag
                    indent = indent.substr(0, indent.length - 1);
                    if (x.charAt(x.length - 1) !== '>')
                        ret =  indent + x.substr(0, x.length - 1) + indent + x.substr(x.length - 1, x.length);
                    else
                        ret = indent + x;
                }
            }
            return ret;
        });

    for (i = pre.length; i--;) {
        html = html.replace('<--TEMPPRE' + i + '/-->', pre[i].tag.replace('<pre>', '<pre>\n').replace('</pre>', pre[i].indent + '</pre>'));
    }

    return html.charAt(0) === '\n' ? html.substr(1, html.length - 1) : html;
}

function generate_content(type ){
    var json_data = generate_json();
    if (json_data["status"] == false ){
        toastr.error(json_data["error"]);
    }

    $.ajax({
        url: "/backend/generator/generate.php",
        data: {
            json_data: json_data["data"],
            type: type
        },
        type: "post",
        dataType: "json",
        success: function(res ){
            if (res["status"] == "success" ){
                if (type == "run" ){
                    location.href = res["data"];
                }else{
                    $("#generated_content").html("");
                    var pre = $("<pre>").addClass("code").appendTo($("#generated_content"));
                    if (type == 'html'){
                        $(pre).text(formatHTML(res["data"]));
                    }else{
                        $(pre).text(res["data"]);    
                    }
                    $('pre.code').highlight({source:1, zebra:1, indent:'space', list:'ol'});
                    $("#generated-modal .modal-title").html("Generated " + type.toUpperCase());
                    $("#generated-modal").modal("show");
                }
            }else if(res["status"] == "duplicated"){
                alert("table is duplicated");
            }
        }
    })
}

function generate_sql(){
    generate_content("sql" );
}

function generate_html(){
    generate_content("html" );
}

function generate_javascript(){
    generate_content("javascript" );
}

function generate_php(){
    generate_content("php" );
}

function save_config(){
    var title = $("#field-title-md").val();
    var type = $("#field-type-md").val();
    var required = $("#required_yes").is(":checked");
    var default_value = $("#field-default-value-md").val();
    var show_table = $("#table_yes").is(":checked");
    var editor_table = $("#editor_yes").is(":checked");

    $(sel_item).find(".field-title-input").val(title )
    $(sel_item).attr("data-columnname", title );
    $(sel_item).find(".field-default-value-input").val(default_value );
    $(sel_item).find(".field-required-input").prop("checked", required );
    $(sel_item).find(".field-show-table-input").prop("checked", show_table );
    $(sel_item).find(".field-show-editor-input").prop("checked", editor_table );
    $(sel_item).find(".field-type-input").val(type);

    $("#column-detail-modal").modal("hide");
}

function edit_field_config(obj ){
    var parent = $(obj ).parent().parent();//.find(".field-props");
    sel_item = parent;
    var title = $(parent).find(".field-title-input").val();
    var type = $(parent).find(".field-type-input").val();
    var requried = $(parent).find(".field-required-input").is(":checked");
    var default_value = $(parent).find(".field-default-value-input").val();
    var show_table = $(parent ).find(".field-show-table-input").is(":checked");
    var editor_table = $(parent).find(".field-show-editor-input").is(":checked");

    $("#field-title-md").val(title );
    $("#field-column-name-md").val(title );
    $("#field-type-md").val(type );
    if (requried ){
        $("#required_yes").click();
    }else{
        $("#required_no").click();
    }
    $("#field-default-value-md").val(default_value );
    if(show_table){
        $("#table_yes").click();
    }else{
        $("#table_no").click();
    }
    if (editor_table ){
        $("#editor_yes").click();
    }else{
        $("#editor_no").click();
    }

    $("#column-detail-modal").modal("show");
}

function add_column_block(){
    var parent = $("#table-property");
    var template = $("#table-prop-item-template").html();
    $(parent).append(template );
}

function validate_name(str ){ 
    return !str.match(/[^a-zA-Z0-9_]/);
}

function generate_json(){
    var table_name = $("#table-name-input").val();
    var primary_key  =$("#primary-key-input").val();
    
    if (table_name == "" ){
        return {"status": false, "error": "Table is required"};
    }else if (!validate_name(table_name )){
        return {"status": false, "error": "Table name only can be letter and number"};
    }

    if (primary_key == "" ){
        return {"status": false, "error": "Primary key is required"};
    }else if (!validate_name(primary_key )){
        return {"status": false, "error": "Primary key only can be letter and number"};
    }

    var columns = [];
    var cols = $("#table-property .table-prop-item");
    for(var i = 0;i < cols.length; i++ ){
        var col = cols[i];
        var title = $(col).find(".field-title-input").val();
        var type = $(col).find(".field-type-input").val();
        var requried = $(col).find(".field-required-input").is(":checked");
        var default_value = $(col).find(".field-default-value-input").val();
        var show_table = $(col ).find(".field-show-table-input").is(":checked");
        var editor_table = $(col).find(".field-show-editor-input").is(":checked");

        if (title == "" ){
            return {"status": false, "error": "Title is required"};
        }else if (!validate_name(title )){
            return {"status": false, "error": "Title only can be letter and number"};
        }

        columns.push({
            title: title,
            type: type,
            requried: requried, 
            default_value: default_value,
            show_table: show_table,
            editor_table: editor_table
        })
    }

    return {
        "status": true,
        data: {
            table_name: table_name,
            primary_key: primary_key,
            columns: columns
        }
    }
}
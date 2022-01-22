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
});

function add_column_block(){
    var parent = $("#table-property");
    var template = $("#table-prop-item-template").html();
    $(parent).append(template );
}
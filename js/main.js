$(function() {
    $( "#directory_search" ).autocomplete({
        source: "search.php",
        minLength: 2,
        search: function (){
            $("#directory_list").empty()
        },
        messages: {
            results: function() {},
            noResults: ''
        },
        response: function(e, ui){
            if (ui.content.length === 0) {
                $("#autocomplete-results").text("No results found");
            } else {
                $("#autocomplete-results").empty();
            }
        },
        create: function() {
            $(this).data('ui-autocomplete')._renderMenu = function( ul, items ) {
                console.log(items);
                $("#directory_list").loadTemplate('tpl.php', items);
            }
        }
    });
});


$("#input-add-group").autocompleter({ 
    
    source: dir + "/grupos/json",
});

$("#btn-add-group").on( "click", function() { 

    var group = $("#input-add-group").val();

    if( $.trim( group ).length > 0  ) {
        
        $("#select-groups").append( "<option value='" + group + "'>" + group + "</option>" );
        
        $("#input-add-group").val("");

        $("#input-add-group").focus();
    }
});

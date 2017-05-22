$("#input-add-group").autocompleter({ 
    
    source: dir + "/json/grupos/"
});

$("#btn-add-group").on( "click", function() { 

    var group = $("#input-add-group").val();
    
    $("#input-add-group").val("");

    if( $.trim( group ).length <= 0 ) {
        
        return;
    }

    var groupsSelect = $.map( $("#select-groups option") ,function(option) {
        return option.value;
    });

    if( $.inArray( group, groupsSelect ) === -1 ) {
         
        $.ajax({
            url: dir + "/json/grupos/existe?nome=" + group,
            dataType: "json",
            success: function(data) {

                if( data.result ) {
                
                    $("#select-groups").append( "<option value='" + group + "'>" + group + "</option>" );
                }
            }
        });
      
    }

});

$("#btn-up-group").on( "click", function() { 

    $("#select-groups option").each( function( index, option ) {
    
        if( option.selected && index > 0 ) {
            
            var prevIndex = index - 1;

            $( option ).insertBefore( $("#select-groups option:eq(" + prevIndex + ")" ) );
        }
    });
});

$("#btn-down-group").on( "click", function() { 

    $("#select-groups option").each( function( index, option ) {
    
        if( option.selected && index < ( $("#select-groups option").length - 1 ) ) {
            
            var nextIndex = index + 1;

            $( option ).insertAfter( $("#select-groups option:eq(" + nextIndex + ")" ) );
        }
    });
});

$("#btn-remove-group").on( "click", function() { 

    $("#select-groups option:selected").remove();
});

function applyRemoveCheck() {

    $( ".btn-remove-check" ).on( "click", function() { 
        var colum = $( this ).parent();
        var line = colum.parent();

        line.remove();
    });
}

applyRemoveCheck();

$( "#btn-add-check" ).on( "click", function() {
   
    var inputAttribute = $("#attribute-check-new").clone();
    var selectOperator = $("#operator-check-new").clone();
    var inputValue = $("#value-check-new").clone();
    var buttonRemove = $("#btn-remove-check-new").clone();

    inputAttribute.attr( "id", "" );
    selectOperator.attr( "id", "" );
    inputValue.attr( "id", "" );
    buttonRemove.attr( "id", "" );

    inputAttribute.attr( "name", "atributo-verificacao[]" );
    selectOperator.attr( "name", "operador-varificacao[]" );
    inputValue.attr( "name", "valor-verificacao[]" );

    var attribute = $( "<td></td>" ).append( inputAttribute );
    var operator = $( "<td></td>" ).append( selectOperator );
    var value = $( "<td></td>" ).append( inputValue );
    var remove = $( "<td></td>" ).append( buttonRemove );

    var attributeLine = $( "<tr></tr>").append( attribute )
        .append( operator )
        .append( value )
        .append( remove );

    $("#attributes-check").prepend( attributeLine );

    applyRemoveCheck();
})



function applyRemoveAttribute() {

    $( ".btn-remove-attribute" ).on( "click", function() { 
        var colum = $( this ).parent();
        var line = colum.parent();

        line.remove();
    });
}

applyRemoveAttribute();

$( "#btn-add-check" ).on( "click", function() {
   
    var inputAttribute = $("#attribute-check-new").clone();
    var selectOperator = $("#operator-check-new").clone();
    var inputValue = $("#value-check-new").clone();
    var buttonRemove = $("#btn-remove-check-new").clone();

    inputAttribute.attr( "id", "" );
    selectOperator.attr( "id", "" );
    inputValue.attr( "id", "" );
    buttonRemove.attr( "id", "" );

    inputAttribute.attr( "name", "attributes-check[]" );
    selectOperator.attr( "name", "operators-check[]" );
    inputValue.attr( "name", "values-check[]" );

    var attribute = $( "<td></td>" ).append( inputAttribute );
    var operator = $( "<td></td>" ).append( selectOperator );
    var value = $( "<td></td>" ).append( inputValue );
    var remove = $( "<td></td>" ).append( buttonRemove );

    var attributeLine = $( "<tr></tr>").append( attribute )
        .append( operator )
        .append( value )
        .append( remove );

    $("#attributes-check").prepend( attributeLine );

    applyRemoveAttribute();
});

$( "#btn-add-reply" ).on( "click", function() {
   
    var inputAttribute = $("#attribute-reply-new").clone();
    var selectOperator = $("#operator-reply-new").clone();
    var inputValue = $("#value-reply-new").clone();
    var buttonRemove = $("#btn-remove-reply-new").clone();

    inputAttribute.attr( "id", "" );
    selectOperator.attr( "id", "" );
    inputValue.attr( "id", "" );
    buttonRemove.attr( "id", "" );

    inputAttribute.attr( "name", "attributes-reply[]" );
    selectOperator.attr( "name", "operators-reply[]" );
    inputValue.attr( "name", "values-reply[]" );

    var attribute = $( "<td></td>" ).append( inputAttribute );
    var operator = $( "<td></td>" ).append( selectOperator );
    var value = $( "<td></td>" ).append( inputValue );
    var remove = $( "<td></td>" ).append( buttonRemove );

    var attributeLine = $( "<tr></tr>").append( attribute )
        .append( operator )
        .append( value )
        .append( remove );

    $("#attributes-reply").prepend( attributeLine );

    applyRemoveAttribute();
});


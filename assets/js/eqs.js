EQS = {

  _init: function() {
    var $switcher = jQuery( '#eqs-switcher' );

    $switcher.select2( {
      'width': '100%'
    } )

    $switcher.on( 'change', function() {
      var url = jQuery( this ).find( 'option:selected' ).val();
      window.location = url;
    } );
  }

};

jQuery( function( $ ) {

  EQS._init();

} );

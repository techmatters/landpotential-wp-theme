( function( $ ) {
    $( document ).on( 'ready js_event_wpv_pagination_completed js_event_wpv_parametric_search_form_updated js_event_wpv_parametric_search_results_updated', function( event, data ) {
        $('.js-wpv-filter-trigger').select2();
    });
})( jQuery );
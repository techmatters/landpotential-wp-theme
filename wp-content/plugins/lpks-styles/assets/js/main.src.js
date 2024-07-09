window.lpks = window.lpks || {};

window.lpks.styleMenus = function() {
	document
		.querySelector( '.current-menu-item' )
		?.parentNode?.classList?.add( 'toggled-on' );
};

document.addEventListener( 'DOMContentLoaded', window.lpks.styleMenus );

//# sourceMappingURL=main.src.js.map
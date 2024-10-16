window.lpks = window.lpks || {};

window.lpks.styleMenus = function() {
	const currentItem = document.querySelector( '.current-menu-item' );

	// parent
	currentItem?.parentNode?.classList?.add( 'toggled-on' );

	// grandparent UL (li > parent ul > li > grandarent ul)
	const grandParent = currentItem.parentNode.parentNode.parentNode;
	if ( 'UL' === grandParent.tagName ) {
		grandParent.classList?.add( 'toggled-on' );
	}
};

document.addEventListener( 'DOMContentLoaded', window.lpks.styleMenus );

//# sourceMappingURL=main.src.js.map
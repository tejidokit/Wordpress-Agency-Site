( function( $, settings ) {

	var happyForms = window.happyForms || {};
	window.happyForms = happyForms;

	happyForms.freeDashboard = {
		init: function() {
			$( document ).on( 'click', '#adminmenu #toplevel_page_happyforms li:last-child a', this.onUpgradeClick.bind(this) );
			$( document ).on( 'click', '#adminmenu #toplevel_page_happyforms li:eq(3) a', this.onResponsesClick.bind(this) );
			$( document ).on( 'click', '#adminmenu #toplevel_page_happyforms li:eq(4) a', this.onExportClick.bind(this) );
			$( document ).on( 'click', '.happyforms-upgrade-modal .happyforms-continue-link', this.onContinueClick.bind(this) );
			$( document ).on( 'click', '.happyforms-upgrade-modal .happyforms-export-button', this.onExportButtonClick.bind(this) );
			$( document ).on( 'click', '.happyforms-upgrade-modal .happyforms-upgrade-modal__close', this.onCloseClick.bind(this) );
		},

		onUpgradeClick: function( e ) {
			e.preventDefault();

			var $link = $(e.target);

			window.open( $link.attr('href') );
		},

		onResponsesClick: function( e ) {
			e.preventDefault();

			this.openModal( settings.responses_modal_id );
		},

		onExportClick: function( e ) {
			e.preventDefault();

			this.openModal( settings.export_modal_id );
		},

		openModal: function( modalId ) {
			tb_show( '', '#TB_inline?width=600&amp;inlineId=' + modalId );
			$( '#TB_window' ).addClass( 'happyforms-admin-modal' ).addClass( modalId );
			$( '#TB_ajaxContent' ).height( 'auto' );
		},

		closeModal: function() {
			tb_remove();
		},

		onContinueClick: function( e ) {
			e.preventDefault();

			this.closeModal();
		},

		onExportButtonClick: function( e ) {
			e.preventDefault();

			$( '.happyforms-upgrade-modal .happyforms-export-button' ).hide();
			$( '.happyforms-upgrade-modal form' ).addClass( 'shown' );
		},

		onCloseClick: function( e ) {
			e.preventDefault();

			this.closeModal();
		}
	};

	$( document ).ready( function() {
		happyForms.freeDashboard.init();
	} );

} )( jQuery, _happyFormsDashboardSettings );

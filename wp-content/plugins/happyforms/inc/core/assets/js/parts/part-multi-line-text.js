( function( $, _, Backbone, api, settings ) {

	happyForms.classes.models.parts.multi_line_text = happyForms.classes.models.Part.extend( {
		defaults: function() {
			return _.extend(
				{},
				settings.formParts.multi_line_text.defaults,
				_.result( happyForms.classes.models.Part.prototype, 'defaults' ),
			);
		},
	} );

	happyForms.classes.views.parts.multi_line_text = happyForms.classes.views.Part.extend( {
		template: '#customize-happyforms-multi-line-text-template',

		initialize: function() {
			happyForms.classes.views.Part.prototype.initialize.apply(this, arguments);

			this.listenTo( this.model, 'change:placeholder', this.onPlaceholderChange );
			this.listenTo( this.model, 'change:character_limit', this.onCharacterLimitChange );
			this.listenTo( this.model, 'change:character_limit_mode', this.onCharacterLimitChange );
		},

		/**
		 * Send updated placeholder value to previewer. Added as a special method
		 * because of 'textarea' selector used instead of 'input'.
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		onPlaceholderChange: function() {
			var data = {
				id: this.model.get( 'id' ),
				callback: 'onMultiLineTextPlaceholderChange',
			};

			happyForms.previewSend( 'happyforms-part-dom-update', data );
		},

		onCharacterLimitChange: function() {
			var model = this.model;

			model.fetchHtml( function( response ) {
				var data = {
					id: model.get( 'id' ),
					html: response,
				};

				happyForms.previewSend( 'happyforms-form-part-refresh', data );
			} );
		},
	} );

	happyForms.previewer = _.extend( happyForms.previewer, {
		onMultiLineTextPlaceholderChange: function( id, html, options ) {
			var part = this.getPartModel( id );
			var $part = this.getPartElement( html );

			this.$( 'textarea', $part ).attr( 'placeholder', part.get( 'placeholder' ) );
		},
	} );

} ) ( jQuery, _, Backbone, wp.customize, _happyFormsSettings );

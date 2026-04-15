( function( wp, wc ) {
    if ( ! wp || ! wc || ! wc.wcBlocksRegistry || ! wc.wcSettings ) {
        return;
    }

    var settings = wc.wcSettings.getSetting( 'woo_mpgs_data', {} );

    if ( ! settings || ! settings.title ) {
        return;
    }

    var createElement = wp.element.createElement;
    var decodeEntities = wp.htmlEntities && wp.htmlEntities.decodeEntities
        ? wp.htmlEntities.decodeEntities
        : function( value ) {
            return value;
        };

    var label = createElement(
        'span',
        { className: 'woo-mpgs-blocks__label' },
        createElement(
            'span',
            { className: 'woo-mpgs-blocks__label-text' },
            settings.title
        ),
        settings.icon_url ? createElement( 'img', {
            src: settings.icon_url,
            alt: settings.title,
            className: 'woo-mpgs-blocks__label-icon',
        } ) : null
    );

    var content = createElement(
        'span',
        null,
        decodeEntities( settings.description || '' )
    );

    wc.wcBlocksRegistry.registerPaymentMethod( {
        name: 'woo_mpgs',
        label: label,
        ariaLabel: settings.title,
        content: content,
        edit: content,
        canMakePayment: function() {
            return true;
        },
        paymentMethodId: 'woo_mpgs',
        supports: {
            features: settings.supports || [ 'products' ],
        },
    } );
} )( window.wp, window.wc );

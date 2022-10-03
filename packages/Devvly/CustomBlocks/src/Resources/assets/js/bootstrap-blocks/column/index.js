import edit, { bgColorOptions } from './edit';

const { InnerBlocks } = wp.blockEditor || wp.editor; // Fallback to 'wp.editor' for backwards compatibility

const Column = {
    title: 'Column',
    icon: 'menu',
    category: 'wp-bootstrap-blocks',
    parent: [ 'wp-bootstrap-blocks/row' ],
    attributes: {
        sizeXl: {type: 'number', default: 0},
        sizeLg: {type: 'number', default: 0},
        sizeMd: {type: 'number', default: 0},
        sizeSm: {type: 'number', default: 0},
        sizeXs: {type: 'number', default: 12},
        equalWidthXl: {type: 'boolean', default: false},
        equalWidthLg: {type: 'boolean', default: false},
        equalWidthMd: {type: 'boolean', default: false},
        equalWidthSm: {type: 'boolean', default: false},
        equalWidthXs: {type: 'boolean', default: false},
        bgColor: {type: 'string', default: ''},
        padding: {type: 'string', default: ''},
        centerContent: {type: 'boolean', default: false}
    },

    // attributes are defined server side with register_block_type(). This is needed to make default attributes available in the blocks render callback.

    getEditWrapperProps( attributes ) {
        const {
            sizeXl,
            sizeLg,
            sizeMd,
            sizeSm,
            sizeXs,
            equalWidthXl,
            equalWidthLg,
            equalWidthMd,
            equalWidthSm,
            equalWidthXs,
            bgColor,
            padding,
            centerContent,
        } = attributes;

        // Prepare styles for selected background-color
        let style = {};
        if ( bgColor ) {
            const selectedBgColor = bgColorOptions.find(
                ( bgColorOption ) => bgColorOption.name === bgColor
            );
            if ( selectedBgColor ) {
                style = {
                    backgroundColor: selectedBgColor.color,
                };
            }
        }

        return {
            'data-size-xs':
                equalWidthXl ||
                equalWidthLg ||
                equalWidthMd ||
                equalWidthSm ||
                equalWidthXs
                    ? 0
                    : sizeXs,
            'data-size-sm':
                equalWidthXl || equalWidthLg || equalWidthMd || equalWidthSm
                    ? 0
                    : sizeSm,
            'data-size-md':
                equalWidthXl || equalWidthLg || equalWidthMd ? 0 : sizeMd,
            'data-size-lg': equalWidthXl || equalWidthLg ? 0 : sizeLg,
            'data-size-xl': equalWidthXl ? 0 : sizeXl,
            'data-bg-color': bgColor,
            'data-padding': padding,
            'data-center-content': centerContent,
            style,
        };
    },

    edit,

    save({ attributes } ) {
        console.log('attributes:', attributes);
        let classes = [];
        if (attributes.equalWidthXs) {
            classes.push("col");
        }
        else if (attributes.sizeXs) {
            classes.push("col-" + attributes.sizeXs);
        }

        if (attributes.equalWidthSm) {
            classes.push("col-sm");
        }
        else if (attributes.sizeSm) {
            classes.push("col-sm-" + attributes.sizeSm);
        }

        if (attributes.equalWidthMd) {
            classes.push("col-md");
        }
        else if (attributes) {
            classes.push("col-md-" + attributes.sizeMd);
        }

        if (attributes.equalWidthLg) {
            classes.push("col-lg");
        }
        else if (attributes.sizeLg) {
            classes.push("col-lg-" + attributes.sizeLg);
        }

        if (attributes.equalWidthXl) {
            classes.push("col-xl");
        }
        else if (attributes.sizeXl) {
            classes.push("col-xl-" + attributes.sizeXl);
        }

        if (attributes.bgColor) {
            classes.push("h-100");
            classes.push("bg-" + attributes.bgColor);
            if (attributes.centerContent) {
                classes.push("d-flex");
                classes.push("flex-column");
                classes.push("justify-content-center");
            }
        }

        if (attributes.padding) {
            classes.push(attributes.padding);
        }

        if (attributes.className && attributes.className.length) {
            classes.push(attributes.className);
        }

        const classesNames = classes.join(" ");
        return (
            <div className={classesNames}>
                <InnerBlocks.Content />
            </div>
        );
    },
}
export default Column;

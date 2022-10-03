/**
 * BLOCK: wp-bootstrap-blocks/row
 */

import edit from './edit';
const { InnerBlocks } = wp.blockEditor || wp.editor; // Fallback to 'wp.editor' for backwards compatibility

const Row = {
    title: "Row",
    icon: 'layout',
    category: 'wp-bootstrap-blocks',
    supports: {
        align: [ 'full' ],
    },
    attributes: {
        template: {type: 'string', default: '1-1'},
        noGutters: {type: 'boolean', default: false},
        alignment: {type: 'string', default: ''},
        verticalAlignment: {type: 'string', default: ''},
        editorStackColumns: {type: 'boolean', default: false},
    },


    // attributes are defined server side with register_block_type(). This is needed to make default attributes available in the blocks render callback.

    getEditWrapperProps( attributes ) {
        return {
            'data-alignment': attributes.alignment,
            'data-vertical-alignment': attributes.verticalAlignment,
            'data-editor-stack-columns': attributes.editorStackColumns,
        };
    },

    edit,

    save({ attributes } ) {
        let classes = ['row'];
        if(attributes.noGutters){
            classes.push('no-gutters');
        }
        if(attributes.alignment){
            switch (attributes.alignment){
                case "left":
                    classes.push("justify-content-start");
                    break;
                case "center":
                    classes.push("justify-content-center");
                    break;
                case "right":
                    classes.push("justify-content-end");
                    break;
                default:
                    //
                    break;
            }
        }
        if(attributes.verticalAlignment){
            switch (attributes.verticalAlignment){
                case "top":
                    classes.push("align-items-start")
                    break;
                case "center":
                    classes.push("align-items-center");
                    break;
                case "bottom":
                    classes.push("align-items-end");
                    break;
                default:
                    //
                    break;
            }
        }
        const classNames = classes.join(" ");
        return (
            <div className={classNames}>
                <InnerBlocks.Content />
            </div>
        );
    },
}
export default Row;
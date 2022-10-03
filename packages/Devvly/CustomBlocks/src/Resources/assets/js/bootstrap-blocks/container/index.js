/**
 * BLOCK: wp-bootstrap-blocks/container
 */

import edit from './edit';
import save from './save';
const { InnerBlocks } = wp.blockEditor || wp.editor; // Fallback to 'wp.editor' for backwards compatibility

const Container = {
    title: 'Container',
    icon: 'feedback',
    category: 'wp-bootstrap-blocks',
    supports: {
        align: false,
    },
    attributes: {
        isFluid: {type: 'boolean', default: false},
        fluidBreakpoint: {type: 'string', default: ''},
        marginAfter: {type: 'string', default: 'mb-2'},
    },
    edit,
    save,
};
export default Container;

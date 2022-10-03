const { InnerBlocks } = wp.blockEditor || wp.editor; // Fallback to 'wp.editor' for backwards compatibility

export default function save({attributes, className}){
    let classes = "container";
    if(attributes.isFluid){
        classes = "container-fluid"
    }
    if(attributes.fluidBreakpoint){
        classes = "container-" + attributes.fluidBreakpoint;
    }
    if(attributes.marginAfter){
        classes += classes.length? " " + attributes.marginAfter: attributes.marginAfter;
    }
    if(attributes.className && attributes.className.length){
        classes+= classes.length? " " + attributes.className: attributes.className;
    }
    return (
        <div className={classes}>
            <InnerBlocks.Content />
        </div>
    )
}
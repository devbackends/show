const { RichText, BlockControls, MediaUpload } = wp.editor
const { Button, Toolbar } = wp.components

export default {
    title: 'MyBlock',
    icon: 'universal-access-alt',
    category: 'devvly-blocks',
    edit: edit,
    save: save,
    attributes: {
        content: { type: 'string' },
        image: { type: 'string', default: 'http://placehold.it/500' },
        preview: { type: 'boolean', default: false }
    }
}

function edit(props) {
    return (
        <div>
            <BlockControls>
                <Toolbar
                    controls={[
                        {
                            icon: props.attributes.preview ? 'edit' : 'visibility',
                            title: 'Preview',
                            onClick: (event) => props.setAttributes({ preview: !props.attributes.preview })
                        }
                    ]}
                />
            </BlockControls>
            { props.attributes.preview ? renderSave(props) : renderEdit(props) }
        </div>

    )
}

function renderSave(props) {
    return (
        <div>
            <h1>{props.attributes.content}</h1>
            <img src={props.attributes.image} alt=""/>
        </div>
    )
}

function renderEdit(props) {
    function selectImage(value) {
        console.log('IMAGE', value)
        props.setAttributes({image: value.url})
    }


    function updateContent(text) {
        props.setAttributes({ content: text})
    }

    return (
        <div>
            <RichText tagName='h1' value={props.attributes.content} onChange={updateContent} />
            <MediaUpload
                onSelect={selectImage}
                allowedTypes={['audio']}
				render={ ( { open } ) => (
					<Button onClick={ open }>
						Open Media Library
					</Button>
				) }
            />
        </div>

    )
}

function save(props) {
    return renderSave(props)
}

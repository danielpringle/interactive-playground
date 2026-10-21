import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

/**
 * Import internal components
 */
import Inspector from './inspector-controls.js';

import './style.scss';
import './editor.scss';


const Edit = ( props ) => {
	const { attributes, setAttributes } = props;

  const {
		paddingBlockStart,
		paddingBlockEnd,
	} = attributes;

  const blockProps = useBlockProps( {
    className: 'hero-pulse-block block-placeholder',
  } );

  const sectionStyle = {
      paddingBlockStart,
      paddingBlockEnd,
  };

  return (
    <>
      <Inspector { ...props } />
			<div { ...blockProps }>
          <section className="hero" style={ sectionStyle }>
            <InnerBlocks
              renderAppender={ InnerBlocks.ButtonBlockAppender }
            />
          </section>
			</div>
    </>

  );
}

export default Edit;
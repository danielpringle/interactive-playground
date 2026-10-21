 /**
  * PostSelect component.
  *
  * Provides a searchable select control for posts belonging to
  * the supplied WordPress post type.
  *
  * Uses the WordPress core data store to retrieve posts matching
  * the current search term.
  */

import { ComboboxControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';

const PostSelect = ( {
	postType = '',
	label = '',
	help = '',
	value = 0,
	onChange,
} ) => {
	/**
	 * Stores the current search term.
	 *
	 * @type {[string, Function]}
	 */
	const [ search, setSearch ] = useState( '' );

	/**
	 * Retrieves posts matching the current search term.
	 *
	 * The query is re-run when the post type or search term changes.
	 *
	 * @type {Array}
	 */
	const posts = useSelect(
		( select ) =>
			postType
				? select( 'core' ).getEntityRecords(
						'postType',
						postType,
						{
							per_page: 20,
							search,
							orderby: 'title',
							order: 'asc',
						}
				  )
				: [],
		[ postType, search ]
	);

	/**
	 * Converts retrieved posts into options for the select control.
	 *
	 * @type {Array}
	 */
	const options = ( posts || [] ).map( ( post ) => ( {
		label: post.title?.rendered || `Post ${ post.id }`,
		value: String( post.id ),
	} ) );

	return (
		<ComboboxControl
			label={ label }
			help={ help }
			value={ value ? String( value ) : '' }
			options={ options }
			onFilterValueChange={ setSearch }
			onChange={ ( selectedValue ) => {
				onChange( parseInt( selectedValue, 10 ) || 0 );
			} }
			__next40pxDefaultSize
			__nextHasNoMarginBottom
		/>
	);
};

export default PostSelect;
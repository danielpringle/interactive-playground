import { useSelect } from "@wordpress/data";
import { Placeholder, Notice, Spinner } from "@wordpress/components";

import { PostTypeGuard } from '../../components/post-type-guard/post-type-guard.js';
import metadata from './block.json';

export default function Edit({ attributes, setAttributes }) {
	const {} = attributes;

  const requiredPostTypes = metadata?.nachalo?.requiredPostTypes || [];

  console.log(requiredPostTypes)

	return (
		<PostTypeGuard postType="events" label="Events Block">
			{" "}
			<div className="wp-block-my-plugin-events-list">
				{" "}
				<h3>Events List Block</h3>{" "}
				<p>
					{" "}
					The <code>events</code> post type exists.{" "}
				</p>{" "}
				{/* Block UI goes here */}{" "}
			</div>{" "}
		</PostTypeGuard>
	);
}

const SpacingIcon = ({ axis = 'block', side = 'start' }) => {
	const isBlock = axis === 'block';
	const isStart = side === 'start';

	return (
		<svg
			xmlns="http://www.w3.org/2000/svg"
			viewBox="0 0 24 24"
			width="24"
			height="24"
			className="preset-input-control__icon"
			aria-hidden="true"
			focusable="false"
		>
			{/* base box */}
			<path
				d="m7.5 6h9v-1.5h-9zm0 13.5h9v-1.5h-9zm-3-3h1.5v-9h-1.5zm13.5-9v9h1.5v-9z"
				style={{ opacity: 0.25 }}
			/>

			{/* highlight */}
			{isBlock ? (
				isStart ? (
					<path d="m7.5 6h9v-1.5h-9z" /> // top
				) : (
					<path d="m7.5 19.5h9v-1.5h-9z" /> // bottom
				)
			) : isStart ? (
				<path d="m4.5 16.5v-9h1.5v9z" /> // left
			) : (
				<path d="m16.5 16.5v-9h1.5v9z" /> // right
			)}
		</svg>
	);
};

export default SpacingIcon;
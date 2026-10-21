import { createElement } from '@wordpress/element';

/**
 * Alert component for displaying important messages.
 *
 * @param {Object} props
 * @param {'info'|'success'|'warning'|'error'} [props.type='info'] - The type of alert.
 * @param {string} props.message - The message to display.
 */
export default function Alert({ type = 'info', message }) {
  const colors = {
    info:   { background: '#e7f3fe', color: '#084298', border: '#b6e0fe' },
    success:{ background: '#d1e7dd', color: '#0f5132', border: '#badbcc' },
    warning:{ background: '#fff3cd', color: '#664d03', border: '#ffecb5' },
    error:  { background: '#f8d7da', color: '#842029', border: '#f5c2c7' },
  };
  const style = {
    padding: '1em',
    borderRadius: '6px',
    margin: '1em 0',
    background: colors[type].background,
    color: colors[type].color,
    border: `1px solid ${colors[type].border}`,
    fontSize: '1em',
  };

  return (
    <div role="alert" style={style}>
      {message}
    </div>
  );
}
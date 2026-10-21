import { PostTypeGuard } from '../../components/post-type-guard/post-type-guard.js';

export default function save({ attributes }) {
  const { message, type } = attributes;

  return (
    <Alert
      type={type}
      message={message}
    />
  );
}
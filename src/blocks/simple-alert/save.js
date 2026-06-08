import { Alert } from '@nachalo/wp-ui-components';

export default function save({ attributes }) {
  const { message, type } = attributes;

  return (
    <Alert
      type={type}
      message={message}
    />
  );
}
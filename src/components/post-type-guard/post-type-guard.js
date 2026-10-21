import { useSelect } from "@wordpress/data";
import { Placeholder, Notice, Spinner } from "@wordpress/components";
export default function PostTypeGuard({
  postType,
  label = "Missing Dependency",
  children,
}) {
  const postTypeObject = useSelect(
    (select) => select("core").getPostType(postType),
    [postType],
  );
  const isResolving = useSelect(
    (select) =>
      select("core/data").isResolving("core", "getPostType", [postType]),
    [postType],
  );
  if (isResolving) {
    return <Spinner />;
  }
  if (!postTypeObject) {
    return (
      <Placeholder label={label}>
        {" "}
        <Notice status="warning" isDismissible={false}>
          {" "}
          The <code>{postType}</code> post type is not registered.{" "}
        </Notice>{" "}
      </Placeholder>
    );
  }
  return children;
}
